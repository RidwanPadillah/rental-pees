<?php

namespace App\Livewire\Transactions;

use Flux\Flux;
use Carbon\Carbon;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Device;
use Livewire\Component;
use App\Models\Transaction;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreateModal extends Component
{
    public $devices = [];
    public $availableDevices = [];
    public $selectedDevice;
    
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }
    public function render()
    {
        return view('livewire.transactions.create-modal');
    }
    #[On('fetchAvailableDevices')]
    public function getAvailableDevices($startDate, $endDate)
    {
        // Pastikan format datetime benar
        $startDate = Carbon::parse($startDate);
        $startDate->setTimezone('Asia/Jakarta');
        
        $endDate = Carbon::parse($endDate);
        $endDate->setTimezone('Asia/Jakarta');

        // Ambil semua device
        $allDevices = Device::where('status', 'active')->get();
        
        // Inisialisasi hasil
        $availableSlots = [];

        foreach ($allDevices as $device) {
            $bookings = Transaction::where('device_id', $device->id)->whereIn('status', ['pending', 'success'])->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function ($q) use ($startDate, $endDate) {
                      $q->where('start_date', '<=', $startDate)
                        ->where('end_date', '>=', $endDate);
                  });
            })->with('device')->get();

            // Jika tidak ada booking, device masih full kosong
            if ($bookings->isEmpty()) {
                $availableSlots[] =  $this->formatAvailableSlot($startDate, $endDate, $device);
                continue;
            }
            
            $suggestions = [];
            $currentStart = clone $startDate;

            foreach ($bookings as $booking) {
                $bookingStart = Carbon::parse($booking->start_date);
                $bookingEnd = Carbon::parse($booking->end_date);

                // Cek apakah ada celah sebelum booking ini
                if ($currentStart < $bookingStart) {
                    $availableSlots[] =  $this->formatAvailableSlot($currentStart, $bookingStart, $device);
                }

                // Perbarui waktu mulai saat ini setelah booking ini
                if ($currentStart < $bookingEnd) {
                    $currentStart = $bookingEnd;
                }
            }

            // Cek apakah masih ada waktu setelah booking terakhir
            if ($currentStart < $endDate) {
                
                $availableSlots[] = $this->formatAvailableSlot($currentStart, $endDate, $device);
            }
        }

        $this->availableDevices = $availableSlots;  
    }

    public function calculateTimeDiffIn30Minutes($from, $until)
    {
        $from = Carbon::parse($from);
        $until = Carbon::parse($until);

        // Hitung selisih dalam menit
        $diffInMinutes = $from->diffInMinutes($until);

        // Konversi ke dalam unit 30 menit
        $diffIn30MinUnit = $diffInMinutes / 30;

        return $diffIn30MinUnit/2;
    }

    public function calculatePrice($from, $until, $type)
    {
        $session = $this->calculateTimeDiffIn30Minutes($from, $until);
        $from = Carbon::parse($from);
        $price = config('site.prices.' . $type. '.weekdays');
        if ( $from->isSaturday() || $from->isSunday() ) $price = config('site.prices.' . $type.'.weekends');

        return $price * $session;
    }

    public function formatAvailableSlot($startDate, $endDate, $device)
    {
        $price = $this->calculatePrice($startDate, $endDate, $device->type);
        $session = $this->calculateTimeDiffIn30Minutes($startDate, $endDate);
        return [
            'device_id' => $device->id,
            'available_from' => $startDate->toDateTimeString(),
            'available_until' => $endDate->toDateTimeString(),
            'device_name' => $device->name,
            'device_type' => $device->type,
            'price' => $price,
            'id' => encrypt(json_encode([
                'device_id' => $device->id,
                'device_name' => $device->name,
                'device_type' => $device->type,
                'available_from' => $startDate,
                'available_until' => $endDate,
                'price' => $price,
                'session' => $session
            ]))
        ];
    }

    public function selectDevice($id)
    {
        $device = json_decode(decrypt($id), true);
        $this->selectedDevice = $id;
    }

    public function order()
    {
        $user = Auth::user();
        $device = json_decode(decrypt($this->selectedDevice), true);
        
        $data = [
            'code' => $this->generateUniqueOrderCode(),
            'status' => 'pending',
            'amount' => $device['price'],
            'start_date' => Carbon::parse($device['available_from'])->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'end_date' => Carbon::parse($device['available_until'])->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'expired_date' => Carbon::parse($device['available_until'])->setTimezone('Asia/Jakarta')->addDays(1)->format('Y-m-d H:i:s'),
            'session' => $device['session'],
            'device_id' => $device['device_id'],
            'user_id' => Auth::user()->id
        ];

        $params = [
            'transaction_details' => [
                'order_id' => $data['code'],
                'gross_amount' => $device['price'],
            ],
            'customer_details' => [
                'first_name' => $user->name
            ],
            'item_details' => [
                [
                    'id' => $device['device_id'],
                    'price' => $device['price'],
                    'quantity' => 1,
                    'name' => $device['device_type'].' '.$device['device_name'] .' ('. $device['session'].' sesi)',
                    'brand' => $device['device_type'],
                ]
            ],
            "callbacks"=> [
                "finish"=> route('transactions.callback', ['code' => $data['code'], 'status' => 'done']),
                "error"=> route('transactions.callback', ['code' => $data['code'], 'status' => 'error']),
            ]
        ];
        
        DB::beginTransaction();
        try {
            $snapToken = Snap::createTransaction($params)->redirect_url;

            $data = array_merge($data, [
                'payment_details' => $snapToken
            ]);

            Transaction::create($data);
            $this->dispatch('midtransSnapToken', $snapToken);
            DB::commit();
            flash('Transaksi berhasil dibuat.')->success()->livewire($this);
        } catch (\Exception $e) {
            DB::rollBack();
            flash('Terjadi kesalahan, transaksi gagal dibuat.')->error()->livewire($this);
        }

        Flux::modals()->close();
    }

    private function generateUniqueOrderCode()
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 5));
        } while (Transaction::where('code', $code)->exists());

        return $code;
    }

}

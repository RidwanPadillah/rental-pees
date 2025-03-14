<?php

namespace App\Livewire\Transactions;

use Midtrans\Config;
use Livewire\Component;
use App\Models\Transaction;
use Illuminate\Http\Request;

class Callback extends Component
{
    public $status, $headingText, $subheadingText, $headingColor;

    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }
    public function mount(Request $request)
    {
        $transaction = Transaction::where('code', $request->get('order_id'))->first();

        switch ($transaction->status) {
            case 'pending':
                $this->headingText = 'Transaksi Belum Dibayar';
                $this->headingColor = 'red';
                $this->subheadingText = 'Transaksi dengan kode ' . $request->get('order_id') . ' belum dibayar, silahkan lakukan pembayaran terlebih dahulu';
                break;

            case 'success':
                $this->headingText = 'Transaksi Berhasil';
                $this->headingColor = 'green';
                $this->subheadingText = 'Transaksi dengan kode ' . $request->get('order_id') . ' berhasil, silahkan datang tepat waktu pada <br>' . $transaction->start_date;
                break;
            case 'failed':
                $this->headingText = 'Transaksi Gagal';
                $this->headingColor = 'red';
                $this->subheadingText = 'Transaksi dengan kode ' . $request->get('order_id') . ' gagal, silahkan coba lagi';
                break;
            case 'cancel':
                $this->headingText = 'Transaksi Dibatalkan';
                $this->headingColor = 'red';
                $this->subheadingText = 'Transaksi dengan kode ' . $request->get('order_id') . ' telah dibatalkan, silahkan transaksi lagi';
                break;
            case 'expired':
                $this->headingText = 'Transaksi Kadaluarsa';
                $this->headingColor = 'red';
                $this->subheadingText = 'Transaksi dengan kode ' . $request->get('order_id') . ' telah kadaluarsa, silahkan transaksi lagi';
                break;
            default:
                $this->headingText = 'Transaksi Tidak Ditemukan';
                $this->headingColor = 'red';
                $this->subheadingText = 'Transaksi dengan kode ' . $request->get('order_id') . ' tidak ditemukan';
                break;
        }
        if (empty($transaction)) return;
        if ($transaction->status !== 'pending') {
            $this->status = $transaction->status;
            return;
        }

        $this->status = $request->get('status');
        $status = (array)\Midtrans\Transaction::status($request->get('order_id'));
        $arrayStatus = [
            200 => 'success',
            201 => 'pending',
            202 => 'failed',
        ];
        $dataUpdate = ['status' => $arrayStatus[$status['status_code']] ?? 'pending'];
        if ($status['status_code'] == 200) {
            $dataUpdate['payment_method'] = $status['payment_type'];
            $dataUpdate['payment_details'] = json_encode($status);
        }
        $transaction->update($dataUpdate);
    }

    public function render()
    {
        return view('livewire.transactions.callback');
    }
}

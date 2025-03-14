<?php

namespace App\Livewire\Transactions;

use App\Models\Device;
use Livewire\Component;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $transactions;
    public $pendingTransactionCount; 

    public function mount()
    {
        $this->transactions = Transaction::select('id', 'code', 'start_date', 'end_date', 'device_id', 'status', 'session', 'user_id')->whereIn('status', ['pending', 'success'])
            ->with('device')
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'title' => strtoupper($transaction->device->type).' | '.$transaction->device->name.' ('.$transaction->session.' sesi)',
                    'start' => $transaction->start_date,
                    'end' => $transaction->end_date,
                    'deviceId' => $transaction->device_id,
                    'backgroundColor' => ($transaction->status === 'pending' ? '#424242' : $transaction->device->color), // Warna event
                    'borderColor' => '#388E3C',
                ];
            });

        $user = Auth::user();
        $this->pendingTransactionCount = Transaction::where('status', 'pending')->where('user_id', $user->id)->count();
    }
    public function render()
    {
        return view('livewire.transactions.create');
    }
}

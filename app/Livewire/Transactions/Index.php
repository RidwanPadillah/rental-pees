<?php

namespace App\Livewire\Transactions;

use Livewire\Component;
use App\Models\Transaction;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $datas;

    public function mount()
    {
        $this->reloadTransactions();
    }

    #[On('reloadTransactions')]
    public function reloadTransactions() 
    {
        $user = Auth::user();
        $this->datas = new Transaction;
        if (!$user->hasRole('admin')) $this->datas = $this->datas->where('user_id', $user->id);
        $this->datas = $this->datas->orderBy('created_at', 'desc')->get();
    }
    public function render()
    {
        $user = Auth::user();
        $datas = Transaction::query();
        if (!$user->hasRole('admin')) $datas = $datas->where('user_id', $user->id);
        // dd($datas);
        return view('livewire.transactions.index', [
            'transactions' => $datas->paginate(10)
        ]);
    }

    public function pay($id)
    {
        $transaction = Transaction::findOrFail($id);
        if ($transaction->status == 'pending') {
            return redirect()->to($transaction->payment_details);
        } else {
            flash('URL pembayaran tidak tersedia.')->error()->livewire($this);
        }
    }

    public function cancel($id)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::findOrFail($id);
            $transaction->update(['status' => 'cancel']);    
            DB::commit();
            flash('Transaksi berhasil dibatalkan.')->livewire($this);
            $this->reloadTransactions();
        } catch (\Exception $e) {
            DB::rollBack();
            flash('Terjadi kesalahan, transaksi gagal dibatalkan.')->error()->livewire($this);
        }
    }

}

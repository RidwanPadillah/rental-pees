<?php

namespace App\Livewire\Devices;

use Flux\Flux;
use App\Models\Device;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    public $name, $type, $status, $color;
    public function render()
    {
        return view('livewire.devices.create');
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'type' => 'required',
            'status' => 'required',
            'color' => 'required'
        ]);

        DB::beginTransaction();
        try {
            Device::create([
                'name' => $this->name,
                'type' => $this->type,
                'status' => $this->status,
                'color' => $this->color
            ]);
            $this->dispatch('reloadDevices');
            DB::commit();
            flash('Perangkat berhasil dibuat.')->success()->livewire($this);
        } catch (\Exception $e) {
            DB::rollBack();
            flash('Terjadi kesalahan, perangkat gagal dibuat.')->error()->livewire($this);
        }

        Flux::modals()->close();
        
        $this->reset();
        
    }
}

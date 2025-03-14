<?php

namespace App\Livewire\Devices;

use Flux\Flux;
use App\Models\Device;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class Edit extends Component
{
    public $name, $type, $status, $id, $color;
    public function render()
    {
        return view('livewire.devices.edit');
    }

    #[On('editDevice')]
    public function editDevice($id) 
    {
        $device = Device::find($id);

        $this->id = $device->id;
        $this->name = $device->name;
        $this->type = $device->type;
        $this->status = $device->status;
        $this->color = $device->color;
        Flux::modal('edit-device')->show();
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
            $device = Device::findOrFail($this->id);
            $device->update([
                'name' => $this->name,
                'type' => $this->type,
                'status' => $this->status,
                'color' => $this->color
            ]);
            $this->dispatch('reloadDevices');
            DB::commit();
            flash('Perangkat berhasil diubah.')->success()->livewire($this);
        } catch (\Exception $e) {
            DB::rollBack();
            flash('Terjadi kesalahan, perangkat gagal diubah.')->error()->livewire($this);
        }

        Flux::modals()->close();
    }
}

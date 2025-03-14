<?php

namespace App\Livewire\Devices;

use Flux\Flux;
use App\Models\Device;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class Delete extends Component
{
    public $id, $name;
    public function render()
    {
        return view('livewire.devices.delete');
    }

    #[On('deleteDevice')]
    public function deleteDevice($id) 
    {
        $device = Device::find($id);

        $this->id = $device->id;
        $this->name = $device->name;
        Flux::modal('delete-device')->show();
    }

    public function delete ()
    {
        DB::beginTransaction();
        try {
            $device = Device::findOrFail($this->id);
            $device->delete();
            DB::commit();
            flash('Perangkat berhasil dihapus.')->success()->livewire($this);

            $this->dispatch('reloadDevices');
        } catch (\Exception $e) {
            DB::rollBack();
            flash('Terjadi kesalahan, perangkat gagal dihapus.')->error()->livewire($this);
        }
        Flux::modals()->close();
    }
}

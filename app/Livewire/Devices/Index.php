<?php

namespace App\Livewire\Devices;

use Flux\Flux;
use App\Models\Device;
use Livewire\Component;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $datas;

    public function mount()
    {
        $this->reloadDevices();
    }
    public function render(): View
    {
        return view('livewire.devices.index', [
            'devices' => Device::paginate(10),
        ]);
    }

    #[On('reloadDevices')]
    public function reloadDevices() 
    {
        $this->datas = Device::all();
    }

    public function edit($id) 
    {
        $this->dispatch('editDevice', $id);
    }

    public function delete($id)
    {
        $this->dispatch('deleteDevice', $id);
    }
}

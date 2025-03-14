<div class="h-full w-full flex-1">
    <div class="relative w-full flex justify-between items-center gap-0">
        <div>
            <flux:heading size="xl" level="1">{{ __('Perangkat') }}</flux:heading>
            <flux:subheading size="lg" class="mb-6">{{ __('Pantau dan Atur Perangkat Anda') }}</flux:subheading>
        </div>
    
        <flux:modal.trigger name="create-device"  class="ml-auto">
        <flux:button>Tambah Perangkat</flux:button>
        </flux:modal.trigger>
    </div>
    <flux:separator variant="subtle" />
    <livewire:devices.create />
    <livewire:devices.edit />
    <livewire:devices.delete />
    
    <div class="overflow-x-auto mt-5">
        <div class="relative overflow-hidden shadow-md rounded-lg">
            <div class="relative overflow-hidden shadow-md ">
                @if (count($datas) == 0)
                <div class="flex justify-center items-center">
                    <div class="text-gray-500 text-center">
                        <div class="text-3xl font-bold mb-4">Oops! Belum Ada Perangkat</div>
                        <div class="text-lg">Tambahkan device pertama Anda dan mulai mengelolanya dengan mudah!</div>
                    </div>
                </div>
                @else
                <table class="table-fixed w-full text-left">
                    <thead class="uppercase bg-[#6b7280] text-[#e5e7eb]" style="background-color: #6b7280; color: #e5e7eb;">
                        <tr>
                            <td class="py-3 border border-gray-200 font-bold p-4">Nama</td>
                            <td class="py-3 border border-gray-200 font-bold p-4">Tipe</td>
                            <td class="py-3 border border-gray-200 font-bold p-4">Status</td>
                            <td class="py-3 border border-gray-200 font-bold p-4">Warna Tanda</td>
                            <td class="py-3 border border-gray-200 font-bold p-4">Aksi</td>
                        </tr>
                    </thead>
                    <tbody class="">
                        @foreach ($datas as $item)
                        <tr class=" py-4">
                            <td class=" py-4 border border-gray-200 p-4">{{ $item->name }}</td>
                            <td class=" py-4 border border-gray-200 p-4">{{ $item->type }}</td>
                            <td class=" py-4 border border-gray-200 p-4">{{ $item->status }}</td>
                            <td class=" py-4 border border-gray-200 p-4"><span class="rounded-lg p-2" style="background-color: {{ $item->color }}">{{ $item->color }}</span></td>
                            <td class=" py-4 border border-gray-200 p-4">
                                <flux:button wire:click="edit({{ $item->id }})">Ubah Data</flux:button>
                                <flux:button wire:click="delete({{ $item->id }})"  variant="danger">Hapus Data</flux:button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
                
            </div>
        </div>
    </div>
    <div class="mt-2">
        {{ $devices->links() }}
    </div>
</div>
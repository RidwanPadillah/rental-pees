<div class="h-full w-full flex-1">
    <div class="relative w-full flex justify-between items-center gap-0">
        <div>
            <flux:heading size="xl" level="1">{{ __('Transaksi') }}</flux:heading>
            <flux:subheading size="lg" class="mb-6">{{ __('Pantau dan Atur Transaksi Anda') }}</flux:subheading>
        </div>
    
        <flux:button :href="route('transactions.create')">Tambah Transaksi</flux:button>
    </div>
    <flux:separator variant="subtle" />
    
    <div class="overflow-x-auto mt-5">
        <div class="relative overflow-hidden shadow-md rounded-lg">
            <div class="relative overflow-hidden shadow-md ">
                @if (count($datas) == 0)
                <div class="flex justify-center items-center">
                    <div class="text-gray-500 text-center">
                        <div class="text-3xl font-bold mb-4">Oops! Belum Ada Transaksi</div>
                        <div class="text-lg">Ayo buat transaksimu sekarang dan dapatkan pengalaman bermain yang lebih menyenangkan!</div>
                    </div>
                </div>
                @else
                <table class="table-fixed w-full text-left">
                    <thead class="uppercase bg-[#6b7280] text-[#e5e7eb]" style="background-color: #6b7280; color: #e5e7eb;">
                        <tr>
                            <td class="py-3 border border-gray-200 font-bold p-4">Kode Transaksi</td>
                            <td class="py-3 border border-gray-200 font-bold p-4">Perangkat</td>
                            <td class="py-3 border border-gray-200 font-bold p-4">Harga</td>
                            <td class="py-3 border border-gray-200 font-bold p-4">Status Pembayaran</td>
                            <td class="py-3 border border-gray-200 font-bold p-4">Tanggal Kadarluarsa</td>
                            <td class="py-3 border border-gray-200 font-bold p-4">Aksi</td>
                        </tr>
                    </thead>
                    <tbody class="">
                        @foreach ($datas as $item)
                        <tr class="">
                            <td class="py-2 border border-gray-200 p-4"><strong>{{ $item->code }}</strong><br><flux:subheading size="lg">{{ date("d M Y", strtotime($item->created_at)) }} ({{ date("H:i", strtotime($item->created_at)) }})</flux:subheading></td>
                            <td class="py-2 border border-gray-200 p-4">{{ $item->device->name }} - {{ $item->device->type }}<br><flux:subheading size="lg">{{ date("d M Y", strtotime($item->start_date)) }} ({{ date("H:i", strtotime($item->start_date)) }} - {{ date("H:i", strtotime($item->end_date)) }})</flux:subheading></td>
                            <td class="py-2 border border-gray-200 p-4">{{ number_format($item->amount) }}</td>
                            <td class="py-2 border border-gray-200 p-4">{{ $item->status }}</td>
                            <td class="py-2 border border-gray-200 p-4">{{ date("d M Y", strtotime($item->expired_date)) }} ({{ date("H:i", strtotime($item->expired_date)) }})</td>
                            <td class="py-2 border border-gray-200 p-4">
                                @if ($item->status == 'pending')
                                    <flux:button size="sm" wire:click="pay({{ $item->id }})" icon-trailing="arrow-up-right" variant="primary">Bayar</flux:button>    
                                    <flux:button size="sm" wire:click="cancel({{ $item->id }})"  variant="danger">Batalkan Pesanan</flux:button>
                                @endif
                                
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
        {{ $transactions->links() }}
    </div>
</div>
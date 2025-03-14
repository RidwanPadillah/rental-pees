<div>
    <flux:modal name="transaction-create-modal" class="w-100">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Daftar Perangkat Tersedia</flux:heading>

                <flux:subheading>
                    <p>Pilih perangkat </p>
                </flux:subheading>
            </div>
            <div>
                @foreach ($availableDevices as $item)
                    <div class="column-2 cursor-pointer p-3 rounded-lg border transition-all duration-300 {{ $selectedDevice === $item['id'] ? 'bg-blue-200 ring-2 ring-blue-500' : '' }}" wire:click="selectDevice('{{ $item['id'] }}')">
                        <div class="flex gap-2">
                            <div class="w-1/2">
                                <flux:heading>{{ $item['device_name'] }} <flux:badge color="lime">{{ $item['device_type'] }}</flux:badge></flux:heading>
                            </div>
                            <div class="w-1/2">    
                                <flux:heading size="lg" class="text-right">Rp {{ number_format($item['price']) }}</flux:heading>    
                            </div>
                        </div>
                        <flux:subheading>{{ date('d M Y', strtotime($item['available_from'])) }} ({{ date('H:i', strtotime($item['available_from'])) }} - {{ date('H:i', strtotime($item['available_until'])) }})</flux:subheading>    
                    </div>
                    <flux:separator variant="subtle" class="mb-2 mt-2" />
                @endforeach
            </div>
            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary" wire:click="order" :disabled="!$selectedDevice">Pesan Sekarang</flux:button>
            </div>
        </div>
    </flux:modal>
</div>

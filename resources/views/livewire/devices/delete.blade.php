<div>
    <flux:modal name="delete-device" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Hapus Perangkat?</flux:heading>

                <flux:subheading>
                    <p>Kamu akan menghapus perangkat <strong>{{ $name }}</strong> ini.</p>
                    <p>Tindakan ini tidak dapat dibatalkan</p>
                </flux:subheading>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="danger" wire:click="delete">Ya! Hapus Perangkat</flux:button>
            </div>
        </div>
    </flux:modal>
</div>

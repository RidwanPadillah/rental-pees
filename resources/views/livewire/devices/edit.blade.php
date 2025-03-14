<div>
    <flux:modal name="edit-device" class="w-200">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Ubah Perangkat</flux:heading>
                <flux:subheading>Ubah Detail Perangkat</flux:subheading>
            </div>

            <flux:input wire:model="name" label="Nama Perangkat" placeholder="Nama Perangkat" />

            <flux:input wire:model="color" label="Warna Tanda" placeholder="Warna Tanda" />

            <flux:select wire:model="type" label="Tipe Perangkat" placeholder="Pilih Tipe Perangkat">
                <flux:select.option value="ps4">PS 4</flux:select.option>
                <flux:select.option value="ps5">PS 5</flux:select.option>
            </flux:select>

            <flux:select wire:model="status" label="Status Perangkat" placeholder="Pilih Status Perangkat">
                <flux:select.option value="active">Aktif</flux:select.option>
                <flux:select.option value="inactive">Tidak Aktif</flux:select.option>
            </flux:select>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary" wire:click="store">Ubah Data</flux:button>
            </div>
        </div>
    </flux:modal>
</div>

<div>
    <div class="flex flex-col items-center justify-center min-h-screen">
        <flux:heading size="xl" class="{{ $headingColor ?? '' }}">{{ $headingText }}</flux:heading>
        <flux:subheading class="text-center">{!! $subheadingText !!}</flux:subheading>

        <flux:separator variant="subtle" class="my-4"/>

        <flux:button variant="primary" href="{{ route('transactions') }}">Kembali ke Transaksi</flux:button>
    </div>
    
</div>

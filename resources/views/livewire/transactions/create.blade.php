<div>
    <div class="relative w-full flex justify-between items-center gap-0">
        <div>
            <flux:heading size="xl" level="1">{{ __('Transaksi') }}</flux:heading>
            <flux:subheading size="lg" class="mb-6">{{ __('Pantau dan Atur Transaksi Anda') }}</flux:subheading>
        </div>
        
        <flux:button :href="route('transactions')" variant="danger">Kembali</flux:button>
    </div>
    <flux:separator variant="subtle" />
    <livewire:transactions.create-modal />
    <div id="calendar" class="mt-6">
        
    </div>
</div>

@assets
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endassets

@script
<script>
    window.onload = function() {
        var calendarEl = document.getElementById('calendar');
        var selectedTime = null;
        if (calendarEl) {
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                validRange: {
                    start: new Date()
                },
                events: {!! json_encode($transactions) !!},
                selectable: true,
                select: function(info) {
                    var pendingTransactionCount = @this.pendingTransactionCount; 
                    if (Number.parseInt(pendingTransactionCount) > 0) {
                        alert("Anda masih memiliki lebih dari 1 transaksi pending. Harap selesaikan transaksi sebelumnya.");
                        return; // Stop eksekusi jika masih ada transaksi pending
                    }
                    var now = new Date();
                    var selectedStart = new Date(info.start);
                    var selectedEnd = new Date(info.end);
                    if (info.allDay) return;

                    Flux.modal('transaction-create-modal').show();
                    Livewire.dispatch('fetchAvailableDevices', { 
                        startDate: selectedStart.toISOString(), 
                        endDate: selectedEnd.toISOString() 
                    });
                    console.log(selectedStart, selectedEnd);
                },
                dateClick: function(info) {
                    calendar.changeView('timeGridDay', info.dateStr);
                },
                selectAllow: function(selectInfo) {
                    var now = new Date();
                    if (selectInfo.start < now) {
                        return false; // Mencegah pemilihan waktu yang sudah terlewat
                    }
                    return true;
                },
                eventDidMount: function(info) {
                    var now = new Date();
                    if (info.event.start < now) {
                        info.el.style.backgroundColor = "#d3d3d3"; // Warna abu-abu untuk slot yang sudah lewat
                        info.el.style.pointerEvents = "none"; // Nonaktifkan interaksi
                        info.el.style.opacity = "0.5"; // Transparansi untuk indikasi disable
                    }
                },
                businessHours: @json(config('site.business_hours')),
                selectConstraint: "businessHours"
            });
            calendar.render();
        } else {
            console.error("Elemen #calendar tidak ditemukan.");
        }
    };
    $wire.on('midtransSnapToken', (snapToken) => {
        window.location.href = snapToken;
    });
</script>
@endscript
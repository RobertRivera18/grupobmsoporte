<div class="bg-white rounded-xl shadow p-4 mt-6">

    <h2 class="text-lg font-semibold mb-4">
        Calendario de Auditorías
    </h2>

    <div wire:ignore x-data x-init="
        const calendar = new FullCalendar.Calendar($refs.calendar,{
            locale:'es',
            initialView:'dayGridMonth',
            height:500,
            events:@js($eventos)
        });

        calendar.render();
    ">
        <div x-ref="calendar"></div>
    </div>

</div>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js"></script>
@endpush
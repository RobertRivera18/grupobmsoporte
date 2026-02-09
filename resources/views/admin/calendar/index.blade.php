<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Home',
        'url' => route('admin.dashboard'),
    ],
    [
        'name' => 'Calendario',
        'url' => route('admin.calendar.index'),
    ],
]">


    <div x-data="data()">
        <div x-ref='calendar'></div>
    </div>

    @push('js')
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js'></script>
        <script>
            function data() {
                return {
                    init() {
                        var calendarEl = this.$refs.calendar
                        var calendar = new FullCalendar.Calendar(calendarEl, {
                            headerToolbar: {
                                left: 'prev,next,today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                            },
                            locale: 'es',
                            initialView: 'dayGridMonth'
                        });
                        calendar.render();
                    }
                }
            }
        </script>
    @endpush
</x-admin-layout>

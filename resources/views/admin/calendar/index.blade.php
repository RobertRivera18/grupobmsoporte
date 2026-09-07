<x-admin-layout :breadcrumbs="[
    ['name' => 'Home',       'url' => route('admin.dashboard')],
    ['name' => 'Calendario', 'url' => route('admin.calendar.index')],
]">

    {{-- Leyenda de colores --}}
    <div class="flex gap-4 mb-4">
        <span class="flex items-center gap-2 text-sm">
            <span class="inline-block w-3 h-3 rounded-full" style="background:#4f46e5"></span> Pendiente
        </span>
        <span class="flex items-center gap-2 text-sm">
            <span class="inline-block w-3 h-3 rounded-full" style="background:#f59e0b"></span> En curso
        </span>
        <span class="flex items-center gap-2 text-sm">
            <span class="inline-block w-3 h-3 rounded-full" style="background:#10b981"></span> Finalizada
        </span>
    </div>

    <div x-data="calendarData()">

        {{-- Calendario --}}
        <div x-ref="calendar"></div>

        {{-- Modal --}}
        <div
            x-show="modalAbierto"
            x-transition
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            @click.self="modalAbierto = false"
        >
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 relative">

                <button
                    @click="modalAbierto = false"
                    class="absolute top-3 right-4 text-gray-400 hover:text-gray-600 text-xl font-bold"
                >&times;</button>

                <h2 class="text-lg font-semibold text-gray-800 mb-4">Detalle del proceso</h2>

                <ul class="space-y-2 text-sm text-gray-700">
                    <li>
                        <span class="font-medium">Área:</span>
                        <span x-text="evento.area"></span>
                    </li>
                    <li>
                        <span class="font-medium">Auditor:</span>
                        <span x-text="evento.auditor"></span>
                    </li>
                    <li>
                        <span class="font-medium">Auditoría:</span>
                        <span x-text="evento.auditoria"></span>
                    </li>
                    <li>
                        <span class="font-medium">Normas:</span>
                        <span x-text="evento.normas"></span>
                    </li>
                    <li>
                        <span class="font-medium">Fecha inicio:</span>
                        <span x-text="evento.fecha_inicio"></span>
                    </li>
                    <li>
                        <span class="font-medium">Fecha fin:</span>
                        <span x-text="evento.fecha_fin"></span>
                    </li>
                    <li>
                        <span class="font-medium">Estado:</span>
                        <span
                            class="inline-block px-2 py-0.5 rounded-full text-white text-xs font-medium"
                            :style="estadoColor(evento.estado)"
                            x-text="evento.estado?.replace('_', ' ')"
                        ></span>
                    </li>
                </ul>

            </div>
        </div>

    </div>

    @push('js')
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js'></script>
        <script>
            const eventos = @json($eventos);

            function calendarData() {
                return {
                    modalAbierto: false,
                    evento: {},

                    estadoColor(estado) {
                        const colores = {
                            pendiente:  'background:#4f46e5',
                            en_curso:   'background:#f59e0b',
                            finalizada: 'background:#10b981',
                        };
                        return colores[estado] ?? 'background:#6b7280';
                    },

                    init() {
                        const self = this;
                        var calendarEl = this.$refs.calendar;
                        var calendar = new FullCalendar.Calendar(calendarEl, {
                            headerToolbar: {
                                left:   'prev,next,today',
                                center: 'title',
                                right:  'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                            },
                            locale:      'es',
                            initialView: 'dayGridMonth',
                            events:      eventos,

                            eventDidMount(info) {
                                const props = info.event.extendedProps;
                                info.el.title =
                                    `Área: ${props.area}\n` +
                                    `Auditor: ${props.auditor}\n` +
                                    `Normas: ${props.normas}`;
                            },

                            eventClick(info) {
                                info.jsEvent.preventDefault();
                                self.evento = info.event.extendedProps;
                                self.modalAbierto = true;
                            },
                        });

                        calendar.render();
                    }
                }
            }
        </script>
    @endpush

</x-admin-layout>
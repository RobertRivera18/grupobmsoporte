<x-app-layout>
    <script type="application/json" id="incidentes-data">{!! $rows->toJson(JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!}</script>
    <div class="py-6 px-4 sm:px-6 lg:px-8" x-data="incidentesTable()" x-init="init()" x-cloak>
        <div class="max-w-7xl mx-auto">

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-5">
                <div>
                    <div
                        class="flex items-center gap-2 text-[11px] font-semibold text-indigo-600 uppercase tracking-wider mb-1">
                        <i class="fas fa-truck-pickup text-[10px]" aria-hidden="true"></i>
                        Control de flota
                    </div>
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight">Historial de Incidentes</h1>
                    <p class="text-xs text-gray-500 mt-0.5">Registro de ingresos y salidas de vehiculos.</p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-2.5">
                        <div
                            class="flex items-center gap-2 px-3.5 py-2 rounded-xl border border-gray-100 bg-gray-50/70">
                            <span class="text-base font-bold text-gray-900 leading-none" x-text="stats.total"></span>
                            <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide">Total</span>
                        </div>
                        <div
                            class="flex items-center gap-2 px-3.5 py-2 rounded-xl border border-gray-100 bg-gray-50/70">
                            <span class="text-base font-bold text-emerald-600 leading-none"
                                x-text="stats.ingresos"></span>
                            <span
                                class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide">Ingresos</span>
                        </div>
                        <div
                            class="flex items-center gap-2 px-3.5 py-2 rounded-xl border border-gray-100 bg-gray-50/70">
                            <span class="text-base font-bold text-amber-600 leading-none" x-text="stats.salidas"></span>
                            <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide">Salidas</span>
                        </div>
                    </div>

                    <a href="{{ route('incidentesvehiculos.create') }}"
                        class="inline-flex items-center px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-lg shadow-sm shadow-indigo-200 transition-colors duration-150">
                        <i class="fas fa-plus mr-1.5 text-[10px]" aria-hidden="true"></i> Nuevo registro
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 sm:p-5">

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 mb-1">
                        <label class="flex items-center gap-2 text-xs text-gray-500">
                            <span class="font-medium">Mostrar</span>
                            <select x-model.number="pageSize" @change="page = 1"
                                class="text-xs bg-white border border-gray-200 text-gray-700 rounded-lg px-2 py-1.5 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                <template x-for="n in [5, 10, 25, 50]" :key="n">
                                    <option :value="n" x-text="n"></option>
                                </template>
                                <option :value="Infinity">Todos</option>
                            </select>
                            <span class="font-medium">registros</span>
                        </label>

                        <div class="relative w-full sm:w-64">
                            <i class="fas fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-xs"
                                aria-hidden="true"></i>
                            <input type="search" x-model.debounce.300ms="search" @input="page = 1"
                                placeholder="Buscar placa, vehiculo, tipo..."
                                aria-label="Buscar en la tabla de incidentes"
                                class="w-full text-xs bg-white border border-gray-200 text-gray-700 rounded-lg pl-8 pr-3 py-1.5 outline-none transition-shadow focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                        </div>
                    </div>

                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-xs text-left text-gray-600">
                            <thead class="text-[11px] text-gray-500 uppercase bg-gray-50/80 border-b border-gray-100">
                                <tr>
                                    <th scope="col" class="cursor-pointer select-none font-semibold px-3 py-3"
                                        @click="sortBy('id')" :aria-sort="ariaSort('id')">
                                        ID <i class="fas ml-1 text-[9px]" :class="sortIcon('id')"
                                            aria-hidden="true"></i>
                                    </th>
                                    <th scope="col" class="cursor-pointer select-none font-semibold px-3 py-3"
                                        @click="sortBy('placa')" :aria-sort="ariaSort('placa')">
                                        Vehiculo <i class="fas ml-1 text-[9px]" :class="sortIcon('placa')"
                                            aria-hidden="true"></i>
                                    </th>
                                    <th scope="col"
                                        class="cursor-pointer select-none font-semibold px-3 py-3 text-center"
                                        @click="sortBy('tipo')" :aria-sort="ariaSort('tipo')">
                                        Tipo de registro <i class="fas ml-1 text-[9px]" :class="sortIcon('tipo')"
                                            aria-hidden="true"></i>
                                    </th>
                                    <th scope="col" class="cursor-pointer select-none font-semibold px-3 py-3"
                                        @click="sortBy('fecha_ts')" :aria-sort="ariaSort('fecha_ts')">
                                        Fecha y hora <i class="fas ml-1 text-[9px]" :class="sortIcon('fecha_ts')"
                                            aria-hidden="true"></i>
                                    </th>
                                    <th scope="col" class="w-16 px-3 py-3">
                                        <span class="sr-only">Acciones</span>
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">
                                <template x-for="row in paginated" :key="row.id">
                                    <tr class="bg-white hover:bg-gray-50/70 transition-colors">
                                        <td class="px-3 py-3 font-bold text-gray-900">
                                            #<span x-text="row.id"></span>
                                        </td>

                                        <td class="px-3 py-3">
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md border border-gray-200 bg-gradient-to-b from-gray-50 to-gray-100 shadow-inner font-mono text-[11px] font-bold tracking-wider text-gray-800 whitespace-nowrap">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"
                                                        aria-hidden="true"></span>
                                                    <span x-text="row.placa"></span>
                                                </span>
                                                <span class="text-gray-500 text-[11px]" x-text="row.vehiculo"></span>
                                            </div>
                                        </td>

                                        <td class="px-3 py-3 text-center">
                                            <template x-if="row.tipo === 1">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                    <span class="w-1.5 h-1.5 mr-1.5 bg-emerald-500 rounded-full"
                                                        aria-hidden="true"></span>
                                                    Ingreso
                                                </span>
                                            </template>
                                            <template x-if="row.tipo !== 1">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                                    <span class="w-1.5 h-1.5 mr-1.5 bg-amber-500 rounded-full"
                                                        aria-hidden="true"></span>
                                                    Salida
                                                </span>
                                            </template>
                                        </td>

                                        <td class="px-3 py-3 text-gray-500 whitespace-nowrap">
                                            <i class="fas fa-clock text-gray-300 mr-1 text-[10px]"
                                                aria-hidden="true"></i>
                                            <span x-text="row.fecha_fmt"></span>
                                        </td>

                                        <td class="px-3 py-3 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <a :href="row.show_url"
                                                    class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-gray-400 hover:bg-indigo-50 hover:text-indigo-600 transition-colors"
                                                    title="Ver detalles" aria-label="Ver detalles del incidente">
                                                    <i class="fas fa-eye text-sm" aria-hidden="true"></i>
                                                </a>
                                               
                                            </div>
                                        </td>
                                    </tr>
                                </template>

                                <tr x-show="filtered.length === 0">
                                    <td colspan="5" class="px-3 py-14 text-center">
                                        <i class="fas fa-clipboard-list text-2xl text-gray-200 mb-2 block"
                                            aria-hidden="true"></i>
                                        <p class="text-sm font-semibold text-gray-500"
                                            x-text="rows.length === 0 ? 'Aun no hay incidentes registrados' : 'Sin resultados para tu busqueda'">
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5"
                                            x-text="rows.length === 0 ? 'Los ingresos y salidas de vehiculos apareceran aqui.' : 'Prueba con otra placa, vehiculo o tipo de registro.'">
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-4 mt-3 border-t border-gray-100"
                        x-show="filtered.length > 0">
                        <p class="text-[11px] text-gray-400" x-text="infoText" aria-live="polite"></p>

                        <div class="flex items-center gap-1" role="navigation" aria-label="Paginacion">
                            <button type="button" @click="page = 1" :disabled="page === 1"
                                aria-label="Primera pagina"
                                class="inline-flex items-center justify-center min-w-[2rem] h-8 px-2 text-[11px] font-medium rounded-md border transition-colors"
                                :class="page === 1 ? 'text-gray-300 border-gray-100 cursor-not-allowed' :
                                    'text-gray-600 bg-white border-gray-200 hover:bg-gray-50 hover:border-gray-300'">
                                <i class="fas fa-angles-left" aria-hidden="true"></i>
                            </button>
                            <button type="button" @click="page = Math.max(1, page - 1)" :disabled="page === 1"
                                aria-label="Pagina anterior"
                                class="inline-flex items-center justify-center min-w-[2rem] h-8 px-2 text-[11px] font-medium rounded-md border transition-colors"
                                :class="page === 1 ? 'text-gray-300 border-gray-100 cursor-not-allowed' :
                                    'text-gray-600 bg-white border-gray-200 hover:bg-gray-50 hover:border-gray-300'">
                                <i class="fas fa-angle-left" aria-hidden="true"></i>
                            </button>

                            <template x-for="p in pageNumbers" :key="p">
                                <button type="button" @click="page = p" :aria-current="p === page ? 'page' : false"
                                    class="inline-flex items-center justify-center min-w-[2rem] h-8 px-2 text-[11px] font-medium rounded-md border transition-colors"
                                    :class="p === page ? 'bg-indigo-600 border-indigo-600 text-white' :
                                        'text-gray-600 bg-white border-gray-200 hover:bg-gray-50 hover:border-gray-300'"
                                    x-text="p">
                                </button>
                            </template>

                            <button type="button" @click="page = Math.min(totalPages, page + 1)"
                                :disabled="page === totalPages" aria-label="Pagina siguiente"
                                class="inline-flex items-center justify-center min-w-[2rem] h-8 px-2 text-[11px] font-medium rounded-md border transition-colors"
                                :class="page === totalPages ? 'text-gray-300 border-gray-100 cursor-not-allowed' :
                                    'text-gray-600 bg-white border-gray-200 hover:bg-gray-50 hover:border-gray-300'">
                                <i class="fas fa-angle-right" aria-hidden="true"></i>
                            </button>
                            <button type="button" @click="page = totalPages" :disabled="page === totalPages"
                                aria-label="Ultima pagina"
                                class="inline-flex items-center justify-center min-w-[2rem] h-8 px-2 text-[11px] font-medium rounded-md border transition-colors"
                                :class="page === totalPages ? 'text-gray-300 border-gray-100 cursor-not-allowed' :
                                    'text-gray-600 bg-white border-gray-200 hover:bg-gray-50 hover:border-gray-300'">
                                <i class="fas fa-angles-right" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('css')
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    @endpush

    @push('js')
        <script>
            function incidentesTable() {
                var NUMERIC_FIELDS = new Set(['id', 'tipo', 'fecha_ts']);

                return {
                    rows: [],
                    search: '',
                    sortField: 'id',
                    sortDir: 'desc',
                    page: 1,
                    pageSize: 10,

                    init: function() {
                        var el = document.getElementById('incidentes-data');
                        try {
                            this.rows = el ? JSON.parse(el.textContent) : [];
                        } catch (e) {
                            console.error('No se pudo leer los datos de incidentes:', e);
                            this.rows = [];
                        }
                    },

                    get stats() {
                        var ingresos = 0;
                        for (var i = 0; i < this.rows.length; i++) {
                            if (this.rows[i].tipo === 1) ingresos++;
                        }
                        return {
                            total: this.rows.length,
                            ingresos: ingresos,
                            salidas: this.rows.length - ingresos
                        };
                    },

                    get filtered() {
                        var term = this.search.trim().toLowerCase();
                        var data = this.rows;

                        if (term) {
                            data = this.rows.filter(function(r) {
                                var tipoTexto = r.tipo === 1 ? 'ingreso' : 'salida';
                                return (
                                    r.placa.toLowerCase().indexOf(term) !== -1 ||
                                    r.vehiculo.toLowerCase().indexOf(term) !== -1 ||
                                    tipoTexto.indexOf(term) !== -1 ||
                                    r.fecha_fmt.toLowerCase().indexOf(term) !== -1 ||
                                    String(r.id).indexOf(term) !== -1
                                );
                            });
                        }

                        var field = this.sortField;
                        var dir = this.sortDir === 'asc' ? 1 : -1;
                        var isNumeric = NUMERIC_FIELDS.has(field);

                        return data.slice().sort(function(a, b) {
                            if (isNumeric) {
                                return (a[field] - b[field]) * dir;
                            }
                            return String(a[field]).localeCompare(String(b[field]), 'es', {
                                sensitivity: 'base'
                            }) * dir;
                        });
                    },

                    get totalPages() {
                        return Math.max(1, Math.ceil(this.filtered.length / this.pageSize));
                    },

                    get paginated() {
                        if (this.pageSize === Infinity) return this.filtered;
                        if (this.page > this.totalPages) this.page = this.totalPages;
                        var start = (this.page - 1) * this.pageSize;
                        return this.filtered.slice(start, start + this.pageSize);
                    },

                    get pageNumbers() {
                        var total = this.totalPages;
                        var current = Math.min(this.page, total);
                        var span = 2;
                        var start = Math.max(1, current - span);
                        var end = Math.min(total, current + span);
                        var pages = [];
                        for (var p = start; p <= end; p++) pages.push(p);
                        return pages;
                    },

                    get infoText() {
                        if (this.filtered.length === 0) return '';
                        if (this.pageSize === Infinity) {
                            return 'Mostrando los ' + this.filtered.length + ' registros';
                        }
                        var start = (this.page - 1) * this.pageSize + 1;
                        var end = Math.min(this.filtered.length, this.page * this.pageSize);
                        return 'Mostrando ' + start + ' a ' + end + ' de ' + this.filtered.length + ' registros';
                    },

                    sortBy: function(field) {
                        if (this.sortField === field) {
                            this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
                        } else {
                            this.sortField = field;
                            this.sortDir = 'asc';
                        }
                    },

                    sortIcon: function(field) {
                        if (this.sortField !== field) return 'fa-sort text-gray-300';
                        return this.sortDir === 'asc' ? 'fa-sort-up text-indigo-600' : 'fa-sort-down text-indigo-600';
                    },

                    ariaSort: function(field) {
                        if (this.sortField !== field) return 'none';
                        return this.sortDir === 'asc' ? 'ascending' : 'descending';
                    }
                };
            }
        </script>
    @endpush

</x-app-layout>

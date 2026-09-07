<x-admin-layout :breadcrumbs="[['name' => 'Dashboard', 'url' => route('admin.dashboard')]]" title="Dashboard Directivo">

    {{-- HEADER WIDGETS (Información de usuario + Branding) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        {{-- Tarjeta de Perfil de Usuario (Ocupa 2 columnas en pantallas grandes) --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800/80 border border-gray-200/80 dark:border-gray-700/60 rounded-2xl p-5 shadow-sm flex flex-col sm:flex-row items-center sm:items-start gap-4">
            <div class="relative">
                <img class="w-16 h-16 rounded-2xl object-cover ring-4 ring-indigo-500/10 dark:ring-indigo-500/20 shadow-sm" 
                     src="{{ Auth::user()->profile_photo_url }}"
                     alt="{{ Auth::user()->name }}">
                <span class="absolute bottom-0 right-0 w-4 h-4 bg-emerald-500 border-2 border-white dark:border-gray-800 rounded-full" title="Conectado"></span>
            </div>

            <div class="flex-1 text-center sm:text-left w-full">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white tracking-tight">
                            ¡Bienvenido de nuevo, {{ Auth::user()->name }}!
                        </h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            Panel general de operaciones e indicadores del sistema.
                        </p>
                    </div>

                    <span class="inline-flex items-center justify-center px-3 py-1 text-xs font-semibold rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 border border-indigo-200/60 dark:border-indigo-800/50 self-center sm:self-start">
                        <i class="fas fa-shield-halved text-[10px] me-1.5"></i>
                        {{ Auth::user()->getRoleNames()->first() ?? 'Usuario' }}
                    </span>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/50 flex items-center justify-between">
                    <span class="text-xs text-gray-400 dark:text-gray-500">
                        Sesión activa
                    </span>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center text-xs font-medium text-rose-600 hover:text-rose-700 dark:text-rose-400 dark:hover:text-rose-300 transition-colors gap-1.5">
                            <i class="fas fa-right-from-bracket text-[11px]"></i>
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Tarjeta de Identidad Corporativa --}}
        <div class="bg-white dark:bg-gray-800/80 border border-gray-200/80 dark:border-gray-700/60 rounded-2xl p-5 shadow-sm flex items-center justify-center text-center relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-indigo-500/5 rounded-full blur-xl group-hover:bg-indigo-500/10 transition-all"></div>
            
            <div class="flex flex-col items-center">
                <img src="{{ asset('img/grupobm.png') }}" class="h-14 w-auto object-contain mb-2 drop-shadow-sm" alt="GrupoBM Logo" />
                <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wider uppercase">GrupoBM</h3>
                <span class="text-[11px] text-gray-400 dark:text-gray-500">Gestión Integral Soporte TI </span>
            </div>
        </div>

    </div>

    {{-- SECCIÓN DE MÉTRICAS Y GRÁFICOS --}}
    @if (Auth::user()->hasRole('Admin'))
        
        {{-- Tarjetas Resumen de Recursos --}}
        <div class="mb-8">
            @livewire('total-resource')
        </div>

        {{-- Gráficos Principales --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800/80 border border-gray-200/80 dark:border-gray-700/60 rounded-2xl p-4 shadow-sm">
                @livewire('admin-tickets-chart')
            </div>

            <div class="bg-white dark:bg-gray-800/80 border border-gray-200/80 dark:border-gray-700/60 rounded-2xl p-4 shadow-sm">
                @livewire('graficos-equipos')
            </div>
        </div>

        {{-- Disponibilidad de Equipos --}}
        <div class="bg-white dark:bg-gray-800/80 border border-gray-200/80 dark:border-gray-700/60 rounded-2xl p-4 shadow-sm">
            @livewire('grafica-equipos-disponibles')
        </div>

    @else

        {{-- Módulo para Usuarios Estándar --}}
        <div class="bg-white dark:bg-gray-800/80 border border-gray-200/80 dark:border-gray-700/60 rounded-2xl p-4 shadow-sm">
            @livewire('tickets-users')
        </div>

    @endif

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

</x-admin-layout>
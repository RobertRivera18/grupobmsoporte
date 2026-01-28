<x-admin-layout :breadcrumbs="[['name' => 'Home', 'url' => route('admin.dashboard')]]">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-2">


        <div class="bg-white shadow rounded-xl px-6 py-4 flex items-center">
            <img class="w-10 h-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                alt="{{ Auth::user()->name }}">

            <div class="ml-4 flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Bienvenido, {{ Auth::user()->name }}
                    </h2>
                    <span
                        class="block sm:inline-block mt-2 sm:mt-0 text-sm sm:text-base px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-center sm:text-left">
                        {{ Auth::user()->getRoleNames()->first() }}
                    </span>

                </div>

                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="text-sm text-red-600 hover:underline">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>


        <div class="bg-white shadow rounded-xl p-6 flex flex-col items-center justify-center text-center">
            <img src="{{ asset('img/grupobm.png') }}" class="h-20 w-auto mb-2" alt="GrupoBM Logo" />
            <h2 class="text-xl font-bold text-gray-800">GrupoBM</h2>
        </div>
    </div>
    @if (Auth::user()->hasRole('Admin'))
        @livewire('total-resource')

        <div class="flex flex-col lg:flex-row gap-4 mt-12">
            <div class="w-full lg:w-1/2">
                @livewire('admin-tickets-chart')
            </div>

            <div class="w-full lg:w-1/2">
                @livewire('graficos-equipos')
            </div>
        </div>
        @livewire('grafica-equipos-disponibles')
    @else
        @livewire('tickets-users')
    @endif


    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

</x-admin-layout>

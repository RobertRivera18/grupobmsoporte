<div>
    <div class="mt-8 flex justify-center">
        @guest
            <a href="{{ route('login') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg shadow transition flex items-center gap-2">
                <i class="fas fa-sign-in-alt"></i>
                Iniciar Sesión
            </a>
        @else
            <a href="{{ route('admin.dashboard') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg shadow transition flex items-center gap-2">
                <i class="fas fa-tachometer-alt"></i>
                Ir a Dashboard
            </a>
        @endguest
    </div>
</div>

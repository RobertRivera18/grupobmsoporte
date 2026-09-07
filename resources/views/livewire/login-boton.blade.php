<div class="mt-8 flex justify-center">
    @guest
        <a href="{{ route('login') }}"
            class="group inline-flex items-center justify-center gap-2.5 px-7 py-3.5 text-white font-semibold text-sm rounded-xl transition-all duration-200 active:scale-[0.98]"
            style="background-color: #243C73; box-shadow: 0 10px 15px -3px rgba(36, 60, 115, 0.3);"
            onmouseover="this.style.backgroundColor='#1a2c55'"
            onmouseout="this.style.backgroundColor='#243C73'"
        >
            <i class="fas fa-sign-in-alt text-xs transition-transform group-hover:translate-x-0.5"></i>
            <span>Iniciar Sesión</span>
        </a>
    @else
        <a href="{{ route('admin.dashboard') }}"
            class="group inline-flex items-center justify-center gap-2.5 px-7 py-3.5 text-white font-semibold text-sm rounded-xl transition-all duration-200 active:scale-[0.98]"
            style="background-color: #243C73; box-shadow: 0 10px 15px -3px rgba(36, 60, 115, 0.3);"
            onmouseover="this.style.backgroundColor='#1a2c55'"
            onmouseout="this.style.backgroundColor='#243C73'"
        >
            <i class="fas fa-tachometer-alt text-xs transition-transform group-hover:rotate-12"></i>
            <span>Ir a Dashboard</span>
        </a>
    @endguest
</div>
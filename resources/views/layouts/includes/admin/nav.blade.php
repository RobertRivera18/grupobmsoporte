<nav class="fixed top-0 z-50 w-full bg-white/90 backdrop-blur-md border-b border-gray-200/80 dark:bg-gray-900/90 dark:border-gray-800 transition-colors duration-200">
    <div class="px-4 py-2.5 lg:px-6">
        <div class="flex items-center justify-between gap-4">
            
            <!-- SECCIÓN IZQUIERDA: TOGGLE + LOGO -->
            <div class="flex items-center justify-start gap-3 lg:gap-4">
                <!-- Botón Toggle Sidebar -->
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" x-on:click="open = !open"
                    aria-controls="logo-sidebar" type="button"
                    class="inline-flex items-center justify-center p-2 text-gray-500 rounded-xl hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200 transition-all">
                    <span class="sr-only">Toggle Sidebar</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <!-- Logo Marca -->
                <a href="/" class="flex items-center gap-3 group focus:outline-none">
                    <div class="p-1 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 transition-transform group-hover:scale-105">
                        <img src="{{ asset('img/grupobm.png') }}" class="h-8 w-auto object-contain" alt="GrupoBM Logo" />
                    </div>
                    <span class="text-lg font-bold tracking-tight text-gray-900 dark:text-white">
                        GrupoBM
                    </span>
                </a>
            </div>

            <!-- SECCIÓN DERECHA: NOTIFICACIONES + DROPDOWN USUARIO -->
            <div class="flex items-center gap-2 sm:gap-3">
                
                <!-- Componente Livewire de Notificaciones -->
                <div class="relative">
                    @livewire('notification')
                </div>

                <div class="h-6 w-px bg-gray-200 dark:bg-gray-800"></div>

                <!-- Dropdown de Usuario Unificado -->
                <div class="relative">
                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button type="button" 
                                class="flex items-center gap-3 p-1.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all group">
                                
                                <!-- Profile Avatar + Status Dot -->
                                <div class="relative shrink-0">
                                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                        <img class="size-9 rounded-lg object-cover ring-2 ring-gray-200 dark:ring-gray-700 group-hover:ring-indigo-500 transition-all"
                                            src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                    @else
                                        <div class="size-9 rounded-lg bg-indigo-600 text-white font-semibold text-sm flex items-center justify-center shadow-sm">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <!-- Online Indicator -->
                                    <span class="absolute -bottom-0.5 -right-0.5 size-2.5 bg-emerald-500 border-2 border-white dark:border-gray-900 rounded-full"></span>
                                </div>

                                <!-- User Info Text -->
                                <div class="hidden lg:flex flex-col text-left">
                                    <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 leading-tight">
                                        {{ Auth::user()->name }}
                                    </span>
                                    <span class="text-[10px] font-medium text-gray-400 dark:text-gray-500">
                                        {{ Auth::user()->email }}
                                    </span>
                                </div>

                                <!-- Dropdown Chevron -->
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-transform duration-200" 
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Menú Desplegable con Estilo TailAdmin -->
                            <div class="py-1.5 divide-y divide-gray-100 dark:divide-gray-800">
                                
                                <!-- Info Resumen (Mobile / Extra Context) -->
                                <div class="px-4 py-2.5">
                                    <p class="text-xs text-gray-400 dark:text-gray-500">Sesión iniciada como</p>
                                    <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate">{{ Auth::user()->name }}</p>
                                </div>

                                <div class="py-1">
                                    <x-dropdown-link href="{{ route('profile.show') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800/60 rounded-lg transition-colors">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                        {{ __('Configuración de Perfil') }}
                                    </x-dropdown-link>

                                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                        <x-dropdown-link href="{{ route('api-tokens.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800/60 rounded-lg transition-colors">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                                            </svg>
                                            {{ __('Tokens de API') }}
                                        </x-dropdown-link>
                                    @endif
                                </div>

                                <!-- Formulario Cerrar Sesión -->
                                <div class="py-1">
                                    <form method="POST" action="{{ route('logout') }}" x-data>
                                        @csrf
                                        <x-dropdown-link href="{{ route('logout') }}" 
                                            @click.prevent="$root.submit();"
                                            class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-lg transition-colors">
                                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                            </svg>
                                            {{ __('Cerrar Sesión') }}
                                        </x-dropdown-link>
                                    </form>
                                </div>

                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>

            </div>
        </div>
    </div>
</nav>
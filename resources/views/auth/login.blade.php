<x-guest-layout>
    <div class="min-h-screen bg-slate-50 text-slate-800 flex items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl border border-slate-100 flex flex-col lg:flex-row overflow-hidden">
            
            <!-- Columna Formulario -->
            <div class="w-full lg:w-1/2 p-8 sm:p-12 flex flex-col justify-between">
                <div>
                    <!-- Header Formulario -->
                    <div class="flex items-center justify-between mb-8">
                        <a href="/" class="flex items-center gap-2 group">
                            <x-application-logo class="w-10 h-10 transition-transform group-hover:scale-105" style="color: #243C73;" />
                        </a>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                            Acceso Seguro
                        </span>
                    </div>

                    <div class="space-y-2 mb-8">
                        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                            Iniciar Sesión
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-500">
                            Ingresa tus credenciales para acceder al Sistema Integral de Gestión.
                        </p>
                    </div>

                    <!-- Mensajes de Estado y Validación -->
                    @session('status')
                        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs sm:text-sm font-medium flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $value }}</span>
                        </div>
                    @endsession

                    <x-validation-errors class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs sm:text-sm" />

                    <!-- Formulario con Alpine.js -->
                    <form method="POST" action="{{ route('login') }}" x-data="{ showPassword: false }" class="space-y-5">
                        @csrf

                        <!-- Campo Email -->
                        <div class="space-y-1">
                            <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                Correo electrónico
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                                <input 
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    placeholder="nombre@empresa.com"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white transition-all duration-200 focus:border-[#243C73] focus:ring-2 focus:ring-[#243C73]/20"
                                />
                            </div>
                        </div>

                        <!-- Campo Password -->
                        <div class="space-y-1">
                            <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                Contraseña
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input 
                                    id="password"
                                    name="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    required
                                    placeholder="••••••••"
                                    class="w-full pl-10 pr-10 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white transition-all duration-200 focus:border-[#243C73] focus:ring-2 focus:ring-[#243C73]/20"
                                />
                                <button 
                                    type="button" 
                                    @click="showPassword = !showPassword" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none"
                                >
                                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a8.98 8.98 0 013.122-.863c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m-2.115 2.115a3 3 0 11-4.243-4.243"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Recordar & Olvidé Contraseña -->
                        <div class="flex items-center justify-between text-xs sm:text-sm">
                            <label for="remember_me" class="inline-flex items-center cursor-pointer select-none text-slate-600 hover:text-slate-900">
                                <x-checkbox id="remember_me" name="remember" class="rounded border-slate-300 shadow-sm" style="color: #243C73;" />
                                <span class="ms-2">Recordar sesión</span>
                            </label>

                          
                        </div>

                        <!-- Botón Principal -->
                        <button 
                            type="submit" 
                            class="w-full py-3.5 px-4 text-white font-semibold rounded-xl shadow-lg transition-all duration-200 flex items-center justify-center gap-2 group hover:opacity-95 active:scale-[0.99]"
                            style="background-color: #243C73; box-shadow: 0 10px 15px -3px rgba(36, 60, 115, 0.3);"
                        >
                            <span>Iniciar Sesión</span>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Footer -->
                <div class="mt-8 pt-6 border-t border-slate-100 text-center lg:text-left">
                    <p class="text-xs text-slate-400">
                        © {{ date('Y') }} Sistema de Gestión. Todos los derechos reservados.
                    </p>
                </div>
            </div>

            <!-- Columna Banner / Color Corporativo -->
            <div class="hidden lg:flex w-1/2 relative p-12 flex-col justify-between overflow-hidden" style="background: linear-gradient(135deg, #243C73 0%, #152344 100%);">
                
                <!-- SVG Patrón Decorativo de Fondo -->
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
                
                <div class="relative z-10 flex items-center justify-end">
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium bg-white/10 text-white backdrop-blur-md border border-white/10">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Sistema Operativo
                    </span>
                </div>

                <!-- Ilustración / Contenido Central -->
                <div class="relative z-10 space-y-6 my-auto">
                    <div class="w-full max-w-md mx-auto">
                        <img src="{{ asset('img/job.svg') }}" alt="Ilustración Plataforma" class="w-full h-auto max-h-64 object-contain filter drop-shadow-2xl" />
                    </div>

                    <div class="space-y-3 text-center text-white">
                        <h2 class="text-2xl font-bold tracking-tight">
                            Plataforma de Control TI & Auditorías
                        </h2>
                        <p class="text-xs text-slate-200/80 leading-relaxed max-w-sm mx-auto">
                            Administración centralizada de inventarios tecnológicos, trazabilidad de asignaciones y seguimiento normativo ISO 9001.
                        </p>
                    </div>
                </div>

                <!-- Tarjetas Informativas -->
                <div class="relative z-10 grid grid-cols-2 gap-3 pt-6 border-t border-white/10">
                    <div class="bg-white/10 backdrop-blur-sm p-3 rounded-xl border border-white/10">
                        <div class="text-xs font-semibold text-white">Gestión TI</div>
                        <div class="text-[10px] text-slate-300">Hardware & Entregas</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm p-3 rounded-xl border border-white/10">
                        <div class="text-xs font-semibold text-white">Normativa ISO</div>
                        <div class="text-[10px] text-slate-300">Auditorías & Hallazgos</div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-guest-layout>
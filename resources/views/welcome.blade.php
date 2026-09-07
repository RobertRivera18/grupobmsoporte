{{-- 
   
    <figure class="mb-12">
        <img class="w-full aspect-[3/1] object-cover object-center" src="{{ asset('img/home/portada.jpg') }}" alt="">
    </figure>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        <h1 class="text-2xl font-semibold text-center mb-8">Lista de Artículos</h1>

        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

           
            <div class="md:col-span-1">
                <form action="">
                 
                    <div class="mb-6">
                        <p class="text-lg font-semibold mb-2">Ordenar por</p>
                        <x-select name="order" class="w-full">
                            <option value="new">Más recientes</option>
                            <option value="old" @selected(request('order') == 'old')>Más antiguos</option>
                        </x-select>
                    </div>

                    
                    <div class="mb-6">
                        <p class="text-lg font-semibold mb-2">Categorías</p>
                        <ul class="space-y-2">
                            @foreach ($categories as $category)
                                <li>
                                    <label class="flex items-center">
                                        <x-checkbox name="category[]" value="{{ $category->id }}"
                                            :checked="is_array(request('category')) && in_array($category->id, request('category'))" />
                                        <span class="ml-2 text-gray-700">{{ $category->name }}</span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <x-button class="w-full">Aplicar Filtros</x-button>
                </form>
            </div>

            
            <div class="md:col-span-3 ">
                <div class="space-y-10">
                    @foreach ($posts as $post)
                        <article class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white rounded-lg p-2">
                            <figure>
                                <img class="rounded-lg w-full object-cover" src="{{ $post->image }}" alt="{{ $post->title }}">
                            </figure>
                            <div>
                              
                                <div class="mb-2">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-sm dark:bg-blue-900 dark:text-blue-300">
                                        {{ $post->category->name }}
                                    </span>
                                </div>

                              
                                <h2 class="text-lg font-semibold">{{ $post->title }}</h2>
                                <hr class="my-2">

                                
                                <div class="mb-2 flex flex-wrap gap-2">
                                    @foreach ($post->tags as $tag)
                                        <a href="{{ route('home') . '?tag=' . $tag->name }}">
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-sm dark:bg-blue-900 dark:text-blue-300">
                                                {{ $tag->name }}
                                            </span>
                                        </a>
                                    @endforeach
                                </div>

                               
                                <p class="text-sm text-gray-600 mb-2">
                                    {{ $post->published_at->format('d M Y') }}
                                </p>

                              
                                <div class="mb-4 text-sm text-gray-800">
                                    {{ Str::limit($post->body, 200) }}
                                </div>

                              
                                <div class="flex justify-end">
                                    <a href="{{ route('posts.show', $post) }}"
                                       class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                        Leer más
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

             
                <div class="mt-10">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </section>

 --}}



<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 text-white rounded-xl flex items-center justify-center font-bold text-lg shadow-md transition-transform hover:scale-105" style="background-color: #243C73;">
                    <i class="fas fa-chart-line text-base" style="color: #f4ac2c;"></i>
                </div>
                <div>
                    <h2 class="font-extrabold text-xl text-slate-900 leading-tight tracking-tight">
                        {{ __('Sistema de Gestión Integral') }}
                    </h2>
                    <span class="text-xs text-slate-500 font-medium flex items-center gap-1.5 mt-0.5">
                        <span class="w-2 h-2 rounded-full" style="background-color: #f4ac2c;"></span>
                        Plataforma de Control & Analítica Gerencial
                    </span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-100/70 min-h-[calc(100vh-8rem)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Hero Gerencial -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xl shadow-slate-200/50 p-8 lg:p-10 relative overflow-hidden">
                <!-- Línea Accent Superior -->
                <div class="absolute top-0 left-0 w-full h-2" style="background: linear-gradient(90deg, #243C73 0%, #243C73 70%, #f4ac2c 100%);"></div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                    
                    <!-- Lado Izquierdo: Propuesta de Valor -->
                    <div class="lg:col-span-5 space-y-6 text-center lg:text-left">
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold border shadow-sm" style="background-color: rgba(36, 60, 115, 0.05); color: #243C73; border-color: rgba(36, 60, 115, 0.15);">
                            <i class="fas fa-chart-pie text-xs" style="color: #f4ac2c;"></i> Información Ejecutiva en Tiempo Real
                        </span>

                        <h1 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                            Toma de decisiones basada en <span class="relative inline-block" style="color: #243C73;">datos reales<span class="absolute bottom-1 left-0 w-full h-1 rounded" style="background-color: #f4ac2c;"></span></span>
                        </h1>

                        <p class="text-sm text-slate-600 leading-relaxed font-normal">
                            Accede a tableros de control gerencial, seguimiento de KPIs de operación, rendimiento por departamentos e historial de solicitudes en una sola plataforma.
                        </p>

                        <div class="pt-2 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                            @livewire('login-boton')
                        </div>
                    </div>

                    <!-- Lado Derecho: Mockup Ilustrativo / Demo Visual -->
                    <div class="lg:col-span-7 rounded-2xl shadow-2xl p-6 relative overflow-hidden border border-slate-700/60" style="background: linear-gradient(145deg, #18284c 0%, #243C73 100%);">
                        
                        <!-- Elemento decorativo de fondo -->
                        <div class="absolute -right-10 -bottom-10 w-48 h-48 rounded-full blur-3xl opacity-20 pointer-events-none" style="background-color: #f4ac2c;"></div>

                        <!-- Header del Mockup -->
                        <div class="flex items-center justify-between border-b border-white/10 pb-3.5 mb-5">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-red-500/80 inline-block"></span>
                                <span class="w-3 h-3 rounded-full bg-amber-500/80 inline-block"></span>
                                <span class="w-3 h-3 rounded-full bg-emerald-500/80 inline-block"></span>
                                <span class="text-xs font-mono text-slate-300 ml-2 font-medium">Panel Gerencial - ISO & Operaciones</span>
                            </div>
                            <span class="text-[10px] font-bold px-2.5 py-1 rounded-full border flex items-center gap-1.5 shadow-sm" style="background-color: rgba(244, 172, 44, 0.15); color: #f4ac2c; border-color: rgba(244, 172, 44, 0.3);">
                                <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background-color: #f4ac2c;"></span> Datos en Vivo
                            </span>
                        </div>

                        <!-- Gráficos Estadísticos de Ejemplo -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 select-none pointer-events-none">
                            
                            <!-- Ejemplo 1: Cumplimiento de SLA -->
                            <div class="bg-white/5 backdrop-blur-md p-4 rounded-xl border border-white/10 hover:border-white/20 transition-all">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-xs font-semibold text-slate-200">Resolución de Casos (SLA)</span>
                                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded" style="background-color: rgba(244, 172, 44, 0.2); color: #f4ac2c;">+14% mes</span>
                                </div>
                                <div class="space-y-2.5">
                                    <div>
                                        <div class="flex justify-between text-[10px] text-slate-300 mb-1 font-medium">
                                            <span>Soporte TI</span>
                                            <span style="color: #f4ac2c;" class="font-bold">92%</span>
                                        </div>
                                        <div class="w-full bg-slate-800/80 h-2 rounded-full overflow-hidden p-0.5 border border-white/5">
                                            <div class="h-full rounded-full transition-all duration-500" style="width: 92%; background-color: #f4ac2c;"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-[10px] text-slate-300 mb-1 font-medium">
                                            <span>Infraestructura</span>
                                            <span class="font-bold text-slate-200">78%</span>
                                        </div>
                                        <div class="w-full bg-slate-800/80 h-2 rounded-full overflow-hidden p-0.5 border border-white/5">
                                            <div class="h-full rounded-full bg-slate-300 transition-all duration-500" style="width: 78%;"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-[10px] text-slate-300 mb-1 font-medium">
                                            <span>Mantenimiento</span>
                                            <span class="font-bold text-emerald-400">85%</span>
                                        </div>
                                        <div class="w-full bg-slate-800/80 h-2 rounded-full overflow-hidden p-0.5 border border-white/5">
                                            <div class="bg-emerald-400 h-full rounded-full transition-all duration-500" style="width: 85%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ejemplo 2: Tendencia Semanal -->
                            <div class="bg-white/5 backdrop-blur-md p-4 rounded-xl border border-white/10 flex flex-col justify-between">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs font-semibold text-slate-200">Volumen de Solicitudes</span>
                                    <i class="fas fa-chart-bar text-xs" style="color: #f4ac2c;"></i>
                                </div>
                                <div class="flex items-end justify-between gap-2 h-20 pt-4 px-1">
                                    <div class="w-full rounded-t-md transition-all" style="height: 45%; background-color: rgba(244, 172, 44, 0.3);"></div>
                                    <div class="w-full rounded-t-md transition-all" style="height: 65%; background-color: rgba(244, 172, 44, 0.5);"></div>
                                    <div class="w-full rounded-t-md transition-all" style="height: 50%; background-color: rgba(244, 172, 44, 0.4);"></div>
                                    <div class="w-full rounded-t-md transition-all" style="height: 85%; background-color: rgba(244, 172, 44, 0.8);"></div>
                                    <div class="w-full rounded-t-md shadow-lg transition-all" style="height: 98%; background-color: #f4ac2c;"></div>
                                </div>
                                <div class="flex justify-between text-[9px] font-semibold text-slate-400 mt-2 border-t border-white/5 pt-1">
                                    <span>Lun</span><span>Mar</span><span>Mié</span><span>Jue</span><span>Vie</span>
                                </div>
                            </div>

                        </div>

                        <!-- Indicadores Secundarios de Muestra -->
                        <div class="grid grid-cols-3 gap-3 pt-4 mt-4 border-t border-white/10 text-center select-none pointer-events-none">
                            <div class="p-2.5 bg-white/5 rounded-xl border border-white/5">
                                <span class="text-[10px] text-slate-400 font-medium block">Efectividad</span>
                                <span class="text-sm font-extrabold text-emerald-400">94.2%</span>
                            </div>
                            <div class="p-2.5 bg-white/5 rounded-xl border border-white/5">
                                <span class="text-[10px] text-slate-400 font-medium block">Tiempo Cierre</span>
                                <span class="text-sm font-extrabold text-slate-100">2.4 hrs</span>
                            </div>
                            <div class="p-2.5 bg-white/5 rounded-xl border border-white/5">
                                <span class="text-[10px] text-slate-400 font-medium block">Satisfacción</span>
                                <span class="text-sm font-extrabold" style="color: #f4ac2c;">4.8 / 5</span>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <!-- Pilares de Valor Gerencial -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="group bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-200 flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 text-xl shadow-sm transition-transform group-hover:scale-110" style="background-color: rgba(36, 60, 115, 0.08); color: #243C73;">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 mb-1 flex items-center justify-between">
                            Control Operativo
                            <span class="w-1.5 h-1.5 rounded-full opacity-0 group-hover:opacity-100 transition-opacity" style="background-color: #f4ac2c;"></span>
                        </h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Visualiza cuellos de botella, niveles de carga por departamento y cumplimiento de métricas en tiempo real.
                        </p>
                    </div>
                </div>

                <div class="group bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-200 flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 text-xl shadow-sm transition-transform group-hover:scale-110" style="background-color: rgba(36, 60, 115, 0.08); color: #243C73;">
                        <i class="fas fa-file-export"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 mb-1 flex items-center justify-between">
                            Reportes Ejecutivos
                            <span class="w-1.5 h-1.5 rounded-full opacity-0 group-hover:opacity-100 transition-opacity" style="background-color: #f4ac2c;"></span>
                        </h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Generación de auditorías e informes de gestión exportables para comités gerenciales y normas ISO.
                        </p>
                    </div>
                </div>

                <div class="group bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-200 flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 text-xl shadow-sm transition-transform group-hover:scale-110" style="background-color: rgba(36, 60, 115, 0.08); color: #243C73;">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 mb-1 flex items-center justify-between">
                            KPIs Personalizados
                            <span class="w-1.5 h-1.5 rounded-full opacity-0 group-hover:opacity-100 transition-opacity" style="background-color: #f4ac2c;"></span>
                        </h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Seguimiento en vivo del índice de resolución, nivel de servicio (SLA) y satisfacción del usuario.
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
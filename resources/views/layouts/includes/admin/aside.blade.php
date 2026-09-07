@php
    $links = [
        // GENERAL
        [
            'section' => 'General',
            'items' => [
                [
                    'name' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'active' => request()->routeIs('admin.dashboard'),
                    'icon' => 'fas fa-gauge-simple-high',
                    'can' => ['Acceso al Dashboard'],
                ],
                [
                    'name' => 'Dashboard Ejecutivo',
                    'url' => route('admin.dashboard.ejecutivo'),
                    'active' => request()->routeIs('admin.dashboard.ejecutivo'),
                    'icon' => 'fas fa-chart-line',
                    'can' => ['Acceso al Dashboard'],
                ],
                [
                    'name' => 'Solicitud Desvinculación',
                    'url' => route('admin.desvinculacion.index'),
                    'active' => request()->routeIs('admin.desvinculacion.*'),
                    'icon' => 'fas fa-user-minus',
                    'can' => ['Acceso al Dashboard'],
                ],
                [
                    'name' => 'Salida Equipos',
                    'url' => route('admin.salidas.index'),
                    'active' => request()->routeIs('admin.salidas.index'),
                    'icon' => 'fas fa-right-from-bracket',
                    'can' => ['Acceso al Dashboard'],
                ],
            ]
        ],

        // GESTIÓN Y OPERACIONES
        [
            'section' => 'Operaciones & Logística',
            'items' => [
                [
                    'name' => 'EPP & Indumentaria',
                    'icon' => 'fas fa-shirt',
                    'submenu' => [
                        [
                            'name' => 'Indumentarias',
                            'url' => route('admin.indumentarias.index'),
                            'active' => request()->routeIs('admin.indumentarias.index'),
                            'icon' => 'fas fa-shirt',
                            'can' => ['Acceso al Dashboard'],
                        ],
                        [
                            'name' => 'Ingresos EPP',
                            'url' => route('admin.indumentarias.ingresos.index'),
                            'active' => request()->routeIs('admin.indumentarias.ingresos.index'),
                            'icon' => 'fa-solid fa-right-to-bracket',
                            'can' => ['Gestion de Articulos'],
                        ],
                        [
                            'name' => 'Entregas EPP',
                            'url' => route('admin.indumentarias.entregas.index'),
                            'active' => request()->routeIs('admin.indumentarias.entregas.*'),
                            'icon' => 'fa-solid fa-box',
                            'can' => ['Gestion de Articulos'],
                        ],
                        [
                            'name' => 'Devoluciones EPP',
                            'url' => route('admin.indumentarias.devoluciones.index'),
                            'active' => request()->routeIs('admin.indumentarias.devoluciones.*'),
                            'icon' => 'fa-solid fa-rotate-left',
                            'can' => ['Gestion de Articulos'],
                        ],
                        [
                            'name' => 'Traslados EPP',
                            'url' => route('admin.indumentarias.traslados.index'),
                            'active' => request()->routeIs('admin.indumentarias.traslados.*'),
                            'icon' => 'fa-solid fa-truck',
                            'can' => ['Gestion de Articulos'],
                        ],
                        [
                            'name' => 'Kardex',
                            'url' => route('admin.indumentarias.kardex.index'),
                            'active' => request()->routeIs('admin.indumentarias.kardex.*'),
                            'icon' => 'fa-solid fa-file-lines',
                            'can' => ['Gestion de Articulos'],
                        ],
                    ],
                ],
                [
                    'name' => 'Gestión Bodega',
                    'icon' => 'fas fa-warehouse',
                    'submenu' => [
                        [
                            'name' => 'Listado de Vehículos',
                            'url' => route('admin.vehiculos.index'),
                            'active' => request()->routeIs('admin.vehiculos.*'),
                            'icon' => 'fas fa-car',
                            'can' => ['Gestion de Categorias'],
                        ],
                        [
                            'name' => 'Mantenimientos',
                            'url' => route('admin.mantenimientos.index'),
                            'active' => request()->routeIs('admin.mantenimientos.*'),
                            'icon' => 'fas fa-gears',
                            'can' => ['Gestion de Categorias'],
                        ],
                        [
                            'name' => 'Revisiones Técnicas',
                            'url' => route('admin.revisiones.index'),
                            'active' => request()->routeIs('admin.revisiones.*'),
                            'icon' => 'fas fa-tools',
                            'can' => ['Gestion de Categorias'],
                        ],
                        [
                            'name' => 'Inventarios Técnicos',
                            'url' => route('admin.tecnicos.index'),
                            'active' => request()->routeIs('admin.tecnicos.*'),
                            'icon' => 'fas fa-boxes',
                            'can' => ['Gestion de Categorias'],
                        ],
                    ],
                ],
                [
                    'name' => 'Gestión de Equipos',
                    'icon' => 'fas fa-desktop',
                    'submenu' => [
                        [
                            'name' => 'Equipos',
                            'url' => route('admin.equipos.index'),
                            'active' => request()->routeIs('admin.equipos.*'),
                            'icon' => 'fas fa-laptop',
                            'can' => ['Gestion de equipos'],
                        ],
                        [
                            'name' => 'Tipos de Equipos',
                            'url' => route('admin.tipoequipos.index'),
                            'active' => request()->routeIs('admin.tipoequipos.*'),
                            'icon' => 'fas fa-cogs',
                            'can' => ['Gestion de Tipos de Equipos'],
                        ],
                        [
                            'name' => 'Asignación Usuarios',
                            'url' => route('admin.asignacion'),
                            'active' => request()->routeIs('admin.asignacion'),
                            'icon' => 'fas fa-user-gear',
                            'can' => ['Gestion de Equipos-Usuarios'],
                        ],
                        [
                            'name' => 'Chips / Medidores',
                            'url' => route('admin.tecnicos'),
                            'active' => request()->routeIs('admin.tecnicos'),
                            'icon' => 'fas fa-sim-card',
                            'can' => ['Gestion de Equipos-Cuadrillas'],
                        ],
                    ],
                ],
                [
                    'name' => 'Reportes',
                    'url' => route('admin.reportes.index'),
                    'active' => request()->routeIs('admin.reportes.*'),
                    'icon' => 'fas fa-file-excel',
                    'can' => ['Gestion de Reportes'],
                ],
            ]
        ],

        // ADMINISTRACIÓN Y AUDITORÍA
        [
            'section' => 'Organización & Control',
            'items' => [
                [
                    'name' => 'Gestión Organizacional',
                    'icon' => 'fas fa-sitemap',
                    'submenu' => [
                        [
                            'name' => 'Procesos de Auditoría',
                            'url' => route('admin.areas.index'),
                            'active' => request()->routeIs('admin.areas.*'),
                            'icon' => 'fas fa-building',
                            'can' => ['Gestion de cuadrillas'],
                        ],
                        [
                            'name' => 'Calendario Auditorías',
                            'url' => route('admin.calendar.index'),
                            'active' => request()->routeIs('admin.calendar.*'),
                            'icon' => 'fas fa-calendar-alt',
                            'can' => ['Auditorias'],
                        ],
                        [
                            'name' => 'Auditorías Internas',
                            'url' => route('admin.auditorias.index'),
                            'active' => request()->routeIs('admin.auditorias.*'),
                            'icon' => 'fas fa-check-circle',
                            'can' => ['Auditorias'],
                        ],
                        [
                            'name' => 'Cuadrillas',
                            'url' => route('admin.cuadrillas.index'),
                            'active' => request()->routeIs('admin.cuadrillas.*'),
                            'icon' => 'fas fa-users-gear',
                            'can' => ['Gestion de cuadrillas'],
                        ],
                    ],
                ],
                [
                    'name' => 'Gestión de Contenidos',
                    'icon' => 'fas fa-folder-open',
                    'submenu' => [
                        [
                            'name' => 'Categorías',
                            'url' => route('admin.categories.index'),
                            'active' => request()->routeIs('admin.categories.*'),
                            'icon' => 'fas fa-inbox',
                            'can' => ['Gestion de Categorias'],
                        ],
                        [
                            'name' => 'Artículos',
                            'url' => route('admin.posts.index'),
                            'active' => request()->routeIs('admin.posts.*'),
                            'icon' => 'fas fa-blog',
                            'can' => ['Gestion de Articulos'],
                        ],
                    ],
                ],
            ]
        ],

        // SEGURIDAD Y SOPORTE
        [
            'section' => 'Seguridad & Soporte',
            'items' => [
                [
                    'name' => 'Gestión de Accesos',
                    'icon' => 'fas fa-shield-halved',
                    'submenu' => [
                        [
                            'name' => 'Roles',
                            'url' => route('admin.roles.index'),
                            'active' => request()->routeIs('admin.roles.*'),
                            'icon' => 'fas fa-user-tag',
                            'can' => ['Gestion de roles'],
                        ],
                        [
                            'name' => 'Permisos',
                            'url' => route('admin.permissions.index'),
                            'active' => request()->routeIs('admin.permissions.*'),
                            'icon' => 'fas fa-key',
                            'can' => ['Gestion de permisos'],
                        ],
                        [
                            'name' => 'Usuarios',
                            'url' => route('admin.users.index'),
                            'active' => request()->routeIs('admin.users.*'),
                            'icon' => 'fas fa-users',
                            'can' => ['Gestion de Usuarios'],
                        ],
                        [
                            'name' => 'Credenciales',
                            'url' => route('admin.credenciales.index'),
                            'active' => request()->routeIs('admin.credenciales.*'),
                            'icon' => 'fas fa-id-card',
                            'can' => ['Gestion de Credenciales'],
                        ],
                    ],
                ],
                [
                    'name' => 'Soporte & Incidentes',
                    'icon' => 'fas fa-headset',
                    'submenu' => [
                        [
                            'name' => 'Incidentes',
                            'url' => route('admin.incidentes.index'),
                            'active' => request()->routeIs('admin.incidentes.*'),
                            'icon' => 'fas fa-triangle-exclamation',
                            'can' => ['Incidentes'],
                        ],
                        [
                            'name' => 'Tickets',
                            'url' => route('admin.tickets.index'),
                            'active' => request()->routeIs('admin.tickets.*'),
                            'icon' => 'fas fa-ticket',
                            'can' => ['Gestion de Tickets'],
                        ],
                    ],
                ],
            ]
        ],
    ];
@endphp

<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 transition-transform -translate-x-full bg-white border-r border-gray-200/80 sm:translate-x-0 dark:bg-gray-900 dark:border-gray-800"
    aria-label="Sidebar" 
    :class="{ '-translate-x-full': !open, 'transform-none': open }">
    
    <div class="h-full px-3 py-4 overflow-y-auto bg-white dark:bg-gray-900 space-y-6 custom-scrollbar">
        @foreach ($links as $group)
            <div>
                <!-- Encabezado de Sección TailAdmin -->
                <h3 class="mb-2 px-3 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                    {{ $group['section'] }}
                </h3>

                <ul class="space-y-1 font-medium">
                    @foreach ($group['items'] as $link)
                        {{-- Ítem con Submenú --}}
                        @if (isset($link['submenu']))
                            @php
                                $hasSubmenuPermission = collect($link['submenu'])->contains(function($sub) {
                                    return auth()->user()->canany($sub['can'] ?? []);
                                });
                                $isSubmenuActive = collect($link['submenu'])->contains(fn($sub) => $sub['active']);
                            @endphp

                            @if ($hasSubmenuPermission)
                                <li x-data="{ openMenu: {{ $isSubmenuActive ? 'true' : 'false' }} }">
                                    <button @click="openMenu = !openMenu"
                                        class="flex items-center w-full p-2.5 text-xs font-medium rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100/80 dark:hover:bg-gray-800/60 hover:text-gray-900 dark:hover:text-white transition-all group {{ $isSubmenuActive ? 'bg-gray-50 dark:bg-gray-800/40 text-indigo-600 dark:text-indigo-400 font-semibold' : '' }}">
                                        
                                        <i class="{{ $link['icon'] }} w-5 h-5 flex items-center justify-center text-sm text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300 transition-colors {{ $isSubmenuActive ? 'text-indigo-600 dark:text-indigo-400' : '' }}"></i>
                                        <span class="flex-1 ms-3 text-left truncate">{{ $link['name'] }}</span>
                                        
                                        <svg class="w-3.5 h-3.5 transform transition-transform duration-200 text-gray-400"
                                            :class="{ 'rotate-180': openMenu }" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>

                                    <!-- Submenú Desplegable con Línea Guía -->
                                    <ul x-show="openMenu" 
                                        x-transition:enter="transition ease-out duration-150"
                                        x-transition:enter-start="opacity-0 -translate-y-1"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-100"
                                        x-transition:leave-start="opacity-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 -translate-y-1"
                                        class="mt-1 pl-4 ms-3.5 space-y-1 border-l-2 border-gray-100 dark:border-gray-800">
                                        
                                        @foreach ($link['submenu'] as $sub)
                                            @canany($sub['can'] ?? [])
                                                <li>
                                                    <a href="{{ $sub['url'] }}"
                                                        class="flex items-center w-full p-2 text-xs font-medium transition-all rounded-lg {{ $sub['active'] ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100/60 dark:hover:bg-gray-800/40 hover:text-gray-900 dark:hover:text-white' }}">
                                                        <i class="{{ $sub['icon'] }} w-4 text-center me-2 text-[11px] {{ $sub['active'] ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400' }}"></i>
                                                        <span class="truncate">{{ $sub['name'] }}</span>
                                                    </a>
                                                </li>
                                            @endcanany
                                        @endforeach
                                    </ul>
                                </li>
                            @endif

                        {{-- Ítem Individual --}}
                        @else
                            @canany($link['can'] ?? [])
                                <li>
                                    <a href="{{ $link['url'] }}"
                                        class="flex items-center p-2.5 text-xs font-medium rounded-xl transition-all group {{ $link['active'] ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100/80 dark:hover:bg-gray-800/60 hover:text-gray-900 dark:hover:text-white' }}">
                                        <i class="{{ $link['icon'] }} w-5 h-5 flex items-center justify-center text-sm text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300 transition-colors {{ $link['active'] ? 'text-indigo-600 dark:text-indigo-400' : '' }}"></i>
                                        <span class="ms-3 truncate">{{ $link['name'] }}</span>
                                    </a>
                                </li>
                            @endcanany
                        @endif
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</aside>
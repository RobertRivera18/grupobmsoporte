@php
    $links = [
        // ==========================
        // 📊 GENERAL
        // ==========================
        [
            'name' => 'Dashboard',
            'url' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.dashboard'),
            'icon' => 'fas fa-gauge-simple-high',
            'can' => ['Acceso al Dashboard'],
        ],

        [
            'name' => 'EPP',
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
                    'active' => request()->routeIs('admin.devoluciones.devoluciones.*'),
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
            'name' => 'Reportes',
            'url' => route('admin.reportes.index'),
            'active' => request()->routeIs('admin.reportes.*'),
            'icon' => 'fas fa-file-excel',
            'can' => ['Gestion de Reportes'],
        ],

        // ==========================
        // 📝 CONTENIDOS
        // ==========================
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

        // ==========================
        // 👥 ORGANIZACIÓN
        // ==========================
        [
            'name' => 'Gestión Organizacional',
            'icon' => 'fas fa-building',
            'submenu' => [
                [
                    'name' => 'Procesos de Auditoria',
                    'url' => route('admin.areas.index'),
                    'active' => request()->routeIs('admin.areas.*'),
                    'icon' => 'fas fa-building',
                    'can' => ['Gestion de cuadrillas'],
                ],
                [
                    'name' => 'Cuadrillas',
                    'url' => route('admin.cuadrillas.index'),
                    'active' => request()->routeIs('admin.cuadrillas.*'),
                    'icon' => 'fas fa-users',
                    'can' => ['Gestion de cuadrillas'],
                ],
            ],
        ],

        // ==========================
        // 🖥️ ACTIVOS / EQUIPOS
        // ==========================
        [
            'name' => 'Gestión de Equipos',
            'icon' => 'fas fa-computer',
            'submenu' => [
                [
                    'name' => 'Equipos',
                    'url' => route('admin.equipos.index'),
                    'active' => request()->routeIs('admin.equipos.*'),
                    'icon' => 'fas fa-computer',
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
                    'icon' => 'fas fa-tools',
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

        // ==========================
        // 🔐 SEGURIDAD
        // ==========================
        [
            'name' => 'Gestión de Accesos',
            'icon' => 'fas fa-shield-alt',
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

        // ==========================
        // 🚨 SOPORTE / INCIDENTES
        // ==========================
        [
            'name' => 'Soporte & Incidentes',
            'icon' => 'fas fa-triangle-exclamation',
            'submenu' => [
                [
                    'name' => 'Incidentes',
                    'url' => route('admin.incidentes.index'),
                    'active' => request()->routeIs('admin.incidentes.*'),
                    'icon' => 'fas fa-exclamation-triangle',
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
    ];
@endphp

<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
    aria-label="Sidebar" :class="{ '-translate-x-full': !open, 'transform-none': open }">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
            @foreach ($links as $link)
                {{-- 🔹 Si el ítem tiene submenú --}}
                @if (isset($link['submenu']))
                    <li x-data="{ open: {{ collect($link['submenu'])->contains(fn($item) => $item['active']) ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="flex items-center w-full p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700 transition duration-75 group">
                            <i class="{{ $link['icon'] }}"></i>
                            <span class="flex-1 ms-3 text-left whitespace-nowrap">{{ $link['name'] }}</span>
                            <svg class="w-3 h-3 transform transition-transform duration-200"
                                :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 4 4 4-4" />
                            </svg>
                        </button>

                        <ul x-show="open" x-transition class="py-2 space-y-1 ml-6">
                            @foreach ($link['submenu'] as $sub)
                                @canany($sub['can'] ?? [])
                                    <li>
                                        <a href="{{ $sub['url'] }}"
                                            class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ $sub['active'] ? 'bg-gray-300 dark:bg-gray-900' : '' }}">
                                            <i class="{{ $sub['icon'] }} w-4"></i>
                                            <span class="ms-2">{{ $sub['name'] }}</span>
                                        </a>
                                    </li>
                                @endcanany
                            @endforeach
                        </ul>
                    </li>
                @else
                    {{-- 🔹 Ítem normal --}}
                    @canany($link['can'] ?? [])
                        <li>
                            <a href="{{ $link['url'] }}"
                                class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700 group {{ $link['active'] ? 'bg-gray-300 dark:bg-gray-900' : '' }}">
                                <i class="{{ $link['icon'] }}"></i>
                                <span class="ms-3">{{ $link['name'] }}</span>
                            </a>
                        </li>
                    @endcanany
                @endif
            @endforeach
        </ul>
    </div>
</aside>

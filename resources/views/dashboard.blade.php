<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Bienvenido al Sistema Helpdesk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">¡Bienvenido!</h1>
                <p class="text-gray-600 mb-6">
                    Este sistema de Helpdesk está diseñado para gestionar solicitudes de soporte de manera eficiente y organizada. Aquí puedes crear, dar seguimiento y resolver tickets de tus usuarios o clientes.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Crear Tickets -->
                    <div class="bg-blue-50 p-6 rounded-lg shadow hover:shadow-lg transition flex items-start gap-4">
                        <i class="fas fa-plus-circle text-blue-500 text-3xl"></i>
                        <div>
                            <h2 class="text-xl font-semibold mb-1">Crear Tickets</h2>
                            <p class="text-gray-600">
                                Permite registrar nuevas solicitudes de soporte indicando la categoría, prioridad y descripción del problema.
                            </p>
                        </div>
                    </div>

                    <!-- Dar Seguimiento -->
                    <div class="bg-green-50 p-6 rounded-lg shadow hover:shadow-lg transition flex items-start gap-4">
                        <i class="fas fa-eye text-green-500 text-3xl"></i>
                        <div>
                            <h2 class="text-xl font-semibold mb-1">Dar Seguimiento</h2>
                            <p class="text-gray-600">
                                Consulta el estado de tus tickets, actualiza información y agrega comentarios para mantener una comunicación clara.
                            </p>
                        </div>
                    </div>

                    <!-- Gestión de Prioridades -->
                    <div class="bg-yellow-50 p-6 rounded-lg shadow hover:shadow-lg transition flex items-start gap-4">
                        <i class="fas fa-exclamation-circle text-yellow-500 text-3xl"></i>
                        <div>
                            <h2 class="text-xl font-semibold mb-1">Gestión de Prioridades</h2>
                            <p class="text-gray-600">
                                Organiza los tickets según su urgencia y criticidad, garantizando que los problemas más importantes se resuelvan primero.
                            </p>
                        </div>
                    </div>

                    <!-- Resolución y Cierre -->
                    <div class="bg-red-50 p-6 rounded-lg shadow hover:shadow-lg transition flex items-start gap-4">
                        <i class="fas fa-check-circle text-red-500 text-3xl"></i>
                        <div>
                            <h2 class="text-xl font-semibold mb-1">Resolución y Cierre</h2>
                            <p class="text-gray-600">
                                Una vez resuelto el problema, puedes cerrar el ticket y notificar al usuario, manteniendo un historial completo de soporte.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Botón de login -->
                <div class="mt-8 flex justify-center">
                    <a href="{{ route('login') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg shadow transition flex items-center gap-2">
                        <i class="fas fa-sign-in-alt"></i>
                        Iniciar Sesión
                    </a>
                </div>

                <p class="mt-6 text-gray-500 text-sm text-center">
                    Navega fácilmente y descubre todas las funcionalidades de nuestro sistema de soporte.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>

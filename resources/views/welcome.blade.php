{{-- <x-app-layout>
   
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
</x-app-layout>
 --}}


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
                @livewire('login-boton')
                <p class="mt-6 text-gray-500 text-sm text-center">
                    Navega fácilmente y descubre todas las funcionalidades de nuestro sistema de soporte.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>

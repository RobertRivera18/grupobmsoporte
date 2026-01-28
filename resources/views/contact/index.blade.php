<x-app-layout>
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-lg shadow p-8">
            <x-validation-errors :errors="$errors"  class="mb-4"/>
            <form action="{{route('contact.store')}}" class="max-w-xl mx-auto bg-white p-6 rounded-2xl shadow-xl space-y-6" method="POST">
                @csrf

                <div>
                    <x-label class="block text-gray-700 font-semibold mb-2">Nombre</x-label>
                    <x-input name="name" value="{{old('name')}}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        type="text" placeholder="Ingrese el nombre de contacto" />
                </div>

                <div>
                    <x-label class="block text-gray-700 font-semibold mb-2">Correo</x-label>
                    <x-input name="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        type="email" placeholder="Ingrese un correo electrónico" />
                </div>

                <div>
                    <x-label class="block text-gray-700 font-semibold mb-2">Mensaje</x-label>
                    <x-textarea name="message"
                        class="w-full h-32 px-4 py-2 border border-gray-300 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Ingrese mensaje de contacto">
                    </x-textarea>
                </div>

                <div class="text-right">
                    <x-button 
                        class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        Enviar
                    </x-button>
                </div>
            </form>

        </div>
    </section>
</x-app-layout>

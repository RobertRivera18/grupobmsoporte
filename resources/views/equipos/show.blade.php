<x-app-layout>


    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <h1 class="text-center">Equipo Asignados a <span class="font-medium">{{ $user->name }}</span>: </h1>

                    <th scope="col" class="px-6 py-3">
                        Nombre
                    </th>

                    <th scope="col" class="px-6 py-3">
                        Marca
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Modelo
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Serie
                    </th>

                </tr>
            </thead>
            <tbody>

                @foreach ($user->equipos as $equipo)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">


                        <td class="px-6 py-4">
                            {{ $equipo->nombre }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $equipo->marca }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $equipo->modelo }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $equipo->serie }}
                        </td>


                    </tr>
                @endforeach


            </tbody>
        </table>
    </div>
</x-app-layout>

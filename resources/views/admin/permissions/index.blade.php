<x-admin-layout :breadcrumbs="[
[
'name'=>'Home',
'url'=>route('admin.dashboard')

],
[
'name'=>'Permisos',
'url'=>route('admin.permissions.index')
],


]">

<x-slot name="action">
    <a class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700"
    href="{{ route('admin.permissions.create') }}">Nuevo</a>
</x-slot>
    
    @if ($permissions->count())
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Id
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nombre
                        </th>

                        <th scope="col" class="px-6 py-3">

                        </th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($permissions as $permission)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $permission->id }}
                            </th>

                            <td class="px-6 py-4">
                                {{ $permission->name }}
                            </td>


                            <td class="px-6 py-4">
                                <a class="text-red-500 font-semibold"
                                    href="{{ route('admin.permissions.edit', $permission) }}">Editar</a>
                            </td>
                        </tr>
                    @endforeach


                </tbody>
            </table>
        </div>
    @else
    <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
        <span class="font-medium">Informacion!</span> Aun no tiene permisos Registrados.
      </div>

    @endif

</x-admin-layout>

<div class="space-y-6 mt-6">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-sm text-gray-500">Total Auditorías</p>
            <p class="text-3xl font-bold text-blue-600">
                {{ $totalAuditorias }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-sm text-gray-500">Planificadas</p>
            <p class="text-3xl font-bold text-yellow-600">
                {{ $planificadas }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-sm text-gray-500">Procesos Auditados</p>
            <p class="text-3xl font-bold text-green-600">
                {{ $totalProcesos }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-sm text-gray-500">Áreas Involucradas</p>
            <p class="text-3xl font-bold text-purple-600">
                {{ $totalAreas }}
            </p>
        </div>

    </div>

    @if($proximaAuditoria)
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-2">
                Próxima Auditoría
            </h3>

            <div class="flex flex-col md:flex-row md:justify-between">
                <div>
                    <p>Año: {{ $proximaAuditoria->anio }}</p>
                    <p>Estado: {{ ucfirst($proximaAuditoria->estado) }}</p>
                </div>

                <div>
                    <p>Inicio: {{ $proximaAuditoria->fecha_inicio->format('d/m/Y') }}</p>
                    <p>Fin: {{ $proximaAuditoria->fecha_fin->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow">
        <div class="px-6 py-4 border-b">
            <h3 class="font-semibold text-gray-700">
                Últimas Auditorías
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
              
                        <th class="px-4 py-3 text-left">Inicio</th>
                        <th class="px-4 py-3 text-left">Fin</th>
                        <th class="px-4 py-3 text-left">Procesos</th>
                        <th class="px-4 py-3 text-left">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ultimasAuditorias as $auditoria)
                        <tr class="border-t">
                           

                            <td class="px-4 py-3">
                                {{ $auditoria->fecha_inicio->format('d/m/Y') }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $auditoria->fecha_fin->format('d/m/Y') }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $auditoria->procesos_count }}
                            </td>

                            <td class="px-4 py-3">
                                @switch($auditoria->estado)
                                    @case('planificada')
                                        <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-800">
                                            Planificada
                                        </span>
                                    @break

                                    @case('en_proceso')
                                        <span class="px-2 py-1 rounded bg-blue-100 text-blue-800">
                                            En Proceso
                                        </span>
                                    @break

                                    @case('finalizada')
                                        <span class="px-2 py-1 rounded bg-green-100 text-green-800">
                                            Finalizada
                                        </span>
                                    @break

                                    @default
                                        <span class="px-2 py-1 rounded bg-gray-100 text-gray-800">
                                            {{ $auditoria->estado }}
                                        </span>
                                @endswitch
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
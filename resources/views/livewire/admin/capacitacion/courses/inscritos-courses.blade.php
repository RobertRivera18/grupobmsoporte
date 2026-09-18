<div class="max-w-7xl mx-auto space-y-6">

    <!-- Cabecera -->
    <div
        class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <span
                class="text-xs font-semibold text-blue-600 uppercase tracking-wider bg-blue-50 px-2.5 py-1 rounded-full">Gestión
                de Participantes</span>
            <h1 class="text-xl font-bold text-slate-800 mt-2">{{ $course->name }}</h1>
            <p class="text-sm text-slate-500 mt-1">Control de avance y estado de los colaboradores inscritos.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm font-medium text-slate-600 bg-slate-100 px-3.5 py-2 rounded-xl">
                Total inscritos: <strong class="text-slate-900">{{ $course->students()->count() }}</strong>
            </span>
        </div>
    </div>

    <!-- Contenido de la Tabla -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 space-y-4">

        <!-- Buscador -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="w-full sm:w-72 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <i class="fas fa-search text-sm"></i>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="w-full pl-9 pr-4 py-2 text-sm rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Buscar colaborador...">
            </div>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto w-full rounded-xl border border-slate-200">
            <table class="min-w-full bg-white divide-y divide-slate-200 text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wider">
                        <th class="px-6 py-3 text-left font-semibold">Colaborador</th>
                        <th class="px-6 py-3 text-left font-semibold">Inscripción</th>
                        <th class="px-6 py-3 text-left font-semibold">Progreso</th>
                        <th class="px-6 py-3 text-left font-semibold">Estado</th>
                        <th class="px-6 py-3 text-right font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">{{ $user->name }}</div>
                                <div class="text-xs text-slate-500">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 text-xs">
                                {{ $user->pivot->enrolled_at ? \Carbon\Carbon::parse($user->pivot->enrolled_at)->format('d/m/Y H:i') : ($user->pivot->created_at ? $user->pivot->created_at->format('d/m/Y H:i') : 'N/D') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-full bg-slate-200 rounded-full h-2.5 max-w-[100px]">
                                        <div class="bg-blue-600 h-2.5 rounded-full"
                                            style="width: {{ $user->pivot->progress ?? 0 }}%"></div>
                                    </div>
                                    <span
                                        class="text-xs font-semibold text-slate-700">{{ $user->pivot->progress ?? 0 }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $status = $user->pivot->status;
                                @endphp
                                <span
                                    class="px-2.5 py-1 text-xs font-semibold rounded-full 
                                        {{ $status == 2 || $status == 'completed' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">
                                    {{ $status == 2 || $status == 'completed' ? 'Completado' : 'En Curso' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $status = $user->pivot->status;
                                    $isCompleted =
                                        $status == 2 || $status == 'completed' || $user->pivot->progress == 100;
                                @endphp

                                @if ($isCompleted)
                                    <a href="{{ route('admin.capacitacion.courses.certificate', [$course, $user]) }}"
                                        target="_blank"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-lg text-xs font-semibold transition shadow-sm"
                                        title="Generar Certificado">
                                        <i class="fa-solid fa-certificate"></i>
                                        <span>Certificado</span>
                                    </a>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-400 rounded-lg text-xs font-medium cursor-not-allowed"
                                        title="El colaborador aún no completa el curso">
                                        <i class="fa-solid fa-certificate"></i>
                                        <span>Certificado</span>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="fas fa-users-slash text-3xl"></i>
                                    <p class="text-sm">No hay colaboradores inscritos en este curso.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="pt-4">
            {{ $users->links() }}
        </div>

    </div>
</div>

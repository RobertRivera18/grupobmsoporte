<div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow-sm border border-gray-100">

    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm font-semibold text-blue-600 uppercase tracking-wider">
            <span>Paso 1</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-800 mt-1">Seleccione Entorno</h2>
        <p class="text-sm text-gray-500">Elija el tipo de ubicación donde se utilizará el generador eléctrico para
            personalizar los equipos disponibles.</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

        <label
            class="relative flex flex-col items-center justify-center p-6 rounded-xl border-2 bg-blue-50/50 border-blue-600 shadow-sm cursor-pointer transition-all duration-200 group hover:border-blue-600">
            <input type="radio" name="entorno" value="residencial" checked class="sr-only">
            <div
                class="p-3 bg-blue-100 text-blue-600 rounded-lg group-hover:scale-105 transition-transform duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <span class="mt-4 font-bold text-gray-800 text-sm tracking-wide uppercase">Residencial</span>
            <span
                class="absolute top-3 right-3 flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-white text-xs">
                ✓
            </span>
        </label>

        <label
            class="relative flex flex-col items-center justify-center p-6 rounded-xl border-2 border-gray-200 bg-white cursor-pointer transition-all duration-200 group hover:border-blue-400 hover:bg-gray-50/50">
            <input type="radio" name="entorno" value="comercial" class="sr-only">
            <div
                class="p-3 bg-gray-100 text-gray-600 rounded-lg group-hover:scale-105 transition-transform duration-200 group-hover:text-blue-500 group-hover:bg-blue-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <span
                class="mt-4 font-bold text-gray-700 text-sm tracking-wide uppercase group-hover:text-gray-900">Comercial</span>
        </label>

        <label
            class="relative flex flex-col items-center justify-center p-6 rounded-xl border-2 border-gray-200 bg-white cursor-pointer transition-all duration-200 group hover:border-blue-400 hover:bg-gray-50/50">
            <input type="radio" name="entorno" value="industrial" class="sr-only">
            <div
                class="p-3 bg-gray-100 text-gray-600 rounded-lg group-hover:scale-105 transition-transform duration-200 group-hover:text-blue-500 group-hover:bg-blue-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    <path d="M3 21h18M3 7l5 4V7l5 4V7l6 5v9H3V7z" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <span
                class="mt-4 font-bold text-gray-700 text-sm tracking-wide uppercase group-hover:text-gray-900">Industrial</span>
        </label>

        <label
            class="relative flex flex-col items-center justify-center p-6 rounded-xl border-2 border-gray-200 bg-white cursor-pointer transition-all duration-200 group hover:border-blue-400 hover:bg-gray-50/50">
            <input type="radio" name="entorno" value="eventos" class="sr-only">
            <div
                class="p-3 bg-gray-100 text-gray-600 rounded-lg group-hover:scale-105 transition-transform duration-200 group-hover:text-blue-500 group-hover:bg-blue-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
            </div>
            <span
                class="mt-4 font-bold text-gray-700 text-sm tracking-wide uppercase group-hover:text-gray-900">Eventos</span>
        </label>

        <label
            class="relative flex flex-col items-center justify-center p-6 rounded-xl border-2 border-gray-200 bg-white cursor-pointer transition-all duration-200 group hover:border-blue-400 hover:bg-gray-50/50">
            <input type="radio" name="entorno" value="campamento" class="sr-only">
            <div
                class="p-3 bg-gray-100 text-gray-600 rounded-lg group-hover:scale-105 transition-transform duration-200 group-hover:text-blue-500 group-hover:bg-blue-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 22L12 3l6 19M6 22h12M12 3v19M9 14h6" />
                </svg>
            </div>
            <span
                class="mt-4 font-bold text-gray-700 text-sm tracking-wide uppercase group-hover:text-gray-900">Campamento</span>
        </label>

    </div>

    <div class="mt-6 flex justify-end">
        <button type="button"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-800 hover:bg-gray-900 text-white font-semibold text-sm rounded-lg transition-colors duration-150 shadow-sm">
            Siguiente
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>

</div>

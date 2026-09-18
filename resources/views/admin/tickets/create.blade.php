<x-admin-layout>

@push('css')
    <style>
        #my-dropzone.dropzone {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 1rem;
        }

        #my-dropzone.dropzone .dz-message {
            width: 100%;
            margin: auto !important;
        }

        #my-dropzone.dropzone.dz-started .dz-message {
            display: none !important;
        }

        #my-dropzone.dropzone .dz-preview {
            width: 132px !important;
            margin: 0 !important;
            padding: 0.625rem;
            display: inline-flex;
            flex-direction: column;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        #my-dropzone.dropzone .dz-preview .dz-image {
            width: 104px !important;
            height: 88px !important;
            margin: 0 auto 0.5rem;
            border-radius: 0.5rem;
            overflow: hidden;
            background: #f3f4f6;
        }

        #my-dropzone.dropzone .dz-preview .dz-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #my-dropzone.dropzone .dz-preview .dz-details {
            position: static !important;
            width: 100%;
            padding: 0 !important;
            text-align: center;
            color: #4b5563;
            font-size: 0.7rem;
            opacity: 1 !important;
        }

        #my-dropzone.dropzone .dz-preview .dz-filename {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        #my-dropzone.dropzone .dz-preview .dz-remove {
            margin-top: 0.5rem;
            color: #dc2626 !important;
            border: 0 !important;
            background: transparent !important;
            text-decoration: none;
            font-size: 0.7rem;
            font-weight: 600;
        }

        #editor-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.125rem;
        }

        #editor, #editor .ql-editor {
            min-height: 210px;
        }

        @media (max-width: 640px) {
            #my-dropzone.dropzone {
                min-height: 150px;
                padding: 0.75rem;
            }

            #my-dropzone.dropzone .dz-preview {
                width: calc(50% - 0.375rem) !important;
            }

            #my-dropzone.dropzone .dz-preview .dz-image {
                width: 100% !important;
                height: 90px !important;
            }
        }
    </style>
@endpush

<div class="min-h-screen bg-slate-50/70">
    <div class="mx-auto w-full max-w-7xl px-3 py-5 sm:px-5 sm:py-7 lg:px-8">

        {{-- Header --}}
        <div class="mb-5 sm:mb-7">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex min-w-0 items-start gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-600/20 sm:h-12 sm:w-12">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">
                                Crear nuevo ticket
                            </h1>
                            <span class="hidden rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-semibold text-blue-700 sm:inline-flex">
                                HelpDesk
                            </span>
                        </div>
                        <p class="mt-1 max-w-2xl text-xs leading-5 text-slate-500 sm:text-sm">
                            Describe tu requerimiento para que el equipo de soporte pueda ayudarte.
                        </p>
                    </div>
                </div>

                <a href="{{ route('admin.tickets.index') }}"
                   class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-400 hover:bg-slate-50 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:w-auto">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver al listado
                </a>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <x-validation-errors :errors="$errors" class="m-3 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700 sm:m-5" />

            <form action="{{ route('admin.tickets.store') }}" method="POST" enctype="multipart/form-data" class="divide-y divide-slate-100" id="ticket-form">
                @csrf

                {{-- Sección 1: Información Básica --}}
                <div class="p-4 sm:p-6 lg:p-7">
                    <div class="mb-5 flex items-start gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6.586a1 1 0 01.707.293l4.414 4.414A1 1 0 0119 8.414V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 sm:text-base">Información del requerimiento</h2>
                            <p class="mt-0.5 text-xs text-slate-500">Indica brevemente qué necesitas solucionar.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        {{-- Título --}}
                        <div>
                            <label for="titulo" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Título del requerimiento <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" maxlength="255" placeholder="Ej. No puedo acceder al sistema"
                                   class="block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-3 text-sm text-slate-900 shadow-sm transition hover:border-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                            <p class="mt-1.5 text-xs text-slate-400">Resume el problema en una frase.</p>
                            @error('titulo')
                                <span class="mt-1.5 block text-xs font-medium text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Categoría --}}
                        <div>
                            <label for="category_id" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Categoría <span class="text-red-500">*</span>
                            </label>
                            <x-select name="category_id" id="category_id"
                                      class="block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-3 text-sm text-slate-900 shadow-sm transition hover:border-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                                <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Seleccione una categoría</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </x-select>
                            <p class="mt-1.5 text-xs text-slate-400">Selecciona el área relacionada con tu solicitud.</p>
                            @error('category_id')
                                <span class="mt-1.5 block text-xs font-medium text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Sección 2: Adjuntos --}}
                <div class="p-4 sm:p-6 lg:p-7">
                    <div class="mb-5 flex items-start gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-violet-50 text-violet-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.586-6.586a4 4 0 00-5.656-5.656l-6.586 6.586a6 6 0 108.485 8.485L20.5 13" />
                            </svg>
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="text-sm font-bold text-slate-900 sm:text-base">Archivos y adjuntos</h2>
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-500">Opcional</span>
                            </div>
                            <p class="mt-0.5 text-xs text-slate-500">Agrega capturas o documentos que ayuden a entender el problema.</p>
                        </div>
                    </div>

                    <div id="my-dropzone" class="dropzone min-h-[165px] w-full cursor-pointer rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 transition hover:border-blue-400 hover:bg-blue-50/30">
                        <div class="dz-message m-0 flex flex-col items-center justify-center px-4 py-8 text-center">
                            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M7 16a4 4 0 01-.88-7.903A5.002 5.002 0 0116.9 6.1 5 5 0 0118 16h-1m-5-4v8m0 0l-3-3m3 3l3-3" />
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-slate-700">Arrastra tus archivos aquí</p>
                            <p class="mt-1 text-xs text-slate-500">o <span class="font-semibold text-blue-600">haz clic para explorar</span></p>
                            <p class="mt-2 text-[11px] text-slate-400">PNG, JPG, PDF, DOC, DOCX · Máximo 8 MB por imagen</p>
                        </div>
                    </div>

                    <div class="mt-3 flex min-h-[20px] items-center justify-between gap-3">
                        <span id="files-summary" class="text-xs font-medium text-blue-600"></span>
                        <span class="text-[11px] text-slate-400">Los archivos se procesan antes del envío.</span>
                    </div>

                    <input type="file" name="documentos[]" id="documentos-real" class="hidden" multiple>

                    @error('documentos.*')
                        <span class="mt-1.5 block text-xs font-medium text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Sección 3: Descripción --}}
                <div class="p-4 sm:p-6 lg:p-7">
                    <div class="mb-5 flex items-start gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h8M8 14h5m-9 7h10a4 4 0 004-4V7a4 4 0 00-4-4H7a4 4 0 00-4 4v10a4 4 0 004 4z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 sm:text-base">Descripción detallada</h2>
                            <p class="mt-0.5 text-xs text-slate-500">Proporciona toda la información necesaria para diagnosticar el requerimiento.</p>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">
                            Detalla tu requerimiento <span class="text-red-500">*</span>
                        </label>

                        <div class="overflow-hidden rounded-xl border border-slate-300 bg-white shadow-sm transition focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/10">
                            <div id="editor-toolbar" class="border-0 border-b border-slate-200 bg-slate-50 px-2 py-2 sm:px-3">
                                <span class="ql-formats">
                                    <button class="ql-bold" type="button"></button>
                                    <button class="ql-italic" type="button"></button>
                                    <button class="ql-underline" type="button"></button>
                                    <button class="ql-strike" type="button"></button>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-blockquote" type="button"></button>
                                    <button class="ql-code-block" type="button"></button>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-header" value="1" type="button"></button>
                                    <button class="ql-header" value="2" type="button"></button>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-list" value="ordered" type="button"></button>
                                    <button class="ql-list" value="bullet" type="button"></button>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-link" type="button"></button>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-clean" type="button"></button>
                                </span>
                            </div>

                            <div id="editor" class="min-h-[210px] border-0 bg-white text-sm text-slate-900"></div>
                        </div>

                        <input type="hidden" name="descripcion" id="descripcion" value="{{ old('descripcion') }}">

                        <div class="mt-2 flex items-start gap-2 text-xs text-slate-400">
                            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18 9 9 0 000 18z" />
                            </svg>
                            <span>Describe qué ocurrió, cuándo ocurrió y, si aplica, los pasos necesarios para reproducir el problema.</span>
                        </div>

                        @error('descripcion')
                            <span class="mt-1.5 block text-xs font-medium text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Footer con Botón Submit --}}
                <div class="flex items-center justify-end bg-slate-50/50 px-4 py-4 sm:px-6 lg:px-7">
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20 sm:w-auto">
                        Guardar ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
    <script>
        Dropzone.autoDiscover = false;
        document.addEventListener('DOMContentLoaded', function () {
            const quill = new Quill('#editor', {
                modules: { toolbar: '#editor-toolbar' },
                theme: 'snow'
            });

            const hiddenDesc = document.querySelector('#descripcion');
            if (hiddenDesc && hiddenDesc.value) {
                quill.clipboard.dangerouslyPasteHTML(hiddenDesc.value);
            }
            const fileInput = document.querySelector('#documentos-real');
            const summarySpan = document.querySelector('#files-summary');
            const dataTransfer = new DataTransfer();

            const myDropzone = new Dropzone('#my-dropzone', {
                url: '#',
                autoProcessQueue: false,
                uploadMultiple: true,
                addRemoveLinks: true,
                dictRemoveFile: 'Eliminar',
                maxFilesize: 8,
                acceptedFiles: 'image/*,application/pdf,.doc,.docx',
            });
            function syncFiles() {
                dataTransfer.items.clear();
                myDropzone.files.forEach(file => {
                    dataTransfer.items.add(file);
                });
                fileInput.files = dataTransfer.files;

                const count = myDropzone.files.length;
                if (count > 0) {
                    summarySpan.textContent = `${count} archivo(s) seleccionado(s)`;
                } else {
                    summarySpan.textContent = '';
                }
            }
            myDropzone.on('addedfile', function(file) {
                syncFiles();
            });

            myDropzone.on('removedfile', function(file) {
                syncFiles();
            });

            const form = document.querySelector('#ticket-form');
            form.addEventListener('submit', function () {
                hiddenDesc.value = quill.root.innerHTML === '<p><br></p>' ? '' : quill.root.innerHTML;
            });
        });
    </script>
@endpush

</x-admin-layout>
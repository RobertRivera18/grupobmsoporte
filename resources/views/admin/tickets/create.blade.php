<x-admin-layout>
    @push('css')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
    @endpush

    <div class="p-6 bg-white rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Desde esta ventana podrá generar nuevos tickets de HelpDesk.</h2>
        <x-validation-errors :errors="$errors" class="mb-4" />
        <form action="{{ route('admin.tickets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <h3 class="text-lg font-bold">Ingresar Información</h3>

            <div>
                <label class="block font-semibold">Título</label>
                <input type="text" name="titulo" placeholder="Ingrese Titulo"
                    class="w-full mt-1 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                @error('titulo')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold">Categoría</label>
                    <x-select name="category_id"
                        class="w-full mt-1 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </x-select>
                </div>

                <div>
                    <label class="block font-semibold">Documentos Adicionales</label>
                    <input type="file" name="documentos[]" multiple
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0 file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />

                </div>
            </div>

            <div>
                <label class="block font-semibold">Descripción</label>
                <textarea name="descripcion" rows="6" id="descripcion"
                    class="w-full mt-1 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300"
                    placeholder="Describa el problema o requerimiento..."></textarea>
                @error('descripcion')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="text-right">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Guardar Ticket
                </button>
            </div>


        </form>
    </div>
    @push('js')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#descripcion').summernote({
                    placeholder: 'Describa el problema o requerimiento...',
                    tabsize: 2,
                    height: 200,
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link', 'picture']],
                        ['view', ['codeview']] 
                    ],
                    styleTags: ['p', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5'],
                });

            });
        </script>
    @endpush


</x-admin-layout>

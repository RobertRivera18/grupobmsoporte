<x-admin-layout :breadcrumbs="[
[
'name'=>'Home',
'url'=>route('admin.dashboard')

],
[
'name'=>'Articulos',
'url'=>route('admin.posts.index')
],
[
'name'=>'Nuevo',
],


]">
    <h1 class="text-2xl  font-semibold mb-2">
        Nuevo Articulo
    </h1>
    <form action="{{ route('admin.posts.store') }}" method="POST" x-data="data()" x-init="$watch('title', value => { string_to_slug(value) })">
        @csrf


        <x-validation-errors :errors="$errors" class="mb-4" />

        <div class="mb-4">
            <x-label class="mb-2">
                Titulo del Articulo
            </x-label>

            <x-input name="title" class="w-full" value="{{ old('title') }}"
                placeholder="Ingrese el nombre del Articulo" x-model="title" />
        </div>



        <div class="mb-4">
            <x-label class="mb-2">
                Slug
            </x-label>

            <x-input name="slug" class="w-full" value="{{ old('slug') }}"
                placeholder="Ingrese el slug del Articulo" x-model="slug" />
        </div>

        <div class="mb-4">
            <x-label class="mb-2">
                Categoria
            </x-label>

            <x-select class="w-full" name="category_id">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </x-select>
        </div>

        <div class="flex justify-end">
            <x-button>
                Crear Articulo
            </x-button>
        </div>

    </form>
    @push('js')
        <script>
            function data() {
                return {
                    title: '',
                    slug: '',
                    string_to_slug(str) {
                        str = str.replace(/^\s+|\s+$/g, '');
                        str = str.toLowerCase();
                        var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
                        var to = "aaaaeeeeiiiioooouuuunc------";
                        for (var i = 0, l = from.length; i < l; i++) {
                            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
                        }
                        str = str.replace(/[^a-z0-9 -]/g, '')
                            .replace(/\s+/g, '-')
                            .replace(/-+/g, '-');
                        this.slug = str;
                    }
                }
            }
        </script>
    @endpush
</x-admin-layout>

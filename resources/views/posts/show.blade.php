<x-app-layout>
    <section class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
            <section class="col-span-1 lg:col-span-3">
                <ul class="flex space-x-2 mb-2">
                    @foreach ($post->tags as $tag)
                        <li>
                            <a href="">
                                <span
                                    class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-blue-900 dark:text-blue-300">{{ $tag->name }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                <h1 class="text-4xl font-semibold">
                    {{ $post->title }}
                </h1>

                <hr class="mt-1 mb-2">
                <div class="flex items-center mb-6">
                    <figure class="mr-4">
                        <img class="h-8 w-8 rounded-full object-cover" src="{{ $post->user->profile_photo_url }}">
                    </figure>

                    <div>
                        <p class="font-semibold">{{ $post->user->name }}</p>
                        <p class="text-sm">{{ $post->published_at->format('d M Y') }}</p>
                    </div>
                </div>

                <figure class="mb-8">
                    <img class="aspect-[16/9] w-full object-cover object-center rounded-lg" src="{{ $post->image }}"
                        alt="">
                </figure>

                <div class="mb-16 text-justify">
                    {{ $post->body }}
                </div>

            </section>

            <aside class="col-span-1 hidden lg:block">
                <h1 class="text-2xl font-semibold mb-6">Artículos Similares</h1>

                <ul class="space-y-4">
                    @foreach ($postRelacionados as $relacionado)
                        <li class="grid grid-cols-2 gap-2">

                            <a href="{{ route('posts.show', $relacionado) }}"
                                class="block">

                                <figure>
                                    <img class="aspect-[16/9] object-cover object-center rounded-md"
                                        src="{{$relacionado->image}}"
                                        alt="">
                                </figure>

                            </a>
                            <div><a href="{{ route('posts.show', $relacionado) }}" class="block">
                                </a>
                                <h1 class="text-sm font-semibold"><a
                                        href="{{ route('posts.show', $relacionado) }}"
                                        class="block">
                                    </a><a href="{{ route('posts.show', $relacionado)}} ">
                                        {{ $relacionado->title }}
                                    </a>
                                </h1>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </aside>
        </div>

</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mis documentos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('carpetas.store') }}" method="post" class="flex">
                        @csrf
                        <input type="text" name="nombre" id="nombre" placeholder="Nombre de la carpeta">
                        <button type="submit" class="px-4 py-2 font-semibold text-sm bg-sky-500 text-white rounded-none shadow-sm">Nueva carpeta</button>
                    </form>
                </div>

                <div class="p-14">
                    @forelse ($carpetas as $carpeta)
                        <a href="{{ route('carpetas.show', $carpeta) }}" class="text-center inline-block">
                            <figure>
                                <img src="/images/folder.png" class="w-20" alt="">
                            </figure>
                            {{ $carpeta->nombre }}
                        </a>
                    @empty
                        Sin ítems
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

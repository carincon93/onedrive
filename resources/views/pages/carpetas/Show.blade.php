<x-app-layout>
    <x-slot name="header">
        @if ($carpeta->children_id)
            <a href="{{ route('carpetas.show', $carpeta->children_id) }}" class="font-semibold text-xl text-gray-800 leading-tight flex">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                </svg>

                {{ $carpeta->carpeta->nombre }}
            </a>
        @else
            <div class="font-semibold text-xl text-gray-800 leading-tight">
                <a href="{{ route('carpetas.index') }}">
                    Mis documentos
                </a>
                / {{ $carpeta->nombre }}
            </div>
        @endif
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('carpetas-hijas.store', $carpeta) }}" method="post" class="flex">
                        @csrf
                        <input type="text" name="nombre" id="nombre" placeholder="Nombre de la carpeta">
                        <button type="submit" class="px-4 py-2 font-semibold text-sm bg-sky-500 text-white rounded-none shadow-sm">Nueva carpeta</button>
                    </form>
                </div>

                <div class="py-14">
                    @foreach ($carpetas_hijas as $carpeta_hija)
                        <div class="w-48 flex flex-col items-center mb-4">
                            <a href="{{ route('carpetas.show', $carpeta_hija) }}" class="text-center inline-block">
                                <figure>
                                    <img src="/images/folder.png" class="w-20" alt="">
                                </figure>
                                {{ $carpeta_hija->nombre }}
                            </a>

                            <form action="{{ route('carpetas.destroy', $carpeta_hija) }}" class="mt-6" method="POST">
                                @method('DELETE')
                                @csrf

                                <button>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <div>
                    <div class="pl-6 mb-10">Archivos cargados</div>
                    @forelse ($carpeta->archivos()->get() as $archivo)
                        <div class="w-48 flex flex-col items-center mb-4">
                            <a href="{{ route('archivos.download', $archivo) }}" target="_blank" download class="flex-col p-6 inline-flex items-center justify-center text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>

                                {{ $archivo->nombre }}
                            </a>
                            <form action="{{ route('archivos.destroy', $archivo) }}" method="POST">
                                @method('DELETE')
                                @csrf

                                <button>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="m-6 text-xs">
                            Aún no se han cargado archivos en esta carpeta
                        </p>
                    @endforelse
                </div>
            </div>
            <div class="p-10 border rounded-lg">
                <h1 class="text-center text-3xl my-20">¿Desea cargar algún archivo?</h1>
                <form action="{{ route('archivos.store', $carpeta) }}" class="flex w-full" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" id="file" class="w-full bg-white p-6">
                    <button type="submit" class="px-4 py-2 font-semibold text-sm bg-sky-500 text-white rounded-none shadow-sm">Cargar archivo</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

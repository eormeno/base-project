<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Patio de Juegos
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($gameApps as $videojuego)
                @if ($videojuego->active)
                    <div
                        class="bg-white shadow-lg rounded-lg overflow-hidden transform hover:scale-105 transition duration-300">
                        <!-- Imagen del videojuego -->
                        <img src="{{ asset('storage/' . $videojuego->image) }}" alt="{{ $videojuego->name }}"
                            class="w-full h-48 object-cover">

                        <div class="p-5 relative h-48">
                            <!-- Nombre del videojuego -->
                            <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $videojuego->name }}</h2>
                            <!-- Descripción con un máximo alto de 40px -->
                            <p class="text-gray-600 text-xs mb-4 h-15 overflow-hidden">
                                {{ Str::limit($videojuego->description, 200) }}</p>
                            <!-- Botón de Jugar siempre abajo -->
                            <div class="">
                                @if ($videojuego->active)
                                    <a href="{{ route('play', $videojuego) }}"
                                        class="block w-32 text-center bg-green-500 hover:bg-blue-600 text-white font-bold py-2 rounded-lg absolute bottom-2 right-2">
                                        Jugar
                                    </a>
                                @else
                                    <a href="#"
                                        class="block w-32 text-center bg-gray-500 text-white font-bold py-2 rounded-lg cursor-not-allowed absolute bottom-2 right-2">
                                        Jugar
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</x-app-layout>

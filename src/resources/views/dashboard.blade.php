<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Videojuegos</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($gameApps as $videojuego)
                <div
                    class="bg-white shadow-lg rounded-lg overflow-hidden transform hover:scale-105 transition duration-300">
                    <!-- Imagen del videojuego -->
                    <img src="{{ asset('storage/' . $videojuego->image) }}" alt="{{ $videojuego->name }}" class="w-full h-48 object-cover">

                    <div class="p-5">
                        <!-- Nombre del videojuego -->
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $videojuego->name }}</h2>
                        <!-- Descripción con un máximo alto de 40px -->
                        <p class="text-gray-600 text-sm mb-4 h-10 overflow-hidden">
                            {{ Str::limit($videojuego->description, 200) }}</p>
                        <!-- Botón de Jugar -->
                        <a href="{{ route('play', $videojuego) }}"
                            class="block w-full text-center bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 rounded-lg">
                            Jugar
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>

<x-app-layout>

    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="text-center p-4 relative">
        <x-event-listener />
        {{ $slot }}
    </div>

</x-app-layout>

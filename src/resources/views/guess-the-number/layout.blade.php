<x-app-layout>

    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('guess-the-number.title') }}
        </h2>
    </x-slot>

    <x-event-listener />

    <div class="text-center p-4 relative">
        <span class="text-xs text-gray-500 dark:text-gray-400">
            <x-event-renderer event="server_time_changed" />
        </span>
        {{ $slot }}
    </div>

</x-app-layout>

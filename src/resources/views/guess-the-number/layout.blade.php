<x-app-layout>

    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="text-center p-3 relative">
        <x-event-listener />
        {{ $slot }}
        {{-- A collapsible/expenddible debug console --}}
        <details class="absolute top-0 right-0 w-3/4">
            <summary class="cursor-pointer text-slate-600 p-1 text-right"></summary>
            {{-- A Clear console button --}}
            <x-button class="active:" onclick="document.getElementById('debug-console').textContent = ''"><x-clear-icon/></x-button>
            <div id="debug-console"
                class="text-left text-gray-200 font-mono text-xs p-2 bg-slate-900 border border-collapse shadow-lg h-64 max-h-64 overflow-y-scroll">
            </div>
        </details>
    </div>

</x-app-layout>

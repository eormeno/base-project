<x-app-layout>

    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="text-center p-5 relative">
        <x-event-listener />
        {{ $slot }}
        {{-- A collapsible/expenddible debug console --}}
        <details class="absolute top-0 right-0 w-4/5">
            <summary class="cursor-pointer text-slate-600 text-right"></summary>
            {{-- A Clear console button --}}
            <div class="w-full text-left">
                <x-button class="absolute top-0 left-0" onclick="document.getElementById('debug-console').textContent = ''"><x-clear-icon /></x-button>
                <div id="debug-console"
                    class="absolute top-10 left-0 text-left text-gray-200 font-mono text-xs p-2 bg-slate-900 border shadow-lg h-64 max-h-64 overflow-y-scroll w-full">
                </div>
            </div>
        </details>
    </div>

</x-app-layout>

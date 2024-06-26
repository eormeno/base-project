<div
    class="p-4 bg-white
    dark:bg-gray-900
    dark:bg-gradient-to-bl
    dark:from-gray-800/50
    dark:via-transparent
    border-b border-gray-200 dark:border-gray-800">

    <div x-init="setInterval(() => { $wire.updateState(); }, 100)" />

    @switch($ui_state)
        @case('buscando oponente')
            <div class="mt-3 align-middle">
                <h1 class="mt-3 text-2xl font-medium text-gray-900 dark:text-white text-center">
                    {{ __('Buscando oponente') }} ({{ $current_state_remaining_time }})
                </h1>
            </div>
        @break

        @case ('oponente encontrado')
            <div class="mt-3 align-middle">
                <h1 class="mt-3 text-2xl font-medium text-gray-900 dark:text-white text-center">
                    {{ __('Oponente encontrado') }}
                </h1>
            </div>
        @break

        @case('pedir jugada')
            <div class="align-middle">
                <h1 class="mt-3 text-2xl font-medium text-gray-900 dark:text-white">
                    {{ __('Ronda') }} : {{ $ronda }}
                </h1>
                <!-- the player points and the opponent points -->
                <h1 class="mt-3 text-2xl font-medium text-gray-900 dark:text-white">
                    {{ __('Tu puntaje: ') }} {{ $puntaje_jugador }}
                </h1>
                <h1 class="mt-3 text-2xl font-medium text-gray-900 dark:text-white">
                    {{ __('Oponente: ') }} {{ $puntaje_oponente }}
                </h1>
                <h1 class="mt-3 mb-2 text-2xl font-medium text-gray-900 dark:text-white">
                    {{ __('Elige tu jugada') }}
                </h1>
                <button wire:click="juegoPropio(0)"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-700 border border-transparent rounded-md shadow-sm hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Papel</button>
                <button wire:click="juegoPropio(1)"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-700 border border-transparent rounded-md shadow-sm hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-geen-500">Piedra</button>
                <button wire:click="juegoPropio(2)"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-700 border border-transparent rounded-md shadow-sm hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Tijeras</button>
            </div>
        @break

        @case('mostrar resultado ronda')
            <div class="mt-3 align middle">
                <h1 class="mt-3 text-2xl font-medium text-gray-900 dark:text-white text-center">
                    {{ __('Resultado') }} : {{ $resultado_ronda }}
                </h1>
            </div>
        @break

        @case('mostrar número ronda')
            <div class="mt-3 align middle">
                <h1 class="mt-3 text-2xl font-medium text-gray-900 dark:text-white text-center">
                    {{ __('Ronda') }} : {{ $ronda }}
                </h1>
            </div>
        @break

        @case('mostrar resultado juego')
            <div class="mt-3 text-center">
                <h1 class="mt-3 text-2xl font-medium text-gray-900 dark:text-white">
                    {{ __('Tu: ') }} {{ $puntaje_jugador }}
                </h1>
                <h1 class="mt-3 text-2xl font-medium text-gray-900 dark:text-white">
                    {{ __('Oponente: ') }} {{ $puntaje_oponente }}
                </h1>
                <h1 class="mt-3 text-3xl font-semibold text-gray-900 dark:text-white">
                    {{ $resultado_juego }}
                </h1>
            </div>
        @break

        @case ('fin')
            <button wire:click="clear"
                class="inline-flex px-4 py-2 text-sm font-medium text-white bg-gray-700 border border-transparent rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Jugar de nuevo
            </button>
        @break

        @default
    @endswitch

    <livewire:debug-bar :$current_state_name :$current_state_remaining_time :$delta_time :player_name="$jugador" />

</div>

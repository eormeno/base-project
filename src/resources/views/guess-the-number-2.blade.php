<x-app-layout>

    @php($game_info = session('game_info'))

    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('guess-the-number.title') }}
        </h2>
    </x-slot>

    <div class="text-center p-4">

        <p class="mt-6 text-lg text-gray-900 dark:text-white text-center">
            {{ __('guess-the-number.description', [
                'remaining_attemts' => $game_info['remaining_attempts'],
                'min_number' => $game_info['min_number'],
                'max_number' => $game_info['max_number'],
            ]) }}
        </p>

        <p class="mt-6 text-lg text-gray-900 dark:text-white text-center">
            {{ $game_info['random_number'] }}
        </p>
        @if ($game_info['message'])
            <p class=" inline-block items-center justify-center p-2 text-xl font-bold text-white bg-green-700 border border-transparent rounded-md shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-geen-500 duration-1000"
                role="alert">
                {{ $game_info['message'] }}
            </p>
        @endif

        @if ($game_info['state'] == 2)
            <form action="{{ route('guess-the-number.guess') }}" method="POST" class="mt-6">
                @csrf
                <input type="number" name="number" id="number"
                    placeholder="{{ __('guess-the-number.enter_number') }}"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    required>
                <button type="submit"
                    class="mt-4 w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('guess-the-number.submit') }}
                </button>
            </form>
        @endif

        @if ($game_info['state'] == 5)
            <!-- A button with link to play again -->
            <a href="{{ route('guess-the-number.play-again') }}"
                class="mt-4 w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('guess-the-number.play-again') }}
            </a>
        @endif


        <p class="mt-6 text-lg text-gray-900 dark:text-white text-center">
            {{ $game_info['state'] }}
        </p>

        <!-- A button with link to reset route -->
        <a href="{{ route('guess-the-number.reset') }}"
            class="mt-4 w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            {{ __('guess-the-number.reset') }}
        </a>

    </div>
</x-app-layout>

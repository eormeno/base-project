<x-app-layout>

    @php($info = session('info'))

    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('guess-the-number.title') }}
        </h2>
    </x-slot>

    <div class="text-center p-4">

        @if ($info['state'] == 'asking_to_play')
            <div class="mt-6 text-lg text-gray-900 dark:text-white text-center">
                {{ $info['description'] }}
            </div>

            <x-button class="mt-4" type="button"
                onclick="window.location='{{ route('guess-the-number.want-to-play') }}'">
                {{ __('guess-the-number.want-to-play') }}
            </x-button>
        @endif

        @if ($info['message'])
            <p class=" inline-block items-center justify-center p-2 text-xl font-bold text-white bg-green-700 border border-transparent rounded-md shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-geen-500 duration-1000"
                role="alert">
                {{ $info['message'] }}
            </p>
        @endif

        @if ($info['state'] == 'playing')
            <p class="mt-6 text-lg text-gray-900 dark:text-white text-center">
                @if ($info['remaining_attempts'] == 1)
                    {{ __('guess-the-number.last_attempt') }}
                @else
                    {{ __('guess-the-number.remaining', [
                        'remaining_attemts' => $info['remaining_attempts'],
                    ]) }}
                @endif
            </p>
            <form action="{{ route('guess-the-number.guess') }}" method="POST" class="mt-6">
                @csrf
                <div>
                    <x-label for="number" value="{{ __('guess-the-number.enter_number') }}" />
                    <x-input id="number" class="block mt-1 w-full" type="number" name="number" :value="old('number')"
                        required autofocus />
                </div>
                <x-button class="mt-4">
                    {{ __('guess-the-number.submit') }}
                </x-button>
            </form>
        @endif

        @if ($info['state'] == 'asking_for_play_again')
            <div class="mt-6">
                <x-button class="mt-4" type="button"
                    onclick="window.location='{{ route('guess-the-number.play-again') }}'">
                    {{ __('guess-the-number.play-again') }}
                </x-button>
            </div>
        @endif
    </div>

    <div class="p-1 text-xs border rounded-sm border-gray-600 m-3">
        <a href="{{ route('guess-the-number.reset') }}"
            class="mt-2 w-min flex justify-center py-2 px-4 border border-transparent rounded-md text-xs font-extralight text-slate-8 bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 opacity-15">
            {{ __('guess-the-number.reset') }}
        </a>
        <!-- Debugging information-->
        <div class="mt-2 text-gray-500">
            <div>
                {{ __('state = ') }} "{{ $info['state'] }}"
            </div>
            @if ($info['random_number'])
                <div>
                    {{ __('random_number = ') }} {{ $info['random_number'] }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

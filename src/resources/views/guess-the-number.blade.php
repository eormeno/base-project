<x-app-layout>

    @php($info = session('info'))

    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('guess-the-number.title') }}
        </h2>
    </x-slot>

    <x-event-listener event="state_changed">
        <x-button class="mt-4" type="button">{{ __('guess-the-number.reset') }}</x-button>
    </x-event-listener>

    <div x-data="invokeRoute()">
        <button @click="invokeRoute">Invoke Route</button>
    </div>

    <script>
        function invokeRoute() {
            return {
                invokeRoute() {
                    fetch('{{ route('guess-the-number.event') }}', {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel CSRF token
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            // if data is empty, do nothing
                            if (Object.keys(data).length === 0) {
                                return;
                            }
                            console.log('Success:', data);
                            // handle the response data here
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                        });
                }
            }
        }
    </script>

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

        @livewire('debug-bar', ['info' => $info, 'include' => ['state', 'random_number'], 'reset_route' => 'guess-the-number.reset'])

        <x-toast :show="$info['message']" :duration="5000">
            {{ $info['message'] }}
        </x-toast>

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
</x-app-layout>

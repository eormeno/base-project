<x-app-layout>

    @php($info = session('info'))

    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('guess-the-number.title') }}
        </h2>
    </x-slot>

    <x-event-listener />

    <div style="display: block">
        <div x-data="invokeRoute()">
            <button @click="invokeRoute">Invoke Route</button>
        </div>

        <script>
            function invokeRoute() {
                return {
                    invokeRoute() {
                        fetch('{{ route('trigger-event-test') }}', {
                                method: 'GET',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
    </div>

    <div class="text-center p-4">

        <!--
        <x-button class="mt-4" type="button">
            <x-event-renderer event="server_time_changed" />
        </x-button>
        -->

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

        <x-toast name="success">
            <div
                class="fixed w-1/2 inset-x-0 top-1/2 transform -translate-y-1/2 translate-x-1/2 flex items-center justify-center p-6 text-xl font-bold text-white bg-green-700 border border-transparent rounded-md shadow-lg">
                <x-toast-message />
            </div>
        </x-toast>

        <x-toast name="error">
            <div
                class="fixed w-1/2 inset-x-0 top-1/2 transform -translate-y-1/2 translate-x-1/2 flex items-center justify-center p-6 text-xl font-bold text-white bg-red-700 border border-transparent rounded-md shadow-lg">
                <x-toast-message />
            </div>
        </x-toast>

        <x-toast name="warning">
            <div
                class="fixed w-1/2 inset-x-0 top-1/2 transform -translate-y-1/2 translate-x-1/2 flex items-center justify-center p-6 text-xl font-bold text-white bg-yellow-700 border border-transparent rounded-md shadow-lg">
                <x-toast-message />
            </div>
        </x-toast>

        @if ($info['state'] == 'playing')
            <p class="mt-6 text-lg text-gray-900 dark:text-white text-center">
                {{ $info['notification'] }}
                @if ($info['remaining_attempts'] == 1)
                    {{ __('guess-the-number.last_attempt') }}
                @else
                    {{ __('guess-the-number.remaining', [
                        'remaining_attemts' => $info['remaining_attempts'],
                    ]) }}
                @endif
            </p>

            <form class="w-1/2 mt-4 border p-2 mx-auto" action="{{ route('guess-the-number.guess') }}" method="POST" class="mt-6">
                @csrf
                <div>
                    <x-label for="number" value="{{ __('guess-the-number.enter_number') }}" />
                    <x-input id="number" class="block mt-1 w-full" type="number" name="number" :value="old('number')"
                        required autofocus />
                    <x-input-error for="number" class="mt-2" />
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

<x-app-layout>

    @php($info = session('info'))

    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('guess-the-number.title') }}
            <!--
            <span class="text-xs text-gray-500 dark:text-gray-400">
                <x-event-renderer event="server_time_changed" />
            </span>
            -->
        </h2>
    </x-slot>

    <x-event-listener />

    <div style="display: none">
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

    <x-toast name="success">
        <div
            class="fixed w-1/2 inset-x-0 top-1/4 transform -translate-y-1/2 translate-x-1/2 flex items-center justify-center p-6 text-xl font-bold text-white bg-green-700 border border-transparent rounded-md shadow-lg">
            <x-toast-message />
        </div>
    </x-toast>

    <x-toast name="error">
        <div
            class="fixed w-1/2 inset-x-0 top-1/4 transform -translate-y-1/2 translate-x-1/2 flex items-center justify-center p-6 text-xl font-bold text-white bg-red-700 border border-transparent rounded-md shadow-lg">
            <x-toast-message />
        </div>
    </x-toast>

    <x-toast name="warning">
        <div
            class="fixed w-1/2 inset-x-0 top-1/4 transform -translate-y-1/2 translate-x-1/2 flex items-center justify-center p-6 text-xl font-bold text-white bg-yellow-600 border border-transparent rounded-md shadow-lg">
            <x-toast-message />
        </div>
    </x-toast>

    <div class="text-center p-4">
        {{ $slot }}
    </div>

    @livewire('debug-bar', ['info' => $info, 'include' => ['state', 'random_number'], 'reset_route' => 'guess-the-number.reset'])

</x-app-layout>

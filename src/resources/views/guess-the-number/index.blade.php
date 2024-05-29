<x-guess-the-number-layout>
    <div style="display: block">

        <!--
        <button onclick="sendEvent()"
            class="inline-flex text-xs items-center justify-center p-1 m-2 font-thin text-white bg-blue-700 border border-transparent rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-blue-500">Send
            event</button>

        <a href="{ route('guess-the-number.reset') }}"
            class="inline-flex text-xs items-center justify-center p-1 m-2 font-thin text-white bg-red-700 border border-transparent rounded-md shadow-sm hover:bg-red-600 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-red-500">
            Reset
        </a>
        -->

        <script>
            // on page load run the sendEvent function
            window.onload = function() {
                sendEvent();
            }

            function sendEvent(event, formData = {}) {
                event = event || '';
                //document.getElementById('main').innerHTML = 'Loading...';
                fetch('{{ route('guess-the-number') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            event: event,
                            data: formData
                        })
                    })
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('main').innerHTML = data;
                    });
            }
        </script>
    </div>

    <div class="left-1/2 border mx-auto border-gray-600 rounded-md p-4 min-w-md max-w-md">
        <div class="relative">
            <div
                class="absolute top-2 w-3/4 left-1/2 transform -translate-x-1/2 z-50 items-center justify-center text-lg font-light text-white">

                <x-toast name="info">
                    <div class="bg-cyan-500 text-slate-800 shadow-lg p-6 border border-transparent rounded-md">
                        <x-toast-message />
                    </div>
                </x-toast>

                <x-toast name="success">
                    <div class="bg-green-700 shadow-lg p-6 border border-transparent rounded-md">
                        <x-toast-message />
                    </div>
                </x-toast>

                <x-toast name="error">
                    <div class="bg-red-700 shadow-lg p-6 border border-transparent rounded-md">
                        <x-toast-message />
                    </div>
                </x-toast>

                <x-toast name="warning">
                    <div class="bg-yellow-600 shadow-lg p-6 border border-transparent rounded-md">
                        <x-toast-message />
                    </div>
                </x-toast>
            </div>
        </div>

        <div id="main">
        </div>

        @livewire('debug-bar', ['include' => ['state', 'random_number'], 'reset_route' => 'guess-the-number.reset'])

    </div>
</x-guess-the-number-layout>

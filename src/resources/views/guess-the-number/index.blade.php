<x-guess-the-number-layout>
    <div style="display: block">

        <!--
        <button onclick="sendEvent()"
            class="inline-flex text-xs items-center justify-center p-1 m-2 font-thin text-white bg-blue-700 border border-transparent rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-blue-500">Send
            event</button>

        <a href="{{ route('guess-the-number.reset') }}"
            class="inline-flex text-xs items-center justify-center p-1 m-2 font-thin text-white bg-red-700 border border-transparent rounded-md shadow-sm hover:bg-red-600 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-red-500">
            Reset
        </a>
        -->

        <script>
            // on page load run the sendEvent function
            window.onload = function () {
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

    <div class="border mx-auto border-gray-600 rounded-md p-5 w-3/4">
        <div>
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
        </div>

        <div id="main" >
        </div>
    </div>
</x-guess-the-number-layout>

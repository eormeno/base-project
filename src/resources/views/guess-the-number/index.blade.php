<x-guess-the-number-layout>
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            previousData();
        });

        window.onload = function() {
            sendEvent();
        }

        function previousData() {
            let data = localStorage.getItem('guess-the-number');
            if (data) {
                document.getElementById('main').innerHTML = data;
            }
        }

        function previousData() {
            let data = localStorage.getItem('guess-the-number');
            if (data) {
                document.getElementById('main').innerHTML = data;
            }
        }

        function sendEvent(event, formData = {}) {
            event = event || '';
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
                    // if the data starts with a <!DOCTYPE html> tag, is an error page
                    if (data.startsWith('<!DOCTYPE html>')) {
                        document.write(data);
                    } else {
                        document.getElementById('main').innerHTML = data;
                        // ensure the html data with script tags is executed
                        document.getElementById('main').querySelectorAll('script').forEach(script => {
                            const newScript = document.createElement('script');
                            Array.from(script.attributes).forEach(attr => {
                                newScript.setAttribute(attr.name, attr.value);
                            });
                            newScript.appendChild(document.createTextNode(script.innerHTML));
                            script.parentNode.replaceChild(newScript, script);
                        });
                        localStorage.setItem('guess-the-number', data);
                    }
                });
        }
    </script>

    <div class="left-1/2 border border-slate-700 mx-auto rounded-md p-4 min-w-md max-w-md">
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

        @if ($debug)
            @livewire('debug-bar', ['include' => ['state', 'random_number'], 'reset_route' => 'guess-the-number.reset'])
        @endif

    </div>
</x-guess-the-number-layout>

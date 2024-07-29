<x-guess-the-number-layout>
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            // previousData();
        });

        window.onload = function() {
            sendEvent();
        }

        // function previousData() {
        //     let data = localStorage.getItem('{{ $routeName }}');
        //     if (data) {
        //         document.getElementById('main').innerHTML = data;
        //     }
        // }

        // find a parent div that has the "key" attribute
        function findParentWithKey(element) {
            let parent = element.parentElement;
            while (parent) {
                if (parent.getAttribute('key')) {
                    return parent;
                }
                parent = parent.parentElement;
            }
            return null;
        }

        function sendEvent(event, formData = {}) {
            // get the element that triggered this function
            const keyParent = findParentWithKey(document.activeElement);
            const source = keyParent ? keyParent.getAttribute('key') : null;
            // console.log('source: ', source);

            event = event || '';
            fetch("{{ route($routeName) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        event: event,
                        source: source,
                        data: formData
                    })
                })
                .then(response => response.text())
                .then(data => {
                    try {
                        // if the data starts with a <!DOCTYPE html> tag, is an error page
                        if (data.startsWith('<!DOCTYPE html>')) {
                            document.write(data);
                        } else {
                            json = JSON.parse(data);
                            // iterate over the json keys, find the element in the dom and update it
                            for (const key in json) {
                                const element = document.getElementById(key);
                                if (element) {
                                    $html = decodeBase64(json[key]);
                                    $html = '<div key="' + key + '">' + $html + '</div>';
                                    element.innerHTML = $html;
                                    runScripts(element);
                                    // localStorage.setItem('{{ $routeName }}', element);
                                }
                            }
                        }
                    } catch (error) {
                        document.write(data);
                    }
                });
        }

        function decodeBase64(data) {
            const binaryString = atob(data);
            const bytes = new Uint8Array(binaryString.length);
            for (let i = 0; i < binaryString.length; i++) {
                bytes[i] = binaryString.charCodeAt(i);
            }
            return new TextDecoder().decode(bytes);
        }

        function runScripts(domElement) {
            domElement.querySelectorAll('script').forEach(script => {
                const newScript = document.createElement('script');
                Array.from(script.attributes).forEach(attr => {
                    newScript.setAttribute(attr.name, attr.value);
                });
                newScript.appendChild(document.createTextNode(script.innerHTML));
                script.parentNode.replaceChild(newScript, script);
            });
        }
    </script>

    <x-slot name="title">
        {{ __("$routeName.title") }}
    </x-slot>

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
    </div>
</x-guess-the-number-layout>

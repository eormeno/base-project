<div id="pulling-result" class="animate-pulse left-1/2 mx-auto m-1 text-xs max-w-xs text-center font-mono text-gray-300 border-collapse bg-red-800 rounded-lg"></div>

<script>
    const fetchWithTimeout = async (interval) => {
        const resultDiv = document.getElementById('pulling-result');

        const fetchData = async (reloaded = 0) => {
            try {
                if (!navigator.onLine) {
                    resultDiv.textContent = 'Disconnected';
                    return;
                }
                let response = await fetch('{{ route('pull-events') }}' + '?reloaded=' + reloaded);
                if (response.ok) {
                    const data = await response.json();
                    if (data.length > 0) {
                        for (let event of data) {
                            displayLogEvent(event);
                            document.dispatchEvent(new CustomEvent(event.name, {
                                detail: event.data
                            }));
                        }
                    }
                } else {
                    console.error('Error en la respuesta:', response.status);
                    resultDiv.textContent = `Error: ${response.status} - ${response.statusText}`;
                }
            } catch (error) {
                resultDiv.textContent = `Disconnected`;
            } finally {
                // Llama a fetchData nuevamente después del intervalo
                setTimeout(fetchData, interval);
            }
        };

        fetchData(1); // Inicia la primera solicitud
    };

    fetchWithTimeout(500);

    function displayLogEvent(event) {
        if (event.name === 'log') {
            message = event.data.message;
            time = event.data.time;
            type = event.data.type;
            info_color = 'text-gray-200';
            time_color = 'text-gray-400';
            switch (type) {
				case 'log':
					info_color = 'text-gray-200';
					console.log(message);
					break;
                case 'info':
                    info_color = 'text-blue-300';
                    console.log('info:', message);
                    break;
                case 'error':
                    info_color = 'text-red-300';
                    console.error(message);
                    break;
                case 'warn':
                    info_color = 'text-yellow-300';
                    console.warn(message);
                    break;
                case 'success':
                    info_color = 'text-green-300';
                    console.log('success:', message);
                    break;
            }
            debugConsole = document.getElementById('debug-console');
            debugConsole.innerHTML += `<p class="${time_color}">${time}: <span class="${info_color}">${message}</span></p>`;
            debugConsole.scrollTop = debugConsole.scrollHeight;
        }
    }
</script>

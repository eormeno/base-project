<div id="polling-result" class="animate-pulse left-1/2 mx-auto m-1 text-xs max-w-xs text-center font-mono text-gray-300 border-collapse bg-red-800 rounded-lg"></div>

<script>
    const fetchWithTimeout = async (interval) => {
        const resultDiv = document.getElementById('polling-result');

        const fetchData = async (reloaded = 0) => {
            try {
                if (!navigator.onLine) {
                    resultDiv.textContent = 'Disconnected';
                    return;
                }
                let response = await fetch('{{ route('poll-events') }}' + '?reloaded=' + reloaded);
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

    // Llamada al servicio cada 5 segundos
    fetchWithTimeout(500);

    // function eventListener() {
    //     return {
    //         async fetchEvents(reloaded = 0) {
    //             try {
    //                 let response = await fetch('{{ route('poll-events') }}' + '?reloaded=' + reloaded);
    //                 if (response.ok) {
    //                     let event_data = await response.json();
    //                     if (event_data.length > 0) {
    //                         for (let event of event_data) {
    //                             displayLogEvent(event);
    //                             document.dispatchEvent(new CustomEvent(event.name, {
    //                                 detail: event.data
    //                             }));
    //                         }
    //                     }
    //                 }
    //             } catch (error) {
    //             }
    //         },

    //         startPolling() {
    //             this.fetchEvents(1);
    //             setInterval(() => {
    //                 try {
    //                     this.fetchEvents();
    //                 } catch (error) {
    //                     // finalization of polling


    //                 }
    //             }, 1000);
    //         }
    //     }
    // }

    function displayLogEvent(event) {
        if (event.name === 'log') {
            message = event.data.message;
            time = event.data.time;
            type = event.data.type;
            info_color = 'text-gray-200';
            time_color = 'text-gray-400';
            switch (type) {
                case 'info':
                    info_color = 'text-blue-300';
                    break;
                case 'error':
                    info_color = 'text-red-300';
                    break;
                case 'warn':
                    info_color = 'text-yellow-300';
                    break;
                case 'success':
                    info_color = 'text-green-300';
                    break;
            }
            debugConsole = document.getElementById('debug-console');
            debugConsole.innerHTML += `<p class="${time_color}">${time}: <span class="${info_color}">${message}</span></p>`;
            debugConsole.scrollTop = debugConsole.scrollHeight;
            //console.log(event.data);
        }
    }
</script>

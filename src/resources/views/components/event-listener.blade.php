<div x-data="eventListener('{{ $event }}')" x-init="startPolling()">

    <div id="{{ $event }}-event-container"></div>

    <script>
        function eventListener(event_name) {
            return {
                async fetchEvents() {
                    try {
                        let response = await fetch('{{ route('poll-events') }}');
                        if (response.ok) {
                            let event_data = await response.json();
                            if (event_data.length > 0) {
                                // iterate through the events
                                for (let event of event_data) {
                                    // check if the event data is the same as the data passed to the event listener
                                    if (event.name === event_name) {
                                        let eventContainer = document.getElementById("{{ $event }}-event-container");
                                        eventContainer.innerHTML = `
                                            <div>
                                                {{ $slot }}
                                            </div>
                                        `;
                                        return;
                                    }
                                }
                            }
                        }
                    } catch (error) {
                        console.error('Error fetching events:', error);
                    }
                },

                startPolling() {
                    this.fetchEvents();
                    setInterval(() => {
                        this.fetchEvents();
                    }, 100);
                }
            }
        }
    </script>
</div>

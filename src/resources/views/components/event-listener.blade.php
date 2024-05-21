<div x-data="eventListener()" x-init="startPolling()">
    <script>
        function eventListener(event_name) {
            return {
                async fetchEvents() {
                    try {
                        let response = await fetch('{{ route('poll-events') }}');
                        if (response.ok) {
                            let event_data = await response.json();
                            if (event_data.length > 0) {
                                for (let event of event_data) {
                                    let elementName = event.name + "-event-render";
                                    let eventRenderer = document.getElementById(elementName);
                                    if (eventRenderer == null) {
                                        console.warn('Event renderer not found:', elementName);
                                        continue;
                                    }
                                    eventRenderer.style.display = 'block';
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
                    }, 500);
                }
            }
        }
    </script>
</div>

<!-- resources/views/components/event-listener.blade.php -->

<div x-data="eventListener()" x-init="startPolling()">
    <div id="event-container">
        <!-- Events will be appended here -->
    </div>

    <script>
        function eventListener() {
            return {
                events: [],

                async fetchEvents() {
                    try {
                        let response = await fetch('{{ route("poll-events") }}');
                        if (response.ok) {
                            let data = await response.json();
                            if (data.length > 0) {
                                this.events.push(...data);
                                // Append the events to the event container
                                let eventContainer = document.getElementById('event-container');
                                // remove all child nodes
                                eventContainer.innerHTML = '';
                                this.events.forEach(event => {
                                    let eventElement = document.createElement('div');
                                    eventElement.textContent = event;
                                    eventContainer.appendChild(eventElement);
                                });
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
                    }, 1000);
                }
            }
        }
    </script>
</div>

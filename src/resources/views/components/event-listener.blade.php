<div x-data="eventListener()" x-init="startPolling()">
    <script>
        function eventListener() {
            return {
                async fetchEvents(reloaded = 0) {
                    try {
                        let response = await fetch('{{ route('poll-events') }}' + '?reloaded='+ reloaded);
                        if (response.ok) {
                            let event_data = await response.json();
                            if (event_data.length > 0) {
                                for (let event of event_data) {
                                    document.dispatchEvent(new CustomEvent(event.name, {
                                        detail: event.data
                                    }));
                                }
                            }
                        }
                    } catch (error) {
                        console.error('Error fetching events:', error);
                    }
                },

                startPolling() {
                    this.fetchEvents(1);
                    setInterval(() => {
                        this.fetchEvents();
                    }, 1000);
                }
            }
        }
    </script>
</div>

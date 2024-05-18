@if ($show)
    <div id="toast"
        class="inline-block items-center justify-center p-2 text-xl font-bold text-white bg-green-700 border border-transparent rounded-md shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-geen-500 duration-1000"
        role="alert">
        {{ $slot }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            setTimeout(() => {
                var toast = document.getElementById('toast');
                if (toast) {
                    toast.style.display = 'none';
                }
            }, {{ $duration }});
        });
    </script>
@endif

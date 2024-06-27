<button onclick="sendEvent('select',{'slug': '{{ $slug }}'})">
    <div
        class="w-12 h-12 m-1 text-gray-600 hover:bg-slate-700 hover:text-white flex items-center justify-center border rounded-md shadow-md relative">
        <img src="{{ asset('images/items/' . $icon) }}" alt="{{ $name }}" class="w-5 h-5">
        @if ($quantity > 0)
            <span class="flex h-4 w-4">
                <span
                    class="absolute bottom-2 right-2 transform translate-x-1/2 translate-y-1/2 inline-flex rounded-full h-4 w-4 bg-sky-700 text-xs text-white items-center justify-center">
                    <div>{{ $quantity }}</div>
                </span>
            </span>
        @endif
    </div>
</button>

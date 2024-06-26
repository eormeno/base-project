<button onclick="sendEvent('select',{'item':{{ $id }}})">
    <div
        class="w-12 h-12 m-1 text-gray-600 hover:bg-slate-700 hover:text-white flex items-center justify-center border rounded-md shadow-md">
        <img src="{{ asset('images/items/' . $icon) }}" alt="{{ $name }}" class="w-8 h-8">
    </div>
</button>

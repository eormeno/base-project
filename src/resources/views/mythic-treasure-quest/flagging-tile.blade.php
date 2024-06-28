<button onclick="sendEvent('tile_flag')"">
    <div
        class="w-10 h-10 bg-slate-800 text-gray-200 text-xs hover:bg-slate-700 flex items-center justify-center border rounded-md border-slate-600">
        <div>
            @if ($hasFlag)
                F
            @endif
        </div>
    </div>
</button>

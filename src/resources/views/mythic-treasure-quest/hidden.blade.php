<button onclick="sendEvent('tile_off_click')"">
    <div class="w-10 h-10 bg-slate-800 text-gray-500 text-xs hover:bg-slate-700 flex items-center justify-center">
        <p>
            @if (!$hasTrap && $trapsAround > 0)
                {{ $trapsAround }}
            @endif
        </p>
    </div>
</button>
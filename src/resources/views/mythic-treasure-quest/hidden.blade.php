<button onclick="sendEvent('tile_clicked')"">
    <div
        class="w-10 h-10 bg-slate-800 text-gray-500 text-xs hover:bg-slate-700 flex items-center justify-center border rounded-md border-slate-600">
        <div>
            @if ($parentModel->marked_as_clue)
                <span class="relative flex h-3 w-3">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-sky-500"></span>
                </span>
            @endif
        </div>
    </div>
</button>

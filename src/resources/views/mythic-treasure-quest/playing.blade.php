<div class="bg-gray-100 flex items-center justify-center">
    <div class="grid grid-cols-8 p-2">
        @for ($i = 0; $i < 8; $i++)
            @for ($j = 0; $j < 8; $j++)
                <div id="tile_{{ $i * 8 + $j }}" class="w-12 h-12 hover:bg-gray-300 flex items-center justify-center"></div>
                <!--
                <button
                    class="text-xs w-12 h-12 bg-white border hover:bg-gray-300 border-gray-300 flex items-center justify-center"
                    onclick="sendEvent('tile_click', { x: {{ $i }}, y: {{ $j }} })">
                </button>
            -->
            @endfor
        @endfor
    </div>
</div>

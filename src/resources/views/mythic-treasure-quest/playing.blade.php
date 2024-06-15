<!-- this is for ensure the tailwindcss classes are loaded -->
<div class="grid-cols-5"></div>
<div class="grid-cols-6"></div>
<div class="grid-cols-7"></div>
<div class="grid-cols-8"></div>
<div class="grid-cols-9"></div>
<div class="grid-cols-10"></div>
<div class="grid-cols-11"></div>
<div class="grid-cols-12"></div>

<div class="bg-gray-100 flex items-center justify-center">
    <div class="grid p-2 grid-cols-{{ $width }}">
        @for ($i = 0; $i < $width; $i++)
            @for ($j = 0; $j < $height; $j++)
                <div id="tile_{{ $i * $width + $j }}"
                    class="w-10 h-10 border hover:bg-gray-300 flex items-center justify-center">
                </div>
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

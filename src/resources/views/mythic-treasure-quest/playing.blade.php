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
        <!-- iterate all the $list -->
        @foreach ($list as $item)
            <div id="{{ $item }}"
                class="w-10 h-10 border hover:bg-gray-300 flex items-center justify-center text-xs">
            </div>
        @endforeach
        {{-- @for ($i = 0; $i < $width; $i++)
            @for ($j = 0; $j < $height; $j++)
                <div class="w-10 h-10 border hover:bg-gray-300 flex items-center justify-center text-xs">
                    <!--
                    <x-state-renderer name="tile" :id="$i * $width + $j" />
                    -->
                    <!-- a button to click on
                    <button class="w-10 h-10 border hover:bg-gray-300 flex items-center justify-center text-xs"
                        onclick="sendEvent('tile_click', { x: {{ $i }}, y: {{ $j }} })">
                    -->
                </div>
            @endfor
        @endfor --}}
    </div>
</div>

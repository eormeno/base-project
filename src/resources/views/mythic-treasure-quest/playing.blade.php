<div class="bg-gray-100 flex items-center justify-center">
    <div class="grid grid-cols-8 p-2">
        @for ($i = 0; $i < 8; $i++)
            @for ($j = 0; $j < 8; $j++)
                <div class="text-xs w-12 h-12 bg-white border border-gray-300 flex items-center justify-center">
                    <!--
                    {{ $i }},{{ $j }}
                    -->
                </div>
            @endfor
        @endfor
    </div>
</div>

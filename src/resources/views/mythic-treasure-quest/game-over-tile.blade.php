<div class="w-10 h-10 bg-red-950 text-gray-100 text-xs flex items-center justify-center">
    <div>
        @if ($model->has_trap)
            <div class="text-red-500 animate-pulse">
                <x-trap />
            </div>
        @elseif ($model->traps_around > 0)
            {{ $model->traps_around }}
        @endif
    </div>
</div>

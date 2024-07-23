<div class="w-10 h-10 bg-slate-600 text-gray-400 text-xs hover:bg-slate-500 flex items-center justify-center">
    <div>
        @if ($model->has_trap)
        <div class="text-red-500">
            <x-trap />
        </div>
        @endif
        @if (!$model->has_trap && $model->traps_around > 0)
            {{ $model->traps_around }}
        @endif
    </div>
</div>

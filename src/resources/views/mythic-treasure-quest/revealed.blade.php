<div class="w-10 h-10 bg-slate-600 text-gray-400 text-xs hover:bg-slate-500 flex items-center justify-center">
    <div>
        @if ($hasTrap)
        <div class="text-red-500">
            <x-trap />
        </div>
        @endif
        @if (!$hasTrap && $trapsAround > 0)
            {{ $trapsAround }}
        @endif
    </div>
</div>

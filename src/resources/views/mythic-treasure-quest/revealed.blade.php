<div class="w-10 h-10 bg-slate-700 text-gray-400 text-xs hover:bg-slate-500 flex items-center justify-center">
    <p>
        @if ($hasTrap)
        <div class="text-red-500">
            <x-trap />
        </div>
        @endif
        @if (!$hasTrap && $trapsAround > 0)
            {{ $trapsAround }}
        @endif
    </p>
</div>

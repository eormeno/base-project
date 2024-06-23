<div class="w-10 h-10 bg-slate-700 text-gray-300 text-xs hover:bg-slate-500 flex items-center justify-center">
    <p>
        @if ($hasTrap)
            X
        @endif
        @if (!$hasTrap && $trapsAround > 0)
            {{ $trapsAround }}
        @endif
    </p>
</div>

<div class="w-10 h-10 bg-red-950 text-gray-100 text-xs flex items-center justify-center">
    <div>
        @if ($hasTrap)
        <div class="text-red-500 animate-pulse">
            <x-trap />
        </div>
        @endif
        @if ($trapsAround > 0)
            {{ $trapsAround }}
        @endif
    </div>
</div>

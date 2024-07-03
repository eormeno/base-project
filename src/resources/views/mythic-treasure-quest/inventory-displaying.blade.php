<div class="flex items-center justify-center mb-1">
    <div class="grid grid-cols-{{ $itemsCount }}">
        @foreach ($items as $item)
            <div id="{{ $item }}"></div>
        @endforeach
    </div>
</div>

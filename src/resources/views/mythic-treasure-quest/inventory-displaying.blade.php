<div class="flex items-center justify-center mb-1">
    <div class="grid grid-cols-{{ count($items) }}">
        @foreach ($items as $item)
            <div id="{{ $item }}"></div>
        @endforeach
    </div>
</div>

<div class="flex items-center justify-center mt-3">
    <div class="grid grid-cols-3">
        @foreach ($items as $item)
            <div id="{{ $item }}"></div>
        @endforeach
    </div>
</div>

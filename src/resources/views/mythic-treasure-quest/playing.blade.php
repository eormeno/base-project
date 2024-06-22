<!-- this is for ensure the tailwindcss classes are loaded -->
<div class="grid-cols-5"></div>
<div class="grid-cols-6"></div>
<div class="grid-cols-7"></div>
<div class="grid-cols-8"></div>
<div class="grid-cols-9"></div>
<div class="grid-cols-10"></div>
<div class="grid-cols-11"></div>
<div class="grid-cols-12"></div>

<div class="flex items-center justify-center">
    <div class="grid grid-cols-{{ $width }}">
        @foreach ($list as $item)
            <div id="{{ $item }}">
            </div>
        @endforeach
    </div>
</div>

<!-- this is for ensure the tailwindcss classes are loaded -->
<div class="grid-cols-5"></div>
<div class="grid-cols-6"></div>
<div class="grid-cols-7"></div>
<div class="grid-cols-8"></div>
<div class="grid-cols-9"></div>
<div class="grid-cols-10"></div>
<div class="grid-cols-11"></div>
<div class="grid-cols-12"></div>

<div class="bg-gray-100 flex items-center justify-center">
    <div class="grid p-2 grid-cols-{{ $width }}">
        @foreach ($list as $item)
            <div id="{{ $item }}"
                class="flex items-center justify-center">
            </div>
        @endforeach
    </div>
</div>

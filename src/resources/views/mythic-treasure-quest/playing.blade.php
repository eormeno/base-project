<!-- this is for ensure the tailwindcss classes are loaded -->
<div class="grid-cols-5"></div>
<div class="grid-cols-6"></div>
<div class="grid-cols-7"></div>
<div class="grid-cols-8"></div>
<div class="grid-cols-9"></div>
<div class="grid-cols-10"></div>
<div class="grid-cols-11"></div>
<div class="grid-cols-12"></div>
<div class="grid-cols-13"></div>
<div class="grid-cols-14"></div>
<div class="grid-cols-15"></div>

<div class="flex items-center justify-center">
    <div class="grid grid-cols-{{ $width }} bg-slate-700">
        @foreach ($list as $item)
            <div id="{{ $item }}">
            </div>
        @endforeach
    </div>
</div>

<div id="inventory"></div>

@if ($playAgain)
    <button class="bg-blue-500 mt-4 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        onclick="sendEvent('play_again')">Play Again</button>
@endif

<!-- this is for ensure the tailwindcss classes are loaded -->
<div class="grid-cols-1"></div>
<div class="grid-cols-2"></div>
<div class="grid-cols-3"></div>
<div class="grid-cols-4"></div>
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
    <div class="grid grid-cols-{{ $width }} bg-slate-600">
        @foreach ($strArrTilesVID as $strTileVID)
            <div id="{{ $strTileVID }}">
            </div>
        @endforeach
    </div>
</div>

<div id="{{ $strInventoryVID }}" class="mt-2 border border-cyan-600 rounded-md shadow-md"></div>

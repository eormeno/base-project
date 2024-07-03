<div class="flex items-center justify-center">
    <div class="grid grid-cols-{{ $width }} bg-slate-600">
        @foreach ($strArrTilesVID as $strTileVID)
            <div id="{{ $strTileVID }}">
            </div>
        @endforeach
    </div>
</div>

<button class="bg-blue-500 mt-2 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded"
    onclick="sendEvent('play_again')">Play Again</button>

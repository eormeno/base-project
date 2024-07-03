<div class="flex items-center justify-center">
    <div class="grid grid-cols-{{ $width }} bg-slate-600">
        @foreach ($strArrTilesVID as $strTileVID)
            <div id="{{ $strTileVID }}">
            </div>
        @endforeach
    </div>
</div>
<button class="bg-red-500 hover:bg-red-700 text-white font-bold mt-2 py-2 px-4 rounded"
    onclick="sendEvent('cancelFlagging')">Cancel flagging</button>

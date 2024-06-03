<div>
    <div
        class="inline-flex w-3/4 items-center justify-center p-4 text-xl font-bold text-white bg-green-700 border border-transparent rounded-md shadow-lg">
        {{ $notification }}
    </div>
    <div
        class="inline-flex mt-2 w-3/4 items-center justify-center p-4 text-xl font-bold text-white bg-cyan-700 border border-transparent rounded-md shadow-lg">
        {{ $subtitle }}
    </div>
    <div
        class="inline-flex mt-2 w-3/4 items-center justify-center p-4 text-xl font-bold text-white bg-orange-500 border border-transparent rounded-md shadow-lg">
        {{ $current_score }}
    </div>
    <div
        class="inline-flex mt-2 w-3/4 items-center justify-center p-4 text-xl font-bold text-white bg-blue-500 border border-transparent rounded-md shadow-lg">
        {{ $historic_score }}
    </div>

    <div class="mt-6 flex">
        <x-button class="m-2 flex" type="button" onclick="sendEvent('play_again')">
            {{ __('guess-the-number.play-again') }}
        </x-button>
        <x-button class="m-2 flex" type="button" onclick="sendEvent('exit')">
            {{ __('guess-the-number.exit') }}
        </x-button>
    </div>
</div>

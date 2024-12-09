<div>
    <div
        class="inline-flex w-3/4 items-center justify-center p-4 text-xl font-bold text-white bg-red-700 border border-transparent rounded-md shadow-lg">
        {{ $notification_txt }}
    </div>

    <div
        class="inline-flex mt-2 w-3/4 items-center justify-center p-4 text-xl font-bold text-white bg-cyan-700 border border-transparent rounded-md shadow-lg">
        {{ $subtitle_txt }}
    </div>

    <div class="mt-6 flex">
        <x-button class="m-2 flex" type="button" onclick="sendEvent('play_again')">
            {{ $play_again_txt }}
        </x-button>
        <x-button class="m-2 flex" type="button" onclick="sendEvent('exit')">
            {{ $exit_txt }}
        </x-button>
    </div>
</div>

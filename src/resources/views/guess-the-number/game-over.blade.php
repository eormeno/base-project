<div>
    <div
        class="inline-flex w-3/4 items-center justify-center p-4 text-xl font-bold text-white bg-red-700 border border-transparent rounded-md shadow-lg">
        {{ $notification }}
    </div>

    <div class="mt-6">
        <x-button class="mt-2" type="button" onclick="sendEvent('play_again')">
            {{ __('guess-the-number.play-again') }}
        </x-button>
    </div>
</div>

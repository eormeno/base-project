<div>

    <div class="mt-3 text-lg text-gray-900 dark:text-white text-center">
        {{ $description }}
    </div>

    <x-button class="mt-4" type="button" onclick="sendEvent('want_to_play')">
        {{ __('guess-the-number.want-to-play') }}
    </x-button>

</div>

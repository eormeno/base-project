<div>

    <div class="mt-3 text-lg text-gray-900 dark:text-white text-center">
        {{ $description }}
    </div>

    <x-button class="mt-4" type="button" onclick="sendEvent('want_to_play')">
        {{ __('guess-the-number.want-to-play') }}
    </x-button>

    <div class="mt-4">
        <h2 class="text-xl text-gray-900 dark:text-white text-center">
            {{ __('guess-the-number.best-scores') }}
        </h2>
        <ul class="mt-2 text-sm text-gray-900 dark:text-white">
            @foreach ($ranking as $name => $score)
                <li>
                    {{ $name }} : {{ $score }}
                </li>
            @endforeach
        </ul>
    </div>

</div>

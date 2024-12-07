<div>

    <div class="uppercase font-bold mt-3 text-lg text-gray-900 dark:text-white text-center">
        {{ $title }}
    </div>

    @isset($clues_array)
        <div class="mt-4 text-lg text-gray-900 dark:text-white text-left">
            <div class="text-left items-start">
                <ul class="list-disc list-inside">
                    @foreach ($clues_array as $clue => $params)
                        <li>{{ __("guess-the-number.$clue", $params) }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endisset

    <div class="mt-4 text-lg text-gray-900 dark:text-white text-center">
        {{ $good_luck }}
    </div>

    <div class="mt-4 text-lg text-gray-900 dark:text-white text-center">
        <x-button class="mt-4 bg-transparent text-cyan-900" type="button" onclick="sendEvent('another_challenge')">
            {{ $another_challenge }}
        </x-button>

        <x-button class="m-4" type="button" onclick="sendEvent('want_to_play')">
            {{ $yes_button }}
        </x-button>
    </div>

</div>

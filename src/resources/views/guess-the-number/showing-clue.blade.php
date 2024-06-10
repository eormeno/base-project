<div>

    <div class="uppercase font-bold mt-3 text-lg text-gray-900 dark:text-white text-center">
        {{ $title }}
    </div>

    <div class="mt-4 text-lg text-gray-900 dark:text-white text-left">
        <div class="text-left items-start">
            <ul class="list-disc list-inside">
                @foreach ($clues as $clue)
                    <li>{{ $clue }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="mt-4 text-lg text-gray-900 dark:text-white text-center">
        {{ $goodLuck }}
    </div>

    <x-button class="mt-4" type="button" onclick="sendEvent('want_to_play')">
        {{ $yes_i_accept_the_challenge }}
    </x-button>

    <x-button class="mt-4" type="button" onclick="sendEvent('another_challenge')">
        {{ $another_challenge }}
    </x-button>

</div>

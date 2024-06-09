<div>

    <span class="text-xs text-gray-500 dark:text-gray-400">
        <x-event-renderer event="server_time_changed" />
    </span>

    <div class="mt-3 text-lg text-gray-900 dark:text-white text-center">
        {{ $description }}
    </div>

    <x-button class="mt-4" type="button" onclick="sendEvent('want_to_play')">
        {{ $yes_i_accept_the_challenge }}
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

    <div class="mt-4 flex justify-center bg-white p-3 items-center">
        <img src="{{ asset('images/logo-leyenda.png') }}" alt="Laravel Logo" class="w-14 h-full mr-3 object-cover">
        <img src="{{ asset('images/fcefn.jpg') }}" alt="Laravel Logo" class="w-12 h-full mr-3 object-scale-down">
        <img src="{{ asset('images/unsj.jpg') }}" alt="Alpine.js Logo" class="w-12 h-full object-scale-down">
    </div>
</div>

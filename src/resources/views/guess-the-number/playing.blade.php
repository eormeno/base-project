<div>
    <p class="mt-3 text-lg text-gray-900 dark:text-white text-center">
        @foreach ($remaining_attempts_message as $attempt => $message)
            {{ $message }}
        @endforeach
    </p>

    <div class="mt-4 w-5/6 p-3 mx-auto">
        <div>
            @if (!$finished)
                <div>
                    <x-label for="number" value="{{ $enter_number_message }}" />
                    <x-input id="number" class="block mt-1 w-full" type="number" name="number" value="{{$last_number}}"
                         autofocus />
                </div>
            @endif
            <div class="mt-4 text-center">
                <p class="text-lg text-gray-900 dark:text-white">
                    @isset($results)
                        @foreach ($results as $result => $message)
                            {{ $message }}
                        @endforeach
                    @endisset
                </p>
            </div>
            @if (!$finished)
                <x-button class="mt-4" type="button"
                    onclick="sendEvent('guess', {
                number: document.getElementById('number').value
                })">
                    {{ $enter_number_button }}
                </x-button>
            @endif
        </div>
    </div>
</div>

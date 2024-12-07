<div>
    <p class="mt-3 text-lg text-gray-900 dark:text-white text-center">
        {{ $notification }}
    </p>

    <div class="mt-4 w-5/6 p-3 mx-auto">
        <div>
            <div>
                <x-label for="number" value="{{ __('guess-the-number.enter_number') }}" />
                <x-input id="number" class="block mt-1 w-full" type="number" name="number" :value="old('number')"
                    autofocus />
            </div>
            {{-- Display the result --}}
            @if (isset($result))
                <div class="mt-4 text-center">
                    <p class="text-lg text-gray-900 dark:text-white">
                        {{ $result }}
                    </p>
                </div>
            @endif
            <x-button class="mt-4" type="button"
                onclick="sendEvent('guess', {
                number: document.getElementById('number').value
                })">
                {{ __('guess-the-number.submit') }}
            </x-button>
        </div>
    </div>
</div>

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
            <x-button class="mt-4" type="button"
                onclick="sendEvent('guess', {
                number: document.getElementById('number').value
                })">
                {{ __('guess-the-number.submit') }}
            </x-button>
        </div>
    </div>
</div>

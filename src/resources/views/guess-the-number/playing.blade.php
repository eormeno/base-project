<x-guess-the-number-layout>

    <p class="mt-6 text-lg text-gray-900 dark:text-white text-center">
        {{ $notification }}
    </p>

    <form class="w-3/4 mt-4 border border-transparent rouded-md shadow-lg p-4 mx-auto"
        action="{{ route('guess-the-number.guess') }}" method="POST" novalidate>
        @csrf
        <div>
            <div>
                <x-label for="number" value="{{ __('guess-the-number.enter_number') }}" />
                <x-input id="number" class="block mt-1 w-full" type="number" name="number"
                    :value="old('number')" autofocus />
                <x-input-error for="number" class="mt-2" />
            </div>
            <x-button class="mt-4">
                {{ __('guess-the-number.submit') }}
            </x-button>
        </div>
    </form>

</x-guess-the-number-layout>

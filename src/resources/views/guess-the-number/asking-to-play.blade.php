<x-guess-the-number-layout>

    <div class="mt-6 text-lg text-gray-900 dark:text-white text-center">
        {{ $description }}
    </div>

    <x-button class="mt-4" type="button" onclick="window.location='{{ route('guess-the-number.want-to-play') }}'">
        {{ __('guess-the-number.want-to-play') }}
    </x-button>

</x-guess-the-number-layout>

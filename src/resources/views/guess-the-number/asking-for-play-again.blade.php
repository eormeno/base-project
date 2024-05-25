<x-guess-the-number-layout>

    <div class="mt-6">
        <x-button class="mt-4" type="button"
            onclick="window.location='{{ route('guess-the-number.play-again') }}'">
            {{ __('guess-the-number.play-again') }}
        </x-button>
    </div>

</x-guess-the-number-layout>

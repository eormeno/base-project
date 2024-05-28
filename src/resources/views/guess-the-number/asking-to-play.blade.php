<x-guess-the-number-layout>

    <div class="mt-6 text-lg text-gray-900 dark:text-white text-center">
        {{ $description }}
    </div>

    <!-- A button that sends a POST request to the route guess-the-number with a parameter of 'want_to_play' -->
    <form method="POST" action="{{ route('guess-the-number') }}">
        @csrf
        <input type="hidden" name="event" value="want_to_play">
        <x-button class="mt-4" type="submit">
            {{ __('guess-the-number.want-to-play') }}
        </x-button>
    </form>

</x-guess-the-number-layout>

<x-guess-the-number-layout>

    <div
        class="inline-flex w-1/2 items-center justify-center p-6 text-xl font-bold text-white bg-red-700 border border-transparent rounded-md shadow-lg">
        {{ $notification }}
    </div>
    <div class="mt-6">
        <form method="POST" action="{{ route('guess-the-number') }}">
            @csrf
            <input type="hidden" name="event" value="play_again">
            <x-button class="mt-4" type="submit">
                {{ __('guess-the-number.play-again') }}
            </x-button>
        </form>
    </div>

</x-guess-the-number-layout>

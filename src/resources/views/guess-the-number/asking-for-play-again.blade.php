<div>

    <div class="mt-6">
        <form method="POST" action="{{ route('guess-the-number') }}">
            @csrf
            <input type="hidden" name="event" value="play_again">
            <x-button class="mt-4" type="submit">
                {{ __('guess-the-number.want-to-play') }}
            </x-button>
        </form>
    </div>

</div>

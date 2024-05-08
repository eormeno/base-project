<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('play.title') }}
        </h2>
    </x-slot>

    <h1 class="mt-8 text-2xl font-medium text-gray-900 dark:text-white">
        {{ __('welcome_session.guess-the-number') }}
    </h1>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <livewire:clash-of-triad game_id="{{ $game_id }}" />
            </div>
        </div>
    </div>
</x-app-layout>

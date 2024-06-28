<x-guess-the-number-layout>
    <div id="routeDiv" route="{{ route($routeName) }}" token="{{ csrf_token() }}"></div>

    <script src="{{ asset('js/states-renderer.js') }}"></script>

    <x-slot name="title">
        {{ __("$routeName.title") }}
    </x-slot>

    <div class="left-1/2 border mx-auto border-gray-600 rounded-md p-2 max-w-md bg-slate-200">
        <div class="relative">
            <div
                class="absolute top-2 w-3/4 left-1/2 transform -translate-x-1/2 z-50 items-center justify-center text-lg font-light text-white">

                <x-toast name="info">
                    <div class="bg-cyan-500 text-slate-800 shadow-lg p-6 border border-transparent rounded-md">
                        <x-toast-message />
                    </div>
                </x-toast>

                <x-toast name="success">
                    <div class="bg-green-700 shadow-lg p-6 border border-transparent rounded-md">
                        <x-toast-message />
                    </div>
                </x-toast>

                <x-toast name="error">
                    <div class="bg-red-700 shadow-lg p-6 border border-transparent rounded-md">
                        <x-toast-message />
                    </div>
                </x-toast>

                <x-toast name="warning">
                    <div class="bg-yellow-600 shadow-lg p-6 border border-transparent rounded-md">
                        <x-toast-message />
                    </div>
                </x-toast>
            </div>
            <a href="{{ route($routeName) }}/reset"
                class="absolute top-1 right-1 text-white bg-gray-600 px-2 py-1 rounded-full hover:bg-gray-700 text-xs">R</a>
        </div>

        <div id="main" class="bg-slate-200">
        </div>
    </div>
</x-guess-the-number-layout>

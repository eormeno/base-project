<x-guess-the-number-layout>

    <div id="routeDiv" route="{{ route('event', $currentGame->id) }}" token="{{ csrf_token() }}"></div>

    <script src="{{ asset('js/webgl-go-renderer.js') }}"></script>

    <x-slot name="title">
        {{ __($currentGame->title) }}
    </x-slot>

    <div class="left-1/2 border mx-auto max-w-min border-gray-600 rounded-md p-2 bg-slate-200">
        <div id="glCanvas" width="{{ $gameApp->width }}" height="{{ $gameApp->height }}"
            style="width: {{ $gameApp->width }}px; height: {{ $gameApp->height }}px;"></div>
    </div>
</x-guess-the-number-layout>


{{-- <script type="module">
    import WebGLImageDrawer from '{{ asset('js/webgl-drawimage.js') }}';
    let backgroundUrl = '{{ asset('storage/images/background.png') }}';
    let ballUrl = '{{ asset('storage/images/soccer_ball.png') }}';

    window.onload = async () => {
        try {
            const drawer = new WebGLImageDrawer(ballUrl);

            await drawer.drawImage(backgroundUrl, 0, 0, 800, 450);
            //await drawer.drawImage(ballUrl, 200, 150, 48, 48);

        } catch (error) {
            console.error('Error al dibujar imágenes:', error);
        }
    };
</script> --}}

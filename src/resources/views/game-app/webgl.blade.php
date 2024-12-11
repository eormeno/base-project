<!--
for most samples webgl-utils only provides shader compiling/linking and
canvas resizing because why clutter the examples with code that's the same in every sample.
See https://webglfundamentals.org/webgl/lessons/webgl-boilerplate.html
and https://webglfundamentals.org/webgl/lessons/webgl-resizing-the-canvas.html
for webgl-utils, m3, m4, and webgl-lessons-ui.
-->
<script src="https://webglfundamentals.org/webgl/resources/webgl-utils.js"></script>

<x-guess-the-number-layout>
    <script src="{{ asset('js/webgl-go-renderer.js') }}"></script>

    <x-slot name="title">
        {{ __("$currentGame->title") }}
    </x-slot>

    <div class="left-1/2 border mx-auto max-w-min border-gray-600 rounded-md p-2 bg-slate-200">
        <canvas id="c"></canvas>
    </div>

</x-guess-the-number-layout>

<style>
    @import url("https://webglfundamentals.org/webgl/resources/webgl-tutorials.css");

    canvas {
        width: 30vw;
        height: 30vw;
        display: block;
    }
</style>

<script  id="vertex-shader-2d" type="notjs">
  // an attribute will receive data from a buffer
  attribute vec4 a_position;

  // all shaders have a main function
  void main() {
    // gl_Position is a special variable a vertex shader
    // is responsible for setting
    gl_Position = a_position;
  }

</script>
<script  id="fragment-shader-2d" type="notjs">
  // fragment shaders don't have a default precision so we need
  // to pick one. mediump is a good default
  precision mediump float;

  void main() {
    // gl_FragColor is a special variable a fragment shader
    // is responsible for setting
    gl_FragColor = vec4(0.4, 0.7, 0.5, 1); // return redish-purple
  }

</script>

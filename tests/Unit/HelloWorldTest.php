<?php

it('returns hello world', function () {
    $helloWorld = new HelloWorld();
    expect($helloWorld->greet())->toBe('Hello, World!');
});
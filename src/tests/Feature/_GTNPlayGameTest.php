<?php

test("The user can see the game in his dashboard", function () {
    userShowGameApp('gtn');
});

test("Clicking 'play' game page is shown", function () {
    getUserPlayingGame('gtn');
});

test('Root game object with components is created', function () {
    $newGame = getUserPlayingGame('gtn');
    $rootGameObject = rootGameObjectForGameIsCreated($newGame);
    gameObjectHasComponents($rootGameObject, ['InitialStateViewComponent', 'GameOverStateComponent', 'PlayingStateComponent', 'PreparingStateComponent', 'ShowingClueStateComponent', 'SuccessStateComponent']);
});

test('Display tables', function () {
    $this->markTestSkipped('Only used for debugging');
    getUserPlayingGame('gtn');
    $this->withoutMockingConsoleOutput();
    showTable('game_objects');
    showTable('components');
    showTable('game_services');
});

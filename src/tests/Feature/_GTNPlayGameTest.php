<?php

test("The user can see the game in his dashboard", function () {
    userShowGameApp('gtn');
});

test("Clicking 'play' game page is shown", function () {
    getUserPlayingGame('gtn');
});

test("The game's root gameobject is created", function () {
    $newGame = getUserPlayingGame('gtn');
    $rootGameObject = rootGameObjectForGameIsCreated($newGame);
    gameObjectHasComponents($rootGameObject, ['InitialStateViewComponent', 'GameOverStateComponent', 'PlayingStateComponent', 'PreparingStateComponent', 'ShowingClueStateComponent', 'SuccessStateComponent']);
});

test("Event 'reload' returns the active gameobject with its view", function () {
    $newGame = getUserPlayingGame('gtn');
    $event = createEvent('reload');
    $response = $this->postJson(route('event', $newGame), $event);
    if ($response->exception) {
        throw $response->exception;
    }
    $response->assertStatus(200);
    /*
     * The expected json should have the following structure
     * {
     *     "root": 1,
     *     "1":  // a base 64 encoded string
     *     "actives": // an array with the active game objects, in this case "1"
     * }
     */
    $response->assertJsonStructure([
        'root' => 1,
        '1',
        'actives',
    ]);
    $this->assertIsString($response->json('1'));
    $this->assertIsArray($response->json('actives'));
});



// test('Display tables', function () {
//     $this->markTestSkipped('Only used for debugging');
//     getUserPlayingGame('gtn');
//     $this->withoutMockingConsoleOutput();
//     showTable('game_objects');
//     showTable('components');
//     showTable('game_services');
// });

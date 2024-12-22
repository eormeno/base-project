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

test("Events interaction returns the active gameobject with its view", function () {
    $newGame = getUserPlayingGame('gtn');

    // Reload the game (this must be the first event)
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
    $response->assertJsonStructure(['root', '1', 'actives',]);
    $this->assertIsString($response->json('1'));
    $this->assertIsArray($response->json('actives'));
    // The game should be in 'initial' state
    existsGameObjectsInDatabase([
        1 => ['gtn.root', $newGame->id, null, 1],
    ]);

    // The user wants to play (a button is clicked that sends this event)
    $event = createEvent('want_to_play');
    $response = $this->postJson(route('event', $newGame), $event);
    $response->assertStatus(200);
    // The game should be in 'showing_clues' state
    existsGameObjectsInDatabase([
        1 => ['gtn.root', $newGame->id, null, 3],
    ]);

    // The user wants to play (a button is clicked that sends this event)
    $response = $this->postJson(route('event', $newGame), $event);
    $response->assertStatus(200);
    existsGameObjectsInDatabase([
        1 => ['gtn.root', $newGame->id, null, 4],
    ]);

    // In test environment the random number is always 512
    $event = createEvent('guess', ['number' => 512]);
    $response = $this->postJson(route('event', $newGame), $event);
    $response->assertStatus(200);
    // The game should be in the 'success' state
    existsGameObjectsInDatabase([
        1 => ['gtn.root', $newGame->id, null, 5],
    ]);
});

// test('Display tables', function () {
//     //$this->markTestSkipped('Only for debugging');
//     getUserPlayingGame('gtn');
//     $this->withoutMockingConsoleOutput();
//     showTable('prefabs', ['name', 'type']);
//     showTable('game_objects', ['id', 'name', 'active', 'game_object_id']);
//     showTable('components');
//     //showTable('game_services');
// });

<?php

use App\Models\Game;
use App\Models\User;
use App\Models\GameApp;

test('dashboard display bba game', function () {
    $user = User::factory()->adminUser()->create();
    $this->artisan('game-apps:reload')->assertExitCode(0);
    $gameApp = GameApp::where('prefix', 'bba')->first();
    $this->assertNotNull($gameApp);
    $this->actingAs($user);
    $response = $this->get('/dashboard');
    $response->assertStatus(200);
    $response->assertSee($gameApp->name);
});

it('can play bba game', function () {
    $user = User::factory()->adminUser()->create();
    $this->artisan('game-apps:reload')->assertExitCode(0);
    $gameApp = GameApp::where('prefix', 'bba')->first();
    $this->assertNotNull($gameApp);
    $this->actingAs($user);
    $response = $this->get(route('play', $gameApp));
    if ($response->exception) {
        // if there is an exception, we show the message
        echo PHP_EOL . str_repeat('-', 80) . PHP_EOL;
        echo "ERROR: " . $response->exception->getMessage() . PHP_EOL;
        echo str_repeat('-', 80) . PHP_EOL;
        $this->assertTrue(false);
    }
    $response->assertStatus(200);

    // the game is created
    $newGame = Game::where('game_app_id', $gameApp->id)->first();
    $this->assertNotNull($newGame);
    // in the table 'game_objects' the following rows are created
    $table = 'game_objects';
    $columns = ['id', 'name', 'game_id', 'game_object_id'];
    $rows = [
        1 => ['bba.root', $newGame->id, null],
        2 => ['bba.ball', null, 1],
        3 => ['slot', null, 1],
    ];
    foreach ($rows as $id => $row) {
        $this->assertDatabaseHas($table, array_combine($columns, array_merge([$id], $row)));
    }

    $table = 'components';
    $columns = ['id', 'game_object_id', 'type', 'enabled', 'awoke', 'messages'];
    $rows = [
        1 => [1, 'App\GameApps\Components\SpriteRendererComponent', 1, 0, null],
    ];
    foreach ($rows as $id => $row) {
        $this->assertDatabaseHas($table, array_combine($columns, array_merge([$id], $row)));
    }

    $table = 'sprite_renderer_components';
    $columns = ['id', 'texture', 'layer'];
    $rows = [
        1 => ['background.jpeg', 0],
    ];
    foreach ($rows as $id => $row) {
        $this->assertDatabaseHas($table, array_combine($columns, array_merge([$id], $row)));
    }

});

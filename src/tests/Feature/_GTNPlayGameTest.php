<?php

use App\Models\Game;
use App\Models\User;
use App\Models\GameApp;
use Tests\Helpers\TableTools;

test('dashboard display bba game', function () {
    $user = User::factory()->adminUser()->create();
    $this->artisan('game-apps:reload')->assertExitCode(0);
    $gameApp = GameApp::where('prefix', 'gtn')->first();
    $this->assertNotNull($gameApp);
    $this->actingAs($user);
    $response = $this->get('/dashboard');
    $response->assertStatus(200);
    $response->assertSee($gameApp->name);
});

it('can play gtn game', function () {
    $user = User::factory()->adminUser()->create();
    $this->artisan('game-apps:reload')->assertExitCode(0);
    $gameApp = GameApp::where('prefix', 'gtn')->first();
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

    $this->withoutMockingConsoleOutput();

    // TableTools::showTable('game_objects', ['id', 'name', 'state_component_id']);
    TableTools::showTable('game_services');
    TableTools::showTable('components');

    // in the table 'game_objects' the following rows are created
    $table = 'game_objects';
    $columns = ['id', 'name', 'game_id', 'game_object_id', 'state_component_id'];
    $rows = [
        1 => ['gtn.root', $newGame->id, null, null],
    ];
    foreach ($rows as $id => $row) {
        $this->assertDatabaseHas($table, array_combine($columns, array_merge([$id], $row)));
    }

    // $table = 'components';
    // $columns = ['id', 'game_object_id', 'type', 'enabled', 'awoke', 'messages'];
    // $rows = [
    //     1 => [1, 'App\GameApps\Components\SpriteRendererComponent', 1, 0, null],
    // ];
    // foreach ($rows as $id => $row) {
    //     $this->assertDatabaseHas($table, array_combine($columns, array_merge([$id], $row)));
    // }

    // $table = 'sprite_renderer_components';
    // $columns = ['id', 'texture', 'layer'];
    // $rows = [
    //     1 => ['background.jpeg', 0],
    // ];
    // foreach ($rows as $id => $row) {
    //     $this->assertDatabaseHas($table, array_combine($columns, array_merge([$id], $row)));
    // }
}

);

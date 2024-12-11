<?php

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

it ('can play bba game', function () {
    $user = User::factory()->adminUser()->create();
    $this->artisan('game-apps:reload')->assertExitCode(0);
    $gameApp = GameApp::where('prefix', 'bba')->first();
    $this->assertNotNull($gameApp);
    $this->actingAs($user);
    $response = $this->get(route('play', $gameApp));
    $response->assertStatus(200);
});

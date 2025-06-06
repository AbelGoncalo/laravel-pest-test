<?php

use App\Models\User;
use function Pest\Laravel\postJson;

it('user authentication sshould can logout', function () {

    $user = User::factory()->create();

    $token = $user->createToken('2e2_test')->plainTextToken;

    postJson(route('auth.logout'), [], [
        'Authorization' => 'Bearer ' . $token,
        'content-type' => 'application/json',
    ])->assertStatus(204);
});

it('user unauthentication  cannot logout', function () {

    $user = User::factory()->create();

    postJson(route('auth.logout'), [], [])
        ->assertJson([
            'message' => 'Unauthenticated.',
        ])
        ->assertStatus(401);
});

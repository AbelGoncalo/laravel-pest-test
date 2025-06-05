<?php

use App\Models\User;

use function Pest\Laravel\postJson;

test('should auth user -with wrong password', function () {

    $user = User::factory()->create();

    $data = [
        'email' => $user->email,
        'password' => 'password',
        'device_name' => '2e2_test',
    ];

    postJson(route('auth.login'), $data)
        ->assertOk()
        ->assertJsonStructure(['token']);
});



it('should fail auth -with email wrong', function () {
    $user = User::factory()->create();
    $data = [
        //'email' => 'fake@email.com',
        'password' => 'password',
        'device_name' => '2e2_test',
    ];
    postJson(route('auth.login'), $data)
        ->assertStatus(422);
});

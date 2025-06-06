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


describe('Validation', function(){

    it('should require email', function(){

        postJson(route('auth.login'),[
            'password' => 'password',
            'device_name' => '2e2_test',
        ])
        ->assertStatus(422)

        ->assertJsonValidationErrors([
            'email'=>trans('validation.required', ['attribute' => 'email'])
        ]);
    });

    it('should require password', function(){

        $user = User::factory()->create();

        postJson(route('auth.login'),[
            'email' => $user->email,
            'device_name' => '2e2_test',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors([
            'password'=>trans('validation.required', ['attribute' => 'password'])
        ]);

    });

});

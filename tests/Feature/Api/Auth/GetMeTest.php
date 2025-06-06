<?php

use App\Models\Permission;
use App\Models\User;
use function Pest\Laravel\getJson;

test('unauthenticated user cannot get our data', function () {
    getJson(route('auth.me'))
    ->assertJson([
        'message' => 'Unauthenticated.',
    ])
    ->assertStatus(401);
});

test('should return user data', function () {
    $user = User::factory()->create();

    $token = $user->createToken('2e2_test')->plainTextToken;
    getJson(route('auth.me'), [
        'Authorization' => 'Bearer ' . $token,
        'content-type' => 'application/json',
    ])
    ->assertJsonStructure([
        'data' => [
            'id',
            'name',
            'email',
            'permissions'=>[]
        ]
    ])
    ->assertOk();
});

test('should return user data and your permissions', function () {

    Permission::factory()->count(10)->create();

    $permissionIds = Permission::factory()->count(10)->create()->pluck('id')->toArray();

    $user = User::factory()->create();

    $token = $user->createToken('2e2_test')->plainTextToken;

    $user->permissions()->attach($permissionIds);


    getJson(route('auth.me'), [
        'Authorization' => 'Bearer ' . $token,
        'content-type' => 'application/json',
    ])
    ->assertJsonStructure([
        'data' => [
            'id',
            'name',
            'email',
            'permissions'=>[
                '*' => [
                    'id',
                    'name',
                    'description'
                ]
            ]
        ]
    ])
    ->assertJsonCount(10, 'data.permissions') // Assuming 10 permissions were created
    ->assertOk();
});


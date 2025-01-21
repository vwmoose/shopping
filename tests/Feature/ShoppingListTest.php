<?php

use App\Models\User;

test('shopping list is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/dashboard');

    $response->assertOk();
});

// test('shopping list is displayed', function () {
//     $user = User::factory()->create();

//     $response = $this
//         ->actingAs($user)
//         ->get('/dashboard');

//     $response->assertOk();
// });
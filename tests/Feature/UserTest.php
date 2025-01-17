<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('allows an Admin to update a user', function () {
    $admin = User::factory()->create(['role' => 'Admin']);
    $user = User::factory()->create();
    Sanctum::actingAs($admin);

    $response = $this->putJson("/api/users/{$user->id}", [
        'name' => 'Updated Name',
    ]);

    $response->assertStatus(200);
});

//it('denies a non-Admin from updating a user', function () {
//    $user = User::factory()->create(['role' => 'Employee']);
//    Sanctum::actingAs($user);
//
//    $response = $this->putJson("/api/users/{$user->id}", [
//        'name' => 'New Name',
//    ]);
//
//    $response->assertStatus(403)
//        ->assertJson(['message' => 'Unauthorized.']);
//});

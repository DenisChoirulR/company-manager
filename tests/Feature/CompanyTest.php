<?php

use App\Models\Company;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('allows an Admin to list companies', function () {
    $admin = User::factory()->create(['role' => 'Admin']);
    Company::factory(3)->create();
    Sanctum::actingAs($admin);

    $response = $this->getJson('/api/companies');

    $response->assertStatus(200)
        ->assertJsonStructure(['data']);
});

it('denies a non-Admin from listing companies', function () {
    $user = User::factory()->create(['role' => 'Employee']);
    Sanctum::actingAs($user);

    $response = $this->getJson('/api/companies');

    $response->assertStatus(403);
});

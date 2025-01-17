<?php

use App\Models\Company;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('allows a user to login with valid credentials', function () {
    $company = Company::factory()->create();
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
        'company_id' => $company->id,
    ]);

    $response = $this->postJson('/api/auth/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['message', 'data' => ['access_token', 'user']]);
});

it('returns 401 for invalid login credentials', function () {
    $response = $this->postJson('/api/auth/login', [
        'email' => 'invalid@example.com',
        'password' => 'invalidpassword',
    ]);

    $response->assertStatus(403);
});

it('allows a logged-in user to logout', function () {
    $company = Company::factory()->create();
    $user = User::factory()->create(['company_id' => $company->id]);
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/auth/logout');

    $response->assertStatus(200);
});

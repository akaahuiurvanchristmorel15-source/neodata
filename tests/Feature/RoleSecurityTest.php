<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest users are redirected to login', function () {
    $this->get('/dashboard')->assertRedirect(route('login'));
});

test('authenticated employe users can access dashboard but not admin routes', function () {
    $user = User::factory()->create([
        'role' => 'employe',
    ]);

    $this->actingAs($user)->get('/dashboard')->assertStatus(200);
    $this->actingAs($user)->get('/categories')->assertStatus(403);
    $this->actingAs($user)->get('/users')->assertStatus(403);
});

test('authenticated admin users can access all routes', function () {
    $user = User::factory()->create([
        'role' => 'admin',
    ]);

    $this->actingAs($user)->get('/dashboard')->assertStatus(200);
    $this->actingAs($user)->get('/categories')->assertStatus(200);
    $this->actingAs($user)->get('/users')->assertStatus(200);
});

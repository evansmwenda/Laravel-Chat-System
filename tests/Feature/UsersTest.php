<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/users');

        $response->assertOk();
    }

    public function test_users_page_contains_empty_list(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/users');

        $response->assertStatus(200);
    }

    public function test_users_page_contains_non_empty_list(): void
    {
        $user = User::create([
            'name' => 'User 1',
            'email' => 'user1@gmail.com',
            'password' => 'password'
        ]);
        $response = $this->actingAs($user)->get('/users');

        $response->assertStatus(200);
        $response->assertSee('User 1');
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChatsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_chats_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/chats');

        $response->assertOk();
    }


    public function test_chats_page_contains_empty_list(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/chats');

        $response->assertStatus(200);
        $response->assertSee('Choose a conversation');
    }
}

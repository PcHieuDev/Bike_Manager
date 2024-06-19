<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_successfully()
    {
        $userData = [
            "name" => "Test User",
            "email" => "testuser@example.com",
            "password" => "password",
            "password_confirmation" => "password",
        ];

        $response = $this->postJson('/api/v1/Register', $userData);

        $response->assertStatus(201)
            ->assertJson(['message' => 'User created']);
    }
}
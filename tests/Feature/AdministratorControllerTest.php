<?php

namespace Tests\Feature;

use App\Models\administrator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class administratorControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_register()
    {
        $email = 'admin_' . Str::random(5) . '@example.com';

        $response = $this->postJson('/api/administrator/register', [
            'email' => $email,
            'password' => 'password123',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('administrators', [
            'email' => $email,
        ]);
    }

    public function test_admin_can_login()
    {
        $email = 'admin_' . Str::random(5) . '@example.com';
        $admin = administrator::create([
            'email' => $email,
            'password' => Hash::make('password123'), // Use Hash::make() for consistency
        ]);

        $response = $this->postJson('/api/administrator/login', [
            'email' => $email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('token', $response->json());
    }

    public function test_invalid_login_credentials()
    {
        $response = $this->postJson('/api/administrator/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
        $response->assertJson([]);
    }
}

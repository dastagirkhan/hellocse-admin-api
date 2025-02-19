<?php

namespace Tests\Feature;

use App\Models\Administrator;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an administrator and authenticate
        $admin = Administrator::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);

        Sanctum::actingAs($admin);
    }

    public function test_authenticated_user_can_create_profile()
    {
        $response = $this->postJson('/api/profiles', [
            'nom' => 'John',
            'prenom' => 'Doe',
            'image' => UploadedFile::fake()->image('dummy.jpg'), // Fake an image
        ]);


        // Get the created profile
        $profile = Profile::first();

        $response->assertStatus(201);
        $this->assertDatabaseHas('profiles', [
            'nom' => 'John',
            'prenom' => 'Doe',
            'statut' => 'en attente', // Default status
        ]);

        Storage::disk('public')->assertExists($profile->image);
    }

    public function test_authenticated_user_can_update_profile()
    {
        $profile = Profile::create([
            'nom' => 'John',
            'prenom' => 'Doe',
            'image' => UploadedFile::fake()->image('dummy.jpg'), // Fake an image
            'statut' => 'en attente',
        ]);

        $response = $this->putJson('/api/profiles/' . $profile->id, [
            'nom' => 'Jane',
            'prenom' => 'Doe',
            'image' => UploadedFile::fake()->image('dummy.jpg'), // Fake an image
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('profiles', [
            'nom' => 'Jane',
            'prenom' => 'Doe',
        ]);
    }

    public function test_authenticated_user_can_delete_profile()
    {
        $profile = Profile::create([
            'nom' => 'John',
            'prenom' => 'Doe',
            'image' => UploadedFile::fake()->image('dummy.jpg'), // Fake an image
            'statut' => 'en attente',
        ]);

        $response = $this->deleteJson('/api/profiles/' . $profile->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('profiles', [
            'id' => $profile->id,
        ]);
    }

    public function test_public_can_view_active_profiles()
    {
        Profile::factory()->create([
            'nom' => 'Ryan',
            'prenom' => 'Tristin',
            'image' => UploadedFile::fake()->image('dummy.jpg'), // Fake an image
            'statut' => 'actif',
        ]);

        Profile::factory()->create([
            'nom' => 'John',
            'prenom' => 'Doe',
            'image' => UploadedFile::fake()->image('dummy.jpg'), // Fake an image
            'statut' => 'inactif',
        ]);

        $response = $this->getJson('/api/profiles');

        $response->assertStatus(200);

        // Assert that only the active profile is returned
        $response->assertJsonFragment([
            'nom' => 'Ryan',
            'prenom' => 'Tristin',
        ]);

        $response->assertJsonMissing([
            'nom' => 'John',
            'prenom' => 'Doe',
        ]);
    }
}

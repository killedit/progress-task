<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['user' => ['id','name','email'], 'token']);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_login()
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure(['user'=>['id','name','email'], 'token']);
    }

    public function test_reset_password_link_can_be_requested()
    {
        $this->markTestSkipped('Password reset not implemented in API yet.');
        // Notification::fake();

        // $user = User::factory()->create();

        // $response = $this->postJson('/api/forgot-password', [
        //     'email' => $user->email,
        // ]);

        // Notification::assertSentTo($user, ResetPassword::class);

        // $response->assertStatus(200);
    }
}

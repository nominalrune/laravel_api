<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has(
                    'user',
                    fn (AssertableJson $userJson) =>
                    $userJson->where('name', 'John Doe')
                        ->where('email', 'john@doe.com')
                        ->hasAll(['id','created_at', 'updated_at'])
                        ->missing('password')
                        ->etc()
                )
            );
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $response=$this->post('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertStatus(401);
    }
}

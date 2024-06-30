<?php

namespace Tests\Feature;

use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_register_a_new_user(): void
    {
        $userData = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => fake()->text(20)
        ];
        $response = $this->json('POST', 'api/register', $userData);

        $response->assertStatus(200);
        $user = $response->json('data.user');
        $this->assertNotNull($user);
        $this->assertEquals($userData['name'], $user['name']);
        $this->assertEquals($userData['email'], $user['email']);
        $accessToken = $response->json('data.access_token');
        $this->assertNotNull($accessToken);
    }

    public function test_login_a_created_user()
    {
        $user = User::factory()->create();

        $userData = [
            'email' => $user->email,
            'password' => 'password'
        ];
        $response = $this->json('POST', 'api/login', $userData);

        $response->assertStatus(200);
        $responseUser = $response->json('data.user');
        $this->assertNotNull($user);
        $this->assertEquals($user->name, $responseUser['name']);
        $this->assertEquals($user->email, $responseUser['email']);
        $accessToken = $response->json('data.access_token');
        $this->assertNotNull($accessToken);
    }

    public function test_register_with_redundant_email()
    {
        $user = User::factory()->create();
        $userData = [
            'name' => fake()->name(),
            'email' => $user->email,
            'password' => fake()->text(20)
        ];
        $response = $this->json('POST', 'api/register', $userData);
        $response->assertJsonValidationErrors('email');
    }

    public function test_login_with_non_registered_email()
    {
        $userData = [
            'email' => fake()->unique()->safeEmail(),
            'password' => fake()->text(20)
        ];
        $response = $this->json('POST', 'api/login', $userData);
        $response->assertJsonValidationErrors('email');
    }

    public function test_login_with_wrong_password()
    {
        $user = User::factory()->create();
        $userData = [
            'email' => $user->email,
            'password' => fake()->text(20)
        ];
        $response = $this->json('POST', 'api/login', $userData);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonFragment(['message' => "Sorry! unable to login, Please make sure you are using right password."]);
    }
}

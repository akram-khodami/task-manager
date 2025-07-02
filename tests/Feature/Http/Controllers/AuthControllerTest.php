<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    //===ثبت نام(موفق)
    public function test_register_displays_validation_errors()
    {
        $register_data = $this->register_data();
        unset($register_data['email']);

        $response = $this->post(
            'api/auth/register',
            $register_data
        );

        $response->assertStatus(302);
    }

    //===ثبت نام(ناموفق)
    public function test_register_creates_and_authenticates_a_user()
    {
        $register_data = $this->register_data();

        $response = $this->post('api/auth/register', $register_data);

        $this->assertDatabaseHas(
            'users',
            [
                'name' => $register_data['name'],
                'email' => $register_data['email'],
            ]
        );

        $response->assertStatus(201);
    }

    //===(دیتای نامعتبر)ورود ناموفق
    public function test_login_displays_validation_errors()
    {
        $response = $this->post(
            'api/auth/login',
            [
                'email' => 'www.email.com', //wrong email
                'password' => NULL, //wrong password
            ]
        );

        $response->assertStatus(302);
    }

    //===(دیتای غلط)ورود ناموفق
    public function test_login_displays_credentials_errors()
    {
        $response = $this->post(
            'api/auth/login',
            [
                'email' => fake()->unique()->safeEmail(),
                'password' => fake()->password(),
            ]
        );

        $response->assertStatus(401);
    }

    //===ورود موفق
    public function test_login_authenticates_and_get_user()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        // 1. لاگین
        $response = $this->postJson('api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('access_token', $response->json());

        // 2. دریافت توکن
        $token = $response->json('access_token');

        // 3. با توکن، درخواست به یک route محافظت‌شده
        $authResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('api/auth/user');

        $authResponse->assertStatus(200);
        $authResponse->assertJsonFragment([
            'email' => $user->email,
        ]);
    }



    private function register_data()
    {
        $name = fake()->name();
        $email = fake()->unique()->safeEmail();
        $password = fake()->password(8);

        return [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ];
    }
}

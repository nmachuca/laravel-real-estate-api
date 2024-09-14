<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_fails_if_body_is_not_provided()
    {
        $this->postJson("/api/register")
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_register_name_not_provided()
    {
        // empty field
        $this->postJson(
            "/api/register",
            [
                'name',
                'email' => config(config('app.default_user.email')),
                'password' => config('app.default_user.password')
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');

        // non-existing field
        $this->postJson(
            "/api/register",
            [
                'email' => config(config('app.default_user.email')),
                'password' => config('app.default_user.password')
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }

    public function test_register_invalid_name()
    {
        // data type
        $this->postJson(
            "/api/register",
            [
                'name' => true,
                'email' => config(config('app.default_user.email')),
                'password' => config('app.default_user.password')
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');

        // min length
        $this->postJson(
            "/api/register",
            [
                'name' => 'a',
                'email' => config(config('app.default_user.email')),
                'password' => config('app.default_user.password')
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }

    public function test_register_invalid_email()
    {
        // format
        $this->postJson(
            "/api/register",
            [
                'name' => config('app.default_user.name'),
                'email' => config('app.default_user.name'),
                'password' => config('app.default_user.password')
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_register_password_not_confirmed()
    {
        // format
        $this->postJson(
            "/api/register",
            [
                'name' => config('app.default_user.name'),
                'email' => config('app.default_user.email'),
                'password' => config('app.default_user.password')
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('password');
    }

    public function test_register_password_length()
    {
        $test = Str::password(7);
        // format
        $this->postJson(
            "/api/register",
            [
                'name' => config('app.default_user.name'),
                'email' => config('app.default_user.email'),
                'password' => $test, 'password_confirmation' => $test
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('password');
    }

    public function test_register_failed_existing_email()
    {
        Artisan::call('db:seed');
        $this->postJson(
            "/api/register",
            [
                'name' => config('app.default_user.name'),
                'email' => config('app.default_user.email'),
                'password' => config('app.default_user.password'),
                'password_confirmation' => config('app.default_user.password'),
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_register_success()
    {
        $this->postJson(
            "/api/register",
            [
                'name' => config('app.default_user.name'),
                'email' => config('app.default_user.email'),
                'password' => config('app.default_user.password'),
                'password_confirmation' => config('app.default_user.password'),
            ])
            ->assertStatus(201)
            ->assertExactJsonStructure([
                "success",
                "data" => [
                    "id",
                    "email"
                ],
                "message"
            ])
            ->assertJson([
                "success" => true,
                "message" => "User registered successfully"
            ]);;
    }

    public function test_login_non_existent_user()
    {
        Artisan::call('db:seed');
        $this->postJson(
            "/api/login",
            [
                'email' => "sampleemail@gmail.com",
                'password' => config('app.default_user.password'),
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_login_not_valid_credentials()
    {
        Artisan::call('db:seed');
        $this->postJson(
            "/api/login",
            [
                'email' => config('app.default_user.email'),
                'password' => "password",
            ])
            ->assertStatus(401)
            ->assertExactJson([
                "success" => false,
                "message" => "Credentials not valid"
            ]);
    }

    public function test_login_success()
    {
        Artisan::call('db:seed');
        $this->postJson(
            "/api/login",
            [
                'email' => config('app.default_user.email'),
                'password' => config('app.default_user.password'),
            ])
            ->assertStatus(200)
            ->assertExactJsonStructure([
                "success",
                "data" => [
                    "type",
                    "token"
                ],
                "message"
            ])
            ->assertJson([
                "success" => true,
                "data" => [
                    "type" => "Bearer"
                ],
                "message" => "User logged in successfully"
            ]);
    }

}

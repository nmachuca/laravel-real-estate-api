<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PersonaResourceTest extends TestCase
{
    use RefreshDatabase;

    private function getUserToken() {
        $user = User::factory()->create([
            'email' => config('app.default_user.email'),
            'password' => config('app.default_user.password'),
        ]);
        $response = $this->postJson(
            '/api/login',
            [
                "email" => $user->email,
                "password" => config('app.default_user.password')
            ]);
        return $response->json()['data']['token'];
    }

    public function test_store_fails_if_body_is_not_provided()
    {
        $token = $this->getUserToken();
        $this->postJson("/api/persona", [], ['Authorization' => "Bearer $token"])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nombre', 'email', 'telefono']);
    }

    public function test_store_required_fields()
    {
        $token = $this->getUserToken();
        $this->postJson(
            "/api/persona",
            [],
            ['Authorization' => "Bearer $token"])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nombre','email', 'telefono']);
    }

    public function test_store_unauthorized()
    {
        $this->postJson(
            "/api/persona",
            [
                "nombre" => config('app.default_user.name'),
                "email" => config('app.default_user.email'),
                "telefono" => "+56998765432"
            ])
            ->assertStatus(401);
    }

    public function test_store_success()
    {
        $this->getUserToken();
        $this->postJson(
            "/api/persona",
            [
                'nombre' => config('app.default_user.name'),
                'email' => config('app.default_user.email'),
                'telefono' => "+56998765432",
            ])
            ->assertStatus(201)
            ->assertExactJsonStructure([
                "success",
                "data" => [
                    "id",
                    "nombre",
                    "email",
                    "telefono"
                ],
                "message"
            ])
            ->assertJson([
                "success" => true,
                "message" => "Persona created successfully"
            ]);;
    }

    public function test_index_required_fields()
    {
        $token = $this->getUserToken();
        $this->postJson(
            "/api/personas",
            [],
            ['Authorization' => "Bearer $token"])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['pagination','filters']);
    }

    public function test_index_required_if_fields()
    {
        $token = $this->getUserToken();
        $this->postJson(
            "/api/personas",
            ["pagination" => true, "sort" => "nombre"],
            ['Authorization' => "Bearer $token"])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['elements_per_page','sort_asc']);
    }

}

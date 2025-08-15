<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminServiceTest extends TestCase
{
    // use RefreshDatabase;

    protected function authenticateAdmin()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $token = $admin->createToken('auth_token')->plainTextToken;
        return ['admin' => $admin, 'token' => $token];
    }

    public function test_admin_can_create_service()
    {
        $auth = $this->authenticateAdmin();

        $response = $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->postJson('/api/services', [
                'name'        => 'Server Maintenance',
                'description' => 'Server maintenance',
                'price'       => 50000,
                'status'      => 'active'
            ]);

        $response->assertStatus(200)
            ->assertJson(['status' => true, 'status_message' => 'Service created successfully!']);
    }

    public function test_admin_can_update_service()
    {
        $auth    = $this->authenticateAdmin();
        $service = Service::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->putJson("/api/services/{$service->id}", [
                'price' => 75000
            ]);

        $response->assertStatus(200)
            ->assertJson(['status' => true, 'status_message' => 'Service updated successfully!']);
    }

    public function test_admin_can_delete_service()
    {
        $auth    = $this->authenticateAdmin();
        $service = Service::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $auth['token'])
            ->deleteJson("/api/services/{$service->id}");

        $response->assertStatus(200)
            ->assertJson(['status' => true, 'status_message' => 'Service deleted successfully!']);
    }
}

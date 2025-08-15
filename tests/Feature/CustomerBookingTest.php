<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerBookingTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user  = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }

    public function test_customer_can_list_services()
    {
        Service::factory()->count(3)->create(['status' => 'active']);
        $auth = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer '.$auth['token'])
                         ->getJson('/api/services');

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'status_message', 'data']);
    }

    public function test_customer_can_book_service()
    {
        $auth    = $this->authenticate();
        $service = Service::factory()->create(['status' => 'active']);

        $response = $this->withHeader('Authorization', 'Bearer '.$auth['token'])
                         ->postJson('/api/bookings', [
                             'service_id'   => $service->id,
                             'booking_date' => now()->addDays(1)->format('Y-m-d')
                         ]);

        $response->assertStatus(200)
                 ->assertJson(['status' => true, 'status_message' => 'Booking created successfully']);
    }
}

<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word . ' Service',
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 10, 500),
            'status' => 'active',
        ];
    }
}

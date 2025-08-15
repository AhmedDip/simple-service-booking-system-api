<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminAndServicesSeeder extends Seeder
{
    public function run(): void
    {

        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Admin User',
                'password' => Hash::make('12345678'),
                'is_admin' => true
            ]
        );

      
        $services = [
            [
                'name'        => 'Web App Development',
                'description' => 'Build web app with modern tools.',
                'price'       => 1500000
            ],
            [
                'name'        => 'Mobile App Design',
                'description' => 'Create user-friendly mobile interfaces.',
                'price'       => 1200000
            ],
            [
                'name'        => 'API Integration',
                'description' => 'Connect apps with external services.',
                'price'       => 1000000
            ],
            [
                'name'        => 'Cloud Deployment',
                'description' => 'Deploy apps on cloud platforms.',
                'price'       => 1800000
            ],
            [
                'name'        => 'Software Maintenance',
                'description' => 'Ongoing support and updates for software.',
                'price'       => 500000
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['name' => $service['name']], $service + ['status' => 'active']);
        }
    }
}

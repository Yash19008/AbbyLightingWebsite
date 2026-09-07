<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LightWorld;

class LightWorldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $worlds = [
            [
                'name' => 'Architectural',
                'link' => '/#arrivals',
                'light_of_image' => '/images/world-architectural-off.png',
                'light_on_image' => '/images/world-architectural-on.png',
                'sort_order' => 1,
            ],
            [
                'name' => 'Decorative',
                'link' => '/decorative-products',
                'light_of_image' => '/images/world-decorative-off.png',
                'light_on_image' => '/images/world-decorative-on.png',
                'sort_order' => 2,
            ],
            [
                'name' => 'Outdoor',
                'link' => '/#arrivals',
                'light_of_image' => '/images/world-outdoor-off.png',
                'light_on_image' => '/images/world-outdoor-on.png',
                'sort_order' => 3,
            ],
            [
                'name' => 'Smart light',
                'link' => '/#arrivals',
                'light_of_image' => '/images/reference/world-smart.png',
                'light_on_image' => '/images/reference/world-smart.png',
                'sort_order' => 4,
            ],
        ];

        foreach ($worlds as $world) {
            LightWorld::updateOrCreate(['name' => $world['name']], $world);
        }
    }
}


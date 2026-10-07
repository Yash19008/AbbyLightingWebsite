<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            // Header Mega
            ['title' => 'Outdoor', 'url' => '/#worlds', 'location' => 'header_mega', 'order' => 1],
            ['title' => 'Smart Lighting', 'url' => '/#worlds', 'location' => 'header_mega', 'order' => 2],

            // Footer Products Panel
            ['title' => 'Architectural', 'url' => '/products', 'location' => 'footer_products', 'order' => 1],
            ['title' => 'Outdoor', 'url' => '/#worlds', 'location' => 'footer_products', 'order' => 2],
            ['title' => 'Smart Lighting', 'url' => '/abby-smart', 'location' => 'footer_products', 'order' => 3],

            // Footer Our Work Panel
            ['title' => 'Clients', 'url' => '/clients', 'location' => 'footer_work', 'order' => 1],
            ['title' => 'Projects', 'url' => '/projects', 'location' => 'footer_work', 'order' => 2],

            // Footer Column 1 Direct
            ['title' => 'Inspiration', 'url' => '/inspiration', 'location' => 'footer_col_1', 'order' => 1],

            // Footer Column 2
            ['title' => 'About Us', 'url' => '/company', 'location' => 'footer_col_2', 'order' => 1],
            ['title' => 'Contact Us', 'url' => '/contact', 'location' => 'footer_col_2', 'order' => 2],
            ['title' => 'Careers', 'url' => '/career', 'location' => 'footer_col_2', 'order' => 3],
            ['title' => 'Catalogues', 'url' => '/catalogues', 'location' => 'footer_col_2', 'order' => 4],
            ['title' => 'Privacy Policy', 'url' => '/privacy-policy', 'location' => 'footer_col_2', 'order' => 5],
            ['title' => 'Terms of Use', 'url' => '/terms-and-conditions', 'location' => 'footer_col_2', 'order' => 6],
            ['title' => 'Fairs & Events', 'url' => '/fair-events', 'location' => 'footer_col_2', 'order' => 7],

            // Footer Column 3
            ['title' => 'Privacy Policy', 'url' => '/privacy-policy', 'location' => 'footer_col_3', 'order' => 1],
            ['title' => 'Terms of Use', 'url' => '/terms-and-conditions', 'location' => 'footer_col_3', 'order' => 2],
        ];

        foreach ($items as $item) {
            \App\Models\MenuItem::create($item);
        }
    }
}

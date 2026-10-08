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
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\MenuItem::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Header Mega
        \App\Models\MenuItem::create(['title' => 'Outdoor', 'url' => '/#worlds', 'location' => 'header_mega', 'type' => 'link', 'order' => 1]);
        \App\Models\MenuItem::create(['title' => 'Smart Lighting', 'url' => '/#worlds', 'location' => 'header_mega', 'type' => 'link', 'order' => 2]);

        // 2. Footer Column 1
        $productsGroup = \App\Models\MenuItem::create(['title' => 'Products', 'url' => '/', 'location' => 'footer_col_1', 'type' => 'group', 'order' => 1]);
        \App\Models\MenuItem::create(['title' => 'Architectural', 'url' => '/products', 'location' => 'footer_col_1', 'type' => 'link', 'parent_id' => $productsGroup->id, 'order' => 1]);
        \App\Models\MenuItem::create(['title' => 'Outdoor', 'url' => '/#worlds', 'location' => 'footer_col_1', 'type' => 'link', 'parent_id' => $productsGroup->id, 'order' => 2]);
        \App\Models\MenuItem::create(['title' => 'Smart Lighting', 'url' => '/abby-smart', 'location' => 'footer_col_1', 'type' => 'link', 'parent_id' => $productsGroup->id, 'order' => 3]);

        $workGroup = \App\Models\MenuItem::create(['title' => 'Our Work', 'url' => '/', 'location' => 'footer_col_1', 'type' => 'group', 'order' => 2]);
        \App\Models\MenuItem::create(['title' => 'Clients', 'url' => '/clients', 'location' => 'footer_col_1', 'type' => 'link', 'parent_id' => $workGroup->id, 'order' => 1]);
        \App\Models\MenuItem::create(['title' => 'Projects', 'url' => '/projects', 'location' => 'footer_col_1', 'type' => 'link', 'parent_id' => $workGroup->id, 'order' => 2]);

        \App\Models\MenuItem::create(['title' => 'Inspiration', 'url' => '/inspiration', 'location' => 'footer_col_1', 'type' => 'link', 'order' => 3]);

        // 3. Footer Column 2
        \App\Models\MenuItem::create(['title' => 'About Us', 'url' => '/company', 'location' => 'footer_col_2', 'type' => 'link', 'order' => 1]);
        \App\Models\MenuItem::create(['title' => 'Contact Us', 'url' => '/contact', 'location' => 'footer_col_2', 'type' => 'link', 'order' => 2]);
        \App\Models\MenuItem::create(['title' => 'Careers', 'url' => '/career', 'location' => 'footer_col_2', 'type' => 'link', 'order' => 3]);
        \App\Models\MenuItem::create(['title' => 'Catalogues', 'url' => '/catalogues', 'location' => 'footer_col_2', 'type' => 'link', 'order' => 4]);
        \App\Models\MenuItem::create(['title' => 'Fairs & Events', 'url' => '/fair-events', 'location' => 'footer_col_2', 'type' => 'link', 'order' => 5]);

        // 4. Footer Column 3
        \App\Models\MenuItem::create(['title' => 'Privacy Policy', 'url' => '/privacy-policy', 'location' => 'footer_col_3', 'type' => 'link', 'order' => 1]);
        \App\Models\MenuItem::create(['title' => 'Terms of Use', 'url' => '/terms-and-conditions', 'location' => 'footer_col_3', 'type' => 'link', 'order' => 2]);
    }
}

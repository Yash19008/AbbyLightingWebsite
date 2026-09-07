<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // Check if admin user already exists to avoid duplicate primary keys
        if (!\App\Models\User::where('email', 'test@example.com')->exists()) {
            \App\Models\User::factory()->create([
                'user_name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('12345678')
            ]);
        }

        $this->call([
            LightWorldSeeder::class,
        ]);
    }
}

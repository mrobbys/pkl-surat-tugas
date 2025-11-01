<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // User::factory()->create([
        //     'email' => 'test@example.com',
        //     'password' => bcrypt('Password1.'),
        // ]);

        $this->call([
            PangkatGolonganSeeder::class,
            RolePermissionSeeder::class,
            // SuratPerjalananDinasSeeder::class,
        ]);
        User::factory(5)->create()->each(function ($user) {
            $user->assignRole('user');
        });
        
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RotationState;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user if it doesn't exist
        if (User::count() === 0) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@mailrouter.local',
                'password' => Hash::make('admin123'), // Change this to a secure password
                'role' => 'admin',
            ]);

            $this->command->info('✓ Admin creado — email: admin@mailrouter.local | password: admin123');
            $this->command->warn('⚠ Cambia la contraseña desde el dashboard');
        }

        // Create a default rotation state if it doesn't exist
        RotationState::firstOrCreate([
            'id' => 1,
        ], [
            'current_index' => 0,
        ]);
    }
}

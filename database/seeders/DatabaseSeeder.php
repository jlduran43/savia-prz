<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MedioPagoSeeder::class,
            TipoVehiculoSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Administrador PRZ',
            'email' => 'admin@prz.cl',
            'password' => 'admin123!',
        ]);
    }
}

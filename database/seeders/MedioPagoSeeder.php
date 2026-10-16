<?php

namespace Database\Seeders;

use App\Models\MedioPago;
use Illuminate\Database\Seeder;

class MedioPagoSeeder extends Seeder
{
    public function run(): void
    {
        MedioPago::firstOrCreate(
            ['nombre' => 'Débito'],
            ['activo' => true]
        );

        MedioPago::firstOrCreate(
            ['nombre' => 'Efectivo'],
            ['activo' => true]
        );

        MedioPago::firstOrCreate(
            ['nombre' => 'Transferencia'],
            ['activo' => true]
        );
    }
}
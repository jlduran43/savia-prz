<?php

namespace Database\Seeders;

use App\Models\TipoVehiculo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoVehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            [
                'nombre' => 'Autos',
                'precio_base' => 3000,
            ],
            [
                'nombre' => 'Furgones hasta 6 personas',
                'precio_base' => 4000,
            ],
            [
                'nombre' => 'Furgones hasta 15 personas',
                'precio_base' => 15000,
            ],
            [
                'nombre' => 'Buses',
                'precio_base' => 35000,
            ],
            [
                'nombre' => 'Buses Diferidos',
                'precio_base' => 45000,
            ],
            [
                'nombre' => 'Autos diferidos',
                'precio_base' => 2000,
            ],
            [
                'nombre' => 'Motos',
                'precio_base' => 1000,
            ],
        ];

        foreach ($tipos as $tipo) {
            TipoVehiculo::firstOrCreate(
                ['nombre' => $tipo['nombre']],
                [
                    'precio_base' => $tipo['precio_base'],
                    'activo' => true,
                ]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RrhhTipoDocumento;

class RrhhTiposDocumentosSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['contratos', 'Contratos', false],
            ['anexos', 'Anexos y pactos', false],
            ['curriculum', 'Currículum vitae', false],
            ['cedula_identidad', 'Cédula de identidad', false],
            ['licencia_conducir', 'Licencia de conducir', false],
            ['liquidaciones', 'Liquidaciones', true],
            ['comprobantes_pago', 'Comprobantes de pago', true],
            ['asistencia', 'Asistencia', true],
            ['vacaciones', 'Vacaciones', false],
            ['permisos', 'Permisos administrativos', false],
            ['certificados', 'Certificados', false],
            ['capacitaciones', 'Capacitaciones', false],
            ['honorarios', 'Boletas de honorarios', true],
            ['finiquitos', 'Finiquitos', false],
            ['otros', 'Otros documentos', false],
        ];

        foreach ($tipos as $orden => $tipo) {
            RrhhTipoDocumento::firstOrCreate(
                ['codigo' => $tipo[0]],
                [
                    'nombre' => $tipo[1],
                    'requiere_periodo' => $tipo[2],
                    'activo' => true,
                    'orden' => $orden + 1,
                ]
            );
        }
    }
}
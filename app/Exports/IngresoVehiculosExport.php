<?php

namespace App\Exports;

use App\Models\RegistroIngreso;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class RegistroIngresosExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithColumnFormatting
{
    public function collection(): Collection
    {
        return RegistroIngreso::query()
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tipo de movimiento',
            'Centro de ingreso',
            'Responsable',
            'Fecha',
            'Hora inicio',
            'Hora término',
            'Horario',
        ];
    }

    public function map($registro): array
    {
        $fecha = Carbon::parse($registro->fecha);

        $horaInicio = Carbon::parse($registro->hora_inicio);
        $horaTermino = Carbon::parse($registro->hora_termino);

        return [
            $registro->id,
            $registro->tipo_movimiento,
            $registro->centro_ingreso,
            $registro->responsable,

            Date::dateTimeToExcel($fecha),

            Date::dateTimeToExcel($horaInicio),

            Date::dateTimeToExcel($horaTermino),

            $horaInicio->format('H:i')
                . '-'
                . $horaTermino->format('H:i'),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => '[$-es-CL]dddd, d "de" mmmm "de" yyyy',
            'F' => 'hh:mm',
            'G' => 'hh:mm',
        ];
    }
}

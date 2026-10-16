<?php

namespace App\Support;

class RutChileno
{
    public static function limpiar(?string $rut): string
    {
        return strtoupper(
            preg_replace('/[^0-9kK]/', '', $rut ?? '')
        );
    }

    public static function esValido(?string $rut): bool
    {
        $rut = self::limpiar($rut);

        if (!preg_match('/^[0-9]{1,8}[0-9K]$/', $rut)) {
            return false;
        }

        $numero = substr($rut, 0, -1);
        $dv = substr($rut, -1);

        $suma = 0;
        $multiplicador = 2;

        for ($i = strlen($numero) - 1; $i >= 0; $i--) {
            $suma += (int) $numero[$i] * $multiplicador;

            $multiplicador = $multiplicador === 7
                ? 2
                : $multiplicador + 1;
        }

        $resto = 11 - ($suma % 11);

        $dvCalculado = match ($resto) {
            11 => '0',
            10 => 'K',
            default => (string) $resto,
        };

        return $dv === $dvCalculado;
    }

    public static function formatear(?string $rut): string
    {
        $rut = self::limpiar($rut);

        if (!preg_match('/^[0-9]{1,8}[0-9K]$/', $rut)) {
            return $rut;
        }

        $numero = substr($rut, 0, -1);
        $dv = substr($rut, -1);

        return number_format((int) $numero, 0, '', '.')
            . '-' . $dv;
    }
}
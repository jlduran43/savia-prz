<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DrivePrzEnlaceController extends Controller
{
    private function propietario(int $id): Documento
    {
        $documento = Documento::findOrFail($id);

        abort_unless(
            (int) $documento->creado_por === (int) auth()->id(),
            403,
            'Solo el propietario puede administrar enlaces externos.'
        );

        return $documento;
    }

    // Generar enlace externo
    public function generar(Request $request, int $id)
    {
        $documento = $this->propietario($id);

        $token = Str::random(64);

        DB::transaction(function () use ($documento, $token) {

            // Invalidar enlaces anteriores del documento
            DB::table('drive_prz_enlaces')
                ->where('documento_id', $documento->id)
                ->whereNull('revocado_at')
                ->update([
                    'revocado_at' => now(),
                    'updated_at' => now(),
                ]);

            DB::table('drive_prz_enlaces')->insert([
                'documento_id' => $documento->id,
                'creado_por' => auth()->id(),
                'token_hash' => hash('sha256', $token),
                'expira_at' => now()->addDays(7),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return response()->json([
            'success' => true,
            'url' => route('drive-prz.publico', [
                'token' => $token
            ]),
            'mensaje' => 'Enlace generado por 7 días.'
        ]);
    }

    // Consultar si existe un enlace activo
    public function estado(int $id)
    {
        $this->propietario($id);

        $enlace = DB::table('drive_prz_enlaces')
            ->where('documento_id', $id)
            ->whereNull('revocado_at')
            ->where('expira_at', '>', now())
            ->latest('id')
            ->first();

        return response()->json([
            'activo' => (bool) $enlace,
            'expira_at' => $enlace?->expira_at,
        ]);
    }

    // Revocar los enlaces
    public function revocar(int $id)
    {
        $this->propietario($id);

        DB::table('drive_prz_enlaces')
            ->where('documento_id', $id)
            ->whereNull('revocado_at')
            ->update([
                'revocado_at' => now(),
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'mensaje' => 'Enlace externo revocado.'
        ]);
    }

    private function verificar(string $token): Documento
    {
        abort_unless(
            preg_match('/^[A-Za-z0-9]{64}$/', $token),
            404
        );

        $enlace = DB::table('drive_prz_enlaces')
            ->where('token_hash', hash('sha256', $token))
            ->whereNull('revocado_at')
            ->where('expira_at', '>', now())
            ->first();

        abort_unless($enlace, 404, 'Enlace inválido o vencido.');

        // Evita abrir archivos enviados a la papelera
        $documento = Documento::query()
            ->whereKey($enlace->documento_id)
            ->firstOrFail();

        abort_unless(
            Storage::disk('documentos_privados')
                ->exists($documento->ruta_archivo),
            404
        );

        return $documento;
    }

    // Pantalla pública sin iniciar sesión
    public function publico(string $token)
    {
        $documento = $this->verificar($token);

        $mime = Storage::disk('documentos_privados')
            ->mimeType($documento->ruta_archivo);

        $permitidos = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'text/plain',
        ];

        return response()
            ->view('drive-prz.publico', [
                'documento' => $documento,
                'token' => $token,
                'visualizable' => in_array(
                    $mime,
                    $permitidos,
                    true
                ),
            ])
            ->header('Cache-Control', 'no-store')
            ->header('Referrer-Policy', 'no-referrer')
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }

    // Contenido del documento público
    public function contenido(string $token)
    {
        $documento = $this->verificar($token);

        $disco = Storage::disk('documentos_privados');

        $mime = $disco->mimeType(
            $documento->ruta_archivo
        );

        $permitidos = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'text/plain',
        ];

        abort_unless(
            in_array($mime, $permitidos, true),
            415,
            'Este archivo no admite vista previa.'
        );

        return $disco->response(
            $documento->ruta_archivo,
            $documento->nombre_original
                ?: 'documento',
            [
                'Content-Type' => $mime,
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'no-store',
                'Content-Security-Policy' => "default-src 'none'; sandbox",
                'Referrer-Policy' => 'no-referrer',
            ],
            'inline'
        );
    }
}
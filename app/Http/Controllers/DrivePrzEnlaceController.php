<?php



namespace App\Http\Controllers;



use App\Models\Documento;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;

use App\Support\DrivePrzAuditoria;



class DrivePrzEnlaceController extends Controller

{

    private function propietario(int $id): Documento

    {

        $documento = Documento::findOrFail($id);



        abort_unless(

            (int) $documento->subido_por === (int) auth()->id(),

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



        DrivePrzAuditoria::registrar(

            request(),

            'REVOCACION',

            $id

        );



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



    public function publico(string $token)

    {

        $documento = $this->verificar($token);



        $enlace = DB::table('drive_prz_enlaces')

            ->where('token_hash', hash('sha256', $token))

            ->first();



        DrivePrzAuditoria::registrar(

            request(),

            'APERTURA_PUBLICA',

            $documento->id,

            $enlace?->id

        );



        $mime = Storage::disk('documentos_privados')

            ->mimeType($documento->ruta_archivo);



        $permitidos = [

            'application/pdf',

            'image/jpeg',

            'image/png',

            'image/gif',

            'image/webp',

        ];



        $visualizable = in_array($mime, $permitidos, true);



        $preview = null;

        // Diferenciar un PDF sin vista previa de un formato no admitido.
        // Sin un estado persistente del Job no se puede afirmar si está
        // pendiente, en procesamiento o falló: se informa como no disponible.
        $estadoPreview = $mime === 'application/pdf'
            ? 'no_disponible'
            : ($visualizable ? 'listo' : 'no_compatible');



        if ($mime === 'application/pdf') {

            $preview = $this->datosPreview($documento);



            // Si todavía no hay imágenes, no entregar el PDF.

            $visualizable = $preview !== null;
            $estadoPreview = $visualizable ? 'listo' : 'no_disponible';

        }



        return response()

            ->view('drive-prz.publico', [

                'documento' => $documento,

                'token' => $token,

                'visualizable' => $visualizable,
                'estadoPreview' => $estadoPreview,

                'paginasPdf' => $preview

                    ? count($preview['paginas'])

                    : 0,

            ])

            ->header('Cache-Control', 'private, no-store, max-age=0')

            ->header('Referrer-Policy', 'no-referrer')

            ->header('X-Content-Type-Options', 'nosniff')

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



        // Los enlaces externos no pueden obtener el PDF original.

        abort_if(

            $mime === 'application/pdf',

            403,

            'El PDF original no está disponible en enlaces externos.'

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



    private function datosPreview(Documento $documento): ?array

    {

        $origen = Storage::disk('documentos_privados');

        $destino = Storage::disk('drive_prz_previews');



        $revision = hash(

            'sha256',

            $documento->ruta_archivo . '|' .

                $origen->size($documento->ruta_archivo) . '|' .

                $origen->lastModified($documento->ruta_archivo)

        );



        $directorio = $documento->id . '/' . $revision;

        $manifest = $directorio . '/manifest.json';



        if (! $destino->exists($manifest)) {

            return null;

        }



        $datos = json_decode($destino->get($manifest), true);



        if (

            ! is_array($datos)

            || ($datos['revision'] ?? null) !== $revision

            || (int) ($datos['documento_id'] ?? 0) !== (int) $documento->id

            || ! isset($datos['paginas'])

            || ! is_array($datos['paginas'])

            || count($datos['paginas']) < 1

            || count($datos['paginas']) > 20

        ) {

            return null;

        }



        foreach ($datos['paginas'] as $nombre) {

            if (

                ! is_string($nombre)

                || ! preg_match('/^pagina-[0-9]{3}**\\.**&#x6A;pg$/', $nombre)

                || ! $destino->exists($directorio . '/' . $nombre)

            ) {

                return null;

            }

        }



        return [

            'directorio' => $directorio,

            'paginas' => $datos['paginas'],

        ];

    }



    public function pagina(string $token, int $pagina)

    {

        // Siempre volver a validar el enlace.

        $documento = $this->verificar($token);



        $enlace = DB::table('drive_prz_enlaces')

            ->where('token_hash', hash('sha256', $token))

            ->first();



        DrivePrzAuditoria::registrar(

            request(),

            'PAGINA_PDF',

            $documento->id,

            $enlace?->id,

            $pagina

        );



        $origen = Storage::disk('documentos_privados');



        abort_unless(

            $origen->mimeType($documento->ruta_archivo)

                === 'application/pdf',

            404

        );



        $preview = $this->datosPreview($documento);



        abort_unless($preview !== null, 404);



        abort_unless(

            $pagina >= 1 &&

                $pagina <= count($preview['paginas']),

            404

        );



        $archivo = $preview['directorio'] . '/' .

            $preview['paginas'][$pagina - 1];



        $disco = Storage::disk('drive_prz_previews');



        abort_unless($disco->exists($archivo), 404);



        return $disco->response(

            $archivo,

            'pagina-' . $pagina . '.jpg',

            [

                'Content-Type' => 'image/jpeg',

                'Content-Disposition' => 'inline',

                'Cache-Control' => 'private, no-store, max-age=0',

                'Pragma' => 'no-cache',

                'X-Content-Type-Options' => 'nosniff',

                'Referrer-Policy' => 'no-referrer',

                'X-Robots-Tag' => 'noindex, nofollow',

            ],

            'inline'

        );

    }

}

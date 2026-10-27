<?php

use Illuminate\Support\Facades\Route;
use App\Exports\RegistroIngresosExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\MicrosoftController;
use App\Models\Documento;
use Illuminate\Support\Facades\Storage;
use App\Support\DocumentoAcceso;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use PhpOffice\PhpSpreadsheet\IOFactory as ExcelIOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Html as ExcelHtmlWriter;
use App\Http\Controllers\DrivePrzController;

Route::get('/admin/compras/formulario-contenido', function () {
    return view(
        'filament.pages.formulario-requerimiento-compra-contenido'
    );
})
    ->middleware('auth')
    ->name('compras.formulario.contenido');

Route::get('/exportar-registros', function () {

    $nombreArchivo = 'registro_ingresos.xlsx';

    Excel::store(
        new RegistroIngresosExport(),
        $nombreArchivo,
        'local'
    );

    $rutaArchivo = storage_path(
        'app/private/' . $nombreArchivo
    );

    return response()->download(
        $rutaArchivo,
        $nombreArchivo
    );
});

Route::get('/microsoft/login', [
    MicrosoftController::class,
    'redirect'
])->name('microsoft.login');

Route::get('/microsoft/callback', [
    MicrosoftController::class,
    'callback'
])->name('microsoft.callback');

Route::get('/microsoft/test', [
    MicrosoftController::class,
    'test'
])->name('microsoft.test');

Route::get('/prueba-session/guardar', function () {
    session(['prueba_session' => 'FUNCIONA']);

    return 'Sesión guardada. Ahora entra a /prueba-session/leer';
});

Route::get('/prueba-session/leer', function () {
    return [
        'valor' => session('prueba_session'),
        'session_id' => session()->getId(),
    ];
});

Route::get('/microsoft/subir-excel', [
    MicrosoftController::class,
    'subirExcel'
])->name('microsoft.subir.excel');


Route::get('/documentos/{documento}/descargar', function (Documento $documento) {

    // Verificar que el usuario tenga permiso
    DocumentoAcceso::verificar($documento);

    $disco = Storage::disk('documentos_privados');

    if (!$disco->exists($documento->ruta_archivo)) {
        abort(404, 'El archivo no existe en el servidor.');
    }

    return $disco->download(
        $documento->ruta_archivo,
        $documento->nombre_original
    );
})
    ->middleware('auth')
    ->name('documentos.descargar');


Route::get('/documentos/{documento}/ver', function (Documento $documento) {

    // 1. Verificar permisos
    DocumentoAcceso::verificar($documento);

    // 2. Acceder al almacenamiento privado
    $disco = Storage::disk('documentos_privados');

    if (!$disco->exists($documento->ruta_archivo)) {
        abort(404, 'El documento no existe.');
    }

    $ruta = $disco->path($documento->ruta_archivo);

    $extension = strtolower(
        $documento->extension
            ?: pathinfo($documento->nombre_original, PATHINFO_EXTENSION)
    );

    // 3. Mantener vista previa original de PDF e imágenes
    $tiposPermitidos = [
        'application/pdf',
        'image/jpeg',
        'image/png',
    ];

    $mime = $disco->mimeType($documento->ruta_archivo);

    if (
        in_array($extension, ['pdf', 'jpg', 'jpeg', 'png'], true)
        && in_array($mime, $tiposPermitidos, true)
    ) {
        return response()->file($ruta, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline',
            'X-Content-Type-Options' => 'nosniff',
            'Content-Security-Policy' =>
            "default-src 'none'; style-src 'unsafe-inline'; img-src 'self' data: blob:; object-src 'none'; frame-ancestors 'none'",
        ]);
    }

    // 4. Vista previa Word y Excel
    if (!in_array($extension, ['doc', 'docx', 'xls', 'xlsx'], true)) {
        abort(415, 'Este tipo de archivo no admite vista previa.');
    }

    // Archivo HTML temporal fuera del directorio público
    $temporal = tempnam(sys_get_temp_dir(), 'savia_preview_');

    if ($temporal === false) {
        abort(500, 'No fue posible preparar la vista previa.');
    }

    try {

        if (in_array($extension, ['doc', 'docx'], true)) {

            // Leer Word
            $lector = $extension === 'doc'
                ? 'MsDoc'
                : 'Word2007';

            $word = WordIOFactory::load($ruta, $lector);

            // Convertir Word a HTML
            $writer = WordIOFactory::createWriter($word, 'HTML');
            $writer->save($temporal);
        } else {

            // Leer Excel
            $reader = ExcelIOFactory::createReaderForFile($ruta);

            $excel = $reader->load($ruta);

            try {
                // Convertir todas las hojas a HTML
                $writer = new ExcelHtmlWriter($excel);

                $writer->writeAllSheets();

                // Evitar recalcular fórmulas durante la vista previa
                $writer->setPreCalculateFormulas(false);

                $writer->save($temporal);
            } finally {
                $excel->disconnectWorksheets();
            }
        }

        $html = file_get_contents($temporal);

        if ($html === false) {
            throw new RuntimeException(
                'No fue posible leer la vista previa.'
            );
        }
    } catch (\Throwable $e) {

        report($e);

        abort(
            422,
            'No fue posible generar la vista previa de este documento.'
        );
    } finally {

        if (file_exists($temporal)) {
            unlink($temporal);
        }
    }

    // 5. Mostrar HTML sin descargar archivo original
    return response($html, 200, [
        'Content-Type' => 'text/html; charset=UTF-8',
        'Content-Disposition' => 'inline',
        'X-Content-Type-Options' => 'nosniff',
        'Cache-Control' => 'private, no-store',
        'Content-Security-Policy' =>
        "sandbox; default-src 'none'; style-src 'unsafe-inline'; img-src data:; font-src data:; object-src 'none'; form-action 'none'; base-uri 'none'; frame-ancestors 'none'",
    ]);
})
    ->middleware('auth')
    ->name('documentos.ver');

Route::get('/documentos/{documento}/office-original', function (Documento $documento) {

    DocumentoAcceso::verificar($documento);

    $extension = strtolower(
        pathinfo($documento->nombre_original ?? '', PATHINFO_EXTENSION)
    );

    abort_unless(
        in_array($extension, ['doc', 'docx', 'xls', 'xlsx'], true),
        415
    );

    $disco = Storage::disk('documentos_privados');

    abort_unless(
        $disco->exists($documento->ruta_archivo),
        404
    );

    $mime = match ($extension) {
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    };

    $response = response()->file(
        $disco->path($documento->ruta_archivo),
        [
            'Content-Type' => $mime,
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]
    );

    $response->setContentDisposition(
        'inline',
        $documento->nombre_original,
        'documento.' . $extension
    );

    return $response;
})
    ->middleware(['auth', 'signed', 'throttle:20,1'])
    ->name('documentos.office-original');

// DRIVE PRZ V5 - EXPLORADOR DE ARCHIVOS

Route::middleware(['auth'])
    ->prefix('drive-prz-v5')
    ->name('drive-prz-v5.')
    ->group(function () {

        // Pantalla
        Route::get('/', [
            DrivePrzController::class,
            'screen'
        ])->name('screen');

        // Listar carpetas y documentos
        Route::get('/data', [
            DrivePrzController::class,
            'listing'
        ])->name('data');

        // Crear carpeta
        Route::post('/folders', [
            DrivePrzController::class,
            'makeFolder'
        ])->name('folders');

        // Crear carpetas por meses
        Route::post('/months', [
            DrivePrzController::class,
            'months'
        ])->name('months');

        // Crear varias carpetas
        Route::post('/bulk', [
            DrivePrzController::class,
            'bulk'
        ])->name('bulk');

        // Crear carpeta si no existe
        Route::post('/ensure-folder', [
            DrivePrzController::class,
            'ensureFolder'
        ])->name('ensure-folder');

        // Subir documentos
        Route::post('/files', [
            DrivePrzController::class,
            'upload'
        ])->name('files');

        // Renombrar carpetas o documentos
        Route::patch('/{kind}/{id}', [
            DrivePrzController::class,
            'rename'
        ])->whereIn('kind', ['folders', 'files'])
            ->name('rename');

        // Eliminar carpetas o documentos
        Route::delete('/{kind}/{id}', [
            DrivePrzController::class,
            'delete'
        ])->whereIn('kind', ['folders', 'files'])
            ->name('delete');

        // Mover carpetas o documentos
        Route::post('/{kind}/{id}/move', [
            DrivePrzController::class,
            'move'
        ])->whereIn('kind', ['folders', 'files'])
            ->name('move');

        // Carpetas disponibles como destino
        Route::get('/destinations', [
            DrivePrzController::class,
            'destinations'
        ])->name('destinations');

        // Descargar documento
        Route::get('/files/{id}/download', [
            DrivePrzController::class,
            'download'
        ])->name('download');

        // Vista previa
        Route::get('/files/{id}/preview', [
            DrivePrzController::class,
            'preview'
        ])->name('preview');
    });

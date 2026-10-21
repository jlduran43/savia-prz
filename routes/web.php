<?php

use Illuminate\Support\Facades\Route;
use App\Exports\RegistroIngresosExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\MicrosoftController;
use App\Models\Documento;
use Illuminate\Support\Facades\Storage;

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

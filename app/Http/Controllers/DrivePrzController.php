<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\DocumentoCarpeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DrivePrzController extends Controller
{
    private function carpetaPropia(int $id): DocumentoCarpeta
    {
        return DocumentoCarpeta::query()
            ->where('creado_por', auth()->id())
            ->findOrFail($id);
    }

    private function documentoPropio(int $id): Documento
    {
        return Documento::query()->where('subido_por', auth()->id())->findOrFail($id);
    }

    public function pantalla()
    {
        return view('drive-prz.panel');
    }

    public function listar(Request $request)
    {
        $data = $request->validate(['carpeta_id' => ['nullable', 'integer']]);
        $id = $data['carpeta_id'] ?? null;
        if ($id !== null) $this->carpetaPropia((int) $id);

        $carpetas = DocumentoCarpeta::query()
            ->where('creado_por', auth()->id())
            ->where('carpeta_padre_id', $id)
            ->orderBy('nombre')->get()
            ->map(fn ($c) => ['id' => $c->id, 'type' => 'folder', 'name' => $c->nombre,
                'parent' => $c->carpeta_padre_id, 'created' => $c->created_at?->toISOString()]);

        // Los documentos existentes sin carpeta se muestran en la raíz, si la columna permite NULL.
        $documentos = Documento::query()
            ->where('subido_por', auth()->id())
            ->where('carpeta_id', $id)
            ->orderBy('nombre_original')->get()
            ->map(fn ($d) => ['id' => $d->id, 'type' => 'file',
                'name' => $d->nombre ?: $d->nombre_original, 'parent' => $d->carpeta_id,
                'created' => $d->created_at?->toISOString()]);

        return response()->json(['items' => $carpetas->concat($documentos)->values()]);
    }

    public function crearCarpeta(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'carpeta_padre_id' => ['nullable', 'integer'],
        ]);
        $padre = $data['carpeta_padre_id'] ?? null;
        if ($padre !== null) $this->carpetaPropia((int) $padre);
        $existe = DocumentoCarpeta::query()->where('creado_por', auth()->id())
            ->where('carpeta_padre_id', $padre)->where('nombre', $data['nombre'])->exists();
        if ($existe) return response()->json(['message' => 'Ya existe una carpeta con ese nombre.'], 422);

        $c = new DocumentoCarpeta();
        $c->nombre = $data['nombre'];
        $c->carpeta_padre_id = $padre;
        $c->creado_por = auth()->id();
        $c->save();
        return response()->json(['ok' => true, 'id' => $c->id], 201);
    }

    public function subir(Request $request)
    {
        $data = $request->validate([
            'carpeta_id' => ['required', 'integer'],
            'archivo' => ['required', 'file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png'],
        ]);
        $this->carpetaPropia((int) $data['carpeta_id']);
        $archivo = $request->file('archivo');
        $ruta = $archivo->store('archivos', 'documentos_privados');
        try {
            $d = new Documento();
            $d->carpeta_id = (int) $data['carpeta_id'];
            $d->nombre = $archivo->getClientOriginalName();
            $d->nombre_original = $archivo->getClientOriginalName();
            $d->ruta_archivo = $ruta;
            $d->subido_por = auth()->id();
            $d->save();
        } catch (\Throwable $e) {
            Storage::disk('documentos_privados')->delete($ruta);
            throw $e;
        }
        return response()->json(['ok' => true, 'id' => $d->id], 201);
    }

    public function renombrarCarpeta(Request $request, int $id)
    {
        $c = $this->carpetaPropia($id);
        $data = $request->validate(['nombre' => ['required', 'string', 'max:120']]);
        $c->nombre = $data['nombre'];
        $c->save();
        return response()->json(['ok' => true]);
    }

    public function renombrarDocumento(Request $request, int $id)
    {
        $d = $this->documentoPropio($id);
        $data = $request->validate(['nombre' => ['required', 'string', 'max:160']]);
        $d->nombre = $data['nombre']; // No cambia el nombre físico del archivo.
        $d->save();
        return response()->json(['ok' => true]);
    }

    public function descargar(int $id)
    {
        $d = $this->documentoPropio($id);
        abort_unless(Storage::disk('documentos_privados')->exists($d->ruta_archivo), 404);
        return Storage::disk('documentos_privados')->download($d->ruta_archivo, $d->nombre_original ?: $d->nombre);
    }

    public function eliminarDocumento(int $id)
    {
        // Conserva el archivo físico para poder restaurar si el modelo usa SoftDeletes.
        $this->documentoPropio($id)->delete();
        return response()->json(['ok' => true]);
    }

    public function eliminarCarpeta(int $id)
    {
        $c = $this->carpetaPropia($id);
        // Evita eliminaciones recursivas accidentales y de elementos compartidos.
        $ocupada = DocumentoCarpeta::where('carpeta_padre_id', $id)->exists()
            || Documento::where('carpeta_id', $id)->exists();
        if ($ocupada) return response()->json(['message' => 'Vacía la carpeta antes de eliminarla.'], 422);
        $c->delete();
        return response()->json(['ok' => true]);
    }
}

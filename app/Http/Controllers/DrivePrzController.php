<?php



namespace App\Http\Controllers;



use App\Models\Documento;

use App\Models\DocumentoCarpeta;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;

use Illuminate\Validation\ValidationException;



class DrivePrzController extends Controller



{



    private function folder(int $id): DocumentoCarpeta



    {



        return DocumentoCarpeta::query()->where('creado_por', auth()->id())->whereNull('deleted_at')->findOrFail($id);



    }







    private function file(int $id): Documento



    {



        return Documento::query()->where('subido_por', auth()->id())->whereNull('deleted_at')->findOrFail($id);



    }







    private function parent(?int $id): void



    {



        if ($id !== null) $this->folder($id);



    }







    private function uniqueFolder(string $name, ?int $parent, ?int $except = null): bool



    {



        return DocumentoCarpeta::query()->where('creado_por', auth()->id())



            ->where('carpeta_padre_id', $parent)



            ->when($except, fn($q) => $q->whereKeyNot($except))



            ->get(['id', 'nombre'])->contains(fn($c) => mb_strtolower(trim($c->nombre)) === mb_strtolower(trim($name)));



    }







    private function createFolder(string $name, ?int $parent): DocumentoCarpeta



    {



        $c = new DocumentoCarpeta();



        $c->nombre = $name;



        $c->carpeta_padre_id = $parent;



        $c->creado_por = auth()->id();



        $c->save();



        return $c;



    }







    public function screen()



    {



        $ruta = resource_path('drive-prz/index.html');







        abort_unless(



            is_file($ruta),



            404,



            'No se encontró el archivo HTML de Drive PRZ.'



        );







        return response(



            file_get_contents($ruta),



            200,



            [



                'Content-Type' => 'text/html; charset=UTF-8',



                'Content-Disposition' => 'inline',



                'X-Content-Type-Options' => 'nosniff',



            ]



        );



    }







    public function listing(Request $request)



    {



        $d = $request->validate(['carpeta_id' => ['nullable', 'integer']]);



        $id = isset($d['carpeta_id']) ? (int)$d['carpeta_id'] : null;



        $this->parent($id);



        $folders = DocumentoCarpeta::query()->where('creado_por', auth()->id())



            ->where('carpeta_padre_id', $id)->whereNull('deleted_at')->orderBy('nombre')->get()



            ->map(fn($c) => ['id' => $c->id, 'type' => 'folder', 'name' => $c->nombre, 'modified' => $c->updated_at?->toISOString(), 'parent' => $c->carpeta_padre_id]);



        $files = Documento::query()->where('subido_por', auth()->id())->where('carpeta_id', $id)->whereNull('deleted_at')



            ->orderBy('nombre_original')->get()



            ->map(fn($f) => ['id' => $f->id, 'type' => 'file', 'name' => $f->nombre_original, 'modified' => $f->updated_at?->toISOString(), 'parent' => $f->carpeta_id, 'size' => (Storage::disk('documentos_privados')->exists($f->ruta_archivo) ? Storage::disk('documentos_privados')->size($f->ruta_archivo) : 0)]);



        $trail = [];



        $seen = [];



        while ($id !== null && !isset($seen[$id])) {



            $seen[$id] = true;



            $c = $this->folder($id);



            array_unshift($trail, ['id' => $c->id, 'name' => $c->nombre]);



            $id = $c->carpeta_padre_id === null ? null : (int)$c->carpeta_padre_id;



        }



        return response()->json(['items' => $folders->concat($files)->values(), 'trail' => $trail]);



    }







    public function makeFolder(Request $request)



    {



        $d = $request->validate(['nombre' => ['required', 'string', 'max:120'], 'carpeta_padre_id' => ['nullable', 'integer']]);



        $parent = isset($d['carpeta_padre_id']) ? (int)$d['carpeta_padre_id'] : null;



        $this->parent($parent);



        $name = trim($d['nombre']);



        if ($this->uniqueFolder($name, $parent)) throw ValidationException::withMessages(['nombre' => 'Ya existe una carpeta con ese nombre.']);



        return response()->json(['id' => $this->createFolder($name, $parent)->id], 201);



    }







    public function months(Request $request)



    {



        $d = $request->validate(['carpeta_padre_id' => ['nullable', 'integer'], 'meses' => ['required', 'array', 'min:1', 'max:12'], 'meses.\\\*' => ['required', 'integer', 'between:1,12', 'distinct']]);



        $parent = isset($d['carpeta_padre_id']) ? (int)$d['carpeta_padre_id'] : null;



        $this->parent($parent);



        $names = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];



        $created = DB::transaction(function () use ($d, $parent, $names) {



            $count = 0;



            foreach ($d['meses'] as $m) {



                $name = sprintf('%02d. %s', $m, $names[$m - 1]);



                if (!$this->uniqueFolder($name, $parent)) {



                    $this->createFolder($name, $parent);



                    $count++;



                }



            }



            return $count;



        });



        return response()->json(['created' => $created, 'skipped' => count($d['meses']) - $created]);



    }







    public function ensureFolder(Request $request)



    {



        $d = $request->validate(['nombre' => ['required', 'string', 'max:120'], 'carpeta_padre_id' => ['nullable', 'integer']]);



        $parent = isset($d['carpeta_padre_id']) ? (int)$d['carpeta_padre_id'] : null;



        $this->parent($parent);



        $name = trim($d['nombre']);



        $existing = DocumentoCarpeta::where('creado_por', auth()->id())->where('carpeta_padre_id', $parent)



            ->get()->first(fn($c) => mb_strtolower($c->nombre) === mb_strtolower($name));



        return response()->json(['id' => $existing?->id ?? $this->createFolder($name, $parent)->id]);



    }







    public function bulk(Request $request)



    {



        $d = $request->validate(['carpeta_padre_id' => ['nullable', 'integer'], 'estructura' => ['required', 'string', 'max:15000']]);



        $root = isset($d['carpeta_padre_id']) ? (int)$d['carpeta_padre_id'] : null;



        $this->parent($root);



        $lines = array_values(array_filter(array_map('trim', preg_split('/\*\***\r\n**\*\*|\*\***\r**\*\*|\*\***\n**\*\*/', $d['estructura']))));



        abort_if(count($lines) > 100, 422, 'Máximo 100 rutas por operación.');



        $created = DB::transaction(function () use ($lines, $root) {



            $n = 0;



            foreach ($lines as $line) {



                $parent = $root;



                $parts = explode('/', $line);



                abort_if(count($parts) > 12, 422, 'Profundidad máxima: 12 carpetas.');



                foreach ($parts as $raw) {



                    $name = trim($raw);



                    abort_if($name === '' || mb_strlen($name) > 120 || preg_match('/[\\\\\\\\<>\x00-\x1F]/', $name), 422, 'Nombre de carpeta inválido.');



                    $existing = DocumentoCarpeta::where('creado_por', auth()->id())->where('carpeta_padre_id', $parent)->get()



                        ->first(fn($c) => mb_strtolower($c->nombre) === mb_strtolower($name));



                    if ($existing) $parent = $existing->id;



                    else {



                        $parent = $this->createFolder($name, $parent)->id;



                        $n++;



                    }



                }



            }



            return $n;



        });



        return response()->json(['created' => $created]);



    }











    public function upload(Request $request)



    {



        $d = $request->validate([



            'carpeta_id' => ['nullable', 'integer'],



            'archivo' => [



                'required',



                'file',



                'max:20480',



            ],



        ], [



            'archivo.required' => 'Debes seleccionar un archivo.',



            'archivo.max' => 'El archivo supera el máximo de 20 MB.',



        ]);







        $carpetaId = isset($d['carpeta_id'])



            ? (int) $d['carpeta_id']



            : null;







        $this->parent($carpetaId);







        $archivo = $request->file('archivo');







        $ruta = $archivo->store(



            'archivos',



            'documentos_privados'



        );







        if (!$ruta) {



            return response()->json([



                'message' => 'No fue posible almacenar el archivo.',



            ], 500);



        }







        try {



            $doc = new Documento();







            $doc->carpeta_id = $carpetaId;



            $doc->nombre_original = $archivo->getClientOriginalName();



            $doc->nombre_almacenado = basename($ruta);



            $doc->ruta_archivo = $ruta;



            $doc->subido_por = auth()->id();







            $doc->save();



        } catch (\Throwable $e) {







            Storage::disk('documentos_privados')->delete($ruta);







            report($e);







            return response()->json([



                'message' => 'No fue posible registrar el archivo.',



            ], 500);



        }







        return response()->json([



            'ok' => true,



            'message' => 'Archivo subido correctamente.',



            'documento' => [



                'id' => $doc->id,



                'nombre' => $doc->nombre_original,



                'carpeta_id' => $doc->carpeta_id,



            ],



        ], 201);



    }







    public function rename(Request $request, string $kind, int $id)



    {



        abort_unless(in_array($kind, ['folders', 'files'], true), 404);



        $d = $request->validate(['nombre' => ['required', 'string', 'max:120']]);



        if ($kind === 'folders') {



            $c = $this->folder($id);



            if ($this->uniqueFolder(trim($d['nombre']), $c->carpeta_padre_id, $id))



                throw ValidationException::withMessages(['nombre' => 'Ya existe otra carpeta con ese nombre.']);



            $c->nombre = trim($d['nombre']);



            $c->save();



        } else {



            $f = $this->file($id);



            $f->nombre_original = trim($d['nombre']);



            $f->save();



        }



        return response()->json(['ok' => true]);



    }







        public function delete(string $kind, int $id)

    {

        abort_unless(in_array($kind, ['folders', 'files'], true), 404);



        DB::transaction(function () use ($kind, $id) {

            $fecha = now();

            $usuario = auth()->id();



            if ($kind === 'files') {

                $archivo = $this->file($id);

                DB::table('documentos')->where('id', $archivo->id)

                    ->where('subido_por', $usuario)->whereNull('deleted_at')

                    ->update(['deleted_at' => $fecha]);

                return;

            }



            $carpeta = $this->folder($id);

            $pendientes = [(int) $carpeta->id];

            $visitados = [];



            while ($pendientes) {

                $carpetaId = array_pop($pendientes);

                if (isset($visitados[$carpetaId])) continue;

                $visitados[$carpetaId] = true;



                $hijos = DB::table('documento_carpetas')

                    ->where('creado_por', $usuario)

                    ->where('carpeta_padre_id', $carpetaId)

                    ->whereNull('deleted_at')->pluck('id');

                foreach ($hijos as $hijo) $pendientes[] = (int) $hijo;



                DB::table('documentos')->where('subido_por', $usuario)

                    ->where('carpeta_id', $carpetaId)->whereNull('deleted_at')

                    ->update(['deleted_at' => $fecha]);



                DB::table('documento_carpetas')->where('id', $carpetaId)

                    ->where('creado_por', $usuario)->whereNull('deleted_at')

                    ->update(['deleted_at' => $fecha]);

            }

        });



        return response()->json(['ok' => true, 'message' => 'Elemento movido a papelera.']);

    }




    // Papelera real: se muestran solo elementos eliminados de primer nivel.
    public function trash()
    {
        $uid = auth()->id();
        $folders = DB::table('documento_carpetas as c')
            ->leftJoin('documento_carpetas as p', 'p.id', '=', 'c.carpeta_padre_id')
            ->where('c.creado_por', $uid)->whereNotNull('c.deleted_at')
            ->where(function ($q) {
                $q->whereNull('c.carpeta_padre_id')->orWhereNull('p.deleted_at');
            })
            ->select('c.id', 'c.nombre', 'c.deleted_at', 'c.carpeta_padre_id')
            ->get()->map(fn ($c) => [
                'id' => $c->id, 'type' => 'folder', 'name' => $c->nombre,
                'modified' => $c->deleted_at, 'parent' => $c->carpeta_padre_id,
                'size' => null,
            ]);
        $files = DB::table('documentos as d')
            ->leftJoin('documento_carpetas as p', 'p.id', '=', 'd.carpeta_id')
            ->where('d.subido_por', $uid)->whereNotNull('d.deleted_at')
            ->where(function ($q) {
                $q->whereNull('d.carpeta_id')->orWhereNull('p.deleted_at');
            })
            ->select('d.id', 'd.nombre_original', 'd.deleted_at', 'd.carpeta_id', 'd.ruta_archivo')
            ->get()->map(fn ($d) => [
                'id' => $d->id, 'type' => 'file', 'name' => $d->nombre_original,
                'modified' => $d->deleted_at, 'parent' => $d->carpeta_id,
                'size' => $d->ruta_archivo && Storage::disk('documentos_privados')->exists($d->ruta_archivo)
                    ? Storage::disk('documentos_privados')->size($d->ruta_archivo) : 0,
            ]);
        return response()->json(['items' => $folders->concat($files)->values()]);
    }

    // Obtiene los descendientes de una carpeta del usuario, sin importar su estado.
    private function trashTree(int $root, int $uid): array
    {
        $ids = [];
        $pending = [$root];
        while ($pending) {
            $id = array_pop($pending);
            if (in_array($id, $ids, true)) continue;
            $ids[] = $id;
            $children = DB::table('documento_carpetas')
                ->where('creado_por', $uid)->where('carpeta_padre_id', $id)->pluck('id');
            foreach ($children as $child) $pending[] = (int) $child;
        }
        return $ids;
    }

    public function restore(string $kind, int $id)
    {
        abort_unless(in_array($kind, ['folders', 'files'], true), 404);
        $uid = auth()->id();
        DB::transaction(function () use ($kind, $id, $uid) {
            $table = $kind === 'folders' ? 'documento_carpetas' : 'documentos';
            $owner = $kind === 'folders' ? 'creado_por' : 'subido_por';
            $item = DB::table($table)->where('id', $id)->where($owner, $uid)
                ->whereNotNull('deleted_at')->lockForUpdate()->first();
            abort_unless($item, 404);
            $parentId = $kind === 'folders' ? $item->carpeta_padre_id : $item->carpeta_id;
            if ($parentId !== null) {
                $parent = DB::table('documento_carpetas')->where('id', $parentId)
                    ->where('creado_por', $uid)->whereNull('deleted_at')->first();
                if (!$parent) $parentId = null;
            }
            if ($kind === 'folders') {
                $duplicate = DB::table('documento_carpetas')->where('creado_por', $uid)
                    ->where('carpeta_padre_id', $parentId)->whereNull('deleted_at')
                    ->where('nombre', $item->nombre)->exists();
                abort_if($duplicate, 422, 'Ya existe una carpeta con ese nombre en el destino.');
                $ids = $this->trashTree($id, $uid);
                // Solo restaurar hijos eliminados en la misma operación que la carpeta.
                DB::table('documento_carpetas')->where('id', $id)->update([
                    'carpeta_padre_id' => $parentId, 'deleted_at' => null,
                ]);
                DB::table('documento_carpetas')->whereIn('id', $ids)
                    ->where('creado_por', $uid)->where('deleted_at', $item->deleted_at)
                    ->update(['deleted_at' => null]);
                DB::table('documentos')->whereIn('carpeta_id', $ids)
                    ->where('subido_por', $uid)->where('deleted_at', $item->deleted_at)
                    ->update(['deleted_at' => null]);
            } else {
                DB::table('documentos')->where('id', $id)->where('subido_por', $uid)
                    ->update(['carpeta_id' => $parentId, 'deleted_at' => null]);
            }
        });
        return response()->json(['ok' => true, 'message' => 'Elemento restaurado correctamente.']);
    }

    public function forceDelete(string $kind, int $id)
    {
        abort_unless(in_array($kind, ['folders', 'files'], true), 404);
        $uid = auth()->id();
        $paths = DB::transaction(function () use ($kind, $id, $uid) {
            $table = $kind === 'folders' ? 'documento_carpetas' : 'documentos';
            $owner = $kind === 'folders' ? 'creado_por' : 'subido_por';
            $item = DB::table($table)->where('id', $id)->where($owner, $uid)
                ->whereNotNull('deleted_at')->lockForUpdate()->first();
            abort_unless($item, 404);
            if ($kind === 'files') {
                DB::table('documentos')->where('id', $id)->where('subido_por', $uid)->delete();
                return array_filter([$item->ruta_archivo]);
            }
            $ids = $this->trashTree($id, $uid);
            // Nunca borrar físicamente contenido todavía activo.
            abort_if(DB::table('documento_carpetas')->whereIn('id', $ids)
                ->where('creado_por', $uid)->whereNull('deleted_at')->exists(), 409,
                'La carpeta contiene subcarpetas activas.');
            abort_if(DB::table('documentos')->whereIn('carpeta_id', $ids)
                ->where('subido_por', $uid)->whereNull('deleted_at')->exists(), 409,
                'La carpeta contiene archivos activos.');
            $paths = DB::table('documentos')->whereIn('carpeta_id', $ids)
                ->where('subido_por', $uid)->pluck('ruta_archivo')->filter()->all();
            DB::table('documentos')->whereIn('carpeta_id', $ids)
                ->where('subido_por', $uid)->delete();
            // Hojas antes que padres, para respetar claves foráneas.
            foreach (array_reverse($ids) as $folderId) {
                DB::table('documento_carpetas')->where('id', $folderId)
                    ->where('creado_por', $uid)->delete();
            }
            return $paths;
        });
        try {
            if ($paths && !Storage::disk('documentos_privados')->delete($paths)) {
                \Log::warning('Drive PRZ: no se pudieron borrar todos los archivos físicos', ['paths' => $paths]);
            }
        } catch (\Throwable $e) { report($e); }
        return response()->json(['ok' => true, 'message' => 'Elemento eliminado definitivamente.']);
    }

public function move(Request $request, string $kind, int $id)



    {



        abort_unless(in_array($kind, ['folders', 'files'], true), 404);







        $data = $request->validate([



            'destino_id' => ['present', 'nullable', 'integer', 'min:1'],



        ]);







        $destinoId = $data['destino_id'] === null



            ? null



            : (int) $data['destino_id'];







        return DB::transaction(function () use ($kind, $id, $destinoId) {







            // Validar que el destino pertenece al usuario.



            if ($destinoId !== null) {



                $this->folder($destinoId);



            }







            if ($kind === 'folders') {







                $carpeta = $this->folder($id);







                $origenId = $carpeta->carpeta_padre_id === null



                    ? null



                    : (int) $carpeta->carpeta_padre_id;







                if ($origenId === $destinoId) {



                    throw ValidationException::withMessages([



                        'destino_id' => 'La carpeta ya se encuentra en este destino.',



                    ]);



                }







                if ($destinoId === (int) $carpeta->id) {



                    throw ValidationException::withMessages([



                        'destino_id' => 'No puedes mover una carpeta dentro de sí misma.',



                    ]);



                }







                // Evitar mover una carpeta dentro de sus descendientes.



                $cursorId = $destinoId;



                $visitados = [];







                while ($cursorId !== null) {







                    if (isset($visitados[$cursorId])) {



                        throw ValidationException::withMessages([



                            'destino_id' => 'Se detectó una estructura circular de carpetas.',



                        ]);



                    }







                    $visitados[$cursorId] = true;







                    if ($cursorId === (int) $carpeta->id) {



                        throw ValidationException::withMessages([



                            'destino_id' => 'No puedes mover una carpeta dentro de una de sus subcarpetas.',



                        ]);



                    }







                    $actual = $this->folder($cursorId);







                    $cursorId = $actual->carpeta_padre_id === null



                        ? null



                        : (int) $actual->carpeta_padre_id;



                }







                // Evitar carpetas duplicadas.



                if ($this->uniqueFolder(



                    $carpeta->nombre,



                    $destinoId,



                    $carpeta->id



                )) {



                    throw ValidationException::withMessages([



                        'destino_id' => 'Ya existe una carpeta con ese nombre en el destino.',



                    ]);



                }







                $carpeta->carpeta_padre_id = $destinoId;



                $carpeta->save();



            } else {







                $archivo = $this->file($id);







                $origenId = $archivo->carpeta_id === null



                    ? null



                    : (int) $archivo->carpeta_id;







                if ($origenId === $destinoId) {



                    throw ValidationException::withMessages([



                        'destino_id' => 'El archivo ya se encuentra en esta carpeta.',



                    ]);



                }







                $archivo->carpeta_id = $destinoId;



                $archivo->save();



            }







            return response()->json([



                'ok' => true,



                'message' => 'Elemento movido correctamente.',



                'destino_id' => $destinoId,



            ]);



        });



    }







    public function destinations()



    {



        $folders = DocumentoCarpeta::query()



            ->where('creado_por', auth()->id())



            ->orderBy('nombre')



            ->get([



                'id',



                'nombre',



                'carpeta_padre_id',



            ]);







        return response()->json([



            'folders' => $folders,



        ]);



    }







    public function download(int $id)



    {



        $d = $this->file($id);



        abort_unless(Storage::disk('documentos_privados')->exists($d->ruta_archivo), 404);



        return Storage::disk('documentos_privados')->download($d->ruta_archivo, $d->nombre_original ?: $d->nombre);



    }







    public function preview(int $id)



    {



        $d = $this->file($id);



        abort_unless(Storage::disk('documentos_privados')->exists($d->ruta_archivo), 404);



        $mime = Storage::disk('documentos_privados')->mimeType($d->ruta_archivo);



        abort_unless(in_array($mime, ['application/pdf', 'image/png', 'image/jpeg'], true), 415, 'Vista previa no disponible.');



        return response()->file(Storage::disk('documentos_privados')->path($d->ruta_archivo), ['Content-Type' => $mime, 'Content-Disposition' => 'inline']);



    }



}

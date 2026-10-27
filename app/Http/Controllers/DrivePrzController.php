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
        return DocumentoCarpeta::query()->where('creado_por', auth()->id())->findOrFail($id);
    }

    private function file(int $id): Documento
    {
        return Documento::query()->where('subido_por', auth()->id())->findOrFail($id);
    }

    private function parent(?int $id): void
    {
        if ($id !== null) $this->folder($id);
    }

    private function uniqueFolder(string $name, ?int $parent, ?int $except = null): bool
    {
        return DocumentoCarpeta::query()->where('creado_por', auth()->id())
            ->where('carpeta_padre_id', $parent)
            ->when($except, fn ($q) => $q->whereKeyNot($except))
            ->get(['id','nombre'])->contains(fn ($c) => mb_strtolower(trim($c->nombre)) === mb_strtolower(trim($name)));
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

    public function screen() { return view('drive-prz-v5.panel'); }

    public function listing(Request $request)
    {
        $d = $request->validate(['carpeta_id' => ['nullable','integer']]);
        $id = isset($d['carpeta_id']) ? (int)$d['carpeta_id'] : null;
        $this->parent($id);
        $folders = DocumentoCarpeta::query()->where('creado_por', auth()->id())
            ->where('carpeta_padre_id', $id)->orderBy('nombre')->get()
            ->map(fn ($c) => ['id'=>$c->id,'type'=>'folder','name'=>$c->nombre,'modified'=>$c->updated_at?->toISOString(),'parent'=>$c->carpeta_padre_id]);
        $files = Documento::query()->where('subido_por', auth()->id())->where('carpeta_id', $id)
            ->orderBy('nombre_original')->get()
            ->map(fn ($f) => ['id'=>$f->id,'type'=>'file','name'=>$f->nombre_original,'modified'=>$f->updated_at?->toISOString(),'parent'=>$f->carpeta_id,'size'=>(Storage::disk('documentos_privados')->exists($f->ruta_archivo) ? Storage::disk('documentos_privados')->size($f->ruta_archivo) : 0)]);
        $trail = [];
        $seen = [];
        while ($id !== null && !isset($seen[$id])) {
            $seen[$id] = true;
            $c = $this->folder($id);
            array_unshift($trail, ['id'=>$c->id,'name'=>$c->nombre]);
            $id = $c->carpeta_padre_id === null ? null : (int)$c->carpeta_padre_id;
        }
        return response()->json(['items'=>$folders->concat($files)->values(),'trail'=>$trail]);
    }

    public function makeFolder(Request $request)
    {
        $d = $request->validate(['nombre'=>['required','string','max:120'],'carpeta_padre_id'=>['nullable','integer']]);
        $parent = isset($d['carpeta_padre_id']) ? (int)$d['carpeta_padre_id'] : null;
        $this->parent($parent);
        $name = trim($d['nombre']);
        if ($this->uniqueFolder($name,$parent)) throw ValidationException::withMessages(['nombre'=>'Ya existe una carpeta con ese nombre.']);
        return response()->json(['id'=>$this->createFolder($name,$parent)->id],201);
    }

    public function months(Request $request)
    {
        $d = $request->validate(['carpeta_padre_id'=>['nullable','integer'], 'meses'=>['required','array','min:1','max:12'], 'meses.*'=>['required','integer','between:1,12','distinct']]);
        $parent = isset($d['carpeta_padre_id']) ? (int)$d['carpeta_padre_id'] : null;
        $this->parent($parent);
        $names = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
        $created = DB::transaction(function () use ($d,$parent,$names) {
            $count = 0;
            foreach ($d['meses'] as $m) {
                $name = sprintf('%02d. %s', $m, $names[$m-1]);
                if (!$this->uniqueFolder($name,$parent)) { $this->createFolder($name,$parent); $count++; }
            }
            return $count;
        });
        return response()->json(['created'=>$created,'skipped'=>count($d['meses'])-$created]);
    }

    public function ensureFolder(Request $request)
    {
        $d = $request->validate(['nombre'=>['required','string','max:120'], 'carpeta_padre_id'=>['nullable','integer']]);
        $parent = isset($d['carpeta_padre_id']) ? (int)$d['carpeta_padre_id'] : null;
        $this->parent($parent);
        $name = trim($d['nombre']);
        $existing = DocumentoCarpeta::where('creado_por',auth()->id())->where('carpeta_padre_id',$parent)
            ->get()->first(fn ($c) => mb_strtolower($c->nombre) === mb_strtolower($name));
        return response()->json(['id'=>$existing?->id ?? $this->createFolder($name,$parent)->id]);
    }

    public function bulk(Request $request)
    {
        $d = $request->validate(['carpeta_padre_id'=>['nullable','integer'], 'estructura'=>['required','string','max:15000']]);
        $root = isset($d['carpeta_padre_id']) ? (int)$d['carpeta_padre_id'] : null;
        $this->parent($root);
        $lines = array_values(array_filter(array_map('trim',preg_split('/\r\n|\r|\n/', $d['estructura']))));
        abort_if(count($lines)>100,422,'Máximo 100 rutas por operación.');
        $created = DB::transaction(function () use($lines,$root) {
            $n=0;
            foreach($lines as $line){
                $parent=$root;
                $parts=explode('/',$line);
                abort_if(count($parts)>12,422,'Profundidad máxima: 12 carpetas.');
                foreach($parts as $raw){
                    $name=trim($raw);
                    abort_if($name==='' || mb_strlen($name)>120 || preg_match('/[\\<>\x00-\x1F]/',$name),422,'Nombre de carpeta inválido.');
                    $existing=DocumentoCarpeta::where('creado_por',auth()->id())->where('carpeta_padre_id',$parent)->get()
                        ->first(fn($c)=>mb_strtolower($c->nombre)===mb_strtolower($name));
                    if($existing) $parent=$existing->id;
                    else {$parent=$this->createFolder($name,$parent)->id; $n++;}
                }
            }
            return $n;
        });
        return response()->json(['created'=>$created]);
    }

    public function upload(Request $request)
    {
        $d = $request->validate(['carpeta_id'=>['required','integer'],'archivo'=>['required','file','max:10240','mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png']]);
        $this->folder((int)$d['carpeta_id']);
        $file = $request->file('archivo');
        $path = $file->store('archivos','documentos_privados');
        try {
            $doc = new Documento();
            $doc->carpeta_id = (int)$d['carpeta_id'];
            $doc->nombre_original = $file->getClientOriginalName();
            $doc->nombre_almacenado = basename($path);
            $doc->ruta_archivo = $path;
            $doc->subido_por = auth()->id();
            $doc->save();
        } catch (\Throwable $e) { Storage::disk('documentos_privados')->delete($path); throw $e; }
        return response()->json(['id'=>$doc->id],201);
    }

    public function rename(Request $request,string $kind,int $id)
    {
        abort_unless(in_array($kind,['folders','files'],true),404);
        $d = $request->validate(['nombre'=>['required','string','max:120']]);
        if ($kind === 'folders') {
            $c = $this->folder($id);
            if ($this->uniqueFolder(trim($d['nombre']),$c->carpeta_padre_id,$id))
                throw ValidationException::withMessages(['nombre'=>'Ya existe otra carpeta con ese nombre.']);
            $c->nombre = trim($d['nombre']); $c->save();
        } else {
            $f = $this->file($id); $f->nombre = trim($d['nombre']); $f->save();
        }
        return response()->json(['ok'=>true]);
    }

    public function delete(string $kind,int $id)
    {
        abort_unless(in_array($kind,['folders','files'],true),404);
        if ($kind === 'files') $this->file($id)->delete();
        else {
            $c = $this->folder($id);
            if (DocumentoCarpeta::where('carpeta_padre_id',$id)->exists() || Documento::where('carpeta_id',$id)->exists())
                throw ValidationException::withMessages(['carpeta'=>'La carpeta contiene elementos. Vacíala antes de eliminar.']);
            $c->delete();
        }
        return response()->json(['ok'=>true]);
    }

    public function move(Request $request,string $kind,int $id)
    {
        abort_unless(in_array($kind,['folders','files'],true),404);
        $d = $request->validate(['destino_id'=>['required','integer']]);
        $dest = $this->folder((int)$d['destino_id']);
        if ($kind === 'folders') {
            $c = $this->folder($id);
            abort_if($c->id === $dest->id,422,'No puedes mover una carpeta dentro de sí misma.');
            $cursor = $dest; $seen = [];
            while ($cursor && !isset($seen[$cursor->id])) {
                abort_if($cursor->id === $c->id,422,'No puedes mover una carpeta a una subcarpeta propia.');
                $seen[$cursor->id] = true;
                $cursor = $cursor->carpeta_padre_id ? $this->folder((int)$cursor->carpeta_padre_id) : null;
            }
            if ($this->uniqueFolder($c->nombre,$dest->id,$c->id)) throw ValidationException::withMessages(['nombre'=>'Ya existe esa carpeta en el destino.']);
            $c->carpeta_padre_id=$dest->id; $c->save();
        } else { $f=$this->file($id); $f->carpeta_id=$dest->id; $f->save(); }
        return response()->json(['ok'=>true]);
    }

    public function destinations()
    {
        $folders = DocumentoCarpeta::query()->where('creado_por',auth()->id())->orderBy('nombre')->get(['id','nombre','carpeta_padre_id']);
        return response()->json(['folders'=>$folders]);
    }

    public function download(int $id)
    {
        $d=$this->file($id);
        abort_unless(Storage::disk('documentos_privados')->exists($d->ruta_archivo),404);
        return Storage::disk('documentos_privados')->download($d->ruta_archivo,$d->nombre_original ?: $d->nombre);
    }

    public function preview(int $id)
    {
        $d=$this->file($id);
        abort_unless(Storage::disk('documentos_privados')->exists($d->ruta_archivo),404);
        $mime=Storage::disk('documentos_privados')->mimeType($d->ruta_archivo);
        abort_unless(in_array($mime,['application/pdf','image/png','image/jpeg'],true),415,'Vista previa no disponible.');
        return response()->file(Storage::disk('documentos_privados')->path($d->ruta_archivo), ['Content-Type'=>$mime,'Content-Disposition'=>'inline']);
    }
}

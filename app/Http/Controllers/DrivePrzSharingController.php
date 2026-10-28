<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\DocumentoCarpeta;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class DrivePrzSharingController extends Controller
{
    private const TABLE = 'drive_prz_permisos';

    private function actor(): User
    {
        $user = auth()->user();
        abort_unless($user instanceof User, 401);
        abort_if(isset($user->activo) && ! $user->activo, 403, 'Cuenta inactiva.');
        return $user;
    }

    private function element(string $kind, int $id): Documento|DocumentoCarpeta
    {
        abort_unless(in_array($kind, ['files', 'folders'], true), 404);
        return $kind === 'files'
            ? Documento::query()->findOrFail($id)
            : DocumentoCarpeta::query()->findOrFail($id);
    }

    private function ownerId(string $kind, Documento|DocumentoCarpeta $item): int
    {
        // Verificar estos nombres de columnas con los modelos de SAVIA PRZ.
        $field = $kind === 'files' ? 'subido_por' : 'creado_por';
        abort_unless($item->getAttribute($field) !== null, 409,
            "El elemento no tiene propietario ($field). Corrige los datos antes de compartir.");
        return (int) $item->getAttribute($field);
    }

    private function assertOwner(string $kind, Documento|DocumentoCarpeta $item): void
    {
        abort_unless($this->ownerId($kind, $item) === (int) $this->actor()->id, 403,
            'Solo el propietario puede compartir este elemento.');
    }

    private function folderAncestors(string $kind, Documento|DocumentoCarpeta $item): array
    {
        $id = $kind === 'files' ? $item->getAttribute('carpeta_id') : $item->getAttribute('carpeta_padre_id');
        $ids = [];
        $seen = [];
        for ($depth = 0; $id !== null && $depth < 100; $depth++) {
            $id = (int) $id;
            abort_if(isset($seen[$id]), 409, 'Estructura de carpetas circular.');
            $seen[$id] = true;
            $folder = DocumentoCarpeta::query()->find($id);
            if (!$folder) break;
            $ids[] = $id;
            $id = $folder->carpeta_padre_id;
        }
        return $ids;
    }

    private function directRole(string $kind, int $id, int $userId): ?string
    {
        return DB::table(self::TABLE)
            ->where('tipo', $kind)->where('elemento_id', $id)
            ->where('usuario_id', $userId)->value('permiso');
    }

    private function everyoneRole(string $kind, int $id): ?string
    {
        return DB::table(self::TABLE)->where('tipo', $kind)
            ->where('elemento_id', $id)->whereNull('usuario_id')->value('permiso');
    }

    private function effectiveRole(string $kind, int $id, Documento|DocumentoCarpeta $item, int $userId): ?string
    {
        if ($this->ownerId($kind, $item) === $userId) return 'edit';
        $roles = [$this->directRole($kind, $id, $userId), $this->everyoneRole($kind, $id)];
        foreach ($this->folderAncestors($kind, $item) as $folderId) {
            $roles[] = $this->directRole('folders', $folderId, $userId);
            $roles[] = $this->everyoneRole('folders', $folderId);
        }
        if (in_array('edit', $roles, true)) return 'edit';
        return in_array('view', $roles, true) ? 'view' : null;
    }

    private function ensureVisible(string $kind, int $id, Documento|DocumentoCarpeta $item): void
    {
        abort_unless($this->effectiveRole($kind, $id, $item, (int) $this->actor()->id) !== null,
            403, 'No tienes permiso para consultar este elemento.');
    }

    private function person(User $user): array
    {
        $name = trim(($user->nombre ?? $user->name ?? '') . ' ' . ($user->apellido ?? ''));
        return ['id' => $user->id, 'name' => $name ?: $user->email, 'email' => $user->email];
    }

    private function validateRole(Request $request): string
    {
        return $request->validate(['role' => ['required', Rule::in(['view', 'edit'])]])['role'];
    }

    public function users(Request $request): JsonResponse
    {
        $this->actor();
        $query = User::query()->where('id', '!=', auth()->id());
        if (Schema::hasColumn('users', 'activo')) $query->where('activo', 1);
        $term = trim((string) $request->query('q', ''));
        if ($term !== '') {
            $like = '%' . addcslashes($term, '%_\\') . '%';
            $query->where(function ($q) use ($like) {
                $q->where('email', 'like', $like);
                foreach (['nombre', 'apellido', 'name'] as $field) {
                    if (Schema::hasColumn('users', $field)) $q->orWhere($field, 'like', $like);
                }
            });
        }
        return response()->json(['users' => $query->orderBy('email')->limit(100)
            ->get()->map(fn ($u) => $this->person($u))->values()]);
    }

    public function show(string $kind, int $id): JsonResponse
    {
        $item = $this->element($kind, $id);
        $this->ensureVisible($kind, $id, $item);
        $owner = User::find($this->ownerId($kind, $item));
        $people = DB::table(self::TABLE . ' as p')
            ->join('users as u', 'u.id', '=', 'p.usuario_id')
            ->where('p.tipo', $kind)->where('p.elemento_id', $id)
            ->whereNotNull('p.usuario_id')->get(['p.usuario_id', 'p.permiso']);
        $people = $people->map(function ($p) {
            $u = User::find($p->usuario_id);
            return $u ? array_merge($this->person($u), ['role' => $p->permiso]) : null;
        })->filter()->values();

        $inherited = [];
        foreach ($this->folderAncestors($kind, $item) as $folderId) {
            $entries = DB::table(self::TABLE)->where('tipo', 'folders')->where('elemento_id', $folderId)->get();
            foreach ($entries as $entry) {
                $u = $entry->usuario_id ? User::find($entry->usuario_id) : null;
                $inherited[] = [
                    'name' => $u ? $this->person($u)['name'] : 'Todos los usuarios',
                    'role' => $entry->permiso,
                    'folder_id' => $folderId,
                ];
            }
        }
        $role = $this->everyoneRole($kind, $id);
        return response()->json([
            'owner' => $owner ? $this->person($owner) : null,
            'can_share' => (int) auth()->id() === $this->ownerId($kind, $item),
            'people' => $people,
            'everyone' => ['enabled' => $role !== null, 'role' => $role ?? 'view'],
            'inherited' => $inherited,
        ]);
    }

    public function store(Request $request, string $kind, int $id): JsonResponse
    {
        $item = $this->element($kind, $id);
        $this->assertOwner($kind, $item);
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', Rule::in(['view', 'edit'])],
        ]);
        $recipient = User::where('email', $data['email'])->first();
        abort_unless($recipient && (! Schema::hasColumn('users', 'activo') || $recipient->activo),
            422, 'Selecciona una cuenta activa de SAVIA PRZ.');
        abort_if((int) $recipient->id === $this->ownerId($kind, $item), 422,
            'El propietario ya tiene acceso completo.');
        DB::table(self::TABLE)->updateOrInsert(
            ['tipo' => $kind, 'elemento_id' => $id, 'usuario_id' => $recipient->id],
            ['permiso' => $data['role'], 'compartido_por' => auth()->id(), 'updated_at' => now(), 'created_at' => now()]
        );
        return response()->json(['ok' => true]);
    }

    public function update(Request $request, string $kind, int $id, int $userId): JsonResponse
    {
        $item = $this->element($kind, $id);
        $this->assertOwner($kind, $item);
        $role = $this->validateRole($request);
        $updated = DB::table(self::TABLE)->where('tipo', $kind)->where('elemento_id', $id)
            ->where('usuario_id', $userId)->update(['permiso' => $role, 'updated_at' => now()]);
        abort_unless($updated, 404, 'El usuario no tiene un permiso directo para este elemento.');
        return response()->json(['ok' => true]);
    }

    public function destroy(string $kind, int $id, int $userId): JsonResponse
    {
        $item = $this->element($kind, $id);
        $this->assertOwner($kind, $item);
        DB::table(self::TABLE)->where('tipo', $kind)->where('elemento_id', $id)
            ->where('usuario_id', $userId)->delete();
        return response()->json(['ok' => true]);
    }

    public function general(Request $request, string $kind, int $id): JsonResponse
    {
        $item = $this->element($kind, $id);
        $this->assertOwner($kind, $item);
        $data = $request->validate([
            'enabled' => ['required', 'boolean'],
            'role' => ['required', Rule::in(['view', 'edit'])],
        ]);
        DB::transaction(function () use ($kind, $id, $data) {
            $query = DB::table(self::TABLE)->where('tipo', $kind)
                ->where('elemento_id', $id)->whereNull('usuario_id');
            $query->delete();
            if ($data['enabled']) {
                DB::table(self::TABLE)->insert([
                    'tipo' => $kind, 'elemento_id' => $id, 'usuario_id' => null,
                    'permiso' => $data['role'], 'compartido_por' => auth()->id(),
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        });
        return response()->json(['ok' => true]);
    }
}
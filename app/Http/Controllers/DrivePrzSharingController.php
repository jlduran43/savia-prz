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
use Illuminate\Validation\ValidationException;

class DrivePrzSharingController extends Controller
{
    private const TABLE = 'drive_prz_permisos';

    private const PERMISOS_DISPONIBLES = [
        'ver',
        'descargar',
        'renombrar',
        'mover',
        'copiar',
        'eliminar',
        'compartir',
    ];

    private function actor(): User
    {
        $user = auth()->user();

        abort_unless($user instanceof User, 401);

        abort_if(
            isset($user->activo) && ! $user->activo,
            403,
            'Cuenta inactiva.'
        );

        return $user;
    }

    private function element(
        string $kind,
        int $id
    ): Documento|DocumentoCarpeta {
        abort_unless(
            in_array($kind, ['files', 'folders'], true),
            404
        );

        return $kind === 'files'
            ? Documento::query()->findOrFail($id)
            : DocumentoCarpeta::query()->findOrFail($id);
    }

    private function ownerId(
        string $kind,
        Documento|DocumentoCarpeta $item
    ): int {
        $field = $kind === 'files'
            ? 'subido_por'
            : 'creado_por';

        abort_unless(
            $item->getAttribute($field) !== null,
            409,
            "El elemento no tiene propietario ($field)."
        );

        return (int) $item->getAttribute($field);
    }

    private function assertOwner(
        string $kind,
        Documento|DocumentoCarpeta $item
    ): void {
        abort_unless(
            $this->ownerId($kind, $item)
                === (int) $this->actor()->id,
            403,
            'Solo el propietario puede compartir este elemento.'
        );
    }

    private function folderAncestors(
        string $kind,
        Documento|DocumentoCarpeta $item
    ): array {
        $id = $kind === 'files'
            ? $item->getAttribute('carpeta_id')
            : $item->getAttribute('carpeta_padre_id');

        $ids = [];
        $seen = [];

        for ($depth = 0; $id !== null && $depth < 100; $depth++) {
            $id = (int) $id;

            abort_if(
                isset($seen[$id]),
                409,
                'Estructura de carpetas circular.'
            );

            $seen[$id] = true;

            $folder = DocumentoCarpeta::query()->find($id);

            if (! $folder) {
                break;
            }

            $ids[] = $id;
            $id = $folder->carpeta_padre_id;
        }

        return $ids;
    }

    private function normalizarPermisos(array $permisos): array
    {
        $permitidos = array_intersect(
            self::PERMISOS_DISPONIBLES,
            $permisos
        );

        return array_values(array_unique($permitidos));
    }

    private function permisosPorRol(?string $role): array
    {
        return match ($role) {
            'edit' => self::PERMISOS_DISPONIBLES,
            'view' => ['ver', 'descargar'],
            default => [],
        };
    }

    private function permisosDeRegistro(object $registro): array
    {
        if ($registro->permisos !== null) {
            $permisos = json_decode(
                (string) $registro->permisos,
                true
            );

            if (is_array($permisos)) {
                return $this->normalizarPermisos($permisos);
            }
        }

        return $this->permisosPorRol(
            $registro->permiso ?? null
        );
    }

    private function obtenerRegistro(
        string $kind,
        int $id,
        ?int $userId
    ): ?object {
        $query = DB::table(self::TABLE)
            ->where('tipo', $kind)
            ->where('elemento_id', $id);

        if ($userId === null) {
            $query->whereNull('usuario_id');
        } else {
            $query->where('usuario_id', $userId);
        }

        return $query->first();
    }

    private function effectivePermissions(
        string $kind,
        int $id,
        Documento|DocumentoCarpeta $item,
        int $userId
    ): array {
        if ($this->ownerId($kind, $item) === $userId) {
            return self::PERMISOS_DISPONIBLES;
        }

        $registros = [
            $this->obtenerRegistro($kind, $id, $userId),
            $this->obtenerRegistro($kind, $id, null),
        ];

        foreach ($this->folderAncestors($kind, $item) as $folderId) {
            $registros[] = $this->obtenerRegistro(
                'folders',
                $folderId,
                $userId
            );

            $registros[] = $this->obtenerRegistro(
                'folders',
                $folderId,
                null
            );
        }

        $permisos = [];

        foreach ($registros as $registro) {
            if ($registro !== null) {
                $permisos = array_merge(
                    $permisos,
                    $this->permisosDeRegistro($registro)
                );
            }
        }

        return $this->normalizarPermisos($permisos);
    }

    private function ensureVisible(
        string $kind,
        int $id,
        Documento|DocumentoCarpeta $item
    ): void {
        $permisos = $this->effectivePermissions(
            $kind,
            $id,
            $item,
            (int) $this->actor()->id
        );

        abort_unless(
            in_array('ver', $permisos, true),
            403,
            'No tienes permiso para consultar este elemento.'
        );
    }

    private function person(User $user): array
    {
        $name = trim(
            ($user->nombre ?? $user->name ?? '') .
            ' ' .
            ($user->apellido ?? '')
        );

        return [
            'id' => $user->id,
            'name' => $name ?: $user->email,
            'email' => $user->email,
        ];
    }

    private function validarPermisos(Request $request): array
    {
        $data = $request->validate([
            'role' => [
                'nullable',
                Rule::in(['view', 'edit']),
            ],
            'permisos' => ['nullable', 'array'],
            'permisos.*' => [
                'string',
                Rule::in(self::PERMISOS_DISPONIBLES),
            ],
        ]);

        if (array_key_exists('permisos', $data)) {
            $permisos = $this->normalizarPermisos(
                $data['permisos'] ?? []
            );
        } elseif (! empty($data['role'])) {
            $permisos = $this->permisosPorRol(
                $data['role']
            );
        } else {
            throw ValidationException::withMessages([
                'permisos' => 'Selecciona al menos un permiso.',
            ]);
        }

        if (! in_array('ver', $permisos, true)) {
            throw ValidationException::withMessages([
                'permisos' => 'El permiso Ver es obligatorio.',
            ]);
        }

        return $permisos;
    }

    private function rolCompatible(array $permisos): string
    {
        $soloLectura = ['ver', 'descargar'];

        return count(array_diff($permisos, $soloLectura)) > 0
            ? 'edit'
            : 'view';
    }

    private function guardarPermiso(
        string $kind,
        int $id,
        ?int $userId,
        array $permisos
    ): void {
        $query = DB::table(self::TABLE)
            ->where('tipo', $kind)
            ->where('elemento_id', $id);

        if ($userId === null) {
            $query->whereNull('usuario_id');
        } else {
            $query->where('usuario_id', $userId);
        }

        $valores = [
            'permiso' => $this->rolCompatible($permisos),
            'permisos' => json_encode($permisos),
            'compartido_por' => $this->actor()->id,
            'updated_at' => now(),
        ];

        DB::transaction(function () use (
            $query,
            $kind,
            $id,
            $userId,
            $valores
        ) {
            $existentes = (clone $query)->lockForUpdate()->get();

            if ($existentes->isNotEmpty()) {
                (clone $query)->update($valores);
                return;
            }

            DB::table(self::TABLE)->insert(
                array_merge(
                    [
                        'tipo' => $kind,
                        'elemento_id' => $id,
                        'usuario_id' => $userId,
                        'created_at' => now(),
                    ],
                    $valores
                )
            );
        });
    }

    public function users(Request $request): JsonResponse
    {
        $this->actor();

        $query = User::query()
            ->where('id', '!=', auth()->id());

        if (Schema::hasColumn('users', 'activo')) {
            $query->where('activo', 1);
        }

        $term = trim((string) $request->query('q', ''));

        if ($term !== '') {
            $like = '%' . addcslashes(
                $term,
                '%_\\'
            ) . '%';

            $query->where(function ($q) use ($like) {
                $q->where('email', 'like', $like);

                foreach (['nombre', 'apellido', 'name'] as $field) {
                    if (Schema::hasColumn('users', $field)) {
                        $q->orWhere($field, 'like', $like);
                    }
                }
            });
        }

        return response()->json([
            'users' => $query
                ->orderBy('email')
                ->limit(100)
                ->get()
                ->map(fn ($u) => $this->person($u))
                ->values(),
        ]);
    }

    public function show(string $kind, int $id): JsonResponse
    {
        $item = $this->element($kind, $id);

        $this->ensureVisible($kind, $id, $item);

        $ownerId = $this->ownerId($kind, $item);
        $owner = User::find($ownerId);

        $people = DB::table(self::TABLE)
            ->where('tipo', $kind)
            ->where('elemento_id', $id)
            ->whereNotNull('usuario_id')
            ->get()
            ->map(function ($registro) {
                $user = User::find($registro->usuario_id);

                if (! $user) {
                    return null;
                }

                return array_merge(
                    $this->person($user),
                    [
                        'role' => $registro->permiso,
                        'permisos' => $this->permisosDeRegistro(
                            $registro
                        ),
                    ]
                );
            })
            ->filter()
            ->values();

        $inherited = [];

        foreach ($this->folderAncestors($kind, $item) as $folderId) {
            $entries = DB::table(self::TABLE)
                ->where('tipo', 'folders')
                ->where('elemento_id', $folderId)
                ->get();

            foreach ($entries as $entry) {
                $user = $entry->usuario_id
                    ? User::find($entry->usuario_id)
                    : null;

                $inherited[] = [
                    'name' => $user
                        ? $this->person($user)['name']
                        : 'Todos los usuarios',
                    'role' => $entry->permiso,
                    'permisos' => $this->permisosDeRegistro(
                        $entry
                    ),
                    'folder_id' => $folderId,
                ];
            }
        }

        $general = $this->obtenerRegistro(
            $kind,
            $id,
            null
        );

        return response()->json([
            'owner' => $owner
                ? $this->person($owner)
                : null,
            'can_share' => (int) auth()->id() === $ownerId,
            'people' => $people,
            'everyone' => [
                'enabled' => $general !== null,
                'role' => $general->permiso ?? 'view',
                'permisos' => $general
                    ? $this->permisosDeRegistro($general)
                    : ['ver', 'descargar'],
            ],
            'inherited' => $inherited,
            'effective_permissions' => $this->effectivePermissions(
                $kind,
                $id,
                $item,
                (int) auth()->id()
            ),
            'available_permissions' => self::PERMISOS_DISPONIBLES,
        ]);
    }

    public function store(
        Request $request,
        string $kind,
        int $id
    ): JsonResponse {
        $item = $this->element($kind, $id);

        $this->assertOwner($kind, $item);

        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $permisos = $this->validarPermisos($request);

        $recipient = User::query()
            ->where('email', $data['email'])
            ->first();

        abort_unless(
            $recipient &&
            (! Schema::hasColumn('users', 'activo') ||
                $recipient->activo),
            422,
            'Selecciona una cuenta activa de SAVIA PRZ.'
        );

        abort_if(
            (int) $recipient->id === $this->ownerId($kind, $item),
            422,
            'El propietario ya tiene acceso completo.'
        );

        $this->guardarPermiso(
            $kind,
            $id,
            (int) $recipient->id,
            $permisos
        );

        return response()->json([
            'ok' => true,
            'permisos' => $permisos,
        ]);
    }

    public function update(
        Request $request,
        string $kind,
        int $id,
        int $userId
    ): JsonResponse {
        $item = $this->element($kind, $id);

        $this->assertOwner($kind, $item);

        $permisos = $this->validarPermisos($request);

        $existing = $this->obtenerRegistro(
            $kind,
            $id,
            $userId
        );

        abort_unless(
            $existing !== null,
            404,
            'El usuario no tiene un permiso directo.'
        );

        $this->guardarPermiso(
            $kind,
            $id,
            $userId,
            $permisos
        );

        return response()->json([
            'ok' => true,
            'permisos' => $permisos,
        ]);
    }

    public function destroy(
        string $kind,
        int $id,
        int $userId
    ): JsonResponse {
        $item = $this->element($kind, $id);

        $this->assertOwner($kind, $item);

        DB::table(self::TABLE)
            ->where('tipo', $kind)
            ->where('elemento_id', $id)
            ->where('usuario_id', $userId)
            ->delete();

        return response()->json(['ok' => true]);
    }

    public function general(
        Request $request,
        string $kind,
        int $id
    ): JsonResponse {
        $item = $this->element($kind, $id);

        $this->assertOwner($kind, $item);

        $data = $request->validate([
            'enabled' => ['required', 'boolean'],
        ]);

        if (! $data['enabled']) {
            DB::table(self::TABLE)
                ->where('tipo', $kind)
                ->where('elemento_id', $id)
                ->whereNull('usuario_id')
                ->delete();

            return response()->json(['ok' => true]);
        }

        $permisos = $this->validarPermisos($request);

        $this->guardarPermiso(
            $kind,
            $id,
            null,
            $permisos
        );

        return response()->json([
            'ok' => true,
            'permisos' => $permisos,
        ]);
    }
}
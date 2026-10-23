<x-filament-panels::page>

    @php
        $elementos = $this->elementosExplorador();
    @endphp

    <div style="display: flex; flex-direction: column; gap: 20px;">

        {{-- NAVEGACIÓN DE CARPETAS --}}

        <nav
            style="
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            font-size: 14px;
        ">

            <a href="{{ $this->urlCarpeta() }}"
                style="
                    color: #176B45;
                    text-decoration: none;
                    font-weight: 600;
                ">
                Inicio
            </a>

            @foreach ($this->rutaCarpetas() as $carpeta)
                <span style="color: #9ca3af;">
                    /
                </span>

                <a href="{{ $this->urlCarpeta($carpeta->id) }}"
                    style="
                        color: #176B45;
                        text-decoration: none;
                        font-weight: 600;
                    ">
                    {{ $carpeta->nombre }}
                </a>
            @endforeach

        </nav>


        <div style="
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 12px;
">

            <a href="{{ \App\Filament\Resources\Documentos\DocumentoResource::getUrl('index') }}"
                class="prz-boton-documentos {{ $this->vista !== 'compartidos' ? 'activo' : 'inactivo' }}">
                Mis archivos
            </a>

            <a href="{{ \App\Filament\Resources\Documentos\DocumentoResource::getUrl('index', ['vista' => 'compartidos']) }}"
                class="prz-boton-documentos {{ $this->vista === 'compartidos' ? 'activo' : 'inactivo' }}">
                Compartidos conmigo
            </a>

        </div>

        {{-- TABLA UNIFICADA TIPO ONEDRIVE --}}

        <div
            style="
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
        ">

            {{-- BUSCADOR --}}

            <div
                style="
                padding: 16px;
                display: flex;
                justify-content: flex-end;
                border-bottom: 1px solid #e5e7eb;
            ">

                <form method="GET" action="{{ $this->urlCarpeta($this->carpetaId) }}"
                    style="
                        display: flex;
                        gap: 8px;
                        width: 100%;
                        max-width: 380px;
                    ">

                    <input type="text" name="buscar" value="{{ $this->buscar }}"
                        placeholder="Buscar archivos o carpetas..." aria-label="Buscar archivos o carpetas"
                        style="
                            flex: 1;
                            min-width: 0;
                            border: 1px solid #d1d5db;
                            border-radius: 8px;
                            padding: 10px 12px;
                            color: #374151;
                            background: white;
                        ">

                    <button type="submit" class="prz-boton-documentos activo">
                        Buscar
                    </button>

                </form>

            </div>


            {{-- LISTADO --}}

            <div style="overflow-x: auto;">

                <table
                    style="
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 14px;
                    text-align: left;
                ">

                    <thead
                        style="
                        background: #f9fafb;
                        color: #374151;
                    ">

                        <tr>

                            <th style="padding: 17px; min-width: 250px;">
                                Nombre
                            </th>

                            <th style="padding: 17px; min-width: 160px;">
                                Subido por
                            </th>

                            <th style="padding: 17px; min-width: 95px;">
                                Tipo
                            </th>

                            <th style="padding: 17px; min-width: 160px;">
                                Acciones
                            </th>

                            <th style="padding: 17px; min-width: 115px;">
                                Opciones
                            </th>

                            <th style="padding: 17px; min-width: 145px;">
                                Fecha de subida
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($elementos as $elemento)
                            <tr wire:key="elemento-{{ $elemento['tipo'] }}-{{ $elemento['id'] }}"
                                style="
                                    border-top: 1px solid #e5e7eb;
                                ">


                                {{-- NOMBRE --}}

                                <td style="padding: 17px;">

                                    @if ($elemento['tipo'] === 'carpeta')
                                        {{-- CARPETA --}}
                                        <a href="{{ $this->urlCarpeta($elemento['id']) }}"
                                            style="
               display: flex;
               align-items: center;
               gap: 12px;
               color: #111827;
               text-decoration: none;
               font-weight: 600;
           ">

                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="#D99A16" stroke-width="1.8" stroke-linecap="round"
                                                stroke-linejoin="round" style="flex-shrink: 0;">
                                                <path
                                                    d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                            </svg>

                                            <span style="overflow-wrap: anywhere;">
                                                {{ $elemento['nombre'] }}
                                            </span>

                                        </a>
                                    @else
                                        {{-- ARCHIVO --}}
                                        @php
                                            $extension = strtolower($elemento['extension'] ?? '');

                                            $tieneVistaPrevia = in_array(
                                                $extension,
                                                ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx'],
                                                true,
                                            );

                                            $colorIcono = match ($extension) {
                                                'pdf' => '#DC2626',
                                                'doc', 'docx' => '#2563EB',
                                                'xls', 'xlsx' => '#16A34A',
                                                'jpg', 'jpeg', 'png' => '#D97706',
                                                default => '#9CA3AF',
                                            };
                                        @endphp

                                        <div
                                            style="
            display: flex;
            align-items: center;
            gap: 12px;
            color: #111827;
        ">


                                            @if (in_array($extension, ['xls', 'xlsx']))
                                                {{-- EXCEL --}}
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="#16A34A" stroke-width="1.8" stroke-linecap="round"
                                                    stroke-linejoin="round" style="flex-shrink: 0;">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                                    <path d="M3 9h18M3 15h18M9 3v18M15 3v18" />
                                                </svg>
                                            @elseif (in_array($extension, ['doc', 'docx']))
                                                {{-- WORD --}}
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="#2563EB" stroke-width="1.8" stroke-linecap="round"
                                                    stroke-linejoin="round" style="flex-shrink: 0;">
                                                    <path
                                                        d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                                    <path d="M14 2v6h6" />
                                                    <path d="M7 13l2 6 3-6 3 6 2-6" />
                                                </svg>
                                            @elseif ($extension === 'pdf')
                                                {{-- PDF --}}
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="#DC2626" stroke-width="1.8" stroke-linecap="round"
                                                    stroke-linejoin="round" style="flex-shrink: 0;">
                                                    <path
                                                        d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                                    <path d="M14 2v6h6" />
                                                    <path d="M7 17h10" />
                                                    <path d="M7 13h10" />
                                                </svg>
                                            @elseif (in_array($extension, ['jpg', 'jpeg', 'png']))
                                                {{-- IMAGEN --}}
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="#D97706" stroke-width="1.8" stroke-linecap="round"
                                                    stroke-linejoin="round" style="flex-shrink: 0;">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                                    <path d="m21 15-5-5L5 21" />
                                                </svg>
                                            @else
                                                {{-- OTROS ARCHIVOS --}}
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="#9CA3AF" stroke-width="1.8" stroke-linecap="round"
                                                    stroke-linejoin="round" style="flex-shrink: 0;">
                                                    <path
                                                        d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                                    <path d="M14 2v6h6" />
                                                </svg>
                                            @endif

                                            <span
                                                style="
    color: #111827;
    font-weight: 500;
    overflow-wrap: anywhere;
">
                                                {{ $elemento['nombre'] }}
                                            </span>

                                        </div>
                                    @endif

                                </td>


                                {{-- SUBIDO POR --}}

                                <td
                                    style="
                                    padding: 17px;
                                    color: #4b5563;
                                ">

                                    {{ $elemento['tipo'] === 'carpeta' ? '—' : $elemento['usuario'] }}

                                </td>


                                {{-- TIPO --}}

                                <td style="padding: 17px;">

                                    @if ($elemento['tipo'] === 'carpeta')
                                        <span style="color: #6b7280;">
                                            Carpeta
                                        </span>
                                    @else
                                        <span
                                            style="
                                            display: inline-block;
                                            padding: 3px 8px;
                                            border-radius: 6px;
                                            background: #ecfdf5;
                                            color: #047857;
                                            font-size: 12px;
                                            font-weight: 600;
                                        ">

                                            {{ $elemento['extension'] ?: 'Archivo' }}

                                        </span>
                                    @endif

                                </td>


                                {{-- ACCIONES DEL ARCHIVO --}}

                                <td style="padding: 17px;">

                                    @if ($elemento['tipo'] === 'archivo')
                                        @php
                                            $extensionArchivo = strtolower($elemento['extension'] ?? '');

                                            $puedeVer = in_array(
                                                $extensionArchivo,
                                                ['pdf', 'jpg', 'jpeg', 'png'],
                                                true,
                                            );
                                        @endphp

                                        <div
                                            style="
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        ">

                                            {{-- BOTÓN VER: SOLO PDF E IMÁGENES --}}
                                            @if ($puedeVer)
                                                <a href="{{ route('documentos.ver', ['documento' => $elemento['id']]) }}"
                                                    target="_blank" rel="noopener noreferrer"
                                                    style="
                        display: inline-flex;
                        align-items: center;
                        gap: 5px;
                        color: #176B45;
                        font-weight: 600;
                        text-decoration: none;
                    ">

                                                    <svg width="17" height="17" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="1.8"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z" />
                                                        <circle cx="12" cy="12" r="3" />
                                                    </svg>

                                                    Ver
                                                </a>
                                            @endif

                                            {{-- BOTÓN DESCARGAR: TODOS LOS ARCHIVOS --}}
                                            <a href="{{ route('documentos.descargar', ['documento' => $elemento['id']]) }}"
                                                style="
                    display: inline-flex;
                    align-items: center;
                    gap: 5px;
                    color: #176B45;
                    font-weight: 600;
                    text-decoration: none;
                ">

                                                <svg width="17" height="17" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="1.8"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 3v12" />
                                                    <path d="m7 10 5 5 5-5" />
                                                    <path d="M5 17v4h14v-4" />
                                                </svg>

                                                Descargar
                                            </a>

                                        </div>
                                    @else
                                        <span style="color: #9ca3af;">—</span>
                                    @endif

                                </td>





                                {{-- OPCIONES DEL DOCUMENTO --}}

                                <td style="padding: 17px;">

                                    @if ($elemento['tipo'] === 'archivo' && $elemento['es_propietario'])
                                        <button type="button"
                                            wire:click="mountAction('cambiarVisibilidad', { documento: {{ $elemento['id'] }} })"
                                            style="
                display: inline-flex;
                align-items: center;
                gap: 7px;
                padding: 7px 10px;
                background: #f3f4f6;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                color: #176B45;
                cursor: pointer;
                font-size: 13px;
                font-weight: 600;
            ">
                                            {{ $elemento['es_publico'] ? 'Volver a privado' : 'Compartir Todos' }}
                                        </button>
                                    @elseif ($elemento['tipo'] === 'archivo' && $elemento['es_publico'])
                                        <span
                                            style="
            color: #176B45;
            font-size: 12px;
        ">
                                            Compartido
                                        </span>
                                    @else
                                        <span style="color: #9ca3af;">
                                            —
                                        </span>
                                    @endif

                                </td>

                                {{-- FECHA --}}

                                <td
                                    style="
                                    padding: 17px;
                                    color: #6b7280;
                                    white-space: nowrap;
                                ">

                                    {{ $elemento['fecha'] ? $elemento['fecha']->format('d/m/Y H:i') : '—' }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6"
                                    style="
                                        padding: 50px;
                                        text-align: center;
                                        color: #6b7280;
                                    ">

                                    No hay carpetas ni archivos
                                    en esta ubicación.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINACIÓN --}}

            @if ($elementos->hasPages())
                <div
                    style="
                    padding: 18px;
                    border-top: 1px solid #e5e7eb;
                ">

                    {{ $elementos->links() }}

                </div>
            @endif

            <div
                style="
                padding: 14px 18px;
                border-top: 1px solid #e5e7eb;
                color: #6b7280;
                font-size: 13px;
            ">

                Mostrando {{ $elementos->firstItem() ?? 0 }}
                a {{ $elementos->lastItem() ?? 0 }}
                de {{ $elementos->total() }} elementos

            </div>

        </div>

    </div>

    <x-filament-actions::modals />

</x-filament-panels::page>

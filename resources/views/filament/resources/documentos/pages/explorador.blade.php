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

                            <th style="padding: 17px; min-width: 90px;">
                                Ver
                            </th>

                            <th style="padding: 17px; min-width: 120px;">
                                Descargar
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
                                        <div
                                            style="
                                            display: flex;
                                            align-items: center;
                                            gap: 12px;
                                            color: #111827;
                                        ">

                                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                                stroke="#9ca3af" stroke-width="1.8" stroke-linecap="round"
                                                stroke-linejoin="round" style="flex-shrink: 0;">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                                <path d="M14 2v6h6" />
                                                <path d="M8 13h8M8 17h8" />
                                            </svg>

                                            <span style="overflow-wrap: anywhere;">
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


                                {{-- VER --}}

                                <td style="padding: 17px;">

                                    @if ($elemento['tipo'] === 'archivo' && in_array($elemento['extension'], ['pdf', 'png', 'jpg', 'jpeg']))
                                        <a href="{{ route('documentos.ver', ['documento' => $elemento['id']]) }}"
                                            target="_blank" rel="noopener noreferrer"
                                            style="
                                                color: #176B45;
                                                text-decoration: underline;
                                            ">
                                            Ver
                                        </a>
                                    @else
                                        <span style="color: #9ca3af;">
                                            —
                                        </span>
                                    @endif

                                </td>


                                {{-- DESCARGAR --}}

                                <td style="padding: 17px;">

                                    @if ($elemento['tipo'] === 'archivo')
                                        <a href="{{ route('documentos.descargar', ['documento' => $elemento['id']]) }}"
                                            style="
                                                color: #176B45;
                                                text-decoration: underline;
                                            ">
                                            Descargar
                                        </a>
                                    @else
                                        <span style="color: #9ca3af;">
                                            —
                                        </span>
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
                                            ⋯

                                            {{ $elemento['es_publico'] ? 'Volver a privado' : 'Compartir con todos' }}
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

                                <td colspan="7"
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

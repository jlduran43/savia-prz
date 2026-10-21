
<x-filament-panels::page>

    {{-- Navegación entre carpetas --}}
    <div style="
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        font-size: 14px;
    ">

        <a
            href="{{ $this->urlCarpeta() }}"
            style="color: #176B45; font-weight: 600;"
        >
            Inicio
        </a>

        @foreach ($this->rutaCarpetas() as $carpeta)

            <span style="color: #9ca3af;">/</span>

            <a
                href="{{ $this->urlCarpeta($carpeta->id) }}"
                style="color: #176B45;"
            >
                {{ $carpeta->nombre }}
            </a>

        @endforeach

    </div>

    {{-- Carpetas de la ubicación actual --}}
    @php
        $carpetas = $this->carpetasVisibles();
    @endphp

    @if ($carpetas->isNotEmpty())

        <div style="margin-bottom: 16px;">

            <h3 style="
                font-size: 16px;
                font-weight: 600;
                margin-bottom: 15px;
            ">
                Carpetas
            </h3>

            <div style="
                display: grid;
                grid-template-columns:
                    repeat(auto-fill, minmax(220px, 1fr));
                gap: 15px;
            ">

                @foreach ($carpetas as $carpeta)

                    <a
                        href="{{ $this->urlCarpeta($carpeta->id) }}"
                        wire:key="carpeta-{{ $carpeta->id }}"
                        style="
                            display: flex;
                            align-items: center;
                            gap: 12px;
                            padding: 17px;
                            border: 1px solid #e5e7eb;
                            border-radius: 12px;
                            text-decoration: none;
                            background: #ffffff;
                            color: #1f2937;
                            box-shadow:
                                0 1px 3px rgba(0,0,0,0.05);
                        "
                    >

                        <svg
                            width="27"
                            height="27"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="#D99A16"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            style="flex-shrink: 0;"
                        >
                            <path
                                d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"
                            />
                        </svg>

                        <span style="
                            font-size: 14px;
                            font-weight: 600;
                            overflow-wrap: anywhere;
                        ">
                            {{ $carpeta->nombre }}
                        </span>

                    </a>

                @endforeach

            </div>

        </div>

    @endif

    {{-- Documentos de la carpeta seleccionada --}}
    <div>
        <h3 style="
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
        ">
            Archivos
        </h3>

        {{ $this->table }}
    </div>

</x-filament-panels::page>

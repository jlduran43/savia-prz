<x-filament-panels::page>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .drive-prz {
            position: relative;
            min-height: 520px;
            padding: 25px;
            background: #ffffff;
            color: #1f2937;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            font-family: inherit;
        }

        .drive-prz-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .drive-prz-heading {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .drive-prz-title {
            margin: 0;
            color: #1f2937;
            font-size: 21px;
            font-weight: 700;
        }

        .drive-prz-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .drive-prz-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 17px;
            border: 1px solid transparent;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 600;
            line-height: 20px;
            cursor: pointer;
            transition: background .2s, border-color .2s;
            text-decoration: none;
        }

        .drive-prz-btn-primary {
            background: #176B45 !important;
            color: #ffffff !important;
        }

        .drive-prz-btn-primary:hover {
            background: #105235 !important;
        }

        .drive-prz-btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border-color: #e5e7eb;
        }

        .drive-prz-btn-secondary:hover {
            background: #e5e7eb;
        }

        .drive-prz-section-title {
            margin: 0 0 15px;
            font-size: 15px;
            font-weight: 700;
            color: #374151;
        }

        .drive-prz-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(175px, 1fr));
            gap: 16px;
            margin-bottom: 30px;
        }

        .drive-prz-folder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            min-height: 145px;
            padding: 20px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #ffffff;
            color: #374151;
            text-align: center;
            cursor: pointer;
            transition: background .2s, border-color .2s, box-shadow .2s;
        }

        .drive-prz-folder:hover {
            background: #f0fdf4;
            border-color: #176B45;
            box-shadow: 0 4px 12px rgba(23, 107, 69, .08);
        }

        .drive-prz-folder-icon {
            font-size: 46px;
            line-height: 1.2;
        }

        .drive-prz-folder-name {
            max-width: 100%;
            font-size: 14px;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .drive-prz-documents {
            display: flex;
            flex-direction: column;
            gap: 9px;
        }

        .drive-prz-document {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 17px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            transition: background .2s;
        }

        .drive-prz-document:hover {
            background: #f9fafb;
        }

        .drive-prz-document-icon {
            font-size: 27px;
            flex-shrink: 0;
        }

        .drive-prz-document-name {
            min-width: 0;
            font-size: 14px;
            font-weight: 500;
            overflow-wrap: anywhere;
        }

        .drive-prz-empty {
            padding: 65px 20px;
            color: #6b7280;
            text-align: center;
        }

        .drive-prz-empty-icon {
            margin-bottom: 16px;
            font-size: 56px;
        }

        .drive-prz-empty-title {
            margin: 0 0 8px;
            font-size: 16px;
            font-weight: 600;
            color: #374151;
        }

        .drive-prz-empty-description {
            margin: 0;
            font-size: 14px;
            color: #6b7280;
        }

        .drive-prz-menu {
            position: fixed;
            z-index: 9999;
            width: 225px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .15);
            overflow: hidden;
        }

        .drive-prz-menu-button {
            display: block;
            width: 100%;
            padding: 12px 16px;
            background: transparent;
            border: none;
            color: #374151;
            font-size: 14px;
            text-align: left;
            cursor: pointer;
        }

        .drive-prz-menu-button:hover {
            background: #f3f4f6;
        }

        .drive-prz-menu-divider {
            margin: 0;
            border: none;
            border-top: 1px solid #e5e7eb;
        }

        .dark .drive-prz,
        .dark .drive-prz-folder,
        .dark .drive-prz-document,
        .dark .drive-prz-menu {
            background: #111827;
            color: #f9fafb;
            border-color: #374151;
        }

        .dark .drive-prz-title,
        .dark .drive-prz-section-title,
        .dark .drive-prz-empty-title {
            color: #f9fafb;
        }

        .dark .drive-prz-folder:hover {
            background: #064e3b;
        }

        .dark .drive-prz-document:hover {
            background: #1f2937;
        }

        .dark .drive-prz-menu-button {
            color: #f9fafb;
        }

        .dark .drive-prz-menu-button:hover {
            background: #374151;
        }

        @media (max-width: 640px) {
            .drive-prz {
                padding: 15px;
            }

            .drive-prz-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            .drive-prz-folder {
                min-height: 125px;
                padding: 15px 10px;
            }

            .drive-prz-header {
                align-items: flex-start;
            }
        }
    </style>

    <div x-data="{
        menu: false,
        x: 0,
        y: 0,
    
        abrirMenu(event) {
            event.preventDefault();
    
            const ancho = 225;
            const alto = 160;
    
            this.x = Math.max(
                8,
                Math.min(event.clientX, window.innerWidth - ancho - 8)
            );
    
            this.y = Math.max(
                8,
                Math.min(event.clientY, window.innerHeight - alto - 8)
            );
    
            this.menu = true;
        }
    }" x-on:contextmenu.prevent="abrirMenu($event)" x-on:click="menu = false"
        x-on:keydown.escape.window="menu = false" class="drive-prz">

        {{-- ENCABEZADO --}}
        <div class="drive-prz-header">

            <div class="drive-prz-heading">

                @if ($carpeta)
                    <button type="button" wire:click="volver" class="drive-prz-btn drive-prz-btn-secondary">
                        ← Volver
                    </button>
                @endif

                <h2 class="drive-prz-title">
                    📁 {{ $carpeta?->nombre ?? 'Mis carpetas' }}
                </h2>

            </div>

            <div class="drive-prz-actions">

                @if ($carpeta)
                    <button type="button" wire:click="mountAction('subirDocumento')"
                        class="drive-prz-btn drive-prz-btn-primary">
                        📤 Subir documento
                    </button>
                @endif

                <button type="button" wire:click="mountAction('nuevaCarpeta')"
                    class="drive-prz-btn drive-prz-btn-primary">
                    + Nueva carpeta
                </button>

            </div>

        </div>


        {{-- CARPETAS --}}
        @if ($carpetas->count())

            <h3 class="drive-prz-section-title">
                Carpetas
            </h3>

            <div class="drive-prz-grid">

                @foreach ($carpetas as $item)
                    <button type="button" wire:key="carpeta-{{ $item->id }}"
                        wire:click="abrirCarpeta({{ $item->id }})" class="drive-prz-folder"
                        title="Abrir {{ $item->nombre }}">

                        <span class="drive-prz-folder-icon">
                            📁
                        </span>

                        <span class="drive-prz-folder-name">
                            {{ $item->nombre }}
                        </span>

                    </button>
                @endforeach

            </div>

        @endif


        {{-- DOCUMENTOS --}}
        @if ($documentos->count())

            <h3 class="drive-prz-section-title">
                Documentos
            </h3>

            <div class="drive-prz-documents">

                @foreach ($documentos as $documento)
                    <div wire:key="documento-{{ $documento->id }}" class="drive-prz-document">

                        <span class="drive-prz-document-icon">
                            📄
                        </span>

                        <span class="drive-prz-document-name">
                            {{ $documento->nombre_original }}
                        </span>

                    </div>
                @endforeach

            </div>

        @endif


        {{-- CARPETA VACÍA --}}
        @if ($carpetas->isEmpty() && $documentos->isEmpty())
            <div class="drive-prz-empty">

                <div class="drive-prz-empty-icon">
                    📂
                </div>

                <p class="drive-prz-empty-title">
                    Esta carpeta está vacía
                </p>

                <p class="drive-prz-empty-description">
                    Haz clic derecho para crear una carpeta
                    o subir documentos.
                </p>

            </div>
        @endif


        {{-- MENÚ CONTEXTUAL --}}
        <div x-cloak x-show="menu" x-on:click.stop x-transition.opacity
            :style="{
                left: x + 'px',
                top: y + 'px'
            }"
            class="drive-prz-menu">

            <button type="button" x-on:click="menu = false; $wire.mountAction('nuevaCarpeta')"
                class="drive-prz-menu-button">
                📁 Nueva carpeta
            </button>

            @if ($carpeta)
                <button type="button" x-on:click="menu = false; $wire.mountAction('subirDocumento')"
                    class="drive-prz-menu-button">
                    📤 Subir documento
                </button>
            @endif

            <hr class="drive-prz-menu-divider">

            <button type="button" x-on:click="menu = false; $wire.$refresh()" class="drive-prz-menu-button">
                🔄 Actualizar
            </button>

        </div>

    </div>

    <x-filament-actions::modals />

</x-filament-panels::page>

<x-filament-panels::page>

    <div x-data="{
        menu: false,
        x: 0,
        y: 0,
        abrirMenu(event) {
            event.preventDefault();
            this.x = Math.min(
                event.clientX,
                window.innerWidth - 230
            );
            this.y = Math.min(
                event.clientY,
                window.innerHeight - 160
            );
            this.menu = true;
        }
    }" x-on:contextmenu.prevent="abrirMenu($event)" x-on:click="menu = false"
        x-on:keydown.escape.window="menu = false"
        class="relative min-h-[500px] bg-white
               dark:bg-gray-900 rounded-xl p-5">

        <!-- Encabezado -->
        <div class="flex items-center justify-between mb-6">

            <div class="flex items-center gap-3">

                @if ($carpeta)
                    <button type="button" wire:click="volver"
                        class="px-3 py-2 rounded-lg bg-gray-100
                               text-gray-700 dark:bg-gray-800
                               dark:text-white">
                        ← Volver
                    </button>
                @endif

                <h2 class="text-xl font-bold">
                    📁 {{ $carpeta?->nombre ?? 'Mis carpetas' }}
                </h2>

            </div>

            <button type="button" wire:click="mountAction('nuevaCarpeta')"
                class="px-4 py-2 rounded-lg text-white font-semibold" style="background-color: #176B45">
                + Nueva carpeta
            </button>

        </div>

        <!-- Carpetas -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

            @foreach ($carpetas as $item)
                <button type="button" wire:key="carpeta-{{ $item->id }}"
                    wire:click="abrirCarpeta({{ $item->id }})"
                    class="flex flex-col items-center gap-3
                           rounded-xl border p-5
                           hover:bg-gray-50
                           dark:hover:bg-gray-800
                           text-center transition">

                    <span class="text-5xl">📁</span>

                    <span class="font-medium break-all">
                        {{ $item->nombre }}
                    </span>

                </button>
            @endforeach

        </div>

        <!-- Documentos -->
        @if ($documentos->count())

            <h3 class="font-semibold mb-3">
                Documentos
            </h3>

            <div class="space-y-2">

                @foreach ($documentos as $documento)
                    <div wire:key="documento-{{ $documento->id }}"
                        class="flex items-center gap-3
                               border rounded-lg p-3">

                        <span class="text-2xl">📄</span>

                        <span class="break-all">
                            {{ $documento->nombre_original }}
                        </span>

                    </div>
                @endforeach

            </div>

        @endif

        <!-- Mensaje carpeta vacía -->
        @if ($carpetas->isEmpty() && $documentos->isEmpty())
            <div class="text-center text-gray-500 py-16">

                <div class="text-5xl mb-4">📂</div>

                <p>Esta carpeta está vacía.</p>

                <p class="text-sm mt-2">
                    Haz clic derecho para crear
                    una carpeta o subir documentos.
                </p>

            </div>
        @endif

        <!-- Menú contextual -->
        <div x-cloak x-show="menu" x-on:click.stop x-transition.opacity
            :style="`
                            position: fixed;
                            left: ${x}px;
                            top: ${y}px;
                            z-index: 9999;
                        `"
            class="w-56 bg-white dark:bg-gray-800
                   rounded-xl shadow-xl border
                   border-gray-200 dark:border-gray-700
                   overflow-hidden">

            <button type="button" x-on:click="menu = false; $wire.mountAction('nuevaCarpeta')"
                class="w-full text-left px-4 py-3
                       hover:bg-gray-100
                       dark:hover:bg-gray-700">
                📁 Nueva carpeta
            </button>

            @if ($carpeta)
                <button type="button" x-on:click="menu = false; $wire.mountAction('subirDocumento')"
                    class="w-full text-left px-4 py-3
                           hover:bg-gray-100
                           dark:hover:bg-gray-700">
                    📤 Subir documento
                </button>
            @endif

            <hr class="border-gray-200 dark:border-gray-700">

            <button type="button" x-on:click="menu = false; $wire.$refresh()"
                class="w-full text-left px-4 py-3
                       hover:bg-gray-100
                       dark:hover:bg-gray-700">
                🔄 Actualizar
            </button>

        </div>

    </div>

    <x-filament-actions::modals />

</x-filament-panels::page>
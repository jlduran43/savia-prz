<x-filament-panels::page>

    <div class="space-y-6">

        <div class="flex items-center gap-3">

            @if ($carpetaActualId !== null)
                <button
                    type="button"
                    wire:click="volver"
                    class="rounded-lg border px-4 py-2"
                >
                    ← Volver
                </button>
            @endif

            <div class="text-sm text-gray-500 dark:text-gray-400">
                Inicio

                @if ($this->carpetaActual)
                    / {{ $this->carpetaActual->nombre }}
                @endif
            </div>

        </div>

        <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-4">

            @forelse ($this->carpetas as $carpeta)

                <button
                    type="button"
                    wire:key="carpeta-{{ $carpeta->id }}"
                    wire:click="abrirCarpeta({{ $carpeta->id }})"
                    class="flex flex-col items-start gap-3 rounded-xl border
                           border-gray-200 bg-white p-5 text-left
                           shadow-sm transition hover:border-green-600
                           hover:shadow-md
                           dark:border-gray-700 dark:bg-gray-900"
                >

                    <x-heroicon-o-folder
                        class="h-12 w-12 text-amber-500"
                    />

                    <span class="font-semibold text-gray-800 dark:text-white">
                        {{ $carpeta->nombre }}
                    </span>

                    <span class="text-xs text-gray-500">
                        Abrir carpeta →
                    </span>

                </button>

            @empty

                <div class="col-span-full rounded-xl border
                            border-dashed border-gray-300 p-10
                            text-center text-gray-500">

                    No hay carpetas en esta ubicación.

                </div>

            @endforelse

        </div>

    </div>

</x-filament-panels::page>
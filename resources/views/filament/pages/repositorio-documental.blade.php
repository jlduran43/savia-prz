
<x-filament-panels::page>

    <div
        x-data="{ mostrarAyuda: false }"
        x-on:keydown.escape.window="mostrarAyuda = false"
        class="space-y-6"
    >

        {{-- NAVEGACIÓN ACTUAL --}}

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


        {{-- BOTÓN DE AYUDA --}}

        <div class="flex flex-wrap items-center gap-3">

            <button
                type="button"
                x-on:click="mostrarAyuda = true"
                class="inline-flex items-center gap-2 rounded-lg
                       border border-blue-200 bg-blue-50
                       px-4 py-2 text-sm font-semibold
                       text-blue-800 transition
                       hover:bg-blue-100
                       dark:border-gray-600
                       dark:bg-gray-800 dark:text-blue-300"
            >
                <x-heroicon-o-question-mark-circle class="h-5 w-5" />

                ¿Cómo se usa?
            </button>

            <span class="text-sm text-gray-500 dark:text-gray-400">
                Abre carpetas, crea una estructura completa
                o importa carpetas desde tu equipo.
            </span>

        </div>


        {{-- CARPETAS EXISTENTES --}}

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


        {{-- VENTANA MODAL DE AYUDA --}}

        <template x-teleport="body">

            <div
                x-show="mostrarAyuda"
                x-cloak
                x-on:keydown.escape.window="mostrarAyuda = false"
                class="fixed inset-0 z-[9999] flex items-center
                       justify-center p-4"
                role="dialog"
                aria-modal="true"
                aria-labelledby="prz-titulo-ayuda"
            >

                {{-- FONDO OSCURO --}}

                <div
                    class="absolute inset-0 bg-slate-900/60"
                    x-on:click="mostrarAyuda = false"
                ></div>


                {{-- CONTENEDOR MODAL --}}

                <div
                    class="relative z-10 flex w-full max-w-3xl
                           max-h-[90vh] flex-col overflow-hidden
                           rounded-2xl bg-white shadow-2xl
                           dark:bg-gray-900"
                >

                    {{-- ENCABEZADO --}}

                    <div class="flex shrink-0 items-center
                                justify-between border-b
                                border-gray-200 px-6 py-5
                                dark:border-gray-700">

                        <h2
                            id="prz-titulo-ayuda"
                            class="text-xl font-bold
                                   text-slate-900 dark:text-white"
                        >
                            Cómo organizar tus documentos
                        </h2>

                        <button
                            type="button"
                            x-on:click="mostrarAyuda = false"
                            aria-label="Cerrar ayuda"
                            class="rounded-lg bg-gray-100 p-2
                                   text-gray-500 hover:bg-gray-200
                                   dark:bg-gray-800"
                        >
                            <x-heroicon-o-x-mark class="h-5 w-5" />
                        </button>

                    </div>


                    {{-- CONTENIDO CON DESPLAZAMIENTO --}}

                    <div class="flex-1 space-y-4 overflow-y-auto
                                px-6 py-6">

                        {{-- PASO 1 --}}

                        <div class="rounded-xl border border-blue-100
                                    bg-slate-50 p-5 dark:border-gray-700
                                    dark:bg-gray-800">

                            <h3 class="mb-2 font-bold text-blue-900
                                       dark:text-blue-300">
                                1. Entra en una carpeta
                            </h3>

                            <p class="text-sm leading-6 text-slate-600
                                      dark:text-gray-300">
                                Haz clic en “Abrir” o sobre la carpeta.
                                Puedes crear nuevas carpetas dentro de ella.
                            </p>

                        </div>


                        {{-- PASO 2 --}}

                        <div class="rounded-xl border border-blue-100
                                    bg-slate-50 p-5 dark:border-gray-700
                                    dark:bg-gray-800">

                            <h3 class="mb-2 font-bold text-blue-900
                                       dark:text-blue-300">
                                2. Sube un archivo
                            </h3>

                            <p class="text-sm leading-6 text-slate-600
                                      dark:text-gray-300">
                                Usa “Subir archivos aquí” o selecciona
                                documentos desde tu computador.

                                Puedes subir archivos PDF, Word, Excel,
                                imágenes y otros formatos compatibles.

                                Revisa los archivos seleccionados antes
                                de confirmar la subida.
                            </p>

                        </div>


                        {{-- PASO 3 --}}

                        <div class="rounded-xl border border-blue-100
                                    bg-slate-50 p-5 dark:border-gray-700
                                    dark:bg-gray-800">

                            <h3 class="mb-2 font-bold text-blue-900
                                       dark:text-blue-300">
                                3. Cambia un archivo de lugar
                            </h3>

                            <p class="text-sm leading-6 text-slate-600
                                      dark:text-gray-300">
                                Pulsa “Mover”, selecciona la carpeta
                                de destino y confirma.

                                Los archivos se organizarán dentro
                                de la carpeta seleccionada.
                            </p>

                        </div>


                        {{-- PASO 4 --}}

                        <div class="rounded-xl border border-blue-100
                                    bg-slate-50 p-5 dark:border-gray-700
                                    dark:bg-gray-800">

                            <h3 class="mb-2 font-bold text-blue-900
                                       dark:text-blue-300">
                                4. Crea muchas carpetas de una vez
                            </h3>

                            <p class="text-sm leading-6 text-slate-600
                                      dark:text-gray-300">
                                Pulsa “Crear varias carpetas”.

                                Escribe los nombres en líneas separadas
                                y utiliza dos espacios para indicar
                                subcarpetas.

                                Revisa la estructura antes de confirmar.
                            </p>

                        </div>


                        {{-- PASO 5 --}}

                        <div class="rounded-xl border border-blue-100
                                    bg-slate-50 p-5 dark:border-gray-700
                                    dark:bg-gray-800">

                            <h3 class="mb-2 font-bold text-blue-900
                                       dark:text-blue-300">
                                5. Crea los meses automáticamente
                            </h3>

                            <p class="text-sm leading-6 text-slate-600
                                      dark:text-gray-300">
                                Entra en la carpeta de destino y pulsa
                                “Crear meses”.

                                Puedes seleccionar enero a diciembre
                                o solamente algunos meses.

                                Los meses existentes no se duplicarán.
                            </p>

                        </div>


                        {{-- PASO 6 --}}

                        <div class="rounded-xl border border-blue-100
                                    bg-slate-50 p-5 dark:border-gray-700
                                    dark:bg-gray-800">

                            <h3 class="mb-2 font-bold text-blue-900
                                       dark:text-blue-300">
                                6. Importa carpetas desde tu equipo
                            </h3>

                            <p class="text-sm leading-6 text-slate-600
                                      dark:text-gray-300">
                                Utiliza “Subir carpeta completa”
                                para incorporar una estructura
                                de carpetas desde tu computador,
                                conservando su organización.
                            </p>

                        </div>


                        {{-- PASO 7 --}}

                        <div class="rounded-xl border border-blue-100
                                    bg-slate-50 p-5 dark:border-gray-700
                                    dark:bg-gray-800">

                            <h3 class="mb-2 font-bold text-blue-900
                                       dark:text-blue-300">
                                7. Comparte tus documentos
                            </h3>

                            <p class="text-sm leading-6 text-slate-600
                                      dark:text-gray-300">
                                Utiliza la opción “Compartir”
                                para permitir el acceso a otros usuarios
                                autorizados del sistema.

                                Puedes definir los permisos disponibles
                                para cada documento.
                            </p>

                        </div>

                    </div>


                    {{-- PIE DEL MODAL --}}

                    <div class="flex shrink-0 justify-end
                                border-t border-gray-200
                                bg-white px-6 py-4
                                dark:border-gray-700
                                dark:bg-gray-900">

                        <button
                            type="button"
                            x-on:click="mostrarAyuda = false"
                            class="rounded-lg px-5 py-2.5
                                   text-sm font-semibold text-white
                                   transition hover:opacity-90"
                            style="background-color: #176B45;"
                        >
                            Entendido
                        </button>

                    </div>

                </div>

            </div>

        </template>

    </div>

</x-filament-panels::page>
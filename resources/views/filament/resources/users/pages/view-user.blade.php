<x-filament-panels::page>

    {{-- Formulario de usuario en modo lectura --}}
    {{ $this->content }}

    {{-- Botón Volver debajo del formulario --}}
    <div class="flex justify-start mt-4">

        <x-filament::button
            tag="a"
            :href="static::getResource()::getUrl('index')"
            color="gray"
        >
            Volver
        </x-filament::button>

    </div>

</x-filament-panels::page>
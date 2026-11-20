
<x-filament-panels::page>
    <div style="
        width: 100%;
        height: calc(100vh - 190px);
        min-height: 650px;
    ">
        <iframe
            src="{{ route('rrhh.directorio') }}"
            title="Sistema de Personal PRZ"
            style="
                width: 100%;
                height: 100%;
                border: none;
                border-radius: 12px;
                background: white;
            "
        ></iframe>
    </div>
</x-filament-panels::page>

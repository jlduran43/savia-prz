<div style="
        display: flex;
        align-items: center;
        gap: 20px;
        margin-left: 24px;
    ">
    {{-- INICIO --}}
    <a href="{{ \App\Filament\Pages\Dashboard::getUrl() }}"
        style="
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            text-decoration: none;
            color: #111827;
            font-size: 16px;
            font-weight: 500;
        ">
        <x-filament::icon icon="heroicon-o-home" style="width: 22px; height: 22px;" />

        Inicio
    </a>


    {{-- REGISTROS --}}
    <div x-data="{ open: false }" style="position: relative;">
        <button type="button" x-on:click="open = ! open"
            style="
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 10px 12px;
                border: 0;
                background: transparent;
                cursor: pointer;
                color: #111827;
                font-size: 16px;
                font-weight: 500;
            ">
            <x-filament::icon icon="heroicon-o-clipboard-document-list" style="width: 22px; height: 22px;" />

            Ingresos

            <x-filament::icon icon="heroicon-m-chevron-down" style="width: 16px; height: 16px;" />
        </button>


        {{-- SUBMENÚ --}}
        <div x-show="open" x-cloak x-on:click.outside="open = false"
            style="
                position: absolute;
                top: 48px;
                left: 0;
                min-width: 240px;
                background: white;
                border: 1px solid #e5e7eb;
                border-radius: 10px;
                padding: 8px;
                box-shadow: 0 10px 25px rgba(0,0,0,.12);
                z-index: 9999;
            ">
            <a href="{{ \App\Filament\Resources\IngresoVehiculos\IngresoVehiculoResource::getUrl('index', panel: 'ingresos') }}"
                style="
                    display: block;
                    padding: 10px 12px;
                    border-radius: 8px;
                    text-decoration: none;
                    color: #111827;
                ">
                Ingreso de vehículos
            </a>

        </div>
    </div>
    {{-- COMPRAS --}}
    <div x-data="{ open: false }" style="position: relative;">
        <button type="button" x-on:click="open = ! open"
            style="
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            border: 0;
            background: transparent;
            cursor: pointer;
            color: #111827;
            font-size: 16px;
            font-weight: 500;
        ">
            <x-filament::icon icon="heroicon-o-shopping-cart" style="width: 22px; height: 22px;" />

            Compras

            <x-filament::icon icon="heroicon-m-chevron-down" style="width: 16px; height: 16px;" />
        </button>

        <div x-show="open" x-cloak x-on:click.outside="open = false"
            style="
        position: absolute;
        top: 48px;
        left: 0;
        min-width: 340px;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 8px;
        box-shadow: 0 10px 25px rgba(0,0,0,.12);
        z-index: 9999;
    ">
            <a href="#"
                style="
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 12px;
            border-radius: 8px;
            text-decoration: none;
            color: #111827;
            font-size: 14px;
        ">
                <x-filament::icon icon="heroicon-o-banknotes" style="width: 20px; height: 20px;" />

                Compras con recursos autogestionados
            </a>

            <a href="#"
                style="
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 12px;
            border-radius: 8px;
            text-decoration: none;
            color: #111827;
            font-size: 14px;
        ">
                <x-filament::icon icon="heroicon-o-building-library" style="width: 20px; height: 20px;" />

                Compras mediante sistema de compras públicas
            </a>

            {{-- FORMULARIO REQUERIMIENTO --}}
            <a href="{{ \App\Filament\Pages\FormularioRequerimientoCompra::getUrl(panel: 'admin') }}"
                style="
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 11px 12px;
                border-radius: 8px;
                text-decoration: none;
                color: #111827;
                font-size: 14px;
            ">
                <x-filament::icon icon="heroicon-o-document-text" style="width: 20px; height: 20px;" />

                Formulario de requerimiento de compra
            </a>

        </div>
    </div>
</div>

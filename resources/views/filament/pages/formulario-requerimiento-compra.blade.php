<x-filament-panels::page>

    <div class="w-full">

        <iframe
            id="iframe-compras"
            src="{{ route('compras.formulario.contenido') }}"
            title="Formulario de Requerimiento de Compra"
            scrolling="no"
            style="
                width: 100%;
                min-height: 800px;
                border: none;
                border-radius: 12px;
                background: #ffffff;
                display: block;
                overflow: hidden;
            "
        ></iframe>

    </div>

    <script>
        function ajustarAlturaFormulario() {

            const iframe = document.getElementById('iframe-compras');

            if (!iframe) return;

            try {
                const documento = iframe.contentDocument
                    || iframe.contentWindow.document;

                if (!documento || !documento.body) return;

                const altura = Math.max(
                    documento.body.scrollHeight,
                    documento.documentElement.scrollHeight
                );

                iframe.style.height = altura + 30 + 'px';

            } catch (error) {
                console.error(
                    'No se pudo ajustar la altura del formulario:',
                    error
                );
            }
        }

        document.getElementById('iframe-compras')
            .addEventListener('load', function () {

                ajustarAlturaFormulario();

                const documento = this.contentDocument;

                if (!documento || !documento.body) return;

                const observer = new ResizeObserver(() => {
                    ajustarAlturaFormulario();
                });

                observer.observe(documento.body);
                observer.observe(documento.documentElement);

            });
    </script>

</x-filament-panels::page>
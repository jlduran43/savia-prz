
<x-filament-panels::page>

    <div class="w-full">

        <iframe
            id="iframe-compras"
            src="{{ route('compras.formulario.contenido') }}"
            title="Formulario de Requerimiento de Compra"
            scrolling="no"
            style="
                width: 100%;
                height: 800px;
                border: none;
                border-radius: 12px;
                background: #ffffff;
                display: block;
                overflow: hidden;
            "
        ></iframe>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const iframe = document.getElementById('iframe-compras');

            if (!iframe) return;

            let observer = null;
            let ajustePendiente = false;

            function ajustarAlturaFormulario() {

                if (ajustePendiente) return;

                ajustePendiente = true;

                requestAnimationFrame(() => {

                    ajustePendiente = false;

                    try {
                        const doc = iframe.contentDocument;

                        if (!doc || !doc.body) return;

                        const altura = Math.max(
                            doc.body.scrollHeight,
                            doc.documentElement.scrollHeight
                        );

                        const nuevaAltura = Math.max(800, altura);

                        if (Math.abs(iframe.offsetHeight - nuevaAltura) > 2) {
                            iframe.style.height = nuevaAltura + 'px';
                        }

                    } catch (error) {
                        console.error('Error ajustando iframe:', error);
                    }
                });
            }

            iframe.addEventListener('load', function () {

                if (observer) {
                    observer.disconnect();
                }

                ajustarAlturaFormulario();

                try {
                    const doc = iframe.contentDocument;

                    if (!doc || !doc.body) return;

                    observer = new ResizeObserver(() => {
                        ajustarAlturaFormulario();
                    });

                    observer.observe(doc.body);

                } catch (error) {
                    console.error('Error observando formulario:', error);
                }
            });

        });
    </script>

</x-filament-panels::page>
window.RrhhDocumentos = (() => {

    const API = '/rrhh-documentos';

    function csrfToken() {
        const meta = document.querySelector(
            'meta[name="csrf-token"]'
        );

        const cookie = document.cookie
            .split('; ')
            .find(c => c.startsWith('XSRF-TOKEN='));

        const headers = {};

        if (meta?.content) {
            headers['X-CSRF-TOKEN'] = meta.content;
        } else if (cookie) {
            headers['X-XSRF-TOKEN'] = decodeURIComponent(
                cookie.substring('XSRF-TOKEN='.length)
            );
        }

        return headers;
    }

    async function request(url, options = {}) {

        const response = await fetch(url, {
            ...options,
            credentials: 'same-origin',

            headers: {
                'Accept': 'application/json',
                ...csrfToken(),
                ...(options.headers || {})
            }
        });

        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            const error = Object.values(data.errors || {})
                .flat()[0];

            throw new Error(
                error ||
                data.message ||
                `Error HTTP ${response.status}`
            );
        }

        return data;
    }

    function validarId(id) {
        if (!/^[1-9]\d*$/.test(String(id))) {
            throw new Error('Identificador inválido.');
        }

        return String(id);
    }

    async function trabajadores() {
        const data = await request(`${API}/trabajadores`);
        return data.trabajadores;
    }

    async function tipos(trabajadorId) {
        const id = validarId(trabajadorId);

        const data = await request(
            `${API}/${id}/tipos`
        );

        return data.tipos;
    }

    async function listar(
        trabajadorId,
        tipoId = null,
        anio = null,
        mes = null
    ) {

        const id = validarId(trabajadorId);
        const params = new URLSearchParams();

        if (tipoId) {
            params.set('tipo_documento_id', tipoId);
        }

        if (anio) {
            params.set('anio', anio);
        }

        if (mes) {
            params.set('mes', mes);
        }

        const data = await request(
            `${API}/${id}?${params.toString()}`
        );

        return data.documentos;
    }

    async function subir({
        trabajadorId,
        tipoId,
        archivos,
        anio = null,
        mes = null,
        observaciones = '',
        onProgress = null
    }) {

        const id = validarId(trabajadorId);
        const lista = Array.from(archivos || []);

        if (!lista.length) {
            throw new Error('Selecciona al menos un archivo.');
        }

        // Validar todos los tamaños antes de comenzar.
        for (const archivo of lista) {
            if (archivo.size > 20 * 1024 * 1024) {
                throw new Error(
                    `${archivo.name} supera los 20 MB.`
                );
            }
        }

        const guardados = [];

        // Una petición por archivo para evitar
        // cargas demasiado grandes en HostGator.
        for (let i = 0; i < lista.length; i++) {

            const formData = new FormData();

            formData.append(
                'tipo_documento_id',
                validarId(tipoId)
            );

            formData.append('archivos[]', lista[i]);

            if (anio) {
                formData.append('anio', anio);
            }

            if (mes) {
                formData.append('mes', mes);
            }

            formData.append(
                'observaciones',
                observaciones
            );

            try {
                const data = await request(
                    `${API}/${id}`,
                    {
                        method: 'POST',
                        body: formData
                    }
                );

                guardados.push(...data.documentos);

                if (typeof onProgress === 'function') {
                    onProgress(i + 1, lista.length);
                }

            } catch (error) {
                throw new Error(
                    `Se guardaron ${guardados.length} archivos. ` +
                    `Error en ${lista[i].name}: ${error.message}`
                );
            }
        }

        return guardados;
    }

    function visualizar(trabajadorId, documentoId) {

        const trabajador = validarId(trabajadorId);
        const documento = validarId(documentoId);

        window.open(
            `${API}/${trabajador}/${documento}/preview`,
            '_blank',
            'noopener,noreferrer'
        );
    }

    function descargar(trabajadorId, documentoId) {

        const trabajador = validarId(trabajadorId);
        const documento = validarId(documentoId);

        window.location.assign(
            `${API}/${trabajador}/${documento}/download`
        );
    }

    async function eliminar(trabajadorId, documentoId) {

        const trabajador = validarId(trabajadorId);
        const documento = validarId(documentoId);

        return request(
            `${API}/${trabajador}/${documento}`,
            {
                method: 'DELETE'
            }
        );
    }

    return {
        trabajadores,
        tipos,
        listar,
        subir,
        visualizar,
        descargar,
        eliminar
    };

})();
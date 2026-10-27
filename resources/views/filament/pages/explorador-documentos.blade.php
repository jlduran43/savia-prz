<x-filament-panels::page>
    <style>
        [x-cloak] {
            display: none !important
        }

        .prz5 {
            font: 14px/1.5 system-ui, sans-serif;
            color: #263c53
        }

        .prz5 * {
            box-sizing: border-box
        }

        .prz5 button,
        .prz5 input,
        .prz5 select {
            font: inherit
        }

        .prz5 button {
            cursor: pointer
        }

        .prz5 button:disabled {
            opacity: .5;
            cursor: not-allowed
        }

        .prz5 .eyebrow {
            font-size: 11px;
            letter-spacing: 2px;
            color: #176b45;
            font-weight: 800
        }

        .prz5 h1 {
            font-size: 28px;
            font-weight: 800;
            margin: 3px 0
        }

        .prz5 .subtitle {
            color: #8192a3;
            margin: 0 0 22px
        }

        .prz5 .toolbar {
            display: flex;
            gap: 9px;
            flex-wrap: wrap;
            margin: 17px 0
        }

        .prz5 .btn {
            padding: 9px 13px;
            border: 1px solid #dce5ee;
            border-radius: 8px;
            background: white;
            color: #345d79;
            font-weight: 700;
            font-size: 12px
        }

        .prz5 .btn.primary {
            background: #176b45;
            color: #fff;
            border-color: #176b45
        }

        .prz5 .btn:hover {
            filter: brightness(.96)
        }

        .prz5 .location {
            margin: 8px 0 18px;
            color: #5d758d;
            font-size: 12px
        }

        .prz5 .panel {
            border: 1px solid #e4e9ef;
            border-radius: 12px;
            background: white;
            overflow: hidden;
            box-shadow: 0 5px 24px #1a293b0d
        }

        .prz5 .tabs {
            display: flex;
            align-items: center;
            gap: 25px;
            border-bottom: 1px solid #e7edf3;
            padding: 0 22px;
            height: 58px
        }

        .prz5 .tab {
            border: 0;
            background: none;
            padding: 17px 0;
            color: #718298;
            font-weight: 700
        }

        .prz5 .tab.active {
            border-bottom: 3px solid #176b45;
            color: #176b45
        }

        .prz5 .crumb {
            padding: 20px 22px;
            color: #718498;
            display: flex;
            gap: 7px;
            align-items: center;
            flex-wrap: wrap
        }

        .prz5 .crumb button {
            border: 0;
            background: none;
            color: #206c52;
            cursor: pointer
        }

        .prz5 .filters {
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
            padding: 0 22px 20px;
            flex-wrap: wrap
        }

        .prz5 .filters input,
        .prz5 .filters select {
            border: 1px solid #dce5ee;
            border-radius: 8px;
            padding: 9px 11px;
            background: #fbfcfd;
            color: #29445f
        }

        .prz5 .filters input {
            min-width: 230px;
            flex: 1;
            max-width: 390px
        }

        .prz5 table {
            border-collapse: collapse;
            width: 100%;
            min-width: 650px
        }

        .prz5 th {
            font-size: 10px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #879aab;
            background: #f8fafc;
            padding: 12px 15px;
            text-align: left
        }

        .prz5 td {
            padding: 13px 15px;
            border-top: 1px solid #eaf0f5;
            font-size: 12px;
            color: #657b8d
        }

        .prz5 .name {
            font-weight: 700;
            color: #264056;
            background: none;
            border: 0;
            text-align: left
        }

        .prz5 .type {
            font-size: 11px;
            background: #eef3f7;
            border-radius: 5px;
            padding: 4px 7px
        }

        .prz5 .smallbtn {
            background: white;
            border: 1px solid #d7e6ee;
            color: #176b45;
            padding: 6px 9px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            margin: 2px
        }

        .prz5 .scroll {
            overflow-x: auto
        }

        .prz5 .fileicon {
            display: inline-block;
            width: 34px;
            height: 38px;
            object-fit: contain;
            vertical-align: middle;
            margin-right: 12px;
        }

        .prz5 .fileicon-grid {
            display: block;
            width: 54px;
            height: 58px;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .prz5 .empty {
            padding: 60px 15px;
            text-align: center;
            color: #8392a3
        }

        .prz5 .footer {
            padding: 13px 22px;
            border-top: 1px solid #e9edf2;
            color: #91a0af;
            font-size: 11px
        }

        .prz5 .tiles {
            padding: 22px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 12px
        }

        .prz5 .tile {
            border: 1px solid #e3eaf1;
            padding: 15px;
            border-radius: 10px;
            min-width: 0
        }

        .prz5 .tile strong {
            display: block;
            overflow-wrap: anywhere;
            margin: 8px 0
        }

        .prz5 .overlay {
            position: fixed;
            inset: 0;
            z-index: 100;
            background: #10263e88;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 14px
        }

        .prz5 .modal {
            background: white;
            width: min(650px, 100%);
            border-radius: 14px;
            max-height: 90dvh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 12px 42px #10263e33
        }

        .prz5 .modalHead,
        .prz5 .modalFoot {
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            border-bottom: 1px solid #e6edf3
        }

        .prz5 .modalFoot {
            border-top: 1px solid #e6edf3;
            border-bottom: 0;
            justify-content: flex-end
        }

        .prz5 .modalBody {
            padding: 20px;
            overflow-y: auto
        }

        .prz5 .modal h2 {
            font-size: 18px;
            font-weight: 800
        }

        .prz5 .modal input[type=text],
        .prz5 .modal textarea,
        .prz5 .modal select {
            width: 100%;
            border: 1px solid #d5e2eb;
            padding: 10px;
            border-radius: 8px
        }

        .prz5 .monthGrid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 9px;
            margin: 17px 0
        }

        .prz5 .month {
            display: flex;
            gap: 7px;
            border: 1px solid #cbdce8;
            border-radius: 8px;
            padding: 10px;
            background: #f5faff;
            font-weight: 650;
            font-size: 12px
        }

        .prz5 .month input {
            accent-color: #176b45
        }

        .prz5 .notice {
            padding: 12px;
            background: #eef9f2;
            border: 1px solid #c9e8d4;
            border-radius: 8px;
            color: #27664b;
            margin: 10px 0
        }

        .prz5 .error {
            background: #fff0ef;
            color: #9f302a;
            border-color: #f4c7c4
        }

        .prz5 .darkmode {
            display: none
        }

        .prz5 .ctx {
            position: fixed;
            z-index: 90;
            box-shadow: 0 10px 30px #14263d33;
            background: white;
            border: 1px solid #dce5ee;
            border-radius: 8px;
            min-width: 190px;
            padding: 6px
        }

        .prz5 .ctx button {
            display: block;
            width: 100%;
            padding: 9px 12px;
            text-align: left;
            background: none;
            border: 0
        }

        .prz5 .ctx button:hover {
            background: #e9f6ee
        }

        .dark .prz5 .panel,
        .dark .prz5 .modal,
        .dark .prz5 .ctx,
        .dark .prz5 .tile {
            background: #162437;
            color: #f1f5f9;
            border-color: #344256
        }

        .dark .prz5 .name {
            color: #f1f5f9
        }

        .dark .prz5 th {
            background: #203148
        }

        .dark .prz5 td {
            border-color: #344256
        }

        @media(max-width:600px) {
            .prz5 .monthGrid {
                grid-template-columns: repeat(2, 1fr)
            }

            .prz5 h1 {
                font-size: 24px
            }

            .prz5 .filters input {
                min-width: 100%
            }
        }

        .prz-ayuda-barra {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin: 16px 0;
        }

        .prz-ayuda-barra span {
            font-size: 13px;
            color: #7187a0;
        }

        .prz-ayuda-overlay {
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .prz-ayuda-fondo {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, .55);
        }

        .prz-ayuda-modal {
            position: relative;
            width: 100%;
            max-width: 820px;
            max-height: 90vh;
            background: white;
            border-radius: 14px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .25);
        }

        .prz-ayuda-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 22px;
            border-bottom: 1px solid #e5e7eb;
        }

        .prz-ayuda-header h2 {
            font-size: 20px;
            font-weight: 700;
            color: #16385d;
        }

        .prz-ayuda-header button {
            background: #f1f5f9;
            border: 0;
            border-radius: 8px;
            padding: 8px 12px;
            cursor: pointer;
        }

        .prz-ayuda-contenido {
            overflow-y: auto;
            padding: 20px;
        }

        .prz-ayuda-paso {
            padding: 18px;
            margin-bottom: 14px;
            background: #f8fafc;
            border: 1px solid #dce7f4;
            border-radius: 12px;
        }

        .prz-ayuda-paso strong {
            display: block;
            color: #17467a;
            margin-bottom: 10px;
        }

        .prz-ayuda-paso p {
            color: #536d8d;
            font-size: 14px;
            line-height: 1.7;
        }

        .prz-ayuda-footer {
            display: flex;
            justify-content: flex-end;
            padding: 16px 22px;
            border-top: 1px solid #e5e7eb;
        }

        .prz-ayuda-footer button {
            background: #176B45;
            color: white;
            border: 0;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            cursor: pointer;
        }
    </style>
    <div class="prz5" x-data="drivePrzV5()" x-init="load()"
        @keydown.escape.window="modal = null; ctx.show = false" @click.window="ctx.show = false">
        <div class="eyebrow">ESPACIO DE TRABAJO</div>
        <h1>Documentos</h1>
        <p class="subtitle">Gestiona y organiza los archivos de tu equipo.</p>
        <div class="toolbar">
            <button class="btn" @click="modal='folder';name=''">＋ Nueva carpeta</button>
            <button class="btn" @click="modal='bulk';outline=''">▱ Crear varias carpetas</button>
            <button class="btn" @click="modal='months';chosen=Array.from({length:12},(_,i)=>i+1)">▦ Crear
                meses</button>
            <button class="btn primary" :disabled="busy" @click="$refs.files.click()"
                x-text="busy ? 'Subiendo archivos...' : '⇧ Subir archivos aquí'">
            </button>
            <button class="btn" @click="$refs.directory.click()">⇧ Subir carpeta completa</button>
        </div>
        <input x-ref="files" type="file" multiple hidden @change="uploadFiles($event)">
        <input x-ref="directory" type="file" webkitdirectory multiple hidden @change="uploadDirectory($event)">
        <div class="location">📍 Estás en: <strong
                x-text="'Mis archivos'+(trail.length?' / '+trail.map(t=>t.name).join(' / '):'')"></strong> <button
                class="btn" :disabled="!trail.length"
                @click="navigate(trail.length>1?trail[trail.length-2].id:null)">↑ Subir un nivel</button>
        </div>
        {{-- AYUDA DEL EXPLORADOR --}}

        <div class="prz-ayuda-barra">

            <button type="button" class="btn" @click="mostrarAyuda = true">
                ❔ ¿Cómo se usa?
            </button>

            <span>
                Abre carpetas, crea una estructura completa
                o importa carpetas desde tu equipo.
            </span>

        </div>
        <section class="panel">
            <div class="tabs"><button class="tab active">Mis archivos</button><button class="tab" disabled
                    title="Se conectará al módulo de permisos existente">Compartidos conmigo</button><span
                    style="margin-left:auto;color:#8798a9;font-size:11px" x-text="filtered.length+' elementos'"></span>
            </div>
            <div class="crumb"><button @click="navigate(null)">Mis archivos</button><template x-for="c in trail"
                    :key="c.id"><span><span> › </span><button @click="navigate(c.id)"
                            x-text="c.name"></button></span></template></div>
            <div class="filters"><input placeholder="Buscar documentos o carpetas..." x-model.debounce.150ms="search">
                <div><select x-model="filter">
                        <option value="all">Todos los tipos</option>
                        <option value="folder">Carpetas</option>
                        <option value="file">Archivos</option>
                    </select> <select x-model="sort">
                        <option value="name">Nombre A-Z</option>
                        <option value="recent">Más recientes</option>
                    </select> <button class="btn" @click="grid=!grid"
                        x-text="grid?'☰ Lista':'▦ Cuadrícula'"></button></div>
            </div>
            <template x-if="notice">
                <div class="notice" style="margin:0 20px 12px" x-text="notice"></div>
            </template>
            <template x-if="!grid">
                <div class="scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Tamaño</th>
                                <th>Modificado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody><template x-for="item in filtered" :key="item.type + '-' + item.id">
                                <tr @contextmenu.prevent.stop="context($event,item)">
                                    <td>
                                        <template x-if="item.type === 'folder'">
                                            <span class="fileicon" style="font-size:28px;">📁</span>
                                        </template>

                                        <template x-if="item.type === 'file'">
                                            <img class="fileicon" :src="tipoIcono(item)" :alt="'Archivo ' + item.name"
                                                loading="lazy">
                                        </template>
                                        <button class="name" @click="open(item)" x-text="item.name"></button>
                                    </td>
                                    <td><span class="type" x-text="item.type==='folder'?'Carpeta':'Documento'"></span>
                                    </td>
                                    <td x-text="item.type==='folder'?'—':size(item.size)"></td>
                                    <td x-text="date(item.modified)"></td>

                                    <td>
                                        <!-- ABRIR CARPETA -->
                                        <template x-if="item.type === 'folder'">
                                            <button class="smallbtn" @click="open(item)">
                                                📂 Abrir
                                            </button>
                                        </template>

                                        <!-- VER: SOLO PDF E IMÁGENES -->
                                        <template x-if="item.type === 'file' && puedeVer(item)">
                                            <button class="smallbtn" @click="open(item)" title="Visualizar archivo">
                                                <span style="display:inline-flex;align-items:center;gap:5px;">
                                                    <svg width="16" height="16" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z" />
                                                        <circle cx="12" cy="12" r="3" />
                                                    </svg>
                                                    Ver
                                                </span>
                                            </button>
                                        </template>

                                        <!-- DESCARGAR: TODOS LOS ARCHIVOS -->
                                        <template x-if="item.type === 'file'">
                                            <button class="smallbtn" @click="download(item)" title="Descargar archivo">
                                                <span style="display:inline-flex;align-items:center;gap:5px;">
                                                    <svg width="16" height="16" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M12 3v12" />
                                                        <path d="m7 10 5 5 5-5" />
                                                        <path d="M5 17v4h14v-4" />
                                                    </svg>
                                                    Descargar
                                                </span>
                                            </button>
                                        </template>

                                        <!-- MOVER -->
                                        <button class="smallbtn" @click="beginMove(item)">
                                            Mover
                                        </button>

                                        <!-- OPCIONES -->
                                        <button class="smallbtn" @click="beginRename(item)">
                                            •••
                                        </button>
                                    </td>

                                </tr>
                            </template></tbody>
                    </table>
                </div>
            </template>
            <template x-if="grid">
                <div class="tiles"><template x-for="item in filtered" :key="item.type + '-' + item.id">
                        <div class="tile" @contextmenu.prevent.stop="context($event,item)">
                            <template x-if="item.type === 'folder'">
                                <div style="font-size:42px;">📁</div>
                            </template>

                            <template x-if="item.type === 'file'">
                                <img class="fileicon-grid" :src="tipoIcono(item)" :alt="'Archivo ' + item.name"
                                    loading="lazy">
                            </template><strong x-text="item.name"></strong><button class="smallbtn"
                                @click="open(item)">Abrir</button><button class="smallbtn"
                                @click="beginRename(item)">Opciones</button>
                        </div>
                    </template></div>
            </template>
            <template x-if="!filtered.length">
                <div class="empty">📂<p>Esta carpeta está vacía o no hay resultados.</p>
                </div>
            </template>
            <div class="footer" x-text="filtered.length+' elementos · Guardados en SAVIA PRZ'"></div>
        </section>
        <div x-show="ctx.show" x-cloak class="ctx" @click.stop :style="`left:${ctx.x}px;top:${ctx.y}px`"><button
                @click="ctx.show=false;open(ctx.item)">Abrir</button><button
                @click="ctx.show=false;beginRename(ctx.item)">Renombrar / eliminar</button><button
                @click="ctx.show=false;beginMove(ctx.item)">Mover</button><template
                x-if="ctx.item?.type==='file'"><button
                    @click="ctx.show=false;download(ctx.item)">Descargar</button></template></div>
        <div x-show="modal" x-cloak class="overlay" @click.self="modal=null">
            <div class="modal">
                <div class="modalHead">
                    <h2
                        x-text="({folder:'Nueva carpeta',months:'Crear carpetas mensuales',bulk:'Crear varias carpetas',rename:'Opciones del elemento',move:'Mover elemento'})[modal]">
                    </h2><button class="btn" @click="modal=null">✕</button>
                </div>
                <div class="modalBody">
                    <template x-if="modal==='folder'">
                        <div><label>Nombre de carpeta</label><input type="text" x-model="name"
                                placeholder="Nombre de carpeta" @keydown.enter="makeFolder()"></div>
                    </template>
                    <template x-if="modal==='months'">
                        <div>
                            <div class="notice">Destino: <b
                                    x-text="'Mis archivos / '+trail.map(t=>t.name).join(' / ')"></b>. Los meses se
                                crearán directamente aquí.</div>
                            <div class="toolbar"><button class="btn"
                                    @click="chosen=Array.from({length:12},(_,i)=>i+1)">Los 12 meses</button><button
                                    class="btn" @click="chosen=[1,2,3,4,5,6]">Enero–junio</button><button
                                    class="btn" @click="chosen=[7,8,9,10,11,12]">Julio–diciembre</button><button
                                    class="btn"
                                    @click="chosen=Array.from({length:12},(_,i)=>i+1).filter(n=>!items.some(it=>it.type==='folder'&&it.name.toLowerCase()===monthName(n).toLowerCase()))">Solo
                                    faltantes</button><button class="btn" @click="chosen=[]">Ninguno</button></div>
                            <strong>Meses que deseas crear</strong>
                            <div class="monthGrid"><template x-for="n in 12" :key="n"><label
                                        class="month"><input type="checkbox" :checked="chosen.includes(n)"
                                            @change="toggleMonth(n)"><span
                                            x-text="monthName(n)"></span></label></template></div>
                            <div class="notice"
                                x-text="chosen.length+' meses seleccionados · '+newMonths.length+' carpetas nuevas.'">
                            </div><small>Los meses ya existentes no se duplicarán.</small>
                        </div>
                    </template>
                    <template x-if="modal==='bulk'">
                        <div>
                            <p>Escribe una carpeta por línea. Para subcarpetas usa <code>/</code>, por ejemplo
                                <code>2026/Contratos</code>.
                            </p>
                            <textarea rows="8" x-model="outline" placeholder="Administración&#10;2026/Contratos&#10;2026/Facturas"></textarea>
                        </div>
                    </template>
                    <template x-if="modal==='rename'">
                        <div><label>Nuevo nombre</label><input type="text" x-model="name">
                            <p style="margin-top:20px"><button class="btn" style="color:#a33"
                                    @click="removeItem()">🗑 Eliminar elemento</button></p><small>Solo elimina carpetas
                                vacías. Los documentos se eliminan lógicamente si el modelo usa SoftDeletes.</small>
                        </div>
                    </template>
                    <template x-if="modal === 'move'">
                        <div>

                            <p style="margin-bottom:16px;color:#64748b">
                                Vas a mover
                                <strong x-text="selected?.name"></strong>.
                                Selecciona una carpeta de destino:
                            </p>

                            <div
                                style="
            border:1px solid #d5e2eb;
            border-radius:12px;
            padding:8px;
            max-height:320px;
            overflow-y:auto;
        ">

                                <!-- Carpeta raíz -->
                                <button type="button" class="btn"
                                    style="
                    width:100%;
                    display:flex;
                    align-items:center;
                    justify-content:space-between;
                    margin-bottom:5px;
                    text-align:left;
                "
                                    :style="destination === 'root'
                                        ?
                                        'background:#e3f1ff;border-color:#93c5fd;' :
                                        ''"
                                    @click="destination = 'root'">

                                    <span>📁 Mis archivos</span>

                                    <span x-show="destination === 'root'">
                                        ✓
                                    </span>
                                </button>

                                <!-- Carpetas disponibles -->
                                <template x-for="folder in moveOptions" :key="folder.id">

                                    <button type="button" class="btn"
                                        style="
                        width:100%;
                        display:flex;
                        align-items:center;
                        justify-content:space-between;
                        margin-bottom:5px;
                        text-align:left;
                    "
                                        :style="destination === String(folder.id) ?
                                            'background:#e3f1ff;border-color:#93c5fd;' :
                                            ''"
                                        @click="destination = String(folder.id)">

                                        <span style="display:flex;align-items:center;gap:8px"
                                            :style="'padding-left:' + (folder.depth * 20) + 'px'">

                                            <span>📁</span>
                                            <span x-text="folder.name"></span>

                                        </span>

                                        <span x-show="destination === String(folder.id)">
                                            ✓
                                        </span>

                                    </button>

                                </template>

                            </div>

                            <!-- Destino seleccionado -->
                            <div
                                style="
            background:#eff6ff;
            border-radius:10px;
            padding:15px;
            margin-top:15px;
            color:#335c80;
        ">

                                <strong>Destino elegido:</strong>
                                <span x-text="moveDestinationName"></span>

                                <p
                                    style="
                font-size:12px;
                color:#8297ad;
                margin-top:6px;
            ">
                                    Los documentos conservarán su propietario
                                    y sus permisos de acceso.
                                </p>

                            </div>

                            <p x-show="!canConfirmMove" style="margin-top:12px;color:#b45309;font-size:13px">
                                Selecciona una carpeta de destino diferente
                                a la ubicación actual.
                            </p>

                        </div>
                    </template>
                </div>
                <div class="modalFoot">

                    <button type="button" class="btn" @click="modal = null">
                        Cerrar
                    </button>

                    <button type="button" class="btn primary"
                        :disabled="busy ||
                            (modal === 'move' && !canConfirmMove) ||
                            (modal === 'months' && !newMonths.length)"
                        @click="confirmModal()"
                        x-text="
            busy
                ? 'Guardando...'
                : modal === 'move'
                    ? 'Mover aquí'
                    : modal === 'months'
                        ? 'Crear ' + newMonths.length + ' carpetas'
                        : 'Guardar'
        ">
                    </button>

                </div>
            </div>
        </div>
        {{-- MODAL DE AYUDA --}}

        <template x-teleport="body">
            <div x-show="mostrarAyuda" x-cloak class="prz-ayuda-overlay"
                @keydown.escape.window="mostrarAyuda = false">

                <div class="prz-ayuda-fondo" @click="mostrarAyuda = false"></div>

                <div class="prz-ayuda-modal">

                    <div class="prz-ayuda-header">
                        <h2>Cómo organizar tus documentos</h2>

                        <button type="button" @click="mostrarAyuda = false" aria-label="Cerrar">
                            ✕
                        </button>
                    </div>

                    <div class="prz-ayuda-contenido">

                        <div class="prz-ayuda-paso">
                            <strong>1. Entra en una carpeta</strong>
                            <p>
                                Haz clic en “Abrir” o doble clic sobre
                                la carpeta. Puedes crear nuevas carpetas
                                dentro de ella.
                            </p>
                        </div>

                        <div class="prz-ayuda-paso">
                            <strong>2. Sube un archivo</strong>
                            <p>
                                Usa “Subir archivos aquí” o arrástralos
                                desde tu computador.

                                Primero se abre una ventana para revisar
                                los documentos seleccionados.

                                Puedes visualizar PDF, imágenes y otros
                                formatos compatibles antes de confirmar
                                la subida.
                            </p>
                        </div>

                        <div class="prz-ayuda-paso">
                            <strong>3. Cambia un archivo de lugar</strong>
                            <p>
                                Pulsa “Mover”, selecciona la carpeta
                                de destino y confirma.
                            </p>
                        </div>

                        <div class="prz-ayuda-paso">
                            <strong>4. Crea muchas carpetas de una vez</strong>
                            <p>
                                Pulsa “Crear varias carpetas”.
                                Escribe los nombres en líneas y usa
                                dos espacios para indicar subcarpetas.
                            </p>
                        </div>

                        <div class="prz-ayuda-paso">
                            <strong>5. Crea los meses automáticamente</strong>
                            <p>
                                Entra en la carpeta de destino,
                                pulsa “Crear meses”, selecciona
                                los meses necesarios y confirma.

                                Los meses existentes no se duplicarán.
                            </p>
                        </div>

                        <div class="prz-ayuda-paso">
                            <strong>6. Importa carpetas desde tu equipo</strong>
                            <p>
                                Utiliza “Subir carpeta completa”
                                para importar carpetas y documentos
                                desde tu computador.
                            </p>
                        </div>

                        <div class="prz-ayuda-paso">
                            <strong>7. Comparte tus documentos</strong>
                            <p>
                                Utiliza “Compartir” para otorgar
                                acceso a usuarios autorizados
                                del sistema.
                            </p>
                        </div>

                    </div>

                    <div class="prz-ayuda-footer">
                        <button type="button" @click="mostrarAyuda = false">
                            Entendido
                        </button>
                    </div>

                </div>
            </div>
        </template>
    </div> {{-- FIN DEL CONTENEDOR prz5 --}}
    <script>
        function drivePrzV5() {
            return {
                api: @js(url('/drive-prz-v5')),
                csrf: @js(csrf_token()),
                current: null,
                trail: [],
                items: [],
                search: '',
                filter: 'all',
                sort: 'name',
                grid: false,
                modal: null,
                notice: '',
                busy: false,
                name: '',
                chosen: [],
                outline: '',
                selected: null,
                destinations: [],
                destination: '',
                moveOrigin: null,
                movePath: [],
                mostrarAyuda: false,
                ctx: {
                    show: false,
                    x: 0,
                    y: 0,
                    item: null
                },
                get filtered() {
                    let a = this.items.filter(x => (this.filter === 'all' || x.type === this.filter) && x.name
                        .toLowerCase().includes(this.search.toLowerCase()));
                    return a.sort((x, y) => this.sort === 'recent' ? new Date(y.modified || 0) - new Date(x.modified ||
                        0) : x.type !== y.type ? (x.type === 'folder' ? -1 : 1) : x.name.localeCompare(y.name,
                        'es', {
                            sensitivity: 'base'
                        }))
                },
                get newMonths() {
                    return this.chosen.filter(n => !this.items.some(x => x.type === 'folder' && x.name.toLowerCase() ===
                        this.monthName(n).toLowerCase()))
                },
                monthName(n) {
                    return String(n).padStart(2, '0') + '. ' + ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
                        'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
                    ][n - 1]
                },
                toggleMonth(n) {
                    this.chosen = this.chosen.includes(n) ? this.chosen.filter(v => v !== n) : [...this.chosen, n].sort((a,
                        b) => a - b)
                },
                size(v) {
                    if (v == null) return '—';
                    return v < 1024 ? v + ' B' : v < 1048576 ? (v / 1024).toFixed(1) + ' KB' : (v / 1048576).toFixed(1) +
                        ' MB'
                },
                date(v) {
                    return v ? new Date(v).toLocaleDateString('es-CL') : '—'
                },
                async request(path, method = 'GET', data = null) {
                    const isForm = data instanceof FormData;
                    let response = await fetch(this.api + path, {
                        method,
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.csrf,
                            ...(data && !isForm ? {
                                'Content-Type': 'application/json'
                            } : {})
                        },
                        body: data ? (isForm ? data : JSON.stringify(data)) : undefined,
                        credentials: 'same-origin'
                    });
                    if (!response.ok) {
                        let problem = await response.json().catch(() => ({}));
                        throw new Error(Object.values(problem.errors || {}).flat()[0] || problem.message ||
                            'Error HTTP ' + response.status)
                    }
                    return response.json()
                },
                async load() {
                    try {
                        const data = await this.request(
                            '/data' +
                            (this.current !== null ?
                                '?carpeta_id=' + this.current :
                                '')
                        );

                        this.items = data.items ?? [];
                        this.trail = data.trail ?? [];

                    } catch (e) {
                        console.error('Error cargando archivos:', e);
                        alert(e.message);
                    }
                },
                navigate(id) {
                    this.current = id;
                    this.search = '';
                    this.load()
                },

                tipoIcono(item) {

                    const base = @js(asset('images/file-icons'));

                    const extension = (item.name || '')
                        .split('.')
                        .pop()
                        .toLowerCase();

                    const iconos = {
                        pdf: 'pdf.svg',

                        doc: 'word.svg',
                        docx: 'word.svg',

                        xls: 'excel.svg',
                        xlsx: 'excel.svg',
                        csv: 'excel.svg',

                        ppt: 'powerpoint.svg',
                        pptx: 'powerpoint.svg',

                        jpg: 'image.svg',
                        jpeg: 'image.svg',
                        png: 'image.svg',
                        gif: 'image.svg',
                        webp: 'image.svg'
                    };

                    const icono = iconos[extension] || 'file.svg';

                    return base + '/' + icono;
                },

                puedeVer(item) {
                    if (item.type !== 'file') return false;

                    const extension = (item.name || '')
                        .split('.')
                        .pop()
                        .toLowerCase();

                    return [
                        'pdf',
                        'jpg',
                        'jpeg',
                        'png',
                        'gif',
                        'webp'
                    ].includes(extension);
                },

                open(item) {
                    if (item.type === 'folder') {
                        this.navigate(item.id);
                        return;
                    }

                    if (this.puedeVer(item)) {
                        window.open(
                            this.api + '/files/' + item.id + '/preview',
                            '_blank',
                            'noopener'
                        );
                        return;
                    }

                    this.download(item);
                },

                download(item) {
                    window.location.href = this.api + '/files/' + item.id + '/download'
                },
                async run(fn) {
                    this.busy = true;
                    try {
                        await fn();
                        this.modal = null;
                        await this.load()
                    } catch (e) {
                        alert(e.message)
                    } finally {
                        this.busy = false
                    }
                },
                makeFolder() {
                    const nombre = this.name.trim();

                    if (!nombre) {
                        alert('Debes ingresar un nombre para la carpeta.');
                        return;
                    }

                    if (this.busy) return;

                    this.run(async () => {
                        await this.request('/folders', 'POST', {
                            nombre: nombre,
                            carpeta_padre_id: this.current
                        });

                        this.notice = 'Carpeta creada correctamente.';
                    });
                },

                async beginMove(item) {
                    if (this.busy) return;

                    this.selected = item;
                    this.moveOrigin = this.current;
                    this.destination = this.current === null ?
                        'root' :
                        String(this.current);

                    try {
                        const data = await this.request('/destinations');

                        this.destinations = data.folders ?? [];
                        this.modal = 'move';
                    } catch (error) {
                        alert(error.message);
                    }
                },

                get moveOptions() {
                    const folders = this.destinations;
                    const excluded = new Set();

                    if (this.selected?.type === 'folder') {
                        const selectedId = Number(this.selected.id);
                        excluded.add(selectedId);

                        // Excluir todos sus descendientes.
                        let changed = true;

                        while (changed) {
                            changed = false;

                            for (const folder of folders) {
                                if (
                                    folder.carpeta_padre_id !== null &&
                                    excluded.has(Number(folder.carpeta_padre_id)) &&
                                    !excluded.has(Number(folder.id))
                                ) {
                                    excluded.add(Number(folder.id));
                                    changed = true;
                                }
                            }
                        }
                    }

                    const result = [];

                    function visit(parentId, depth, visited = new Set()) {
                        if (depth > 100) return;

                        for (const folder of folders) {
                            const parent = folder.carpeta_padre_id === null ?
                                null :
                                Number(folder.carpeta_padre_id);

                            if (parent !== parentId) continue;

                            const id = Number(folder.id);

                            if (excluded.has(id) || visited.has(id)) continue;

                            result.push({
                                id,
                                name: folder.nombre,
                                depth,
                            });

                            const nextVisited = new Set(visited);
                            nextVisited.add(id);

                            visit(id, depth + 1, nextVisited);
                        }
                    }

                    visit(null, 0);

                    return result;
                },

                get moveDestinationName() {
                    if (this.destination === 'root') {
                        return 'Mis archivos';
                    }

                    const folder = this.destinations.find(
                        f => String(f.id) === this.destination
                    );

                    return folder?.nombre ?? 'Selecciona una carpeta';
                },

                get canConfirmMove() {
                    if (!this.selected || this.busy) return false;

                    const target = this.destination === 'root' ?
                        null :
                        Number(this.destination);

                    const origin = this.moveOrigin === null ?
                        null :
                        Number(this.moveOrigin);

                    if (target === origin) return false;

                    if (target === null) return true;

                    return this.moveOptions.some(
                        folder => folder.id === target
                    );
                },

                async confirmModal() {
                    if (this.modal === 'folder')
                        return this.makeFolder();
                    if (this.modal === 'months')
                        return this.run(async () => {
                            let data = await this.request('/months', 'POST', {
                                carpeta_padre_id: this.current,
                                meses: this.newMonths
                            });

                            this.notice = data.created + ' carpetas creadas, ' + data.skipped + ' omitidas.'
                        });
                    if (this.modal === 'bulk')
                        return this.run(async () => {
                            let d = await this.request('/bulk', 'POST', {
                                carpeta_padre_id: this.current,
                                estructura: this.outline
                            });
                            this.notice = d.created + ' carpetas creadas.'
                        });
                    if (this.modal === 'rename') {
                        return this.run(async () => {
                            const kind = this.selected.type === 'folder' ?
                                'folders' :
                                'files';

                            await this.request(
                                '/' + kind + '/' + this.selected.id,
                                'PATCH', {
                                    nombre: this.name
                                }
                            );

                            this.notice = 'Nombre actualizado.';
                        });
                    }

                    if (this.modal === 'move') {

                        if (!this.canConfirmMove) {
                            alert('Selecciona una carpeta de destino válida.');
                            return;
                        }

                        return this.run(async () => {

                            const kind = this.selected.type === 'folder' ?
                                'folders' :
                                'files';

                            const destinoId = this.destination === 'root' ?
                                null :
                                Number(this.destination);

                            const resultado = await this.request(
                                '/' + kind + '/' + this.selected.id + '/move',
                                'POST', {
                                    destino_id: destinoId
                                }
                            );

                            this.notice = resultado.message ??
                                'Elemento movido correctamente.';

                        });
                    }
                },
                async removeItem() {
                    if (!confirm('¿Eliminar ' + this.selected.name + '?')) return;
                    await this.run(async () => {
                        await this.request('/' + (this.selected.type === 'folder' ? 'folders' : 'files') +
                            '/' + this.selected.id, 'DELETE');
                        this.notice = 'Elemento eliminado.'
                    })
                },
                context(e, item) {
                    this.ctx = {
                        show: true,
                        x: Math.min(e.clientX, innerWidth - 205),
                        y: Math.min(e.clientY, innerHeight - 180),
                        item
                    }
                },


                async uploadFiles(e) {
                    const files = [...e.target.files];
                    e.target.value = '';

                    if (!files.length) return;

                    if (this.current === null) {
                        alert('Primero crea o abre una carpeta para subir documentos.');
                        return;
                    }

                    if (this.busy) return;

                    this.busy = true;
                    this.notice = '';

                    let correctos = 0;
                    const errores = [];

                    const carpetaId = this.current;

                    try {
                        for (const archivo of files) {
                            const body = new FormData();

                            body.append('archivo', archivo);
                            body.append('carpeta_id', carpetaId);

                            try {
                                await this.request('/files', 'POST', body);
                                correctos++;

                                this.notice =
                                    'Subiendo archivos: ' +
                                    (correctos + errores.length) +
                                    ' de ' + files.length;

                            } catch (error) {
                                errores.push(archivo.name + ': ' + error.message);
                            }
                        }

                        await this.load();

                        if (errores.length === 0) {
                            this.notice =
                                '✅ ' + correctos +
                                ' archivo(s) subido(s) correctamente.';
                        } else {
                            this.notice =
                                '⚠️ ' + correctos +
                                ' archivo(s) subido(s), ' +
                                errores.length + ' con error.';

                            alert(
                                'Resultado de la carga:\n\n' +
                                'Correctos: ' + correctos + '\n' +
                                'Errores: ' + errores.length + '\n\n' +
                                errores.join('\n')
                            );
                        }

                    } finally {
                        this.busy = false;
                    }
                },

                async uploadDirectory(e) {
                    const files = [...e.target.files];
                    e.target.value = '';
                    if (!files.length) return;
                    await this.run(async () => {
                        let created = 0;
                        let dirs = new Map([
                            ['', this.current]
                        ]);
                        const paths = [...new Set(files.flatMap(f => {
                            let parts = (f.webkitRelativePath || f.name).split('/');
                            return parts.slice(0, -1).map((_, i) => parts.slice(0, i + 1)
                                .join('/'))
                        }))].sort((a, b) => a.split('/').length - b.split('/').length);
                        for (const p of paths) {
                            const split = p.split('/'),
                                parent = split.slice(0, -1).join('/');
                            let d = await this.request('/ensure-folder', 'POST', {
                                nombre: split.at(-1),
                                carpeta_padre_id: dirs.get(parent)
                            });
                            dirs.set(p, d.id);
                            created++
                        }
                        for (const f of files) {
                            let p = (f.webkitRelativePath || f.name).split('/').slice(0, -1).join('/');
                            let id = dirs.get(p);
                            if (id == null) {
                                throw Error('Primero crea o abre una carpeta de destino.')
                            }
                            let body = new FormData();
                            body.append('archivo', f);
                            body.append('carpeta_id', id);
                            await this.request('/files', 'POST', body)
                        }
                        this.notice = created + ' carpetas procesadas y ' + files.length +
                            ' archivos subidos.'
                    })
                }
            }
        }
    </script>
</x-filament-panels::page>

/* Drive PRZ - Acciones de archivos compartidos (complemento no invasivo).
 * Agregar al final de resources/drive-prz/index.html:
 * <script src="/js/drive-prz-shared-actions.js" defer></script>
 * Copiar este archivo a public/js/drive-prz-shared-actions.js
 */
(() => {
  'use strict';
  const BASE = '/drive-prz-v5';
  const marker = 'data-prz-shared-menu';
  const styles = document.createElement('style');
  styles.textContent = `
    .prz-shared-trigger{margin-left:8px;border:1px solid #dbe4ef;background:#fff;color:#25405d;border-radius:8px;padding:7px 10px;cursor:pointer;font-weight:700}
    .prz-shared-trigger:hover{background:#f1f6fb}
    .prz-shared-menu{position:fixed;z-index:99999;min-width:190px;padding:6px;background:#fff;border:1px solid #dce5ee;box-shadow:0 12px 32px #0002;border-radius:10px}
    .prz-shared-menu button{display:block;width:100%;border:0;background:transparent;text-align:left;padding:10px;border-radius:6px;cursor:pointer;color:#243b53}
    .prz-shared-menu button:hover{background:#f0f6f5}
    .prz-shared-dialog-backdrop{position:fixed;inset:0;background:#172b36a8;z-index:100000;display:grid;place-items:center;padding:18px}
    .prz-shared-dialog{background:white;border-radius:14px;width:min(440px,100%);padding:24px;box-shadow:0 16px 45px #0003;color:#253b4e}
    .prz-shared-dialog h3{margin:0 0 8px;font-size:19px}.prz-shared-dialog p{font-size:14px;color:#607185}
    .prz-shared-dialog input,.prz-shared-dialog select{width:100%;padding:11px;border-radius:8px;border:1px solid #cbd5e1;margin:10px 0 18px;font:inherit}
    .prz-shared-dialog-actions{display:flex;justify-content:flex-end;gap:10px}
    .prz-shared-dialog-actions button{border:1px solid #d7e0e8;padding:9px 14px;border-radius:8px;cursor:pointer;background:white}
    .prz-shared-dialog-actions button.prz-primary{background:#176b45;color:#fff;border-color:#176b45}
    .prz-shared-dialog-actions button:disabled{opacity:.55;cursor:wait}
    /* Selector Copiar: estética azul/blanco Drive PRZ, independiente de Mover */
    .prz-copy-panel{width:min(510px,100%);padding:0;overflow:hidden;font-family:inherit}
    .prz-copy-head{display:flex;justify-content:space-between;align-items:center;padding:19px 22px;border-bottom:1px solid #e5ebf2}
    .prz-copy-head h3{margin:0;color:#243b53;font-size:19px}
    .prz-copy-close{border:0;background:transparent;color:#64748b;cursor:pointer;font-size:25px;line-height:1}
    .prz-copy-body{padding:18px 22px}
    .prz-copy-source{background:#f6f8fc;padding:11px 12px;border-radius:10px;display:flex;gap:9px;align-items:center;color:#334b65;font-size:13px;overflow-wrap:anywhere}
    .prz-copy-source-icon{font-size:22px}
    .prz-copy-help{margin:14px 0 9px!important;color:#697f94!important;font-size:13px!important}
    .prz-copy-crumbs{display:flex;align-items:center;flex-wrap:wrap;gap:5px;padding:9px 0;color:#627792;font-size:13px}
    .prz-copy-crumbs button{border:0;background:transparent;color:#2165b0;font:inherit;cursor:pointer;padding:3px}
    .prz-copy-crumbs strong{color:#263e59}
    .prz-copy-folders{border:1px solid #e0e7f0;border-radius:10px;min-height:155px;max-height:275px;overflow:auto;padding:5px}
    .prz-copy-folder{width:100%;display:flex;align-items:center;gap:10px;text-align:left;background:transparent;border:0;border-radius:8px;padding:12px;color:#2d4562;font:inherit;cursor:pointer}
    .prz-copy-folder:hover{background:#edf4fc}
    .prz-copy-folder .prz-copy-foldername{flex:1;overflow-wrap:anywhere}
    .prz-copy-folder .prz-copy-arrow{color:#7d94ad}
    .prz-copy-empty{padding:28px 12px;text-align:center;color:#8797ab;font-size:13px}
    .prz-copy-current{font-size:12px;color:#627792;margin-top:11px;overflow-wrap:anywhere}
    .prz-copy-actions{padding:15px 22px;border-top:1px solid #e5ebf2;display:flex;justify-content:flex-end;gap:10px}
    .prz-copy-actions button{border:1px solid #d7e2ee;border-radius:9px;padding:10px 16px;background:white;color:#324a66;font:inherit;cursor:pointer}
    .prz-copy-actions .prz-copy-confirm{background:#2168b8;border-color:#2168b8;color:white;font-weight:600}
    .prz-copy-actions button:disabled{opacity:.55;cursor:wait}
  `;
  document.head.appendChild(styles);

  let currentItems = [];
  let currentMenu = null;
  let busy = false;
  const normalize = str => String(str || '').trim().replace(/\s+/g, ' ').toLocaleLowerCase('es');
  const escapeSelector = str => (window.CSS && CSS.escape) ? CSS.escape(str) : str.replace(/[^a-zA-Z0-9_-]/g, '\\$&');
  const can = (item, action) => (item.permissions || item.permisos || []).includes(action);
  function csrf() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (meta) return meta.content;
    const cookie = document.cookie.split('; ').find(x => x.startsWith('XSRF-TOKEN='));
    return cookie ? decodeURIComponent(cookie.slice('XSRF-TOKEN='.length)) : '';
  }
  async function request(path, options = {}) {
    const headers = {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'};
    if (options.body) headers['Content-Type'] = 'application/json';
    if (options.method && options.method !== 'GET') headers['X-CSRF-TOKEN'] = csrf();
    const r = await fetch(BASE + path, {credentials:'same-origin',...options,headers:{...headers,...options.headers}});
    const data = await r.json().catch(() => ({}));
    if (!r.ok) throw new Error(data.message || Object.values(data.errors || {})[0]?.[0] || `Error HTTP ${r.status}`);
    return data;
  }
  function sharedVisible() {
    const active = [...document.querySelectorAll('[role="tab"],button,a')]
      .filter(e => /compartidos conmigo/i.test(e.textContent || ''))
      .some(e => e.getAttribute('aria-selected') === 'true' || e.classList.contains('active'));
    if (active) return true;
    const text = document.body.innerText || '';
    return /Estás en:\s*Compartidos conmigo/i.test(text) && /Compartido por/i.test(text);
  }
  function closeMenu() { currentMenu?.remove(); currentMenu = null; }
  function dialog({title,description,placeholder,value,options,submitText = 'Guardar'}) {
    return new Promise(resolve => {
      const backdrop = document.createElement('div'); backdrop.className = 'prz-shared-dialog-backdrop';
      const panel = document.createElement('div'); panel.className = 'prz-shared-dialog';
      const heading = document.createElement('h3'); heading.textContent = title;
      const p = document.createElement('p'); p.textContent = description;
      const control = document.createElement(options ? 'select' : 'input');
      if (options) {
        options.forEach(opt => { const o = new Option(opt.label, String(opt.value)); control.add(o); });
      } else { control.type = 'text'; control.placeholder = placeholder || ''; control.value = value || ''; control.maxLength = 120; }
      const actions = document.createElement('div'); actions.className = 'prz-shared-dialog-actions';
      const cancel = document.createElement('button'); cancel.textContent = 'Cancelar'; cancel.type = 'button';
      const ok = document.createElement('button'); ok.textContent = submitText; ok.type = 'button'; ok.className='prz-primary';
      actions.append(cancel,ok); panel.append(heading,p,control,actions); backdrop.append(panel); document.body.append(backdrop);
      const done = v => { backdrop.remove(); resolve(v); };
      cancel.onclick = () => done(null);
      backdrop.onclick = e => { if (e.target === backdrop) done(null); };
      ok.onclick = () => { const v = control.value.trim(); if (!v && !options) return control.focus(); done(v); };
      control.onkeydown = e => {if(e.key==='Escape') done(null); if(e.key==='Enter') ok.click();};
      control.focus();
    });
  }
  /** Navegador de carpetas propias. Se envía null al backend para la raíz. */
  function copyDestinationDialog(item, folders) {
    return new Promise(resolve => {
      const folderId = f => Number(f.id);
      const parentId = f => {
        const v = f.carpeta_padre_id ?? f.parent_id ?? f.parentId ?? f.padre_id ?? null;
        return v === '' || v == null ? null : Number(v);
      };
      const all = folders.map(f => ({
        id: folderId(f),
        name: String(f.nombre ?? f.name ?? `Carpeta ${f.id}`),
        parent: parentId(f),
      })).filter(f => Number.isSafeInteger(f.id) && f.id > 0);
      const ids = new Set(all.map(f => f.id));
      // Si el endpoint no entrega jerarquía, las carpetas se muestran en una lista plana.
      const hasParentFields = folders.some(f => ['carpeta_padre_id','parent_id','parentId','padre_id'].some(k => Object.hasOwn(f,k)));
      let current = null;
      const backdrop = document.createElement('div'); backdrop.className = 'prz-shared-dialog-backdrop';
      const panel = document.createElement('div'); panel.className = 'prz-shared-dialog prz-copy-panel';
      panel.setAttribute('role','dialog'); panel.setAttribute('aria-modal','true'); panel.setAttribute('aria-label','Copiar archivo');
      const head = document.createElement('div'); head.className = 'prz-copy-head';
      const heading = document.createElement('h3'); heading.textContent = 'Copiar archivo';
      const close = document.createElement('button'); close.type='button'; close.className='prz-copy-close'; close.textContent='×'; close.setAttribute('aria-label','Cerrar');
      head.append(heading,close);
      const body = document.createElement('div'); body.className='prz-copy-body';
      const source = document.createElement('div'); source.className='prz-copy-source';
      const icon=document.createElement('span'); icon.className='prz-copy-source-icon'; icon.textContent='📄';
      const file=document.createElement('strong'); file.textContent=String(item.name || 'Archivo compartido');
      source.append(icon,file);
      const help=document.createElement('p'); help.className='prz-copy-help';
      help.textContent='Abre una carpeta para elegir dónde guardar una copia independiente del archivo.';
      const crumbs=document.createElement('div'); crumbs.className='prz-copy-crumbs';
      const folderList=document.createElement('div'); folderList.className='prz-copy-folders';
      const selected=document.createElement('div'); selected.className='prz-copy-current';
      body.append(source,help,crumbs,folderList,selected);
      const actions=document.createElement('div'); actions.className='prz-copy-actions';
      const cancel=document.createElement('button'); cancel.type='button'; cancel.textContent='Cancelar';
      const confirm=document.createElement('button'); confirm.type='button'; confirm.className='prz-copy-confirm'; confirm.textContent='Copiar aquí';
      actions.append(cancel,confirm); panel.append(head,body,actions); backdrop.append(panel); document.body.append(backdrop);
      const onKey=e=>{if(e.key==='Escape')finish(null);};
      document.addEventListener('keydown',onKey);
      const finish=v=>{document.removeEventListener('keydown',onKey);backdrop.remove();resolve(v);};
      close.onclick=cancel.onclick=()=>finish(null);
      backdrop.onclick=e=>{if(e.target===backdrop)finish(null);};
      confirm.onclick=()=>finish(current === null ? 'root' : String(current));
      const button=(text, click)=>{const b=document.createElement('button');b.type='button';b.textContent=text;b.onclick=click;return b;};
      function pathTo(id) {
        const chain=[]; const seen=new Set(); let node=all.find(f=>f.id===id);
        while(node && !seen.has(node.id)) { chain.unshift(node);seen.add(node.id);node=all.find(f=>f.id===node.parent); }
        return chain;
      }
      function render() {
        crumbs.replaceChildren(); folderList.replaceChildren();
        crumbs.append(button('Mis archivos',()=>{current=null;render();}));
        for(const f of pathTo(current)) {
          const sep=document.createElement('span');sep.textContent='›';crumbs.append(sep);
          crumbs.append(button(f.name,()=>{current=f.id;render();}));
        }
        const children = all.filter(f => hasParentFields
          ? (current===null ? f.parent===null || !ids.has(f.parent) : f.parent===current)
          : current===null).sort((a,b)=>a.name.localeCompare(b.name,'es'));
        for(const f of children) {
          const b=document.createElement('button');b.type='button';b.className='prz-copy-folder';
          const folderIcon=document.createElement('span');folderIcon.textContent='📁';
          const name=document.createElement('span');name.className='prz-copy-foldername';name.textContent=f.name;
          const arrow=document.createElement('span');arrow.className='prz-copy-arrow';arrow.textContent='›';
          b.append(folderIcon,name,arrow);
          b.onclick=()=>{current=f.id;render();};
          folderList.append(b);
        }
        if(!children.length) {
          const empty=document.createElement('div');empty.className='prz-copy-empty';
          empty.textContent=all.length ? 'No hay más carpetas aquí.' : 'Todavía no tienes carpetas. Puedes copiar a la raíz.';
          folderList.append(empty);
        }
        selected.textContent='Destino: '+(['Mis archivos',...pathTo(current).map(f=>f.name)].join(' / '));
      }
      render(); close.focus();
    });
  }

  async function execute(item, action) {
    if (busy || !can(item, action)) return;
    try {
      let url, body, method = 'POST';
      if (action === 'renombrar') {
        const name = await dialog({title:'Renombrar documento', description:'Modifica el nombre del documento compartido.', value:item.name});
        if (name === null || name === item.name) return;
        method = 'PATCH'; url = `/shared/files/${item.id}`; body = {nombre:name};
      } else if (action === 'mover') {
        // Consultar destinos del archivo concreto: Laravel verifica propietario y permisos.
        const data = await request('/shared/destinations?archivo_id=' + encodeURIComponent(item.id));
        const options = [];
        if (data.root_available === true) {
          options.push({value:'root',label:'Raíz del propietario (fuera de las carpetas)'});
        }
        for (const f of (data.folders || [])) {
          if (Number(f.id) === Number(item.parent)) continue;
          if (Number(f.creado_por) !== Number(item.owner_id)) continue;
          options.push({value:String(f.id),label:f.nombre + ' (carpeta del propietario)'});
        }
        if (!options.length) return alert('No hay otros destinos autorizados para este archivo. Pide al propietario que comparta otra carpeta con permiso Mover o que autorice Mover directamente sobre el archivo para devolverlo a su raíz.');
        const id = await dialog({title:'Mover documento',description:'El archivo seguirá perteneciendo al propietario. Selecciona un destino autorizado.',options,submitText:'Mover'});
        if (id === null) return;
        url = `/shared/files/${item.id}/move`; body = {destino_id:id === 'root' ? null : Number(id)};
      } else if (action === 'copiar') {
        const data = await request('/destinations');
        const raw = Array.isArray(data) ? data : (data.folders || data.items || data.destinations || []);
        const ownFolders = raw.filter(f => f.type === 'folder' || f.type === 'folders' || !f.type);
        const dest = await copyDestinationDialog(item, ownFolders);
        if (dest === null) return;
        url = `/shared/files/${item.id}/copy`; body = {destino_id: dest === 'root' ? null : Number(dest)};
      } else return;
      busy = true;
      await request(url,{method,body:JSON.stringify(body)});
      alert(action === 'copiar' ? 'Copia creada en Mis archivos.' : 'Cambio guardado correctamente.');
      // Una recarga sincroniza también los estados internos del frontend original.
      window.location.reload();
    } catch (error) { alert(error.message || 'No se pudo completar la operación.'); }
    finally { busy = false; }
  }
  window.przSharedExecuteAction = execute;
  function showMenu(item, trigger) {
    closeMenu();
    const menu = document.createElement('div'); menu.className='prz-shared-menu';
    [['renombrar','Renombrar'],['mover','Mover'],['copiar','Copiar']].forEach(([key,text]) => {
      if (!can(item,key)) return;
      const b = document.createElement('button'); b.type='button'; b.textContent=text;
      b.onclick=()=>{closeMenu();execute(item,key);}; menu.append(b);
    });
    if (!menu.childNodes.length) return;
    document.body.append(menu); currentMenu = menu;
    const rect = trigger.getBoundingClientRect();
    const width = menu.getBoundingClientRect().width;
    menu.style.top = `${Math.min(rect.bottom + 5, innerHeight - menu.offsetHeight - 8)}px`;
    menu.style.left = `${Math.max(8,Math.min(rect.right-width, innerWidth-width-8))}px`;
  }
  function getRow(item) {
    // Buscar solamente filas existentes; no tocar el renderizado original.
    const rows = [...document.querySelectorAll('tr')];
    return rows.find(row => {
      const cells = row.querySelectorAll('td');
      return cells.length >= 3 && /Compartido por/i.test(row.textContent || '') &&
        normalize(cells[0].textContent).includes(normalize(item.name));
    }) || rows.find(row => /Compartido por/i.test(row.textContent || '') && normalize(row.textContent).includes(normalize(item.name)));
  }
  function decorate() {
    if (!sharedVisible()) return;
    for (const item of currentItems) {
      if (item.type !== 'file' || !can(item, 'ver')) continue;
      const row = getRow(item);
      if (!row) continue;

      // Los comandos se ofrecen mediante clic derecho en el HTML principal.
      // Eliminar cualquier botón antiguo generado por este complemento.
      row.querySelectorAll(`[${marker}], .prz-shared-trigger`).forEach(button => button.remove());

      // Mantener actualizada la etiqueta del permiso.
      const label = [...row.querySelectorAll('small,span,p,div')].find(el =>
        el.children.length === 0 && el.textContent?.trim().endsWith('· Solo ver'));
      if (label && item.role === 'edit') {
        label.textContent = label.textContent.replace(/Solo ver\s*$/, 'Puede editar');
      }
    }
  }

  let scheduled = false;
  function schedule() {if(scheduled) return; scheduled=true; setTimeout(()=>{scheduled=false;decorate();},150);}
  async function refresh() {
    if(!sharedVisible()) return;
    try {
      // Misma lista que usa el frontend; para carpetas abiertas se conserva la detección por nombre.
      const folder = window.przSharedCurrentFolder;
      const d = await request('/shared' + (folder == null ? '' : '?carpeta_id=' + encodeURIComponent(folder)));
      currentItems = d.items || []; schedule();
    } catch (e) {console.warn('Drive PRZ compartidos: no fue posible cargar permisos',e);}
  }
  document.addEventListener('click',e=>{if(currentMenu && !currentMenu.contains(e.target) && !e.target.closest(`[${marker}]`))closeMenu();});
  document.addEventListener('keydown',e=>{if(e.key==='Escape')closeMenu();});
  document.addEventListener('click',e=>{if(e.target.closest('button,a,[role="tab"]'))setTimeout(refresh,350);},true);
  new MutationObserver(schedule).observe(document.body,{childList:true,subtree:true});
  refresh();
  setInterval(()=>{ if(sharedVisible())refresh(); },4000);
})();

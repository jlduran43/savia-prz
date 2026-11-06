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
  async function execute(item, action) {
    if (busy || !can(item, action)) return;
    try {
      let url, body, method = 'POST';
      if (action === 'renombrar') {
        const name = await dialog({title:'Renombrar documento', description:'Modifica el nombre del documento compartido.', value:item.name});
        if (name === null || name === item.name) return;
        method = 'PATCH'; url = `/shared/files/${item.id}`; body = {nombre:name};
      } else if (action === 'mover') {
        const data = await request('/shared/destinations');
        const options = (data.folders || []).filter(f => Number(f.id) !== Number(item.parent)).filter(f => Number(f.creado_por) === Number(item.owner_id))
          .map(f => ({value:f.id,label:f.nombre + ' (carpeta del propietario)'}));
        if (!options.length) return alert('No hay carpetas de destino autorizadas. El propietario debe compartir una carpeta con permiso Mover.');
        const id = await dialog({title:'Mover documento',description:'Solo puedes moverlo a carpetas autorizadas del propietario.',options,submitText:'Mover'});
        if (id === null) return;
        url = `/shared/files/${item.id}/move`; body = {destino_id:Number(id)};
      } else if (action === 'copiar') {
        const data = await request('/destinations');
        const raw = Array.isArray(data) ? data : (data.folders || data.items || data.destinations || []);
        const ownFolders = raw.filter(f => (f.type === 'folder' || f.type === 'folders' || !f.type)).map(f => ({value:f.id,label:f.name || f.nombre || `Carpeta ${f.id}`}));
        const options = [{value:'root',label:'Mis archivos (raíz)'},...ownFolders];
        const dest = await dialog({title:'Copiar a mis archivos',description:'Se creará una copia independiente que será tuya; el original no se modifica.',options,submitText:'Copiar'});
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
    [['renombrar','Renombrar'],['mover','Mover'],['copiar','Copiar a Mis archivos']].forEach(([key,text]) => {
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

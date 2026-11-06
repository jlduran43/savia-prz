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
    /* Ventana Mover - mismo lenguaje visual del modal principal de Drive PRZ */
    .prz-move-overlay{position:fixed;inset:0;background:rgba(76,96,122,.34);backdrop-filter:blur(2px);z-index:100010;display:flex;align-items:center;justify-content:center;padding:24px}
    .prz-move-modal{width:min(820px,100%);max-height:min(760px,calc(100vh - 48px));overflow:hidden;display:flex;flex-direction:column;background:#ffffff;border:1px solid #e5ebf3;border-radius:22px;box-shadow:0 24px 60px rgba(45,67,92,.18);font-family:inherit;color:#334e68}
    .prz-move-head{display:flex;align-items:center;justify-content:space-between;padding:24px 28px 18px;border-bottom:1px solid #e8eef5}
    .prz-move-symbol{display:none}
    .prz-move-heading{min-width:0;flex:1}
    .prz-move-heading h3{font-size:20px;font-weight:800;margin:0;color:#23384d;line-height:1.35}
    .prz-move-heading p{display:none}
    .prz-move-x{width:42px;height:42px;flex:none;border:0;border-radius:12px;background:#f3f6fa;font-size:24px;line-height:1;color:#7d8fa4;cursor:pointer;display:grid;place-items:center}
    .prz-move-x:hover{background:#eaf0f6;color:#4c637a}
    .prz-move-content{padding:20px 28px 24px;overflow:auto}
    .prz-move-file{border:1px solid #d7e3f1;background:#f4f8fc;border-radius:12px;padding:14px 16px;display:flex;gap:10px;align-items:flex-start;margin-bottom:18px;min-width:0}
    .prz-move-file svg{width:20px;height:20px;color:#5f7895;flex:none;margin-top:1px}
    .prz-move-file-meta{min-width:0;flex:1}
    .prz-move-file-name{display:block;font-weight:700;color:#4f6780;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-size:13px;margin-bottom:3px}
    .prz-move-file-caption{display:block;font-size:13px;line-height:1.45;color:#59728e}
    .prz-move-label{font-size:14px;font-weight:700;color:#425b76;margin:0 0 12px}
    .prz-move-options{display:grid;gap:10px;max-height:320px;overflow:auto;padding:1px}
    .prz-move-option{width:100%;border:1px solid #d8e3ef;border-radius:12px;background:#fff;padding:14px 15px;display:flex;align-items:center;gap:12px;text-align:left;cursor:pointer;font:inherit;color:#324b65;transition:background .15s,border-color .15s, box-shadow .15s}
    .prz-move-option:hover{background:#f8fbfe;border-color:#b9d0e8}
    .prz-move-option[aria-checked="true"]{border-color:#a7c4e3;background:#f5f9fd;box-shadow:0 0 0 1px #c6dbef inset}
    .prz-move-option-icon{width:38px;height:38px;border-radius:10px;background:#f9edcb;color:#d39e2e;display:grid;place-items:center;flex:none}
    .prz-move-option[data-root="true"] .prz-move-option-icon{background:#e9f1fa;color:#6b89aa}
    .prz-move-option-icon svg{width:21px;height:21px}
    .prz-move-option-text{flex:1;min-width:0}
    .prz-move-option-title{display:block;font-size:14px;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#2d465f}
    .prz-move-option-caption{display:block;font-size:12px;color:#7b8fa3;margin-top:3px}
    .prz-move-radio{width:20px;height:20px;border:2px solid #c5d4e3;border-radius:50%;flex:none;display:grid;place-items:center;background:#fff}
    .prz-move-option[aria-checked="true"] .prz-move-radio{border-color:#94b7da}
    .prz-move-option[aria-checked="true"] .prz-move-radio::after{content:"";width:10px;height:10px;border-radius:50%;background:#9abfe4}
    .prz-move-footer{border-top:1px solid #e8eef5;padding:20px 28px;display:flex;justify-content:flex-end;gap:12px;background:#fbfdff}
    .prz-move-footer button{font:inherit;font-size:14px;font-weight:700;padding:12px 22px;border-radius:12px;cursor:pointer;border:1px solid #d8e2ec;background:#fff;color:#45617d}
    .prz-move-footer button:hover{background:#f3f7fb}
    .prz-move-footer button.prz-move-submit{background:#8fb6e0;border-color:#8fb6e0;color:#fff;box-shadow:none}
    .prz-move-footer button.prz-move-submit:hover{background:#82addb;border-color:#82addb}
    .prz-move-option:focus-visible,.prz-move-footer button:focus-visible,.prz-move-x:focus-visible{outline:3px solid #c5daf0;outline-offset:2px}
    @media(max-width:720px){.prz-move-modal{width:min(96vw,100%)}.prz-move-head{padding:20px 18px 15px}.prz-move-content{padding:18px}.prz-move-footer{padding:16px 18px}.prz-move-heading h3{font-size:18px}}
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
  // Diálogo exclusivo para mover: conserva los valores y las comprobaciones del backend.
  function moveDialog(item, options) {
    return new Promise(resolve => {
      const iconFolder = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7a2 2 0 0 1 2-2h5l2 2h7a2 2 0 0 1 2 2v10H3z"/></svg>';
      const iconHome = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1z"/></svg>';
      const iconFile = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 3h7l5 5v13H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M14 3v6h5M9 14h6M9 18h6"/></svg>';
      const overlay = document.createElement('div'); overlay.className = 'prz-move-overlay';
      const modal = document.createElement('section'); modal.className = 'prz-move-modal';
      modal.setAttribute('role','dialog'); modal.setAttribute('aria-modal','true');
      const headingId = 'prz-move-title'; modal.setAttribute('aria-labelledby',headingId);
      const head = document.createElement('div'); head.className = 'prz-move-head';
      const symbol = document.createElement('span'); symbol.className = 'prz-move-symbol'; symbol.innerHTML = iconFolder;
      const headerText = document.createElement('div'); headerText.className = 'prz-move-heading';
      const title = document.createElement('h3'); title.id=headingId; title.textContent = 'Mover archivo';
      const sub = document.createElement('p'); sub.textContent='';
      headerText.append(title,sub);
      const close = document.createElement('button');close.type='button';close.className='prz-move-x';close.textContent='×';close.setAttribute('aria-label','Cerrar');
      head.append(symbol,headerText,close);
      const content = document.createElement('div'); content.className='prz-move-content';
      const file = document.createElement('div');file.className='prz-move-file';
      const ficon=document.createElement('span');ficon.innerHTML=iconFile;
      const fmeta=document.createElement('div');fmeta.className='prz-move-file-meta';
      const fname=document.createElement('span');fname.className='prz-move-file-name';fname.textContent=item.name || 'Archivo compartido';fname.title=item.name || '';
      const fcaption=document.createElement('span');fcaption.className='prz-move-file-caption';fcaption.textContent='El archivo seguirá perteneciendo al propietario. Selecciona un destino autorizado para cambiar su ubicación.';
      fmeta.append(fname,fcaption);
      file.append(ficon,fmeta);
      const label=document.createElement('p');label.className='prz-move-label';label.textContent='Selecciona la carpeta de destino';
      const choices=document.createElement('div');choices.className='prz-move-options';choices.setAttribute('role','radiogroup');choices.setAttribute('aria-label','Destino del archivo');
      let selected=String(options[0].value);
      const buttons=[];
      const update=()=>buttons.forEach(b=>{const active=b.dataset.value===selected;b.setAttribute('aria-checked',String(active));b.tabIndex=active?0:-1;});
      options.forEach((opt,index)=>{
        const root=String(opt.value)==='root';
        const b=document.createElement('button');b.type='button';b.className='prz-move-option';b.dataset.value=String(opt.value);b.dataset.root=String(root);b.setAttribute('role','radio');
        const bi=document.createElement('span');bi.className='prz-move-option-icon';bi.innerHTML=root?iconHome:iconFolder;
        const bt=document.createElement('span');bt.className='prz-move-option-text';
        const name=document.createElement('span');name.className='prz-move-option-title';name.textContent=root?'Raíz del propietario':String(opt.label).replace(/ \(carpeta del propietario\)$/,'');
        const detail=document.createElement('span');detail.className='prz-move-option-caption';detail.textContent=root?'Fuera de las carpetas':'Carpeta compartida autorizada';
        bt.append(name,detail);
        const radio=document.createElement('span');radio.className='prz-move-radio';radio.setAttribute('aria-hidden','true');
        b.append(bi,bt,radio);b.onclick=()=>{selected=b.dataset.value;update();};
        b.onkeydown=e=>{if(e.key==='ArrowDown'||e.key==='ArrowUp'){e.preventDefault();const next=(buttons.indexOf(b)+(e.key==='ArrowDown'?1:-1)+buttons.length)%buttons.length;selected=buttons[next].dataset.value;update();buttons[next].focus();}};
        buttons.push(b);choices.append(b);
      });
      update();content.append(file,label,choices);
      const footer=document.createElement('div');footer.className='prz-move-footer';
      const cancel=document.createElement('button');cancel.type='button';cancel.textContent='Cerrar';
      const submit=document.createElement('button');submit.type='button';submit.className='prz-move-submit';submit.textContent='Mover archivo';
      footer.append(cancel,submit);modal.append(head,content,footer);overlay.append(modal);
      const previousFocus=document.activeElement;
      function done(value){document.removeEventListener('keydown',onKey,true);overlay.remove();if(previousFocus?.isConnected)previousFocus.focus();resolve(value);}
      function onKey(e){if(e.key==='Escape'){e.preventDefault();done(null);}if(e.key==='Tab'){const focusable=[close,...buttons,cancel,submit];const first=focusable[0],last=focusable[focusable.length-1];if(e.shiftKey&&document.activeElement===first){e.preventDefault();last.focus();}else if(!e.shiftKey&&document.activeElement===last){e.preventDefault();first.focus();}}}
      close.onclick=cancel.onclick=()=>done(null);submit.onclick=()=>done(selected);
      overlay.onclick=e=>{if(e.target===overlay)done(null);};
      document.body.append(overlay);document.addEventListener('keydown',onKey,true);buttons[0].focus();
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
        const id = await moveDialog(item, options);
        if (id === null) return;
        url = `/shared/files/${item.id}/move`; body = {destino_id:id === 'root' ? null : Number(id)};
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

/* Drive PRZ: iconos en Acciones de Mis archivos y Compartidos conmigo.
   No reemplaza listeners, rutas ni permisos: solo contenido visual. */
(() => {
  'use strict';
  const paths = {
    'Abrir': '<path d="M3 7a2 2 0 0 1 2-2h5l2 2h7a2 2 0 0 1 2 2v10H3z"/><path d="M3 11h18"/>',
    'Ver': '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>',
    'Descargar': '<path d="M12 3v12m-5-5 5 5 5-5M4 17v4h16v-4"/>',
    'Mover': '<path d="M3 7a2 2 0 0 1 2-2h5l2 2h7a2 2 0 0 1 2 2v10H3z"/><path d="m10 13 3 3-3 3m3-3H7"/>',
    'Compartir': '<circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.7 10.7 6.6-4.1m-6.6 6.7 6.6 4.1"/>',
    'Copiar': '<rect width="13" height="13" x="8" y="8" rx="2"/><path d="M16 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h3"/>',
    'Renombrar': '<path d="m12 19 7-7 3 3-7 7-4 1 1-4Z"/><path d="M3 6h12M3 10h7M3 14h5"/>',
    'Mover a la papelera': '<path d="M3 6h18M8 6V4h8v2m3 0-1 15H6L5 6m5 4v7m4-7v7"/>',
    'Quitar de mis compartidos': '<path d="M3 7h18v14H3zM3 11h18m-12 5h6"/>'
  };
  const css = document.createElement('style');
  css.textContent = `
    #filesArea .actions .prz-icon-action,
    #filesArea .tilefoot .prz-icon-action {
      display:inline-flex; align-items:center; justify-content:center;
      width:36px; min-width:36px; height:36px; padding:0 !important;
      border:1px solid #d7e4f5; border-radius:9px;
      background:#fff; color:#1763ad; cursor:pointer;
      vertical-align:middle; text-decoration:none;
    }
    #filesArea .actions .prz-icon-action:hover,
    #filesArea .tilefoot .prz-icon-action:hover {
      background:#e9f2ff; border-color:#9cc5f4; color:#115aa3;
    }
    #filesArea .prz-icon-action:focus-visible {
      outline:2px solid #2078c8; outline-offset:2px;
    }
    #filesArea .prz-icon-action svg { width:18px; height:18px; flex:none; }
    #filesArea .prz-icon-action.prz-danger { color:#bb3743; }
    #filesArea .prz-icon-action.prz-danger:hover { background:#fff0f1; border-color:#e9b9bf; }
    #filesArea .actions, #filesArea .tilefoot { gap:7px; }
  `;
  document.head.appendChild(css);
  const icon = label => `<svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">${paths[label]}</svg>`;
  function update(root) {
    const area = document.getElementById('filesArea');
    if (!area) return;
    const actions = area.querySelectorAll('.actions > button.action, .actions > a.action, .tilefoot > button.action, .tilefoot > a.action');
    for (const action of actions) {
      if (action.dataset.przIconized === '1') continue;
      const label = action.textContent.trim().replace(/\s+/g, ' ');
      if (!Object.prototype.hasOwnProperty.call(paths, label)) continue;
      action.dataset.przIconized = '1';
      action.classList.add('prz-icon-action');
      if (label === 'Mover a la papelera') action.classList.add('prz-danger');
      action.title = label;
      action.setAttribute('aria-label', label);
      action.innerHTML = icon(label);
    }
  }
  function start() {
    const area = document.getElementById('filesArea');
    if (!area) return;
    update();
    // Renderizados dinámicos al navegar entre carpetas y cambiar vista.
    new MutationObserver(() => update()).observe(area, {childList:true, subtree:true});
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start, {once:true});
  else start();
})();

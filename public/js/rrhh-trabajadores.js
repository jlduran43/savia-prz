(() => {
'use strict';
const init = () => {
 const original = document.getElementById('directoryAddPerson');
 if (!original || document.getElementById('rrhhServerDialog')) return;
 const api='/rrhh/trabajadores';
 const style=document.createElement('style');
 style.textContent=`#rrhhServerDialog{width:min(850px,94vw);max-height:90vh;border:1px solid #cbd9e8;border-radius:16px;padding:24px;color:#18334e;background:white;box-shadow:0 20px 70px #0004}#rrhhServerDialog::backdrop{background:#142b46a0}#rrhhServerDialog [hidden]{display:none!important}#rrhhServerDialog .rw-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}#rrhhServerDialog label{display:flex;flex-direction:column;gap:5px;font-weight:600;font-size:13px}#rrhhServerDialog input,#rrhhServerDialog select,#rrhhServerDialog textarea{width:100%;box-sizing:border-box;padding:10px;border:1px solid #c5d5e8;border-radius:8px;background:white;color:#18334e}#rrhhServerDialog .rw-full{grid-column:1/-1}#rrhhServerDialog button{padding:10px 15px;border:1px solid #c5d5e8;border-radius:8px;background:#f1f6fc;color:#18334e;cursor:pointer}#rrhhServerDialog button.rw-primary{background:#176b45;color:white;border-color:#176b45}#rrhhServerDialog button:disabled{opacity:.5;cursor:default}#rrhhServerDialog .rw-nav{display:flex;gap:8px;flex-wrap:wrap;margin:16px 0}#rrhhServerDialog .rw-nav span{flex:1;padding:9px;background:#edf3fa;border-radius:8px;text-align:center;font-size:13px}#rrhhServerDialog .rw-nav .active{background:#155a98;color:white}#rrhhServerDialog .rw-footer{display:flex;gap:8px;justify-content:flex-end;margin-top:20px;flex-wrap:wrap}#rrhhServerDialog .rw-error{color:#a12626;white-space:pre-wrap}#rrhhServerDialog .rw-row{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:12px 0;border-bottom:1px solid #dce5ee}#rrhhServerDialog pre{white-space:pre-wrap;overflow-wrap:anywhere;font:inherit}#rrhhServerDialog h2{margin:0 0 8px}#rrhhServerDialog .rw-organizacion>label{grid-column:auto;min-width:0;width:100%;box-sizing:border-box}@media(max-width:600px){#rrhhServerDialog .rw-grid{grid-template-columns:1fr}}`;
 document.head.append(style);
 const d=document.createElement('dialog');d.id='rrhhServerDialog';
 d.innerHTML=`<h2 id="rwTitle">Registrar trabajador</h2><div id="rwMessage" role="status" aria-live="polite" class="rw-error"></div><form id="rwForm" novalidate><div class="rw-nav" id="rwNav"></div><div id="rwFields"></div><div class="rw-footer"><button type="button" id="rwCancel">Cancelar</button><button type="button" id="rwBack">Anterior</button><button type="button" id="rwNext" class="rw-primary">Siguiente</button><button type="submit" id="rwSave" class="rw-primary" hidden>Guardar ficha</button></div></form><section id="rwList" hidden></section>`;
 document.body.append(d); const q=id=>d.querySelector('#'+id), f=q('rwForm'); let step=0,busy=false,users=[],listPage=1;
 const groups=[
 [['usuario_id','Usuario SAVIA PRZ (opcional)','select',false,[]],['nombres','Nombres','text',true,150],['apellido_paterno','Primer apellido','text',true,80],['apellido_materno','Segundo apellido','text',false,80],['rut','RUT','text',true,15],['fecha_nacimiento','Fecha de nacimiento','date'],['email','Correo institucional','email',false,255],['telefono','Teléfono institucional','tel',false,30]],
 [['area','Área de desempeño','select',false,[['PRZ general','PRZ general'],['Administración','Administración'],['Operaciones','Operaciones'],['Museo','Museo'],['Marketing','Marketing'],['Otra','Otra / por revisar']]],['cargo','Cargo o función principal','text',true,255],['dependencia', 'Dependencia', 'text', false, 255],['jefatura_directa','Jefatura directa','text',false,255],['cargo_jefe_inmediato', 'Cargo del jefe inmediato', 'text', false, 255],['numero_dependientes','Número de personas que dependen de este trabajador','number',false],['formacion','Profesión o formación','text',false,255]],
 [['tipo_vinculo','Tipo de contrato','select',true,[['codigo_trabajo','Código del Trabajo'],['honorarios','Honorarios']]],['tipo_contrato','Modalidad de contrato','select',true,[['indefinido','Indefinido'],['plazo_fijo','Plazo fijo']]],['tipo_jornada','Jornada laboral','select',true,[['full_time','Full time'],['part_time','Part time']]],['detalle_part_time','Detalle Part Time','select',true,[['a_llamada','A llamada'],['30_hrs','30 horas'],['20_hrs','20 horas'],['otro','Otro']]],['otra_jornada','Especificar jornada','text',true,110],['estado_vinculo','Estado contractual','select',true,[['vigente','Vigente (confirmado)'],['terminado','Finalizado (confirmado)'],['suspendido','Suspendido (confirmado)'],['por_verificar','Pendiente de documentación']]],['situacion_administrativa','Situación administrativa','select',false,[['normal','En funciones'],['vacaciones','Vacaciones'],['permiso','Permiso'],['licencia','Licencia (sin detalle médico)'],['otra','Otra situación']]],['fecha_ingreso','Fecha de ingreso a la institución','date',true],['fecha_inicio','Fecha de inicio contractual','date',true],['fecha_termino','Fecha de término contractual','date'],['fecha_desvinculacion','Fecha de desvinculación efectiva','date']],
 [['observacion','Observación interna (no se exporta)','textarea',false,1000]]];
 const names=['Datos personales','Organización','Contrato','Confirmación']; const panels=[];
 groups.forEach((group,i)=>{const p=document.createElement('section');p.className='rw-grid';if(i===1)p.classList.add('rw-organizacion');p.hidden=i!==0;panels.push(p);q('rwFields').append(p);group.forEach(([name,label,type,required,extra])=>{const l=document.createElement('label');l.textContent=label;const el=document.createElement(type==='select'?'select':type==='textarea'?'textarea':'input');el.name=name;el.required=!!required;if(el.tagName==='INPUT')el.type=type;if(typeof extra==='number')el.maxLength=extra;if(name==='numero_dependientes'){el.min='0';el.max='65535';el.step='1';el.placeholder='Ej. 0, 3, 10';}if(type==='select'){el.add(new Option(name==='usuario_id'?'Registro manual (sin usuario)':'Seleccione…',''));for(const [v,t] of extra)el.add(new Option(t,v));}if(['usuario_id','nombres','observacion'].includes(name))l.className='rw-full';l.append(el);p.append(l);});});
 const summary=document.createElement('pre');summary.className='rw-full';panels[3].prepend(summary);const senior=document.createElement('p');senior.className='rw-full';panels[2].append(senior);
 const el=n=>f.elements.namedItem(n), val=n=>el(n).value.trim(); const msg=t=>q('rwMessage').textContent=t||'';
 function conditional()
 {
    const codigo=val('tipo_vinculo')==='codigo_trabajo';
    const modalidad=el('tipo_contrato');
    modalidad.disabled=!codigo;
    modalidad.parentElement.hidden=!codigo;
    if(!codigo)modalidad.value='';
    const labor=codigo&&!!val('tipo_contrato');
    const part=labor&&val('tipo_jornada')==='part_time';
    const other=part&&val('detalle_part_time')==='otro';
    for(const [n,show] of [['tipo_jornada',labor],['detalle_part_time',part],['otra_jornada',other],['fecha_termino',val('tipo_contrato')!=='indefinido']])
        {
            const e=el(n);e.disabled=!show;
            e.parentElement.hidden=!show;
            if(!show)
                e.value='';
        }
        el('fecha_termino').required=val('tipo_contrato')==='plazo_fijo';
        senior.textContent = 'Antigüedad institucional: ' +
        calcularAntiguedad(
            val('fecha_ingreso'),
            val('fecha_desvinculacion')
        );
 }
 function showStep(i){step=i;conditional();panels.forEach((p,j)=>p.hidden=j!==i);q('rwNav').replaceChildren(...names.map((n,j)=>{const s=document.createElement('span');s.textContent=(j+1)+'. '+n;if(j===i)s.className='active';return s;}));q('rwBack').hidden=i===0;q('rwNext').hidden=i===3;q('rwSave').hidden=i!==3;if(i===3)summary.textContent=groups.slice(0,3).flat().filter(([n])=>!el(n).disabled&&val(n)).map(([n,l])=>l+': '+(el(n).tagName==='SELECT'?el(n).selectedOptions[0].textContent:val(n))).join('\n');}
 function valid(i){for(const e of panels[i].querySelectorAll('input,select,textarea'))if(!e.disabled&&!e.checkValidity()){showStep(i);e.reportValidity();return false;}return true;}
 async function request(url,opts={}){const headers={'Accept':'application/json',...opts.headers};const meta=document.querySelector('meta[name="csrf-token"]');const cookie=document.cookie.split('; ').find(x=>x.startsWith('XSRF-TOKEN='));if(meta?.content)headers['X-CSRF-TOKEN']=meta.content;else if(cookie)headers['X-XSRF-TOKEN']=decodeURIComponent(cookie.slice(11));const res=await fetch(url,{...opts,headers,credentials:'same-origin',cache:'no-store'});const data=await res.json().catch(()=>null);if(!res.ok||!data||res.redirected){const e=new Error(data?.errors?Object.values(data.errors).flat().join('\n'):res.status===419?'Sesión vencida. Recarga SAVIA PRZ.':res.status===403?'No tienes permiso para gestionar trabajadores.':`Error HTTP ${res.status}. Verifica la sesión y las rutas.`);e.fields=data?.errors||{};throw e;}return data;}
 async function loadUsers(){const select=el('usuario_id');select.disabled=true;try{users=await request('/rrhh/usuarios');if(!Array.isArray(users))throw Error('Formato de usuarios inesperado');select.replaceChildren(new Option('Registro manual (sin usuario)',''),...users.map(u=>new Option([u.nombre||u.name,u.apellido_paterno,u.apellido_materno].filter(Boolean).join(' '),String(u.id))));}catch(e){msg('No se pudo cargar usuarios. Puedes registrar manualmente. '+e.message);}finally{select.disabled=false;}}
 el('usuario_id').addEventListener('change', () => {

    const usuarioId = val('usuario_id');

    const camposAutocompletados = [
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'rut',
        'email',
        'cargo',
        'jefatura_directa'
    ];

    // Si selecciona Registro manual (sin usuario)
    if (!usuarioId) {

        camposAutocompletados.forEach(campo => {

            const input = el(campo);

            if (input) {
                input.value = '';
            }

        });

        conditional();

        return;
    }

    // Buscar usuario seleccionado
    const u = users.find(x =>
        String(x.id) === usuarioId
    );

    if (!u) return;

    // Autocompletar datos del usuario
    const datos = {
        nombres: u.nombre || u.name,
        apellido_paterno: u.apellido_paterno || u.apellido,
        apellido_materno: u.apellido_materno,
        rut: u.rut,
        email: u.email,
        cargo: u.cargo,
        jefatura_directa: u.jefatura
    };

    for (const [campo, valor] of Object.entries(datos)) {

        const input = el(campo);

        if (input) {
            input.value = valor || '';
        }

    }

    conditional();

});
 f.addEventListener('input',conditional);f.addEventListener('change',conditional);q('rwBack').onclick=()=>showStep(step-1);q('rwNext').onclick=()=>{msg('');if(valid(step))showStep(step+1);};q('rwCancel').onclick=()=>{if(!busy)d.close();};d.addEventListener('cancel',e=>{if(busy)e.preventDefault();});
 f.addEventListener('submit',async e=>{e.preventDefault();if(busy||step!==3)return;conditional();for(let i=0;i<4;i++)if(!valid(i))return;const payload={};for(const [n,v] of new FormData(f))payload[n]=v.trim()||null;if(payload.tipo_vinculo==='honorarios')payload.tipo_contrato='honorarios';delete payload.tipo_vinculo;payload.usuario_id=payload.usuario_id?Number(payload.usuario_id):null;payload.numero_dependientes=payload.numero_dependientes===null?null:Number(payload.numero_dependientes);busy=true;q('rwSave').disabled=q('rwCancel').disabled=q('rwBack').disabled=true;msg('Guardando en MySQL…');try{const data=await request(api,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)});if(!data.trabajador?.id)throw Error('Respuesta sin identificador. Revisa el listado antes de repetir.');f.hidden=true;await showList(1,'Trabajador guardado correctamente. ID: '+data.trabajador.id);}catch(err){const field=Object.keys(err.fields||{})[0];const i=groups.findIndex(g=>g.some(x=>x[0]===field));if(i>=0)showStep(i);msg(err.message);}finally{busy=false;q('rwSave').disabled=q('rwCancel').disabled=q('rwBack').disabled=false;}});
 function button(label,fn){const b=document.createElement('button');b.type='button';b.textContent=label;b.onclick=fn;return b;}
 async function showList(page=1,notice=''){listPage=page;f.hidden=true;const host=q('rwList');host.hidden=false;q('rwTitle').textContent='Trabajadores registrados en MySQL';msg(notice);host.replaceChildren(button('Cerrar',()=>d.close()));const body=document.createElement('div');host.append(body);try{const data=await request(api+'?page='+page);if(!Array.isArray(data.data))throw Error('Formato de listado inesperado');if(!data.data.length)body.textContent='No hay trabajadores registrados en MySQL.';for(const t of data.data){const row=document.createElement('div');row.className='rw-row';const text=document.createElement('span');text.textContent=[t.nombres,t.apellido_paterno,t.apellido_materno].filter(Boolean).join(' ')+' · '+t.rut;row.append(text,button('Ver ficha',()=>showDetail(t.id)));body.append(row);}if(data.current_page>1)host.append(button('Anterior',()=>showList(page-1)));if(data.current_page<data.last_page)host.append(button('Siguiente',()=>showList(page+1)));}catch(e){msg((notice?notice+'\n':'')+e.message);}}
 async function showDetail(id){
    const host=q('rwList');
    host.replaceChildren(button('Volver al listado',()=>showList(listPage)),button('Cerrar',()=>d.close()));
    msg('');
    try{
        const {trabajador:t}=await request(api+'/'+encodeURIComponent(id));q('rwTitle').textContent='Ficha registrada · '+id;const pre=document.createElement('pre');const date=v=>v?String(v).slice(0,10):'—';
        pre.textContent=[...groups[0].filter(x=>x[0]!=='usuario_id').map(([n,l])=>l+': '+(t[n]||'—')), 'Formación: '+(t.formacion||'—'),'Observación interna: '+(t.observacion||'—'),'','CONTRATOS',...(t.contratos||[]).map(c=>[c.tipo_contrato,c.cargo,c.area,date(c.fecha_inicio),date(c.fecha_termino),'Dependencia: ' + (t.dependencia || '—'),'Cargo del jefe inmediato: ' + (t.cargo_jefe_inmediato || '—'),'Personas a cargo: '+(c.numero_dependientes??'No informado')].filter(Boolean).join(' · ')),'','HISTORIAL LABORAL',...(t.historial_laboral||[]).map(h=>date(h.fecha_efectiva)+' · '+h.tipo_movimiento+' · '+(h.motivo||''))].join('\n');
        host.append(pre);}catch(e){msg(e.message);
        }}
 function openNew(){if(d.open)return;f.reset();f.hidden=false;q('rwList').hidden=true;q('rwTitle').textContent='Registrar trabajador';msg('');showStep(0);d.showModal();loadUsers();}
 original.addEventListener('click',e=>{e.preventDefault();e.stopImmediatePropagation();openNew();},true);
 const listButton=document.createElement('button');listButton.type='button';listButton.className=original.className;listButton.textContent='Trabajadores en MySQL';listButton.addEventListener('click',()=>{if(!d.open)d.showModal();showList();});original.after(listButton);
};
if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',init,{once:true});else init();
})();

function calcularAntiguedad(fechaIngreso, fechaDesvinculacion = null) {

    if (!fechaIngreso) {
        return 'No calculada';
    }

    const inicio = new Date(fechaIngreso + 'T00:00:00');
    const hoy = new Date();

    hoy.setHours(0, 0, 0, 0);

    if (Number.isNaN(inicio.getTime())) {
        return 'Fecha incorrecta';
    }

    if (inicio > hoy) {
        return 'Pendiente de ingreso';
    }

    let fin = hoy;

    if (fechaDesvinculacion) {
        const desvinculacion = new Date(
            fechaDesvinculacion + 'T00:00:00'
        );

        if (Number.isNaN(desvinculacion.getTime()) ||
            desvinculacion < inicio) {
            return 'Fecha incorrecta';
        }

        if (desvinculacion <= hoy) {
            fin = desvinculacion;
        }
    }

    function sumarMeses(fecha, cantidad) {
        const resultado = new Date(fecha.getTime());
        const dia = resultado.getDate();

        resultado.setDate(1);
        resultado.setMonth(resultado.getMonth() + cantidad);

        const ultimoDia = new Date(
            resultado.getFullYear(),
            resultado.getMonth() + 1,
            0
        ).getDate();

        resultado.setDate(Math.min(dia, ultimoDia));

        return resultado;
    }

    let totalMeses =
        (fin.getFullYear() - inicio.getFullYear()) * 12 +
        fin.getMonth() - inicio.getMonth();

    if (sumarMeses(inicio, totalMeses) > fin) {
        totalMeses--;
    }

    const anios = Math.floor(totalMeses / 12);
    const meses = totalMeses % 12;

    const referencia = sumarMeses(inicio, totalMeses);

    const dias = Math.round(
        (
            Date.UTC(
                fin.getFullYear(),
                fin.getMonth(),
                fin.getDate()
            ) -
            Date.UTC(
                referencia.getFullYear(),
                referencia.getMonth(),
                referencia.getDate()
            )
        ) / 86400000
    );

    return [
        `${anios} ${anios === 1 ? 'año' : 'años'}`,
        `${meses} ${meses === 1 ? 'mes' : 'meses'}`,
        `${dias} ${dias === 1 ? 'día' : 'días'}`
    ].join(', ');
}

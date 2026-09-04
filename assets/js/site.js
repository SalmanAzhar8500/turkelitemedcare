
(function(){
  const qs=(s,r=document)=>r.querySelector(s), qsa=(s,r=document)=>[...r.querySelectorAll(s)];
  const mobile=qs('.mobile-toggle'), nav=qs('.main-nav');
  if(mobile){mobile.addEventListener('click',()=>{const o=nav.classList.toggle('open');mobile.setAttribute('aria-expanded',String(o));});}
  qsa('.mega-toggle').forEach(b=>b.addEventListener('click',e=>{e.preventDefault();e.stopPropagation();const item=b.closest('.has-mega');const open=item.classList.toggle('open');b.setAttribute('aria-expanded',String(open));}));
  const modal=qs('.search-modal'), open=qs('.search-toggle'), close=qs('.search-close'), input=qs('.search-input'), results=qs('.search-results');
  function hrefFor(url){return (window.SITE_PREFIX||'')+url;}
  function renderSearch(q=''){
    if(!results)return; const text=q.trim().toLowerCase();
    if(!text){results.innerHTML='<div style="padding:18px;color:#6f828c;font-size:12px">Search across specialties, procedures, clinics and doctors.</div>';return;}
    const hits=(window.SEARCH_INDEX||[]).filter(x=>(x.title+' '+x.type+' '+(x.terms||'')).toLowerCase().includes(text)).slice(0,16);
    const escHtml=t=>String(t).replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    results.innerHTML=hits.length?hits.map(x=>`<a class="search-result" href="${hrefFor(x.url)}"><span><b>${escHtml(x.title)}</b><small>${escHtml(x.type)}</small></span><span>→</span></a>`).join(''):'<div style="padding:18px;color:#6f828c;font-size:12px">No matching page found.</div>';
  }
  if(open&&modal){open.addEventListener('click',()=>{modal.classList.add('open');modal.setAttribute('aria-hidden','false');renderSearch();setTimeout(()=>input&&input.focus(),50)});}
  if(close&&modal){close.addEventListener('click',()=>{modal.classList.remove('open');modal.setAttribute('aria-hidden','true')});}
  if(modal){modal.addEventListener('click',e=>{if(e.target===modal)close.click()});}
  if(input){input.addEventListener('input',e=>renderSearch(e.target.value));}
  qsa('.demo-form').forEach(form=>form.addEventListener('submit',e=>{e.preventDefault();form.querySelectorAll('input,select,textarea').forEach(function(f){if(f.type!=='hidden'&&f.type!=='checkbox'){f.value='';}});alert('This enquiry has not been sent — the form is not yet connected to the coordination system. No details were stored.');}));
  const city=qs('#clinic-city'), spec=qs('#clinic-specialty'), reset=qs('#clinic-reset');
  function filterClinics(){qsa('#clinic-grid .clinic-card').forEach(c=>{const okCity=!city.value||c.dataset.city===city.value;const okSpec=!spec.value||(c.dataset.specs||'').includes(spec.value);c.style.display=okCity&&okSpec?'':'none';});}
  if(city)city.addEventListener('change',filterClinics); if(spec)spec.addEventListener('change',filterClinics); if(reset)reset.addEventListener('click',()=>{city.value='';spec.value='';filterClinics();});
  const intake=qs('#treatment-form');
  if(intake){
    let step=1; const steps=qsa('.form-step',intake), dots=qsa('.form-progress span',intake);
    function show(n){step=n;steps.forEach(x=>x.classList.toggle('active',+x.dataset.step===n));dots.forEach((d,i)=>d.classList.toggle('active',i<n));}
    qsa('.next-step',intake).forEach(b=>b.addEventListener('click',()=>{const pane=qs(`.form-step[data-step="${step}"]`,intake);const req=qsa('[required]',pane);if(req.some(x=>!x.checkValidity())){req.find(x=>!x.checkValidity()).reportValidity();return;} show(Math.min(3,step+1));}));
    qsa('.prev-step',intake).forEach(b=>b.addEventListener('click',()=>show(Math.max(1,step-1))));
    const params=new URLSearchParams(location.search), proc=params.get('procedure'); if(proc){const el=qs('#procedure-input');if(el)el.value=proc.split('-').map(s=>s.charAt(0).toUpperCase()+s.slice(1)).join(' ');}
    intake.addEventListener('submit',e=>{e.preventDefault();qsa('.form-step',intake).forEach(s=>s.classList.remove('active'));qs('.form-progress',intake).style.display='none';qs('.form-success',intake).hidden=false;});
  }
  // Load optional video map dynamically via a script tag, then mount iframe if an ID exists.
  if(qs('.video-module')){
    const s=document.createElement('script');s.src=(window.SITE_PREFIX||'')+'assets/js/video-map.js';s.onload=()=>{/* iframes are mounted on click by v7-enhancements.js */};document.head.appendChild(s);
  }
})();

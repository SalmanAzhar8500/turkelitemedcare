(function(){
  'use strict';
  const qs=(s,r=document)=>r.querySelector(s), qsa=(s,r=document)=>[...r.querySelectorAll(s)];
  const copy=window.SITE_RUNTIME_COPY||{};

  // Mobile navigation.
  const mobile=qs('.mobile-toggle'), nav=qs('.main-nav');
  if(mobile&&nav){
    mobile.addEventListener('click',()=>{
      const opened=nav.classList.toggle('open');
      mobile.setAttribute('aria-expanded',String(opened));
    });
  }

  // Treatments mega-menu. The parent link remains a normal link; the adjacent
  // disclosure button owns menu expansion so keyboard and touch users have both choices.
  qsa('.mega-toggle').forEach(button=>{
    button.addEventListener('click',event=>{
      event.preventDefault(); event.stopPropagation();
      const item=button.closest('.has-mega');
      const opened=item.classList.toggle('open');
      button.setAttribute('aria-expanded',String(opened));
    });
  });
  document.addEventListener('keydown',event=>{
    if(event.key!=='Escape') return;
    qsa('.has-mega.open').forEach(item=>{
      item.classList.remove('open');
      const toggle=qs('.mega-toggle',item); if(toggle) toggle.setAttribute('aria-expanded','false');
    });
  });
  document.addEventListener('click',event=>{
    qsa('.has-mega.open').forEach(item=>{
      if(item.contains(event.target)) return;
      item.classList.remove('open');
      const toggle=qs('.mega-toggle',item); if(toggle) toggle.setAttribute('aria-expanded','false');
    });
  });

  // Global search: locale-aware and keyboard accessible.
  const modal=qs('.search-modal'), searchToggle=qs('.search-toggle'), close=qs('.search-close'), input=qs('.search-input'), results=qs('.search-results');
  let returnFocus=null;
  function hrefFor(url){
    let path=String(url||'').replace(/^\/+/, '');
    path=path.replace(/\/index\.html$/,'').replace(/\.html$/,'');
    const locale=(window.SITE_LOCALE||document.documentElement.lang||'en').toLowerCase();
    const prefix=locale==='en'?'':locale+'/';
    return new URL(prefix+path, window.location.origin+'/').toString();
  }
  const escHtml=t=>String(t).replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  function renderSearch(q=''){
    if(!results) return;
    const text=q.trim().toLowerCase();
    if(!text){results.innerHTML=`<div class="search-empty">${escHtml(copy.searchPrompt||'Search treatments, specialties and provider profiles.')}</div>`;return;}
    const locale=(window.SITE_LOCALE||document.documentElement.lang||'en').toLowerCase();
    const hits=(window.SEARCH_INDEX||[])
      .filter(x=>(!x.locales||x.locales.includes(locale))&&(x.title+' '+x.type+' '+(x.terms||'')).toLowerCase().includes(text))
      .slice(0,16);
    results.innerHTML=hits.length
      ? hits.map(x=>`<a class="search-result" href="${hrefFor(x.url)}"><span><b>${escHtml(x.title)}</b><small>${escHtml(x.type)}</small></span><span aria-hidden="true">→</span></a>`).join('')
      : `<div class="search-empty">${escHtml(copy.noResults||'No matching page found.')}</div>`;
  }
  function setModal(opened){
    if(!modal) return;
    modal.classList.toggle('open',opened);
    modal.setAttribute('aria-hidden',opened?'false':'true');
    if('inert' in modal) modal.inert=!opened;
    if(searchToggle) searchToggle.setAttribute('aria-expanded',opened?'true':'false');
    document.body.style.overflow=opened?'hidden':'';
    if(opened){returnFocus=document.activeElement;renderSearch(input?.value||'');setTimeout(()=>input?.focus(),40);}
    else if(returnFocus&&returnFocus.focus){returnFocus.focus();}
  }
  if(modal){modal.setAttribute('role','dialog');modal.setAttribute('aria-modal','true');modal.setAttribute('aria-hidden','true');if('inert' in modal) modal.inert=true;}
  if(searchToggle&&modal){searchToggle.setAttribute('aria-haspopup','dialog');searchToggle.setAttribute('aria-expanded','false');searchToggle.addEventListener('click',()=>setModal(true));}
  close?.addEventListener('click',()=>setModal(false));
  modal?.addEventListener('click',event=>{if(event.target===modal)setModal(false)});
  input?.addEventListener('input',event=>renderSearch(event.target.value));
  document.addEventListener('keydown',event=>{
    if(event.key==='Escape'&&modal?.classList.contains('open')){event.preventDefault();setModal(false);return;}
    if(event.key!=='Tab'||!modal?.classList.contains('open')) return;
    const focusable=qsa('a[href],button:not([disabled]),input:not([disabled]),select:not([disabled]),textarea:not([disabled]),[tabindex]:not([tabindex="-1"])',modal).filter(el=>!el.hidden);
    if(!focusable.length)return;
    const first=focusable[0],last=focusable[focusable.length-1];
    if(event.shiftKey&&document.activeElement===first){event.preventDefault();last.focus();}
    else if(!event.shiftKey&&document.activeElement===last){event.preventDefault();first.focus();}
  });

  // Legacy clinic filters are retained for older compatible templates.
  const city=qs('#clinic-city'), spec=qs('#clinic-specialty'), reset=qs('#clinic-reset');
  function filterClinics(){qsa('#clinic-grid .clinic-card').forEach(card=>{const okCity=!city?.value||card.dataset.city===city.value;const okSpec=!spec?.value||(card.dataset.specs||'').includes(spec.value);card.style.display=okCity&&okSpec?'':'none';});}
  city?.addEventListener('change',filterClinics); spec?.addEventListener('change',filterClinics); reset?.addEventListener('click',()=>{if(city)city.value='';if(spec)spec.value='';filterClinics();});
})();

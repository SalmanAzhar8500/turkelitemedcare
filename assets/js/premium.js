(function(){
  'use strict';
  const qs=(s,r=document)=>r.querySelector(s), qsa=(s,r=document)=>[...r.querySelectorAll(s)];

  // Sticky-header state
  const header=qs('.site-header');
  const setHeader=()=>header&&header.classList.toggle('is-scrolled',window.scrollY>20);
  setHeader(); window.addEventListener('scroll',setHeader,{passive:true});

  // Hero slider
  const slider=qs('.js-hero-slider');
  if(slider){
    const slides=qsa('.doctor-slide',slider), dots=qsa('.hero-dot',slider); let index=0, timer;
    const show=i=>{index=(i+slides.length)%slides.length;slides.forEach((s,n)=>s.classList.toggle('is-active',n===index));dots.forEach((d,n)=>{d.classList.toggle('is-active',n===index);d.setAttribute('aria-current',n===index?'true':'false')});};
    const next=()=>show(index+1); const play=()=>{clearInterval(timer);timer=setInterval(next,6500)};
    qs('[data-hero-next]',slider)?.addEventListener('click',()=>{next();play()});
    qs('[data-hero-prev]',slider)?.addEventListener('click',()=>{show(index-1);play()});
    dots.forEach((d,n)=>d.addEventListener('click',()=>{show(n);play()}));
    slider.addEventListener('mouseenter',()=>clearInterval(timer));slider.addEventListener('mouseleave',play);
    show(0);play();
  }

  // Gallery filter
  qsa('[data-gallery-filter]').forEach(btn=>btn.addEventListener('click',()=>{
    const value=btn.dataset.galleryFilter;
    qsa('[data-gallery-filter]').forEach(b=>b.classList.toggle('is-active',b===btn));
    qsa('[data-gallery-item]').forEach(item=>item.classList.toggle('is-hidden',value!=='all'&&!item.dataset.galleryItem.includes(value)));
  }));

  // A quiet, scrollbar-free specialist carousel with drag, touch and reduced-motion support.
  qsa('[data-doctor-carousel]').forEach(carousel=>{
    const track=qs('.doctor-carousel-track',carousel), progress=qs('[data-doctor-carousel-progress] span',carousel); if(!track) return;
    let startX=0,startScroll=0,dragged=false,timer;
    const maxScroll=()=>Math.max(track.scrollWidth-track.clientWidth,0);
    const updateProgress=()=>{const max=maxScroll();if(progress){progress.style.width=max?`${Math.max(8,(track.scrollLeft/max)*100)}%`:'100%';progress.parentElement.hidden=!max;}};
    const cardStep=()=>track.querySelector('.template-doctor-link, .template-doctor')?.getBoundingClientRect().width+20||300;
    const stop=()=>clearInterval(timer);
    const play=()=>{stop();if(matchMedia('(prefers-reduced-motion: reduce)').matches||!maxScroll())return;timer=setInterval(()=>{const next=track.scrollLeft+cardStep();track.scrollTo({left:next>=maxScroll()-2?0:next,behavior:'smooth'});},5000);};
    track.addEventListener('pointerdown',event=>{stop();startX=event.clientX;startScroll=track.scrollLeft;dragged=false;track.setPointerCapture(event.pointerId);track.classList.add('is-dragging');});
    track.addEventListener('pointermove',event=>{if(!track.classList.contains('is-dragging'))return;const distance=event.clientX-startX;if(Math.abs(distance)>4)dragged=true;track.scrollLeft=startScroll-distance;});
    const stopDrag=()=>{track.classList.remove('is-dragging');play();};
    track.addEventListener('pointerup',stopDrag);track.addEventListener('pointercancel',stopDrag);
    track.addEventListener('click',event=>{if(dragged){event.preventDefault();event.stopPropagation();dragged=false;}},true);
    track.addEventListener('scroll',updateProgress,{passive:true});
    carousel.addEventListener('mouseenter',stop);carousel.addEventListener('mouseleave',play);carousel.addEventListener('focusin',stop);carousel.addEventListener('focusout',play);
    window.addEventListener('resize',()=>{updateProgress();play();},{passive:true});
    updateProgress();play();
  });

  // Testimonial carousel
  const tStage=qs('.testimonial-stage');
  if(tStage){
    const items=qsa('.testimonial-item',tStage), nav=qsa('[data-testimonial]'); let ti=0, tt;
    const setT=i=>{ti=(i+items.length)%items.length;items.forEach((x,n)=>x.classList.toggle('is-active',n===ti));nav.forEach((x,n)=>x.classList.toggle('is-active',n===ti));};
    const run=()=>{clearInterval(tt);tt=setInterval(()=>setT(ti+1),5600)};
    nav.forEach((x,n)=>x.addEventListener('click',()=>{setT(n);run()}));setT(0);run();
  }

  // Scroll reveals, disabled for reduced motion
  if(!matchMedia('(prefers-reduced-motion: reduce)').matches && 'IntersectionObserver' in window){
    const io=new IntersectionObserver(entries=>entries.forEach(e=>{if(e.isIntersecting){e.target.classList.add('revealed');io.unobserve(e.target)}}),{threshold:.08});
    qsa('[data-reveal]').forEach(x=>io.observe(x));
  }else qsa('[data-reveal]').forEach(x=>x.classList.add('revealed'));

  // Back to top
  const back=document.createElement('button');back.className='back-to-top';back.type='button';back.setAttribute('aria-label','Back to top');back.innerHTML='↑';document.body.appendChild(back);
  const setBack=()=>back.classList.toggle('show',window.scrollY>650);setBack();window.addEventListener('scroll',setBack,{passive:true});back.addEventListener('click',()=>scrollTo({top:0,behavior:'smooth'}));

  // Demo quick forms route into the treatment-plan flow instead of pretending to submit medical data.
  qsa('.home-route-form').forEach(form=>form.addEventListener('submit',e=>{
    e.preventDefault();
    const fd=new FormData(form), params=new URLSearchParams();
    const specialty=fd.get('specialty'); if(specialty) params.set('specialty',String(specialty));
    location.href='treatment-plan.html'+(params.toString()?'?'+params.toString():'');
  }));
})();

// Query-string handoff into the treatment request form.
(function(){
  const form=document.querySelector('#treatment-form'); if(!form) return;
  const params=new URLSearchParams(location.search);
  const map={
    'Dental':'dental','Hair Restoration':'hair-restoration','Plastic & Cosmetic Surgery':'cosmetic-surgery',
    'Bariatric & Weight Loss':'bariatric-surgery','Eye Care':'eye-care','Orthopedics':'orthopedics','ENT':'ent',
    'Urology':'urology','Fertility & Reproductive Medicine':'fertility','General Surgery':'general-surgery'
  };
  const sp=params.get('specialty');
  if(sp){const sel=document.querySelector('#intake-specialty'); if(sel){const v=map[sp]||sp; if([...sel.options].some(o=>o.value===v)) sel.value=v;}}
  const proc=params.get('procedure');
  if(proc){const input=document.querySelector('#procedure-input'); if(input) input.value=proc.replace(/-/g,' ').replace(/\b\w/g,c=>c.toUpperCase());}
  ['name','email','phone','country'].forEach(key=>{const v=params.get(key); if(!v) return; const field=form.elements.namedItem(key); if(field) field.value=v;});
  const clinic=params.get('clinic'), doctor=params.get('doctor');
  if(clinic||doctor){
    const ref=document.createElement('input');ref.type='hidden';ref.name='provider_reference';ref.value=clinic?`clinic:${clinic}`:`doctor:${doctor}`;form.appendChild(ref);
    const step=document.querySelector('.form-step[data-step="1"]');
    if(step){const note=document.createElement('div');note.className='notice';note.innerHTML=`<b>Provider reference included</b><p>Your request is linked to the ${clinic?'clinic':'doctor'} profile you selected. You can still change specialty or procedure before submitting.</p>`;step.insertBefore(note,step.querySelector('.step-actions'));}
  }
})();

// V5 consultation handoff: preserve procedure/contact fields into the treatment-plan URL.
document.querySelectorAll('.consultation-mini-form').forEach(function(form){form.addEventListener('submit',function(e){e.preventDefault();var fd=new FormData(form),p=new URLSearchParams();fd.forEach(function(v,k){if(v)p.set(k,String(v));});var prefix=window.SITE_PREFIX||'';location.href=prefix+'treatment-plan.html?'+p.toString();});});

/* GKA motion layer: header states, hero slideshow, smooth scroll and scroll-driven effects.
   Everything here is progressive: without JS (or with reduced motion) the page is complete and static. */
(() => {
  const root = document.documentElement;
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const fine = matchMedia('(hover: hover) and (pointer: fine)').matches;
  const $ = (s, c = document) => c.querySelector(s);
  const $$ = (s, c = document) => [...c.querySelectorAll(s)];

  /* ---------- header: pill when scrolled, hide on scroll down ---------- */
  const hdr = $('.gka-header-wrap');
  if (hdr) {
    let lastY = scrollY;
    const onScroll = () => {
      const y = scrollY;
      hdr.classList.toggle('is-scrolled', y > 40);
      const drawerOpen = root.classList.contains('gka-lock');
      const menuOpen = hdr.matches(':focus-within');
      hdr.classList.toggle('is-hidden', !drawerOpen && !menuOpen && y > 480 && y > lastY + 2);
      if (y < lastY - 2 || y < 480) hdr.classList.remove('is-hidden');
      lastY = y;
    };
    addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ---------- intro curtain (first homepage visit per session) ---------- */
  const loader = $('.gka-loader');
  const loaderDone = new Promise((resolve) => {
    if (!loader || !root.classList.contains('gka-preload')) { loader?.remove(); resolve(); return; }
    const num = $('b', loader);
    const mark = $('img', loader);
    const t0 = performance.now();
    const dur = 1100;
    mark.animate([{ opacity: 0, transform: 'translateY(12px)' }, { opacity: 1, transform: 'none' }], { duration: 700, easing: 'cubic-bezier(.16,1,.3,1)', fill: 'forwards' });
    const tick = (t) => {
      const p = Math.min(1, (t - t0) / dur);
      num.textContent = String(Math.round((1 - Math.pow(1 - p, 3)) * 100)).padStart(3, '0');
      if (p < 1) { requestAnimationFrame(tick); return; }
      loader.animate([{ clipPath: 'inset(0 0 0 0)' }, { clipPath: 'inset(0 0 100% 0)' }], { duration: 900, easing: 'cubic-bezier(.76,0,.24,1)', fill: 'forwards' })
        .finished.then(() => loader.remove());
      setTimeout(resolve, 250);
    };
    requestAnimationFrame(tick);
    try { sessionStorage.setItem('gka-intro', '1'); } catch (e) { /* private mode */ }
  });

  /* ---------- hero slideshow ---------- */
  const hero = $('.gka-hero');
  if (hero) {
    const slides = $$('.gka-hero-slide', hero);
    const bars = $$('.gka-hero-bars i', hero);
    const idx = $('.gka-hero-idx', hero);
    const cap = $('.gka-hero-cap', hero);
    const DUR = 6500;
    hero.style.setProperty('--gka-slide', `${DUR}ms`);
    let i = 0;
    let timer = null;
    let visible = true;
    const go = (n) => {
      slides[i].classList.remove('is-active');
      i = (n + slides.length) % slides.length;
      const img = slides[i];
      if (img.loading === 'lazy') img.loading = 'eager';
      img.classList.add('is-active');
      bars.forEach((b, k) => {
        b.classList.remove('is-active');
        b.classList.toggle('is-done', k < i);
      });
      void bars[i]?.offsetWidth; // restart the bar animation
      bars[i]?.classList.add('is-active');
      if (idx) idx.textContent = String(i + 1).padStart(2, '0');
      if (cap) {
        cap.style.opacity = '0';
        setTimeout(() => { cap.textContent = img.dataset.caption || ''; cap.style.opacity = ''; }, 250);
      }
    };
    const play = () => { stop(); if (!reduce && visible && !document.hidden && slides.length > 1) timer = setInterval(() => go(i + 1), DUR); };
    const stop = () => { clearInterval(timer); timer = null; };
    // Preload the next slides after the first paint so the crossfade never shows a blank frame.
    addEventListener('load', () => slides.slice(1).forEach((s) => { s.loading = 'eager'; }), { once: true });
    if (!reduce && slides.length > 1) {
      loaderDone.then(() => { go(0); play(); });
      document.addEventListener('visibilitychange', play);
      new IntersectionObserver(([e]) => { visible = e.isIntersecting; visible ? play() : stop(); }).observe(hero);
    }
  }

  /* ---------- business index: floating image preview ---------- */
  const index = $('.gka-index');
  if (index && fine) {
    const box = document.createElement('div');
    box.className = 'gka-follow';
    box.setAttribute('aria-hidden', 'true');
    box.innerHTML = '<img alt="">';
    document.body.appendChild(box);
    const img = $('img', box);
    let x = 0, y = 0, cx = 0, cy = 0, raf = 0;
    const loop = () => {
      cx += (x - cx) * 0.16; cy += (y - cy) * 0.16;
      box.style.left = `${cx}px`; box.style.top = `${cy}px`;
      raf = Math.abs(x - cx) + Math.abs(y - cy) > 0.3 ? requestAnimationFrame(loop) : 0;
    };
    index.addEventListener('pointermove', (e) => { x = e.clientX + 24; y = e.clientY; if (!raf) raf = requestAnimationFrame(loop); });
    $$('li', index).forEach((li) => {
      const src = $('figure img', li)?.currentSrc || $('figure img', li)?.src;
      if (!src) return;
      li.addEventListener('pointerenter', (e) => {
        if (!box.classList.contains('is-on')) { cx = x = e.clientX + 24; cy = y = e.clientY; loop(); }
        img.src = src;
        box.classList.add('is-on');
      });
      li.addEventListener('pointerleave', () => box.classList.remove('is-on'));
    });
    addEventListener('scroll', () => box.classList.remove('is-on'), { passive: true });
  }

  /* ---------- magnetic buttons ---------- */
  if (fine && !reduce) {
    $$('.gka-magnet').forEach((el) => {
      el.addEventListener('pointermove', (e) => {
        const r = el.getBoundingClientRect();
        el.style.transform = `translate(${(e.clientX - r.left - r.width / 2) * 0.22}px, ${(e.clientY - r.top - r.height / 2) * 0.3}px)`;
      });
      el.addEventListener('pointerleave', () => { el.style.transform = ''; });
    });
  }

  /* ---------- counters (no-GSAP path too) ---------- */
  const fmt = (n, raw) => (raw.length === 4 && raw.startsWith('20') ? String(n) : n.toLocaleString('id-ID'));
  const counters = $$('.gka-count');
  const runCount = (el) => {
    const raw = el.dataset.to;
    const to = +raw;
    if (reduce || !to) return;
    const from = raw.length === 4 && raw.startsWith('20') ? to - 16 : 0;
    const t0 = performance.now();
    const step = (t) => {
      const p = Math.min(1, (t - t0) / 1600);
      el.textContent = fmt(Math.round(from + (to - from) * (1 - Math.pow(1 - p, 4))), raw);
      if (p < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
  };
  if (counters.length && 'IntersectionObserver' in window) {
    const cio = new IntersectionObserver((es) => es.forEach((e) => { if (e.isIntersecting) { runCount(e.target); cio.unobserve(e.target); } }), { rootMargin: '0px 0px -15% 0px' });
    counters.forEach((c) => cio.observe(c));
  }

  /* ---------- GSAP + Lenis ---------- */
  const { gsap, ScrollTrigger, Lenis } = window;
  if (reduce || !gsap || !ScrollTrigger) return;
  gsap.registerPlugin(ScrollTrigger);

  if (Lenis) {
    const lenis = new Lenis({ duration: 1.15, easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)), smoothWheel: true });
    window.gkaLenis = lenis;
    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add((t) => lenis.raf(t * 1000));
    gsap.ticker.lagSmoothing(0);
    // In-page anchors go through Lenis so they ease like the wheel does.
    document.addEventListener('click', (e) => {
      const a = e.target.closest('a[href^="#"]');
      if (!a || a.getAttribute('href').length < 2) return;
      const t = document.querySelector(a.getAttribute('href'));
      if (t) { e.preventDefault(); lenis.scrollTo(t, { offset: -20 }); }
    });
  }

  // Hero: image drifts slower than the page, copy lifts and fades.
  if (hero) {
    gsap.to('.gka-hero-media', { yPercent: 18, scale: 1.06, ease: 'none', scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: true } });
    gsap.to(['.gka-hero-body', '.gka-hero-foot'], { y: -80, opacity: 0, ease: 'none', scrollTrigger: { trigger: hero, start: 'top top', end: '70% top', scrub: true } });
  }

  // Statement: words light up as they scroll through the viewport.
  $$('.gka-scrub').forEach((p) => {
    // Words stay in the accessibility tree (a <p> cannot carry aria-label); only their opacity changes.
    const walk = (node) => [...node.childNodes].forEach((c) => {
      if (c.nodeType === 3) {
        const frag = document.createDocumentFragment();
        c.textContent.split(/(\s+)/).forEach((part) => {
          if (!part) return;
          if (/^\s+$/.test(part)) { frag.appendChild(document.createTextNode(' ')); return; }
          const s = document.createElement('span');
          s.className = 'gka-sw';
          s.textContent = part;
          frag.appendChild(s);
        });
        c.replaceWith(frag);
      } else if (c.nodeType === 1 && c.tagName === 'IMG') {
        c.classList.add('gka-sw');
      } else if (c.nodeType === 1) {
        walk(c);
      }
    });
    walk(p);
    p.classList.add('gka-scrub-on');
    gsap.to($$('.gka-sw', p), { opacity: 1, stagger: 0.1, ease: 'none', scrollTrigger: { trigger: p, start: 'top 82%', end: 'bottom 42%', scrub: 0.6 } });
  });

  const mm = gsap.matchMedia();

  // Process: vertical scroll drives the track sideways while the rail is pinned.
  mm.add('(min-width: 1024px)', () => {
    $$('.gka-hscroll').forEach((wrap) => {
      const track = $('.gka-track', wrap);
      const bar = $('.gka-hbar i', wrap);
      wrap.classList.add('is-pinned');
      const dist = () => Math.max(0, track.scrollWidth - wrap.clientWidth);
      const tl = gsap.timeline({ scrollTrigger: { trigger: wrap, start: 'center center', end: () => `+=${dist()}`, pin: true, scrub: 0.8, invalidateOnRefresh: true, anticipatePin: 1 } });
      tl.to(track, { x: () => -dist(), ease: 'none' }, 0);
      if (bar) tl.to(bar, { scaleX: 1, ease: 'none' }, 0);
      $$('.gka-panel-img img', wrap).forEach((img) => tl.fromTo(img, { xPercent: -6 }, { xPercent: 6, ease: 'none' }, 0));
      return () => { wrap.classList.remove('is-pinned'); gsap.set(track, { clearProps: 'transform' }); };
    });
  });

  // Product cards: curtain reveal from the bottom edge.
  const cards = $$('.gka-home .gka-pcard, .gka-prod .gka-pcard');
  if (cards.length) {
    gsap.set(cards, { clipPath: 'inset(22% 0% 0% 0% round 28px)' });
    ScrollTrigger.batch(cards, {
      start: 'top 88%',
      once: true,
      onEnter: (els) => gsap.to(els, { clipPath: 'inset(0% 0% 0% 0% round 28px)', duration: 1.3, ease: 'expo.out', stagger: 0.12 }),
    });
  }

  // ESG landscape parallax.
  $$('.gka-esg-media').forEach((m) => gsap.fromTo(m, { yPercent: -8 }, { yPercent: 8, ease: 'none', scrollTrigger: { trigger: m.parentElement, start: 'top bottom', end: 'bottom top', scrub: true } }));

  // Footer wordmark rises letter-group by letter-group.
  const wm = $('.gka-wordmark');
  if (wm) {
    wm.setAttribute('aria-hidden', 'true');
    const parts = [];
    [...wm.childNodes].forEach((n) => {
      const wrap = (txt, host) => txt.split(/(\s+)/).forEach((w) => {
        if (!w) return;
        if (/^\s+$/.test(w)) { host.appendChild(document.createTextNode(' ')); return; }
        const s = document.createElement('span'); s.className = 'gka-wl'; s.textContent = w; host.appendChild(s); parts.push(s);
      });
      if (n.nodeType === 3) { const f = document.createDocumentFragment(); wrap(n.textContent, f); n.replaceWith(f); }
      else if (n.nodeType === 1) { const t = n.textContent; n.textContent = ''; wrap(t, n); }
    });
    gsap.from(parts, { yPercent: 105, duration: 1.2, ease: 'expo.out', stagger: 0.08, scrollTrigger: { trigger: wm, start: 'top 95%' } });
  }

  // Images and fonts change layout height; recompute trigger positions once they settle.
  addEventListener('load', () => ScrollTrigger.refresh());
  document.fonts?.ready.then(() => ScrollTrigger.refresh());
})();

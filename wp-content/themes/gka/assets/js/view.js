(() => {
  const norm = (p) => (p.replace(/\/+$/, '') || '') + '/';
  const here = norm(location.pathname);
  const isCurrent = (href) => {
    const p = norm(new URL(href, location.origin).pathname);
    return p === here || (p !== '/' && here.startsWith(p));
  };

  // Desktop menu: mark current item and its parent.
  document.querySelectorAll('.gka-menu .wp-block-navigation-item__content[href]').forEach((a) => {
    if (!isCurrent(a.href)) return;
    const li = a.closest('.wp-block-navigation-item');
    li.classList.add('is-current');
    li.parentElement.closest('.wp-block-navigation-item')?.classList.add('is-current');
  });

  // Mobile drawer, built from the desktop menu so there is one source of truth.
  const menu = document.querySelector('.gka-menu .wp-block-navigation__container');
  const burgers = document.querySelectorAll('.gka-burger');
  if (menu && burgers.length) {
    const esc = (s) => s.replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));
    const label = (a) => esc(a.textContent.trim());
    const items = [...menu.children].map((li) => {
      const a = li.querySelector(':scope > a, :scope > .wp-block-navigation-item__content');
      const subs = [...li.querySelectorAll(':scope .wp-block-navigation__submenu-container a')];
      if (!a) return '';
      const cur = isCurrent(a.href) || subs.some((s) => isCurrent(s.href));
      if (!subs.length) {
        return `<li><a class="gka-d-link${cur ? ' is-current' : ''}" href="${a.href}"${cur ? ' aria-current="page"' : ''}>${label(a)}</a></li>`;
      }
      const subItems = subs.map((s) => `<li><a href="${s.href}"${isCurrent(s.href) && norm(new URL(s.href).pathname) === here ? ' aria-current="page"' : ''}>${label(s)}</a></li>`).join('');
      return `<li><details${cur ? ' open' : ''}><summary class="gka-d-link${cur ? ' is-current' : ''}">${label(a)}<span class="gka-chev" aria-hidden="true"></span></summary><ul>${subItems}</ul></details></li>`;
    }).join('');

    const drawer = document.createElement('div');
    drawer.id = 'gka-drawer';
    drawer.className = 'gka-drawer';
    drawer.setAttribute('role', 'dialog');
    drawer.setAttribute('aria-modal', 'true');
    drawer.setAttribute('aria-label', 'Menu');
    drawer.hidden = true;
    drawer.innerHTML = `
      <div class="gka-d-top">
        <a class="gka-d-logo" href="/"><img src="/wp-content/themes/gka/assets/brand/gka-mark-light.svg" alt="" width="50" height="32"><span>Gemilang Karya Agri</span></a>
        <button type="button" class="gka-d-close" aria-label="Tutup menu"><span></span><span></span></button>
      </div>
      <nav aria-label="Menu utama seluler"><ul class="gka-d-list">${items}</ul></nav>
      <div class="gka-d-foot">
        <a class="gka-d-cta" href="/hubungi-kami/">Hubungi Kami <span aria-hidden="true">→</span></a>
        <div class="gka-d-contact"><a href="https://wa.me/6287771491004">WhatsApp</a><a href="tel:+622545753355">(0254) 575 3355</a></div>
      </div>`;
    document.body.appendChild(drawer);

    let lastFocus = null;
    const open = () => {
      lastFocus = document.activeElement;
      drawer.hidden = false;
      requestAnimationFrame(() => drawer.classList.add('is-open'));
      document.documentElement.classList.add('gka-lock');
      burgers.forEach((b) => b.setAttribute('aria-expanded', 'true'));
      drawer.querySelector('.gka-d-close').focus();
    };
    const close = () => {
      drawer.classList.remove('is-open');
      document.documentElement.classList.remove('gka-lock');
      burgers.forEach((b) => b.setAttribute('aria-expanded', 'false'));
      setTimeout(() => { drawer.hidden = true; }, 220);
      lastFocus?.focus();
    };
    burgers.forEach((b) => b.addEventListener('click', open));
    drawer.querySelector('.gka-d-close').addEventListener('click', close);
    drawer.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') close();
      if (e.key !== 'Tab') return;
      const f = [...drawer.querySelectorAll('a, button, summary')].filter((el) => el.offsetParent !== null);
      if (e.shiftKey && document.activeElement === f[0]) { e.preventDefault(); f[f.length - 1].focus(); }
      else if (!e.shiftKey && document.activeElement === f[f.length - 1]) { e.preventDefault(); f[0].focus(); }
    });
    matchMedia('(min-width: 1024px)').addEventListener('change', (m) => { if (m.matches && !drawer.hidden) close(); });
  }

  // Scroll cue in the hero: jump to the first section below it.
  document.querySelectorAll('.gka-scroll-cue').forEach((b) => b.addEventListener('click', () => {
    const next = document.querySelector('.gka-hero')?.nextElementSibling || document.querySelector('main');
    next?.scrollIntoView({ behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth', block: 'start' });
  }));

  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (!reduce) {
    document.documentElement.classList.add('gka-intro');

    // Split a heading into per-word spans; screen readers get the plain text via aria-label.
    const split = (h) => {
      if (h.dataset.gkaSplit) return;
      h.dataset.gkaSplit = '1';
      h.setAttribute('aria-label', h.textContent.replace(/\s+/g, ' ').trim());
      let n = 0;
      const walk = (node) => {
        [...node.childNodes].forEach((c) => {
          if (c.nodeType === 3) {
            const frag = document.createDocumentFragment();
            c.textContent.split(/(\s+)/).forEach((part) => {
              if (!part) return;
              if (/^\s+$/.test(part)) { frag.appendChild(document.createTextNode(' ')); return; }
              const w = document.createElement('span');
              w.className = 'gka-w';
              w.setAttribute('aria-hidden', 'true');
              const i = document.createElement('span');
              i.className = 'gka-wi';
              i.style.setProperty('--i', n++);
              i.textContent = part;
              w.appendChild(i);
              frag.appendChild(w);
            });
            c.replaceWith(frag);
          } else if (c.nodeType === 1 && !c.classList.contains('gka-w')) {
            walk(c);
          }
        });
      };
      walk(h);
      h.classList.add('gka-split');
    };

    const headings = document.querySelectorAll('.gka-hero h1, .gka-phead h1, main h2.wp-block-heading, .gka-apply h2, .gka-esg-card h2');
    headings.forEach(split);

    const show = (el) => el.classList.add('is-revealed');
    if ('IntersectionObserver' in window) {
      const hio = new IntersectionObserver((entries) => {
        entries.forEach((e) => { if (e.isIntersecting) { show(e.target); hio.unobserve(e.target); } });
      }, { rootMargin: '0px 0px -12% 0px', threshold: 0.15 });
      headings.forEach((h) => (h.closest('.gka-hero') ? requestAnimationFrame(() => setTimeout(() => show(h), 250)) : hio.observe(h)));
      document.querySelectorAll('.gka-eyebrow').forEach((e) => hio.observe(e));
    } else {
      headings.forEach(show);
    }
    // Safety net: never leave a heading hidden (e.g. print, very long pages, observer quirks).
    setTimeout(() => document.querySelectorAll('.gka-split:not(.is-revealed)').forEach((h) => {
      if (h.getBoundingClientRect().top < innerHeight) show(h);
    }), 2500);
    addEventListener('beforeprint', () => document.querySelectorAll('.gka-split, .gka-eyebrow').forEach(show));
  }

  // Scroll reveal (transform only: content is never hidden).
  if (matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) return;
  const targets = document.querySelectorAll('.gka-head, .gka-steps > li, .gka-prod li, .gka-jobs li, .gka-esg > a, .gka-timeline > li, .gka-pillars > div');
  document.documentElement.classList.add('gka-motion');
  const io = new IntersectionObserver((entries) => {
    entries.forEach((e) => { if (e.isIntersecting) { e.target.classList.add('is-in'); io.unobserve(e.target); } });
  }, { rootMargin: '0px 0px -8% 0px' });
  targets.forEach((el, i) => {
    if (el.getBoundingClientRect().top <= innerHeight) return;
    el.style.setProperty('--gka-delay', `${(i % 4) * 80}ms`);
    el.classList.add('gka-reveal');
    io.observe(el);
  });
})();

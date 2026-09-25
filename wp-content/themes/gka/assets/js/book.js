(() => {
  const root = document.querySelector('.gka-book');
  if (!root) return;
  const count = +root.dataset.count;
  const base = root.dataset.base;
  const token = root.dataset.token;
  const stage = root.querySelector('.gka-book-stage');
  const spread = root.querySelector('.gka-book-spread');
  const leaves = [...root.querySelectorAll('.gka-book-leaf')];
  const posEl = root.querySelector('[data-book="pos"]');
  const loading = root.querySelector('.gka-book-loading');
  const cache = new Map();
  let index = 1;
  let ratio = +root.dataset.ratio || 1.4143;
  let pageW = 0, pageH = 0;

  const twoUp = () => stage.clientWidth >= 860;

  const load = (n) => {
    if (n < 1 || n > count) return Promise.resolve(null);
    if (!cache.has(n)) {
      cache.set(n, new Promise((res) => {
        const img = new Image();
        img.decoding = 'async';
        img.onload = () => res(img);
        img.onerror = () => res(null);
        img.src = `${base}${n}/?t=${encodeURIComponent(token)}`;
      }));
    }
    return cache.get(n);
  };

  /** Fit pages inside the frame: as large as they go, never stretched out of shape. */
  const fit = () => {
    const pages = twoUp() ? 2 : 1;
    const cs = getComputedStyle(stage);
    const availW = stage.clientWidth - parseFloat(cs.paddingLeft) - parseFloat(cs.paddingRight) - (pages - 1) * 2;
    const maxH = Math.max(260, Math.min(innerHeight * (document.fullscreenElement ? 0.86 : 0.74), 820));
    pageW = Math.floor(Math.min(availW / pages, maxH * ratio));
    pageH = Math.round(pageW / ratio);
    leaves.forEach((l) => { l.style.width = pageW + 'px'; l.style.height = pageH + 'px'; });
    spread.style.height = pageH + 'px';
  };

  const paint = async (leaf, n) => {
    if (!n) { leaf.hidden = true; return; }
    leaf.hidden = false;
    const img = await load(n);
    if (!img) { leaf.hidden = true; return; }
    ratio = img.width / img.height;
    fit();
    const cv = leaf.querySelector('canvas');
    const dpr = Math.min(devicePixelRatio || 1, 2);
    cv.width = Math.round(pageW * dpr);
    cv.height = Math.round(pageH * dpr);
    cv.style.width = pageW + 'px';
    cv.style.height = pageH + 'px';
    const ctx = cv.getContext('2d');
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    ctx.clearRect(0, 0, pageW, pageH);
    ctx.drawImage(img, 0, 0, pageW, pageH);
    cv.setAttribute('role', 'img');
    cv.setAttribute('aria-label', `Halaman ${n} dari ${count}`);
  };

  const render = async (dir = 0) => {
    root.classList.remove('is-flip-next', 'is-flip-prev');
    if (dir > 0) root.classList.add('is-flip-next');
    if (dir < 0) root.classList.add('is-flip-prev');
    fit();
    const pair = twoUp() ? [index, index + 1 <= count ? index + 1 : 0] : [index, 0];
    await Promise.all([paint(leaves[0], pair[0]), paint(leaves[1], pair[1])]);
    loading.hidden = true;
    posEl.textContent = pair[1] ? `${pair[0]}–${pair[1]}` : String(pair[0]);
    [index + 2, index + 3, index - 1].forEach(load);
    root.querySelector('[data-book="prev"]').disabled = index <= 1;
    root.querySelector('[data-book="next"]').disabled = index + (twoUp() ? 2 : 1) > count;
  };

  const step = (d) => {
    const jump = twoUp() ? 2 : 1;
    const next = index + d * jump;
    if (next < 1 || next > count) return;
    index = next;
    render(d);
  };

  root.querySelector('[data-book="prev"]').addEventListener('click', () => step(-1));
  root.querySelector('[data-book="next"]').addEventListener('click', () => step(1));
  const fullBtn = root.querySelector('[data-book="full"]');
  fullBtn.addEventListener('click', () => {
    if (document.fullscreenElement) document.exitFullscreen();
    else stage.requestFullscreen?.().catch(() => {});
  });
  document.addEventListener('fullscreenchange', () => {
    const on = document.fullscreenElement === stage;
    root.classList.toggle('is-full', on);
    fullBtn.textContent = on ? 'Keluar' : 'Layar penuh';
    render();
  });
  addEventListener('keydown', (e) => {
    if (!root.getBoundingClientRect().height) return;
    if (e.key === 'ArrowRight' || e.key === 'PageDown') { e.preventDefault(); step(1); }
    if (e.key === 'ArrowLeft' || e.key === 'PageUp') { e.preventDefault(); step(-1); }
  });
  let x0 = null;
  root.addEventListener('touchstart', (e) => { x0 = e.touches[0].clientX; }, { passive: true });
  root.addEventListener('touchend', (e) => {
    if (x0 === null) return;
    const dx = e.changedTouches[0].clientX - x0;
    if (Math.abs(dx) > 45) step(dx < 0 ? 1 : -1);
    x0 = null;
  }, { passive: true });
  root.addEventListener('contextmenu', (e) => e.preventDefault());
  root.addEventListener('dragstart', (e) => e.preventDefault());

  let t;
  const refit = () => { clearTimeout(t); t = setTimeout(() => render(), 160); };
  if ('ResizeObserver' in window) new ResizeObserver(refit).observe(stage);
  addEventListener('resize', refit);
  addEventListener('orientationchange', refit);
  render();
})();

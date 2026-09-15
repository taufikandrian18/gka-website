(() => {
  // Mark the current menu item (links are custom URLs, so core can't).
  const here = location.pathname.replace(/\/+$/, '/') || '/';
  document.querySelectorAll('.gka-menu .wp-block-navigation-item__content[href]').forEach((a) => {
    const p = new URL(a.href, location.origin).pathname;
    const li = a.closest('.wp-block-navigation-item');
    if (p === here || (p !== '/' && here.startsWith(p))) {
      li.classList.add('is-current');
      const top = li.parentElement.closest('.wp-block-navigation-item');
      if (top) top.classList.add('is-current');
    }
  });

  if (matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) return;
  const targets = document.querySelectorAll('.gka-head, .gka-steps > li, .gka-prod li, .gka-jobs li, .gka-esg > a, .gka-timeline > li, .gka-pillars > div');
  document.documentElement.classList.add('gka-motion');
  const io = new IntersectionObserver((entries) => {
    entries.forEach((e) => { if (e.isIntersecting) { e.target.classList.add('is-in'); io.unobserve(e.target); } });
  }, { rootMargin: '0px 0px -8% 0px' });
  targets.forEach((el, i) => {
    if (el.getBoundingClientRect().top <= innerHeight) return; // already visible: never hide it
    el.style.setProperty('--gka-delay', `${(i % 4) * 80}ms`);
    el.classList.add('gka-reveal');
    io.observe(el);
  });
})();

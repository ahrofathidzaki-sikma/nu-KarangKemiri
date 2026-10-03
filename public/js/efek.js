/* Efek & animasi halaman publik — tanpa library tambahan */
(function () {
  'use strict';
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var $ = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };

  /* ---------- Progres scroll, navbar, tombol ke atas ---------- */
  var nav = $('.site-nav');
  var bar = document.createElement('div');
  bar.className = 'scroll-progress';
  document.body.appendChild(bar);

  var toTop = document.createElement('button');
  toTop.type = 'button';
  toTop.className = 'to-top';
  toTop.setAttribute('aria-label', 'Kembali ke atas');
  toTop.innerHTML = '<i class="bi bi-arrow-up"></i>';
  toTop.addEventListener('click', function () {
    window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
  });
  document.body.appendChild(toTop);

  var lastY = window.pageYOffset, ticking = false;
  function onScroll() {
    var y = window.pageYOffset;
    var max = document.documentElement.scrollHeight - window.innerHeight;
    bar.style.transform = 'scaleX(' + (max > 0 ? Math.min(y / max, 1) : 0) + ')';
    if (nav) {
      nav.classList.toggle('scrolled', y > 10);
      var menuOpen = !!$('#nav.show');
      if (window.innerWidth < 992 && !menuOpen) {
        if (y > lastY + 6 && y > 140) nav.classList.add('hide');
        else if (y < lastY - 6 || y < 80) nav.classList.remove('hide');
      } else {
        nav.classList.remove('hide');
      }
    }
    toTop.classList.toggle('show', y > 500);
    lastY = y;
    ticking = false;
  }
  window.addEventListener('scroll', function () {
    if (!ticking) { ticking = true; requestAnimationFrame(onScroll); }
  }, { passive: true });
  onScroll();

  /* Menu HP: tutup bila menyentuh area luar */
  var navEl = $('#nav');
  if (navEl) {
    navEl.addEventListener('show.bs.collapse', function () { if (nav) nav.classList.remove('hide'); });
    document.addEventListener('click', function (e) {
      if (navEl.classList.contains('show') && !navEl.contains(e.target) && !e.target.closest('.navbar-toggler') && window.bootstrap) {
        window.bootstrap.Collapse.getOrCreateInstance(navEl).hide();
      }
    });
  }

  /* ---------- Muncul saat di-scroll ---------- */
  function staggerDelay(el) {
    var col = el.closest('[class*="col-"]') || el;
    var p = col.parentElement;
    var i = p ? Array.prototype.indexOf.call(p.children, col) : 0;
    return (i % 4) * 90;
  }

  if ('IntersectionObserver' in window && !reduce) {
    var targets = [
      ['.section .eyebrow, .section-title, .article-body, .ayat, .admin-card', ''],
      ['.card-nu', ''],
      ['.gallery-item', 'zoom'],
      ['.site-footer .row > div', '']
    ];
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) return;
        var el = en.target;
        io.unobserve(el);
        el.classList.add('in');
        // setelah selesai, lepas kelas agar efek hover normal kembali
        setTimeout(function () {
          el.classList.remove('reveal', 'in', 'zoom');
          el.style.removeProperty('--d');
        }, 1000 + parseInt(el.style.getPropertyValue('--d') || 0, 10));
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });

    var seen = [];
    targets.forEach(function (t) {
      $$(t[0]).forEach(function (el) {
        if (el.closest('.hero') || el.closest('.page-head') || seen.indexOf(el) > -1) return;
        seen.push(el);
        el.classList.add('reveal');
        if (t[1]) el.classList.add(t[1]);
        el.style.setProperty('--d', staggerDelay(el) + 'ms');
        io.observe(el);
      });
    });
  }

  /* ---------- Hitung naik angka statistik ---------- */
  function countUp(el) {
    var end = parseInt(el.textContent, 10);
    if (isNaN(end) || end <= 0 || reduce) return;
    var t0 = null, dur = 1300;
    el.textContent = '0';
    function step(ts) {
      if (t0 === null) t0 = ts;
      var p = Math.min((ts - t0) / dur, 1);
      el.textContent = Math.round(end * (1 - Math.pow(1 - p, 3)));
      if (p < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }
  var nums = $$('.stat-tile b');
  if (nums.length && 'IntersectionObserver' in window) {
    var io2 = new IntersectionObserver(function (es) {
      es.forEach(function (e) { if (e.isIntersecting) { io2.unobserve(e.target); countUp(e.target); } });
    }, { threshold: 0.6 });
    nums.forEach(function (n) { io2.observe(n); });
  }

  /* ---------- Lightbox galeri (geser untuk pindah foto) ---------- */
  var items = $$('a.gallery-item[href]');
  if (items.length) {
    var box = document.createElement('div');
    box.className = 'lb';
    box.setAttribute('role', 'dialog');
    box.setAttribute('aria-modal', 'true');
    box.setAttribute('aria-label', 'Tampilan foto');
    box.innerHTML =
      '<button type="button" class="lb-x" aria-label="Tutup"><i class="bi bi-x-lg"></i></button>' +
      '<button type="button" class="lb-nav lb-prev" aria-label="Sebelumnya"><i class="bi bi-chevron-left"></i></button>' +
      '<figure class="lb-fig"><img alt=""><figcaption></figcaption></figure>' +
      '<button type="button" class="lb-nav lb-next" aria-label="Berikutnya"><i class="bi bi-chevron-right"></i></button>';
    document.body.appendChild(box);

    var img = $('img', box), cap = $('figcaption', box), cur = 0;
    var show = function (i) {
      cur = (i + items.length) % items.length;
      var a = items[cur], thumb = $('img', a), c = $('.cap', a);
      img.classList.remove('in');
      img.onload = function () { img.classList.add('in'); };
      img.src = a.getAttribute('href');
      img.alt = thumb ? thumb.alt : '';
      cap.textContent = (items.length > 1 ? (cur + 1) + ' / ' + items.length + (c ? ' — ' : '') : '') + (c ? c.textContent : '');
    };
    var open = function (i) { show(i); box.classList.add('open'); document.documentElement.classList.add('lb-lock'); };
    var close = function () { box.classList.remove('open'); document.documentElement.classList.remove('lb-lock'); };

    items.forEach(function (a, i) {
      a.addEventListener('click', function (e) {
        if (e.metaKey || e.ctrlKey || e.shiftKey) return;
        e.preventDefault();
        open(i);
      });
    });
    $('.lb-x', box).addEventListener('click', close);
    $('.lb-prev', box).addEventListener('click', function () { show(cur - 1); });
    $('.lb-next', box).addEventListener('click', function () { show(cur + 1); });
    box.addEventListener('click', function (e) { if (e.target === box || e.target.tagName === 'FIGURE') close(); });
    document.addEventListener('keydown', function (e) {
      if (!box.classList.contains('open')) return;
      if (e.key === 'Escape') close();
      else if (e.key === 'ArrowLeft') show(cur - 1);
      else if (e.key === 'ArrowRight') show(cur + 1);
    });
    var sx = 0, sy = 0;
    box.addEventListener('touchstart', function (e) { sx = e.touches[0].clientX; sy = e.touches[0].clientY; }, { passive: true });
    box.addEventListener('touchend', function (e) {
      var dx = e.changedTouches[0].clientX - sx, dy = e.changedTouches[0].clientY - sy;
      if (Math.abs(dx) > 50 && Math.abs(dx) > Math.abs(dy)) show(dx < 0 ? cur + 1 : cur - 1);
      else if (dy > 90 && Math.abs(dy) > Math.abs(dx)) close();
    }, { passive: true });
  }
})();

{{-- JS animasi bersama (reveal bertahap, count-up, loading tombol, progres navigasi).
     Pasang di AKHIR <body>. Tanpa JS, konten tetap tampil normal. --}}
<script>
    (function () {
        'use strict';
        var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        /* --- 1. Reveal bertahap ------------------------------------------------ */
        var SELECTORS = [
            '.page-main > .card', '.page-main > .row > [class*="col"]', '.page-main .card',
            '.page-main .alert', '.page-main .table-responsive', '.page-main .list-group-item',
            '.page-main h1', '.page-main > div > h1', '.page-main .page-head',
            '.shop-main section', '.shop-main article', '.shop-main form', '.shop-main h1',
            '.shop-main .grid > *', '.shop-main ul.space-y-2 > li', '.shop-main .space-y-3 > *',
            '[data-reveal]'
        ].join(',');

        var CONTAINERS = 'section, article, form, .table-responsive';

        function collect() {
            var nodes = Array.prototype.slice.call(document.querySelectorAll(SELECTORS));
            var seen = new Set();
            return nodes.filter(function (el) {
                if (seen.has(el)) return false;
                seen.add(el);
                if (el.closest('.modal, .offcanvas, .dropdown-menu, .toast-pop, [data-no-reveal]')) return false;
                el.classList.add('rv');
                // Container besar cukup memudar; anak-anaknya yang bergerak bertahap
                if (el.matches(CONTAINERS)) el.classList.add('rv-fade');
                return true;
            });
        }

        function initReveal() {
            var targets = collect();
            if (!targets.length) return;
            if (reduce || !('IntersectionObserver' in window)) {
                targets.forEach(function (el) { el.classList.add('is-in'); });
                return;
            }
            // Indeks tahap per induk agar kartu bersaudara muncul berurutan (maks 7 tahap)
            var counters = new Map();
            targets.forEach(function (el) {
                var key = el.parentElement;
                var n = counters.get(key) || 0;
                el.style.setProperty('--rv-i', Math.min(n, 7));
                counters.set(key, n + 1);
            });
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) {
                    if (!e.isIntersecting) return;
                    var el = e.target;
                    // Setelah dua frame agar transisi benar-benar terpicu
                    requestAnimationFrame(function () { requestAnimationFrame(function () { el.classList.add('is-in'); }); });
                    io.unobserve(el);
                });
            }, { rootMargin: '0px 0px -8% 0px', threshold: 0 });
            targets.forEach(function (el) { io.observe(el); });
            // Jaring pengaman: tampilkan semuanya bila observer tak kunjung memicu
            setTimeout(function () { targets.forEach(function (el) { el.classList.add('is-in'); }); }, 2500);
        }

        /* --- 2. Count-up untuk angka statistik: <span data-count="1250"> ---------- */
        function initCount() {
            var els = document.querySelectorAll('[data-count]');
            if (!els.length) return;
            function run(el) {
                var target = parseFloat(el.getAttribute('data-count'));
                if (isNaN(target)) return;
                var decimals = (el.getAttribute('data-count').split('.')[1] || '').length;
                var fmt = new Intl.NumberFormat('id-ID', { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
                if (reduce) { el.textContent = fmt.format(target); return; }
                var start = null, dur = 900;
                function step(ts) {
                    if (start === null) start = ts;
                    var p = Math.min((ts - start) / dur, 1);
                    var eased = 1 - Math.pow(1 - p, 4);
                    el.textContent = fmt.format(target * eased);
                    if (p < 1) requestAnimationFrame(step);
                }
                requestAnimationFrame(step);
            }
            if (!('IntersectionObserver' in window)) { els.forEach(run); return; }
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) { if (e.isIntersecting) { run(e.target); io.unobserve(e.target); } });
            }, { threshold: .4 });
            els.forEach(function (el) { io.observe(el); });
        }

        /* --- 3. Tombol loading saat form dikirim --------------------------------- */
        function initSubmitLoading() {
            document.addEventListener('submit', function (ev) {
                var form = ev.target;
                if (!form || form.tagName !== 'FORM' || form.hasAttribute('data-no-loading')) return;
                if ((form.getAttribute('method') || 'get').toLowerCase() === 'get') return;
                if (form.target && form.target !== '_self') return;
                // Tunda satu tick: bila ada handler lain (mis. konfirmasi) yang membatalkan submit, jangan tampilkan loading
                setTimeout(function () {
                    if (ev.defaultPrevented) return;
                    var btn = ev.submitter || form.querySelector('button[type="submit"], button:not([type])');
                    if (!btn || btn.classList.contains('is-loading')) return;
                    btn.classList.add('is-loading');
                    btn.setAttribute('aria-busy', 'true');
                    setTimeout(function () { btn.classList.remove('is-loading'); btn.removeAttribute('aria-busy'); }, 9000);
                }, 0);
            }, false);
        }

        /* --- 4. Bilah progres + pembersihan saat kembali dari cache -------------- */
        function initProgress() {
            var bar = document.createElement('div');
            bar.className = 'nav-progress';
            bar.setAttribute('aria-hidden', 'true');
            document.body.appendChild(bar);
            document.addEventListener('click', function (e) {
                if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
                var a = e.target.closest && e.target.closest('a[href]');
                if (!a || a.target === '_blank' || a.hasAttribute('download') || a.hasAttribute('data-bs-toggle')) return;
                var href = a.getAttribute('href');
                if (!href || href.charAt(0) === '#' || /^(javascript:|mailto:|tel:)/i.test(href)) return;
                if (a.origin !== location.origin) return;
                if (a.pathname === location.pathname && a.search === location.search) return;
                bar.classList.add('is-active');
                setTimeout(function () { bar.classList.remove('is-active'); }, 10000);
            }, false);
            window.addEventListener('pageshow', function (e) {
                bar.classList.remove('is-active');
                if (e.persisted) {
                    document.querySelectorAll('.is-loading').forEach(function (b) { b.classList.remove('is-loading'); });
                }
            });
        }

        /* --- 5. Perataan scroll mulus untuk tautan #anchor ---------------------- */
        function initAnchors() {
            document.addEventListener('click', function (e) {
                var a = e.target.closest && e.target.closest('a[href^="#"]');
                if (!a || a.hasAttribute('data-bs-toggle')) return;
                var id = a.getAttribute('href').slice(1);
                if (!id) return;
                var t = document.getElementById(id);
                if (!t) return;
                e.preventDefault();
                t.scrollIntoView({ behavior: reduce ? 'auto' : 'smooth', block: 'start' });
                if (history.replaceState) history.replaceState(null, '', '#' + id);
            });
        }

        function boot() {
            initReveal(); initCount(); initSubmitLoading(); initProgress(); initAnchors();
        }
        // Partial ini dipasang di akhir <body>, jadi seluruh konten sudah ter-parse.
        boot();

        // API kecil untuk konten dinamis (mis. hasil AJAX): window.motionRefresh()
        window.motionRefresh = initReveal;
    })();
</script>

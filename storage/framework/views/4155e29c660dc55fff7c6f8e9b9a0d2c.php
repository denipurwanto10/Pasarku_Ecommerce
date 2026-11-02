
<style>
    :root {
        --ease-out: cubic-bezier(.22, 1, .36, 1);
        --ease-in-out: cubic-bezier(.65, 0, .35, 1);
        --dur-fast: .16s;
        --dur: .32s;
        --dur-slow: .6s;
    }

    /* ---------- Transisi antar halaman (browser yang mendukung) ----------
       Topbar / sidebar / bottom-nav diberi nama sendiri sehingga tetap
       diam di tempat; hanya area konten yang memudar silang. */
    @view-transition { navigation: auto; }
    ::view-transition-group(root) { animation-duration: .38s; animation-timing-function: var(--ease-out); }
    ::view-transition-old(root) { animation: vt-out .18s ease both; }
    ::view-transition-new(root) { animation: vt-in .38s var(--ease-out) both; }
    @keyframes vt-out { to { opacity: 0; } }
    @keyframes vt-in { from { opacity: 0; transform: translateY(6px); } }
    .topbar, .shop-header { view-transition-name: site-header; }
    .sidebar { view-transition-name: site-sidebar; }
    .bottom-nav { view-transition-name: site-bottomnav; }
    ::view-transition-old(site-header), ::view-transition-new(site-header),
    ::view-transition-old(site-sidebar), ::view-transition-new(site-sidebar),
    ::view-transition-old(site-bottomnav), ::view-transition-new(site-bottomnav) { animation: none; mix-blend-mode: normal; }

    /* ---------- Masuk halaman ---------- */
    html.js .page-main,
    html.js .page-enter { animation: page-enter .45s var(--ease-out) both; }
    @keyframes page-enter { from { opacity: 0; } }

    /* ---------- Reveal bertahap saat elemen masuk viewport ----------
       Kelas .rv dipasang otomatis oleh JS; .is-in ditambahkan oleh observer. */
    html.js .rv {
        opacity: 0; transform: translateY(14px);
        transition: opacity var(--dur-slow) var(--ease-out), transform var(--dur-slow) var(--ease-out);
        transition-delay: calc(var(--rv-i, 0) * 55ms);
        will-change: opacity, transform;
    }
    html.js .rv.is-in { opacity: 1; transform: none; will-change: auto; }
    html.js .rv.rv-fade { transform: none; transition-duration: .5s; }

    /* ---------- Mikro-interaksi ---------- */
    .btn, button, a, .card, .more-menu-item, .icon-btn { -webkit-tap-highlight-color: transparent; }
    @media (hover: hover) {
        .card-lift, a.card, .lift { transition: transform var(--dur) var(--ease-out), box-shadow var(--dur) var(--ease-out), border-color var(--dur) ease; }
        .card-lift:hover, a.card:hover, .lift:hover { transform: translateY(-2px); }
    }
    .pressable { transition: transform var(--dur-fast) var(--ease-out); }
    .pressable:active { transform: scale(.97); }

    /* Gambar produk: zoom pelan saat hover, tanpa meluap dari bingkai */
    .zoom-img { overflow: hidden; }
    .zoom-img img { transition: transform .7s var(--ease-out); }
    @media (hover: hover) { .zoom-img:hover img, a:hover > .zoom-img img, .group:hover .zoom-img img { transform: scale(1.04); } }

    /* ---------- Tombol loading (cegah klik ganda + beri umpan balik) ---------- */
    .is-loading { position: relative; pointer-events: none; color: transparent !important; }
    .is-loading > * { visibility: hidden; }
    .is-loading::after {
        content: ''; position: absolute; inset: 0; margin: auto; width: 1.05em; height: 1.05em;
        border-radius: 50%; border: 2px solid currentColor; border-right-color: transparent;
        color: #fff; opacity: .9; animation: spin .7s linear infinite;
    }
    .btn-outline-primary.is-loading::after, .btn-outline-secondary.is-loading::after,
    .btn-outline-danger.is-loading::after, .btn-outline-success.is-loading::after { color: var(--ink); }
    [data-bs-theme="dark"] .btn-primary.is-loading::after { color: #131412; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ---------- Skeleton (opsional: tambahkan class .skeleton) ---------- */
    .skeleton { position: relative; overflow: hidden; background: var(--line, #e3e0d9); border-radius: .5rem; }
    .skeleton::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,.45), transparent);
        transform: translateX(-100%); animation: shimmer 1.4s var(--ease-in-out) infinite;
    }
    @keyframes shimmer { to { transform: translateX(100%); } }

    /* ---------- Bilah progres tipis saat berpindah halaman ---------- */
    .nav-progress {
        position: fixed; top: 0; left: 0; right: 0; height: 2px; z-index: 3000; pointer-events: none;
        background: var(--accent, #1f6652); transform: scaleX(0); transform-origin: left; opacity: 0;
    }
    .nav-progress.is-active { opacity: 1; transform: scaleX(.82); transition: transform 8s cubic-bezier(.1, .6, .2, 1), opacity .2s ease; }

    /* ---------- Hormati preferensi "kurangi gerakan" ---------- */
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { animation-duration: .001ms !important; animation-iteration-count: 1 !important; transition-duration: .001ms !important; scroll-behavior: auto !important; }
        html.js .rv { opacity: 1; transform: none; }
        @view-transition { navigation: none; }
    }
</style>
<?php /**PATH C:\laragon\www\ecommerce\resources\views/layouts/_motion.blade.php ENDPATH**/ ?>
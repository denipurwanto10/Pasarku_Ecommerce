
<style>
    :root {
        /* Permukaan & teks */
        --ink: #1b1a17;
        --ink-2: #2b2926;
        --mist: #f6f5f2;
        --surface: #ffffff;
        --line: #e7e4dd;
        --line-strong: #d6d2c8;
        --muted: #6f6d66;

        /* Aksen tunggal (nama --teal dipertahankan untuk kompatibilitas) */
        --teal: #1f6652;
        --teal-dark: #174f40;
        --teal-soft: #e7f0ec;
        --accent: var(--teal);

        /* Status */
        --amber: #c98a2b;
        --amber-ink: #7a4e10;
        --amber-soft: #f8efdd;
        --coral: #c4453f;
        --coral-soft: #f8e8e6;
        --info: #37608f;
        --info-soft: #e6edf5;

        --mono: 'Geist Mono', ui-monospace, SFMono-Regular, Menlo, monospace;
        --display: 'Geist', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif;
        --sans: 'Geist', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif;

        /* Permukaan gelap tetap (tombol utama, kartu ID, brand-mark) */
        --ink-fixed: #1b1a17;
        --ink-fixed-2: #2b2926;

        --shadow-1: rgba(27,26,23,.04);
        --shadow-2: rgba(27,26,23,.06);
        --shadow-3: rgba(27,26,23,.14);
        --shadow-4: rgba(27,26,23,.20);
        --shadow-5: rgba(27,26,23,.26);
        --focus-ring: color-mix(in srgb, var(--teal) 28%, transparent);

        --radius: .85rem;
        --radius-sm: .6rem;
        --radius-lg: 1.1rem;

        /* Variabel Bootstrap diarahkan ke token kita */
        --bs-body-bg: var(--mist);
        --bs-body-color: var(--ink);
        --bs-body-font-family: var(--sans);
        --bs-border-color: var(--line);
        --bs-secondary-color: var(--muted);
        --bs-tertiary-bg: var(--mist);
        --bs-link-color: var(--teal);
        --bs-link-hover-color: var(--teal-dark);
    }

    [data-bs-theme="dark"] {
        --ink: #ecEAe4;
        --ink-2: #dedbd3;
        --mist: #131412;
        --surface: #1b1c1a;
        --line: #2b2c29;
        --line-strong: #3a3b37;
        --muted: #9a9890;

        --teal: #55ad93;
        --teal-dark: #86cbb6;
        --teal-soft: rgba(85,173,147,.14);
        --amber: #d9a043;
        --amber-ink: #e8bd78;
        --amber-soft: rgba(217,160,67,.14);
        --coral: #e0706a;
        --coral-soft: rgba(224,112,106,.14);
        --info: #7ea6d6;
        --info-soft: rgba(126,166,214,.14);

        --shadow-1: rgba(0,0,0,.35);
        --shadow-2: rgba(0,0,0,.40);
        --shadow-3: rgba(0,0,0,.45);
        --shadow-4: rgba(0,0,0,.50);
        --shadow-5: rgba(0,0,0,.55);
        --focus-ring: color-mix(in srgb, var(--teal) 34%, transparent);
    }

    /* ---------- Dasar ---------- */
    * { -webkit-tap-highlight-color: transparent; scrollbar-width: thin; scrollbar-color: var(--line-strong) transparent; }
    *::-webkit-scrollbar { width: 8px; height: 8px; }
    *::-webkit-scrollbar-track { background: transparent; }
    *::-webkit-scrollbar-thumb { background-color: var(--line-strong); border-radius: 50rem; border: 2px solid transparent; background-clip: content-box; }
    *::-webkit-scrollbar-thumb:hover { background-color: var(--muted); }

    html { scroll-padding-top: 5rem; -webkit-text-size-adjust: 100%; }
    html, body { height: 100%; }
    body {
        background-color: var(--mist); color: var(--ink);
        font-family: var(--sans); font-size: .94rem; line-height: 1.55;
        font-feature-settings: 'ss01', 'cv11';
        -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; text-rendering: optimizeLegibility;
    }
    h1, h2, h3, h4, h5, h6 { font-family: var(--display); font-weight: 620; letter-spacing: -.022em; text-wrap: balance; }
    h1, .h1 { font-size: clamp(1.45rem, 1.1rem + 1.2vw, 1.9rem); }
    h2, .h2 { font-size: clamp(1.25rem, 1rem + .8vw, 1.5rem); }
    h4, .h4 { font-size: 1.15rem; }
    h5, .h5 { font-size: 1.02rem; }
    p { text-wrap: pretty; }
    a { text-decoration: none; transition: color .18s ease; }
    .font-mono, .table thead th, code, kbd { font-family: var(--mono); }
    .font-mono, .table td, .table th, .badge { font-variant-numeric: tabular-nums; }
    .text-muted, .form-text { color: var(--muted) !important; }
    ::selection { background: color-mix(in srgb, var(--teal) 24%, transparent); }
    :focus-visible { outline: 2px solid var(--teal); outline-offset: 2px; border-radius: 4px; }
    hr { border-color: var(--line); opacity: 1; }

    /* Transisi ganti tema yang halus */
    body, .topbar, .sidebar, .bottom-nav, .card, .table, .form-control, .form-select,
    .badge, .alert, .dropdown-menu, .modal-content, .offcanvas, .navbar-collapse,
    .notif-menu, .toast-pop, .more-menu-item, .more-menu-ico, .icon-btn {
        transition: background-color .25s ease, border-color .25s ease, color .25s ease, box-shadow .25s ease;
    }

    /* ---------- Topbar ---------- */
    .topbar {
        background: color-mix(in srgb, var(--surface) 86%, transparent);
        -webkit-backdrop-filter: saturate(1.4) blur(16px); backdrop-filter: saturate(1.4) blur(16px);
        border-bottom: 1px solid var(--line); position: sticky; z-index: 1020;
    }
    .topbar .navbar-brand {
        color: var(--ink); font-family: var(--display); font-weight: 650; letter-spacing: -.02em;
        display: flex; align-items: center; gap: .65rem; font-size: 1.02rem;
    }
    .brand-mark {
        width: 34px; height: 34px; border-radius: .6rem; flex: 0 0 auto;
        background: var(--ink-fixed); color: #fff; font-size: .95rem;
        display: flex; align-items: center; justify-content: center;
    }
    .topbar .live-clock {
        font-family: var(--mono); font-size: .78rem; font-weight: 500; color: var(--muted);
        letter-spacing: .01em; display: flex; align-items: center; gap: .45rem;
        padding: .35rem .7rem; border: 1px solid var(--line); border-radius: 50rem; background: var(--surface);
    }
    .topbar .live-clock i { color: var(--teal); font-size: .55rem; animation: livePulse 2.4s ease-in-out infinite; }
    @keyframes livePulse { 0%,100% { opacity: 1; } 50% { opacity: .3; } }
    .topbar .icon-btn {
        width: 40px; height: 40px; border-radius: .7rem; color: var(--muted); border: 0;
        display: flex; align-items: center; justify-content: center; font-size: 1.1rem;
        position: relative; background: transparent;
        transition: background-color .18s ease, color .18s ease, transform .18s var(--ease-out);
    }
    .topbar .icon-btn:hover { background: color-mix(in srgb, var(--ink) 6%, transparent); color: var(--ink); }
    .topbar .icon-btn:active { transform: scale(.92); }
    .notif-dot {
        position: absolute; top: 6px; right: 6px; min-width: 16px; height: 16px; padding: 0 4px;
        border-radius: 50rem; background: var(--coral); color: #fff; font-size: .62rem; font-weight: 650;
        display: flex; align-items: center; justify-content: center; line-height: 1;
        box-shadow: 0 0 0 2px var(--surface);
    }
    .user-chip { display: flex; align-items: center; gap: .55rem; color: var(--ink); font-weight: 550; font-size: .88rem; }
    .user-chip img { box-shadow: 0 0 0 1px var(--line); }
    .role-pill {
        font-size: .64rem; font-weight: 600; padding: .18rem .5rem; border-radius: 50rem;
        background: var(--teal-soft); color: var(--teal-dark); text-transform: uppercase; letter-spacing: .06em;
        font-family: var(--mono);
    }

    /* ---------- Sidebar (rail desktop) ---------- */
    .sidebar { background: var(--surface); color: var(--muted); border-right: 1px solid var(--line) !important; }
    .sidebar .side-eyebrow {
        font-family: var(--mono); font-size: .64rem; text-transform: uppercase; letter-spacing: .14em;
        color: var(--muted); padding: .3rem .85rem .9rem; opacity: .8;
    }
    .sidebar .nav-link {
        position: relative; color: var(--muted); font-weight: 520; font-size: .9rem;
        border-radius: .65rem; padding: .6rem .85rem; display: flex; align-items: center; gap: .75rem;
        margin-bottom: .15rem; border-left: 0;
        transition: background-color .2s ease, color .2s ease, transform .25s var(--ease-out);
    }
    .sidebar .nav-link i { font-size: 1.05rem; width: 1.25rem; text-align: center; transition: color .2s ease, transform .3s var(--ease-out); }
    .sidebar .nav-link:hover { background: color-mix(in srgb, var(--ink) 5%, transparent); color: var(--ink); }
    .sidebar .nav-link:hover i { transform: translateX(1px) scale(1.06); }
    .sidebar .nav-link.active { background: var(--teal-soft); color: var(--teal-dark); font-weight: 600; }
    .sidebar .nav-link.active i { color: var(--teal); }
    .sidebar .nav-link.active::before {
        content: ''; position: absolute; left: -.55rem; top: 22%; bottom: 22%; width: 3px; border-radius: 0 3px 3px 0; background: var(--teal);
        animation: railIn .4s var(--ease-out) both;
    }
    @keyframes railIn { from { transform: scaleY(0); } }
    .sidebar .nav-link .badge { font-family: var(--mono); }
    .nav-pills .nav-link.active, .nav-pills .show > .nav-link { background: var(--teal-soft); color: var(--teal-dark); }

    /* ---------- Kartu ---------- */
    .card {
        --bs-card-bg: var(--surface); --bs-card-border-color: var(--line); --bs-card-cap-bg: transparent;
        border: 1px solid var(--line); border-radius: var(--radius); background: var(--surface);
        box-shadow: 0 1px 2px var(--shadow-1);
    }
    .card-header { background: transparent; border-bottom: 1px solid var(--line); font-weight: 600; padding: .9rem 1.1rem; }
    .card-footer { background: transparent; border-top: 1px solid var(--line); }
    .card-body { padding: 1.15rem; }
    @media (min-width: 768px) { .card-body { padding: 1.3rem; } }

    /* ---------- Tombol ---------- */
    .btn {
        --bs-btn-border-radius: .65rem; --bs-btn-focus-box-shadow: 0 0 0 .22rem var(--focus-ring);
        border-radius: .65rem; font-weight: 560; font-size: .9rem; letter-spacing: -.005em;
        padding: .52rem 1rem; display: inline-flex; align-items: center; justify-content: center; gap: .45rem;
        transition: background-color .2s ease, border-color .2s ease, color .2s ease, box-shadow .25s var(--ease-out), transform .18s var(--ease-out);
    }
    .btn-sm { padding: .36rem .75rem; font-size: .82rem; border-radius: .55rem; }
    .btn-lg { padding: .72rem 1.3rem; font-size: 1rem; border-radius: .75rem; }
    .btn:active:not(:disabled) { transform: scale(.975); }
    .btn:disabled, .btn.disabled { opacity: .5; box-shadow: none; transform: none; }

    .btn-primary { background: var(--ink-fixed); border-color: var(--ink-fixed); color: #fff; --bs-btn-focus-shadow-rgb: 27,26,23; }
    .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
        background: var(--ink-fixed-2) !important; border-color: var(--ink-fixed-2) !important; color: #fff !important;
        box-shadow: 0 6px 16px -8px var(--shadow-4);
    }
    [data-bs-theme="dark"] .btn-primary { background: #ecEAe4; border-color: #ecEAe4; color: #131412; }
    [data-bs-theme="dark"] .btn-primary:hover, [data-bs-theme="dark"] .btn-primary:focus, [data-bs-theme="dark"] .btn-primary:active {
        background: #fff !important; border-color: #fff !important; color: #131412 !important;
    }
    .btn-success { background: var(--teal); border-color: var(--teal); color: #fff; }
    .btn-success:hover, .btn-success:focus, .btn-success:active { background: var(--teal-dark) !important; border-color: var(--teal-dark) !important; color: #fff !important; }
    [data-bs-theme="dark"] .btn-success { color: #0d1a15; }
    .btn-danger { background: var(--coral); border-color: var(--coral); color: #fff; }
    .btn-danger:hover, .btn-danger:focus, .btn-danger:active { background: color-mix(in srgb, var(--coral) 86%, #000) !important; border-color: color-mix(in srgb, var(--coral) 86%, #000) !important; color: #fff !important; }
    .btn-warning { background: var(--amber); border-color: var(--amber); color: #2a1c06; }
    .btn-warning:hover { background: color-mix(in srgb, var(--amber) 88%, #000) !important; border-color: transparent !important; color: #2a1c06 !important; }

    .btn-outline-primary { color: var(--ink); border-color: var(--line-strong); background: transparent; }
    .btn-outline-primary:hover, .btn-outline-primary:focus, .btn-outline-primary:active { background: var(--ink-fixed) !important; border-color: var(--ink-fixed) !important; color: #fff !important; }
    [data-bs-theme="dark"] .btn-outline-primary:hover { background: #ecEAe4 !important; border-color: #ecEAe4 !important; color: #131412 !important; }
    .btn-outline-secondary { border-color: var(--line-strong); color: var(--muted); background: transparent; }
    .btn-outline-secondary:hover, .btn-outline-secondary:active { background: color-mix(in srgb, var(--ink) 6%, transparent) !important; color: var(--ink) !important; border-color: var(--line-strong) !important; }
    .btn-outline-danger { color: var(--coral); border-color: color-mix(in srgb, var(--coral) 35%, var(--line)); background: transparent; }
    .btn-outline-danger:hover, .btn-outline-danger:active { background: var(--coral) !important; border-color: var(--coral) !important; color: #fff !important; }
    .btn-outline-success { color: var(--teal-dark); border-color: color-mix(in srgb, var(--teal) 35%, var(--line)); background: transparent; }
    .btn-outline-success:hover, .btn-outline-success:active { background: var(--teal) !important; border-color: var(--teal) !important; color: #fff !important; }
    .btn-link { color: var(--teal); font-weight: 560; }
    .btn-close { transition: opacity .2s ease, transform .2s var(--ease-out); }
    .btn-close:hover { transform: rotate(90deg); }
    [data-bs-theme="dark"] .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }

    /* ---------- Form ---------- */
    .form-label { font-weight: 600; font-size: .8rem; color: var(--ink); margin-bottom: .4rem; letter-spacing: -.003em; }
    .form-control, .form-select {
        background-color: var(--surface); color: var(--ink);
        border: 1px solid var(--line-strong); border-radius: .65rem; padding: .56rem .85rem; font-size: .92rem;
        transition: border-color .2s ease, box-shadow .25s var(--ease-out), background-color .2s ease;
    }
    .form-control::placeholder { color: color-mix(in srgb, var(--muted) 65%, transparent); }
    .form-control:hover:not(:focus):not(:disabled), .form-select:hover:not(:focus):not(:disabled) { border-color: color-mix(in srgb, var(--muted) 55%, var(--line)); }
    .form-control:focus, .form-select:focus { border-color: var(--teal); box-shadow: 0 0 0 .22rem var(--focus-ring); background-color: var(--surface); color: var(--ink); }
    .form-control:disabled, .form-select:disabled { background: var(--mist); opacity: .7; }
    .form-check-input { border-color: var(--line-strong); transition: background-color .2s ease, border-color .2s ease, box-shadow .2s ease; }
    .form-check-input:checked { background-color: var(--teal); border-color: var(--teal); }
    .form-check-input:focus { box-shadow: 0 0 0 .22rem var(--focus-ring); border-color: var(--teal); }
    .form-switch .form-check-input { transition: background-position .25s var(--ease-out), background-color .2s ease; }
    .input-group-text { background: var(--mist); border-color: var(--line-strong); color: var(--muted); }
    .is-invalid, .was-validated :invalid { border-color: var(--coral) !important; }
    .invalid-feedback { color: var(--coral); }

    /* ---------- Tabel ---------- */
    .table { --bs-table-bg: transparent; --bs-table-color: var(--ink); --bs-table-hover-bg: color-mix(in srgb, var(--ink) 3.5%, transparent); --bs-table-striped-bg: color-mix(in srgb, var(--ink) 2.5%, transparent); font-size: .9rem; margin-bottom: 0; }
    .table > :not(caption) > * > * { padding: .85rem .95rem; border-bottom-color: var(--line); }
    .table thead th {
        font-size: .66rem; text-transform: uppercase; letter-spacing: .08em; color: var(--muted);
        font-weight: 600; background: transparent; border-bottom: 1px solid var(--line-strong); white-space: nowrap;
    }
    .table tbody tr { transition: background-color .15s ease; }
    .table tbody tr:last-child > * { border-bottom: 0; }
    .table-responsive { border-radius: var(--radius); -webkit-overflow-scrolling: touch; }
    .card > .table-responsive, .card > .table { border-radius: 0 0 var(--radius) var(--radius); }

    /* ---------- Badge (pil lembut) ---------- */
    .badge { font-weight: 600; border-radius: 50rem; padding: .38em .72em; font-size: .7rem; letter-spacing: .005em; }
    .badge.bg-success { background: var(--teal-soft) !important; color: var(--teal-dark) !important; }
    .badge.bg-warning { background: var(--amber-soft) !important; color: var(--amber-ink) !important; }
    .badge.bg-danger { background: var(--coral-soft) !important; color: var(--coral) !important; }
    .badge.bg-info { background: var(--info-soft) !important; color: var(--info) !important; }
    .badge.bg-secondary { background: color-mix(in srgb, var(--ink) 7%, transparent) !important; color: var(--muted) !important; }
    .badge.bg-primary { background: var(--ink-fixed) !important; color: #fff !important; }
    [data-bs-theme="dark"] .badge.bg-light.text-dark { background: var(--mist) !important; color: var(--ink) !important; border-color: var(--line) !important; }

    /* ---------- Alert ---------- */
    .alert { border-radius: var(--radius-sm); border: 1px solid transparent; font-size: .9rem; padding: .8rem 1rem; }
    .alert-success { background: var(--teal-soft); color: var(--teal-dark); border-color: color-mix(in srgb, var(--teal) 18%, transparent); }
    .alert-danger { background: var(--coral-soft); color: var(--coral); border-color: color-mix(in srgb, var(--coral) 18%, transparent); }
    .alert-warning { background: var(--amber-soft); color: var(--amber-ink); border-color: color-mix(in srgb, var(--amber) 22%, transparent); }
    .alert-info { background: var(--info-soft); color: var(--info); border-color: color-mix(in srgb, var(--info) 18%, transparent); }

    /* ---------- Dropdown / Modal / Offcanvas / Collapse (transisi halus) ---------- */
    .dropdown-menu {
        --bs-dropdown-bg: var(--surface); --bs-dropdown-border-color: var(--line); --bs-dropdown-link-hover-bg: color-mix(in srgb, var(--ink) 5%, transparent);
        --bs-dropdown-link-color: var(--ink); --bs-dropdown-link-hover-color: var(--ink); --bs-dropdown-link-active-bg: var(--teal-soft); --bs-dropdown-link-active-color: var(--teal-dark);
        border-radius: .85rem; padding: .35rem; border: 1px solid var(--line);
        box-shadow: 0 14px 34px -12px var(--shadow-4), 0 2px 6px var(--shadow-1); font-size: .88rem;
    }
    .dropdown-item { border-radius: .55rem; padding: .5rem .75rem; transition: background-color .15s ease; }
    .dropdown-divider { border-color: var(--line); margin: .3rem .2rem; }
    /* `translate`/`scale` terpisah dari `transform` sehingga tidak bentrok dengan posisi Popper */
    .dropdown-menu.show { animation: menuIn .22s var(--ease-out) both; transform-origin: top right; }
    @keyframes menuIn { from { opacity: 0; translate: 0 -6px; scale: .97; } }

    .modal.fade .modal-dialog { transform: translateY(16px) scale(.98); opacity: 0; transition: transform .4s var(--ease-out), opacity .3s ease; }
    .modal.show .modal-dialog { transform: none; opacity: 1; }
    .modal-content { background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-lg); box-shadow: 0 30px 70px -20px var(--shadow-5); }
    .modal-header, .modal-footer { border-color: var(--line); }
    .modal-backdrop { --bs-backdrop-bg: #11100d; }
    .modal-backdrop.show { --bs-backdrop-opacity: .42; -webkit-backdrop-filter: blur(3px); backdrop-filter: blur(3px); }
    .modal-backdrop.fade, .offcanvas-backdrop.fade { transition: opacity .3s ease; }

    .offcanvas { background: var(--surface); border-color: var(--line); transition: transform .5s var(--ease-out); }
    .collapsing { transition: height .4s var(--ease-out); }
    .fade { transition: opacity .25s ease; }

    .nav-tabs { border-bottom-color: var(--line); }
    .nav-tabs .nav-link { color: var(--muted); font-weight: 560; border: 0; border-bottom: 2px solid transparent; transition: color .2s ease, border-color .25s var(--ease-out); }
    .nav-tabs .nav-link:hover { color: var(--ink); border-color: var(--line-strong); }
    .nav-tabs .nav-link.active { color: var(--ink); background: transparent; border-color: var(--teal); }
    .tab-pane.fade { transition: opacity .3s ease; }

    .pagination { --bs-pagination-color: var(--ink); --bs-pagination-bg: var(--surface); --bs-pagination-border-color: var(--line); --bs-pagination-hover-bg: var(--mist); --bs-pagination-hover-color: var(--ink); --bs-pagination-hover-border-color: var(--line-strong); --bs-pagination-active-bg: var(--ink-fixed); --bs-pagination-active-border-color: var(--ink-fixed); --bs-pagination-active-color: #fff; --bs-pagination-focus-box-shadow: 0 0 0 .22rem var(--focus-ring); --bs-pagination-border-radius: .55rem; gap: .25rem; }
    .page-link { border-radius: .55rem !important; transition: background-color .2s ease, color .2s ease; }
    .list-group { --bs-list-group-bg: var(--surface); --bs-list-group-border-color: var(--line); --bs-list-group-color: var(--ink); --bs-list-group-border-radius: var(--radius-sm); }
    .progress { background: color-mix(in srgb, var(--ink) 7%, transparent); border-radius: 50rem; height: .55rem; }
    .progress-bar { background: var(--teal); transition: width .9s var(--ease-out); }

    /* ---------- Timeline status ---------- */
    .status-timeline { list-style: none; margin: 0; padding: 0; }
    .status-timeline li { position: relative; padding: 0 0 1.35rem 2.4rem; }
    .status-timeline li:last-child { padding-bottom: 0; }
    .status-timeline li::before { content: ''; position: absolute; left: .6rem; top: 1.6rem; bottom: -.2rem; width: 1px; background: var(--line-strong); }
    .status-timeline li:last-child::before { display: none; }
    .status-timeline .tl-dot {
        position: absolute; left: 0; top: 0; width: 1.3rem; height: 1.3rem; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; font-size: .68rem;
        background: var(--mist); color: var(--muted); border: 2px solid var(--surface); box-shadow: 0 0 0 1px var(--line-strong);
    }
    .status-timeline li.is-approved .tl-dot { background: var(--teal-soft); color: var(--teal-dark); box-shadow: 0 0 0 1px color-mix(in srgb, var(--teal) 30%, transparent); }
    .status-timeline li.is-processing .tl-dot { background: var(--info-soft); color: var(--info); box-shadow: 0 0 0 1px color-mix(in srgb, var(--info) 30%, transparent); }
    .status-timeline li.is-rejected .tl-dot { background: var(--coral-soft); color: var(--coral); box-shadow: 0 0 0 1px color-mix(in srgb, var(--coral) 30%, transparent); }
    .status-timeline li.is-pending .tl-dot { background: var(--amber-soft); color: var(--amber-ink); box-shadow: 0 0 0 1px color-mix(in srgb, var(--amber) 35%, transparent); }
    .status-timeline .tl-title { font-weight: 600; font-size: .88rem; color: var(--ink); }
    .status-timeline .tl-meta { font-size: .74rem; color: var(--muted); font-family: var(--mono); margin-top: .1rem; }
    .status-timeline .tl-note { font-size: .8rem; color: var(--muted); margin-top: .35rem; background: var(--mist); border-radius: .6rem; padding: .5rem .7rem; }
    .status-timeline li.is-future .tl-title { color: var(--muted); }
    .status-timeline li.is-future .tl-dot { opacity: .5; }

    /* ---------- Kartu dokumen ---------- */
    .doc-card { border: 1px solid var(--line); border-radius: var(--radius); padding: 1rem; height: 100%; display: flex; flex-direction: column; gap: .5rem; background: var(--surface); transition: border-color .25s ease, transform .35s var(--ease-out), box-shadow .35s var(--ease-out); }
    @media (hover: hover) { .doc-card:hover { border-color: var(--line-strong); transform: translateY(-2px); box-shadow: 0 12px 28px -16px var(--shadow-4); } }
    .doc-card .doc-ico { width: 40px; height: 40px; border-radius: .65rem; background: var(--mist); color: var(--ink); display: flex; align-items: center; justify-content: center; font-size: 1.05rem; flex: 0 0 auto; }
    .doc-empty-slot { border: 1.5px dashed var(--line-strong); border-radius: var(--radius); padding: 1rem; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: .4rem; color: var(--muted); text-align: center; transition: border-color .25s ease, background-color .25s ease; }
    .doc-empty-slot:hover { border-color: var(--teal); background: var(--teal-soft); }

    /* ---------- Navigasi bawah (mobile) ---------- */
    .bottom-nav {
        position: fixed; bottom: 0; left: 0; right: 0; z-index: 1030;
        background: color-mix(in srgb, var(--surface) 88%, transparent);
        -webkit-backdrop-filter: saturate(1.5) blur(18px); backdrop-filter: saturate(1.5) blur(18px);
        border-top: 1px solid var(--line);
        display: flex; align-items: center; justify-content: space-around;
        padding: .4rem .5rem calc(.4rem + env(safe-area-inset-bottom));
    }
    .bottom-nav a {
        position: relative; color: var(--muted); font-size: .64rem; text-align: center; flex: 1; padding: .3rem .1rem;
        display: flex; flex-direction: column; align-items: center; gap: .18rem; font-weight: 560; letter-spacing: .005em;
        transition: color .2s ease;
    }
    .bottom-nav a i { font-size: 1.22rem; transition: transform .35s var(--ease-out); }
    .bottom-nav a:active i { transform: scale(.86); }
    .bottom-nav a.active { color: var(--teal); }
    .bottom-nav a.active i { transform: translateY(-1px); }
    .bottom-nav a.active::after { content: ''; position: absolute; top: -.4rem; width: 18px; height: 3px; border-radius: 0 0 3px 3px; background: var(--teal); animation: dotIn .4s var(--ease-out) both; }
    @keyframes dotIn { from { transform: scaleX(0); opacity: 0; } }
    .bottom-nav .scan-fab-wrap { flex: 0 0 auto; width: 64px; }
    .bottom-nav .scan-fab-wrap a::after { display: none; }
    .bottom-nav .scan-fab {
        width: 52px; height: 52px; border-radius: 50%; margin: -28px auto 0;
        background: var(--teal); color: #fff; display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem; box-shadow: 0 10px 22px -8px color-mix(in srgb, var(--teal) 70%, #000);
        border: 4px solid var(--mist);
        transition: transform .35s var(--ease-out), box-shadow .3s ease;
    }
    .bottom-nav .scan-fab-wrap a:active .scan-fab { transform: scale(.92); }
    [data-bs-theme="dark"] .bottom-nav .scan-fab { color: #0d1a15; }
    .bottom-nav .scan-label { color: var(--teal); margin-top: .15rem; }

    @media (max-width: 767.98px) {
        body { padding-bottom: 0; }
        .mobile-nav-spacer { display: block; height: 96px; width: 100%; }
    }
    @media print { .bottom-nav, .topbar, .nav-progress { display: none !important; } }

    /* ---------- Menu "Lainnya" (offcanvas bawah) ---------- */
    .more-menu-offcanvas { border-top-left-radius: 1.3rem; border-top-right-radius: 1.3rem; height: auto !important; max-height: calc(100vh - 96px); box-shadow: 0 -16px 40px -12px var(--shadow-5); border: 1px solid var(--line); border-bottom: 0; }
    .more-menu-offcanvas::before { content: ''; position: absolute; top: .55rem; left: 50%; transform: translateX(-50%); width: 36px; height: 4px; border-radius: 50rem; background: var(--line-strong); }
    .more-menu-offcanvas .offcanvas-header { border-bottom: 1px solid var(--line); padding: 1.15rem 1.1rem .7rem; }
    .more-menu-offcanvas .offcanvas-title { font-family: var(--display); font-size: .96rem; font-weight: 620; }
    .more-menu-offcanvas .offcanvas-body { padding: .9rem 1.1rem calc(1.1rem + 82px + env(safe-area-inset-bottom)); overflow-y: visible; }
    .more-menu-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: .6rem; }
    .more-menu-item {
        position: relative; display: flex; flex-direction: column; align-items: center; gap: .45rem;
        padding: .85rem .3rem; border-radius: .9rem; background: var(--mist); color: var(--ink);
        font-size: .74rem; font-weight: 560; text-align: center; border: 1px solid var(--line);
        transition: transform .25s var(--ease-out), background-color .2s ease, border-color .2s ease;
    }
    .more-menu-item:active { transform: scale(.94); }
    .more-menu-item.active { background: var(--teal-soft); color: var(--teal-dark); border-color: color-mix(in srgb, var(--teal) 30%, var(--line)); }
    .more-menu-item.active .more-menu-ico { background: var(--teal); color: #fff; }
    .more-menu-ico { width: 38px; height: 38px; border-radius: .7rem; background: var(--surface); color: var(--ink); display: flex; align-items: center; justify-content: center; font-size: 1.05rem; box-shadow: 0 1px 2px var(--shadow-2); }
    .more-menu-badge { position: absolute; top: .35rem; right: .35rem; background: var(--coral) !important; color: #fff !important; font-family: var(--mono); }
    .offcanvas.show .more-menu-item { animation: itemIn .5s var(--ease-out) both; }
    .offcanvas.show .more-menu-item:nth-child(1) { animation-delay: .06s; } .offcanvas.show .more-menu-item:nth-child(2) { animation-delay: .09s; }
    .offcanvas.show .more-menu-item:nth-child(3) { animation-delay: .12s; } .offcanvas.show .more-menu-item:nth-child(4) { animation-delay: .15s; }
    .offcanvas.show .more-menu-item:nth-child(5) { animation-delay: .18s; } .offcanvas.show .more-menu-item:nth-child(6) { animation-delay: .21s; }
    .offcanvas.show .more-menu-item:nth-child(n+7) { animation-delay: .24s; }
    @keyframes itemIn { from { opacity: 0; transform: translateY(10px); } }

    /* ---------- Hamburger ---------- */
    .navbar-toggler.app-toggler { width: 40px; height: 40px; border-radius: .7rem; padding: 0; display: flex; align-items: center; justify-content: center; background: transparent; box-shadow: none !important; transition: background-color .2s ease; }
    .navbar-toggler.app-toggler:hover { background: color-mix(in srgb, var(--ink) 6%, transparent); }
    .app-toggler .toggler-icon { position: relative; width: 20px; height: 14px; display: block; }
    .app-toggler .toggler-icon span { position: absolute; left: 0; width: 100%; height: 1.75px; border-radius: 2px; background: var(--ink); transition: transform .4s var(--ease-out), opacity .2s ease, top .4s var(--ease-out), width .3s var(--ease-out); }
    .app-toggler .toggler-icon span:nth-child(1) { top: 0; }
    .app-toggler .toggler-icon span:nth-child(2) { top: 6px; width: 65%; }
    .app-toggler .toggler-icon span:nth-child(3) { top: 12px; }
    .app-toggler:not(.collapsed) .toggler-icon span:nth-child(1) { top: 6px; transform: rotate(45deg); }
    .app-toggler:not(.collapsed) .toggler-icon span:nth-child(2) { opacity: 0; width: 100%; }
    .app-toggler:not(.collapsed) .toggler-icon span:nth-child(3) { top: 6px; transform: rotate(-45deg); }

    /* ---------- Panel menu mobile ---------- */
    @media (max-width: 767.98px) {
        .navbar-collapse { margin-top: .7rem; background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius); box-shadow: 0 16px 36px -14px var(--shadow-4); overflow: hidden; }
        .navbar-nav.ms-auto { margin-left: 0 !important; gap: 0 !important; }
    }
    .mobile-menu-section { padding: .9rem 1rem; border-bottom: 1px solid var(--line); }
    .mobile-menu-section:last-child { border-bottom: 0; }
    .mobile-menu-heading { display: flex; align-items: center; justify-content: space-between; font-family: var(--display); font-weight: 620; font-size: .85rem; color: var(--ink); margin-bottom: .6rem; }
    .mobile-menu-heading i { color: var(--teal); margin-right: .3rem; }
    .mobile-notif-list { max-height: 260px; overflow-y: auto; display: flex; flex-direction: column; gap: .5rem; }
    .mobile-notif-row { display: flex; align-items: flex-start; gap: .6rem; padding: .6rem .7rem; background: var(--mist); border-radius: .7rem; }
    .mobile-notif-row .notif-text { font-size: .8rem; color: var(--ink); line-height: 1.4; margin: 0; }
    .mobile-profile-card { display: flex; align-items: center; gap: .7rem; margin-bottom: .8rem; }
    .mobile-profile-card i.bi-person-circle { font-size: 2.4rem; color: var(--muted); }
    .mobile-profile-info { display: flex; flex-direction: column; gap: .25rem; }
    .mobile-profile-info strong { font-size: .95rem; color: var(--ink); font-weight: 600; }
    .mobile-menu-link { display: flex; align-items: center; gap: .6rem; padding: .7rem .8rem; border-radius: .7rem; background: var(--mist); color: var(--ink); font-weight: 560; font-size: .86rem; margin-bottom: .5rem; transition: background-color .2s ease, transform .2s var(--ease-out); }
    .mobile-menu-link:active { transform: scale(.98); }
    .mobile-menu-link i { color: var(--teal); }

    /* ---------- Toast ---------- */
    .toast-stack { position: fixed; top: 84px; right: 18px; z-index: 1080; display: flex; flex-direction: column; gap: .6rem; width: min(360px, calc(100vw - 36px)); pointer-events: none; }
    @media (max-width: 767.98px) { .toast-stack { top: auto; bottom: 96px; right: 12px; left: 12px; width: auto; } }
    .toast-pop {
        pointer-events: auto; position: relative; overflow: hidden; display: flex; align-items: flex-start; gap: .7rem;
        background: var(--surface); border: 1px solid var(--line); border-radius: .9rem;
        box-shadow: 0 16px 34px -12px var(--shadow-4), 0 2px 6px var(--shadow-1);
        padding: .8rem .9rem .9rem .95rem; opacity: 0; transform: translateX(28px) scale(.97);
        animation: toastIn .5s var(--ease-out) forwards;
    }
    .toast-pop.is-leaving { animation: toastOut .3s var(--ease-out) forwards; }
    @keyframes toastIn { to { opacity: 1; transform: none; } }
    @keyframes toastOut { to { opacity: 0; transform: translateX(18px) scale(.97); } }
    @media (max-width: 767.98px) {
        .toast-pop { transform: translateY(16px) scale(.97); }
        @keyframes toastOut { to { opacity: 0; transform: translateY(10px) scale(.97); } }
    }
    .toast-pop .toast-icon { flex: 0 0 auto; width: 32px; height: 32px; border-radius: .6rem; background: var(--toast-accent-soft, var(--teal-soft)); color: var(--toast-accent, var(--teal-dark)); display: flex; align-items: center; justify-content: center; font-size: 1rem; }
    .toast-pop .toast-body { flex: 1 1 auto; min-width: 0; padding: 0; }
    .toast-pop .toast-title { font-weight: 620; font-size: .84rem; color: var(--ink); margin: 0 0 .1rem; }
    .toast-pop .toast-msg { font-size: .82rem; color: var(--muted); line-height: 1.4; word-wrap: break-word; }
    .toast-pop .toast-close { flex: 0 0 auto; border: 0; background: transparent; color: var(--muted); font-size: .9rem; line-height: 1; padding: .2rem; margin: -.1rem -.2rem 0 0; border-radius: .4rem; transition: background-color .2s ease, color .2s ease; }
    .toast-pop .toast-close:hover { background: var(--mist); color: var(--ink); }
    .toast-pop .toast-progress { position: absolute; left: 0; bottom: 0; height: 2px; width: 100%; background: var(--toast-accent, var(--teal)); opacity: .45; transform-origin: left; animation: toastShrink linear forwards; }
    @keyframes toastShrink { from { transform: scaleX(1); } to { transform: scaleX(0); } }
    .toast-pop.toast-success { --toast-accent: var(--teal); --toast-accent-soft: var(--teal-soft); }
    .toast-pop.toast-error   { --toast-accent: var(--coral); --toast-accent-soft: var(--coral-soft); }
    .toast-pop.toast-warning { --toast-accent: var(--amber); --toast-accent-soft: var(--amber-soft); }
    .toast-pop.toast-info    { --toast-accent: var(--info); --toast-accent-soft: var(--info-soft); }

    /* ---------- Dropdown notifikasi ---------- */
    .notif-menu { padding: 0 !important; overflow: hidden; border-radius: 1rem; border-color: var(--line); box-shadow: 0 20px 44px -16px var(--shadow-5); }
    .notif-menu .notif-head { display: flex; align-items: center; justify-content: space-between; padding: .85rem 1rem .7rem; border-bottom: 1px solid var(--line); background: var(--mist); }
    .notif-menu .notif-head strong { font-family: var(--display); font-size: .88rem; font-weight: 620; }
    .notif-menu .notif-head .notif-count { font-family: var(--mono); font-size: .68rem; font-weight: 600; color: var(--muted); background: var(--surface); border: 1px solid var(--line); border-radius: 50rem; padding: .1rem .5rem; }
    .notif-menu .notif-list { max-height: 340px; overflow-y: auto; }
    .notif-menu .notif-row { display: flex; align-items: flex-start; gap: .65rem; padding: .75rem 1rem; border-bottom: 1px solid var(--line); transition: background-color .15s ease; }
    .notif-menu .notif-row:last-child { border-bottom: 0; }
    .notif-menu .notif-row:hover { background: var(--mist); }
    .notif-menu .notif-row .notif-ico { flex: 0 0 auto; width: 32px; height: 32px; border-radius: .65rem; display: flex; align-items: center; justify-content: center; font-size: .95rem; background: var(--mist); }
    .notif-menu .notif-row .notif-text { font-size: .82rem; color: var(--ink); line-height: 1.4; margin: 0; }
    .notif-menu .notif-empty { padding: 2.2rem 1rem; text-align: center; color: var(--muted); }
    .notif-menu .notif-empty i { font-size: 1.6rem; display: block; margin-bottom: .4rem; opacity: .5; }
    .notif-menu .notif-empty span { font-size: .82rem; }
    .notif-menu .notif-foot { padding: .55rem 1rem; text-align: center; border-top: 1px solid var(--line); background: var(--mist); }
    .notif-menu .notif-foot span { font-size: .74rem; color: var(--muted); font-weight: 560; }
    #notifDropdown { transition: transform .2s var(--ease-out); }
    #notifDropdown:active { transform: scale(.92); }
    .notif-dot { animation: notifPulse 2.4s ease-in-out infinite; }
    @keyframes notifPulse { 0%,100% { box-shadow: 0 0 0 2px var(--surface), 0 0 0 2px rgba(196,69,63,.0); } 50% { box-shadow: 0 0 0 2px var(--surface), 0 0 0 5px rgba(196,69,63,.18); } }

    /* ---------- Modal konfirmasi ---------- */
    .confirm-overlay { position: fixed; inset: 0; z-index: 2000; background: rgba(17,16,13,.42); -webkit-backdrop-filter: blur(4px); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; padding: 1.1rem; opacity: 0; animation: confirmOverlayIn .28s ease forwards; }
    .confirm-overlay.is-leaving { animation: confirmOverlayOut .2s ease forwards; }
    @keyframes confirmOverlayIn { to { opacity: 1; } }
    @keyframes confirmOverlayOut { to { opacity: 0; } }
    .confirm-card {
        width: 100%; max-width: 396px; background: var(--surface); border: 1px solid var(--line);
        border-radius: 1.2rem; box-shadow: 0 34px 70px -18px var(--shadow-5);
        padding: 1.6rem 1.5rem 1.35rem; position: relative; overflow: hidden;
        transform: translateY(14px) scale(.96); opacity: 0; animation: confirmCardIn .45s var(--ease-out) forwards;
    }
    .confirm-overlay.is-leaving .confirm-card { animation: confirmCardOut .2s ease forwards; }
    @keyframes confirmCardIn { to { transform: none; opacity: 1; } }
    @keyframes confirmCardOut { to { transform: translateY(6px) scale(.97); opacity: 0; } }
    .confirm-icon { width: 46px; height: 46px; border-radius: .85rem; margin-bottom: 1rem; background: var(--confirm-accent-soft, var(--amber-soft)); color: var(--confirm-accent, var(--amber-ink)); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
    .confirm-title { font-family: var(--display); font-weight: 620; font-size: 1.08rem; color: var(--ink); margin: 0 0 .4rem; }
    .confirm-message { font-size: .87rem; color: var(--muted); line-height: 1.6; margin: 0 0 1.5rem; word-wrap: break-word; }
    .confirm-actions { display: flex; gap: .6rem; justify-content: flex-end; }
    .confirm-actions .btn { min-width: 92px; }
    .confirm-ok { color: #fff; border: none; }
    .confirm-ok-danger  { background: var(--coral); }
    .confirm-ok-danger:hover  { background: color-mix(in srgb, var(--coral) 86%, #000); }
    .confirm-ok-warning { background: var(--ink-fixed); }
    .confirm-ok-warning:hover { background: var(--ink-fixed-2); color: #fff; }
    .confirm-ok-success { background: var(--teal); }
    .confirm-ok-success:hover { background: var(--teal-dark); color: #fff; }
    .confirm-ok-info    { background: var(--info); }
    .confirm-ok-info:hover { background: color-mix(in srgb, var(--info) 86%, #000); color: #fff; }
    [data-bs-theme="dark"] .confirm-ok-warning { background: #ecEAe4; color: #131412; }
    [data-bs-theme="dark"] .confirm-ok-success, [data-bs-theme="dark"] .confirm-ok-danger, [data-bs-theme="dark"] .confirm-ok-info { color: #0d1210; }
    .confirm-card.confirm-danger  { --confirm-accent: var(--coral); --confirm-accent-soft: var(--coral-soft); }
    .confirm-card.confirm-warning { --confirm-accent: var(--amber-ink); --confirm-accent-soft: var(--amber-soft); }
    .confirm-card.confirm-success { --confirm-accent: var(--teal-dark); --confirm-accent-soft: var(--teal-soft); }
    .confirm-card.confirm-info    { --confirm-accent: var(--info); --confirm-accent-soft: var(--info-soft); }
    @media (max-width: 420px) { .confirm-actions { flex-direction: column-reverse; } .confirm-actions .btn { width: 100%; } }

    /* ---------- Responsif: sentuhan mobile ---------- */
    @media (max-width: 767.98px) {
        body { font-size: .92rem; }
        /* 16px mencegah iOS memperbesar layar saat input difokuskan */
        .form-control, .form-select { font-size: 16px; min-height: 46px; }
        .btn { min-height: 44px; }
        .btn-sm { min-height: 38px; }
        .page-main { padding-left: 1rem !important; padding-right: 1rem !important; }
        .table-responsive { margin-left: -.15rem; margin-right: -.15rem; }
        .modal-dialog:not(.modal-dialog-centered) { margin: .75rem; }
        .modal-dialog.modal-dialog-scrollable .modal-content { max-height: calc(100dvh - 1.5rem); }
    }
</style>
<?php /**PATH C:\laragon\www\ecommerce\resources\views/layouts/_design-system.blade.php ENDPATH**/ ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Pasarku'); ?> — Belanja Langsung dari Penjual Lokal</title>
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', 'Pasarku menghubungkan kamu dengan penjual lokal terpercaya. Belanja langsung, dukung usaha kecil, semuanya dalam satu tempat.'); ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Pasarku">
    <meta property="og:title" content="<?php echo $__env->yieldContent('title', 'Pasarku'); ?> — Belanja Langsung dari Penjual Lokal">
    <meta property="og:description" content="<?php echo $__env->yieldContent('meta_description', 'Pasarku menghubungkan kamu dengan penjual lokal terpercaya. Belanja langsung, dukung usaha kecil, semuanya dalam satu tempat.'); ?>">
    <?php if (! empty(trim($__env->yieldContent('meta_image')))): ?><meta property="og:image" content="<?php echo $__env->yieldContent('meta_image'); ?>"><?php endif; ?>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="theme-color" content="#6d28d9">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <script>
        try {
            if (localStorage.getItem('pasarku_theme') === 'dark') {
                document.documentElement.classList.add('dark-mode');
            }
        } catch (e) {}
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        clay: { 50:'#f5f3ff', 100:'#ede9fe', 200:'#ddd6fe', 300:'#c4b5fd', 400:'#a78bfa', 500:'#7c3aed', 600:'#6d28d9', 700:'#5b21b6', 800:'#4c1d95', 900:'#2e1065' },
                        forest: { 50:'#ecfdf5', 100:'#d1fae5', 200:'#a7f3d0', 300:'#6ee7b7', 400:'#34d399', 500:'#10b981', 600:'#059669', 700:'#047857', 800:'#065f46', 900:'#064e3b' },
                        ink: { 50:'#f8fafc', 100:'#f1f5f9', 200:'#e2e8f0', 300:'#cbd5e1', 400:'#94a3b8', 500:'#64748b', 600:'#475569', 700:'#334155', 800:'#1e293b', 900:'#0f172a' },
                        price: { DEFAULT:'#d0021b', 50:'#fef2f2' },
                    },
                    fontFamily: {
                        serif: ['Sora', 'sans-serif'],
                        sans: ['Inter', 'sans-serif'],
                    },
                    borderRadius: {
                        DEFAULT: '0.5rem',
                    },
                    boxShadow: {
                        soft: '0 1px 2px rgba(15,23,42,0.06), 0 1px 6px -2px rgba(15,23,42,0.06)',
                        card: '0 1px 2px rgba(15,23,42,0.05), 0 4px 14px -6px rgba(15,23,42,0.12)',
                        glow: '0 1px 2px rgba(109,40,217,0.15), 0 2px 8px -2px rgba(109,40,217,0.18)',
                    },
                }
            }
        }
    </script>
    <style>
        html { -webkit-text-size-adjust: 100%; }
        /* overflow-x: clip (bukan hidden) — tetap mencegah scroll horizontal tanpa
           membuat body jadi scroll container, yang bisa merusak position:sticky (header). */
        html, body { max-width: 100%; overflow-x: clip; }
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .font-serif { font-family: 'Sora', sans-serif; letter-spacing: -0.01em; }
        img, svg, video { max-width: 100%; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
        ::-webkit-scrollbar-track { background: transparent; }
        .texture-dots { background-image: radial-gradient(#7c3aed22 1px, transparent 1px); background-size: 18px 18px; }
        .texture-grain { position: relative; }
        .texture-grain::after {
            content: ''; position: absolute; inset: 0; pointer-events: none; opacity: .25; mix-blend-mode: overlay;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='140' height='140'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.35'/%3E%3C/svg%3E");
        }
        .btn-tactile { transition: transform .15s ease, background-color .2s ease, box-shadow .2s ease; }
        .btn-tactile:active { transform: scale(0.96); }
        a, button { -webkit-tap-highlight-color: transparent; }
        .link-underline { position: relative; }
        .link-underline::after { content: ''; position: absolute; left: 0; right: 100%; bottom: -2px; height: 1.5px; background: currentColor; transition: right .25s ease; }
        .link-underline:hover::after { right: 0; }
        .gradient-brand { background-image: linear-gradient(180deg, #7c3aed 0%, #6d28d9 100%); }
        .gradient-mesh { background-image: radial-gradient(at 15% 15%, rgba(124,58,237,0.08) 0px, transparent 45%), radial-gradient(at 85% 0%, rgba(16,185,129,0.06) 0px, transparent 45%); }
        .glass { background: rgba(255,255,255,0.92); backdrop-filter: blur(10px) saturate(140%); -webkit-backdrop-filter: blur(10px) saturate(140%); }

        /* ===== Elemen khas marketplace ===== */
        /* Kartu produk: kotak, border tipis, radius kecil — bukan pil/rounded besar */
        .market-card { border-radius: 0.5rem; border: 1px solid #eef0f3; background: #fff; transition: box-shadow .15s ease, border-color .15s ease; }
        .market-card:hover { box-shadow: 0 2px 10px -2px rgba(15,23,42,0.12); border-color: #e2e8f0; }
        /* Harga: merah tegas khas marketplace, harga coret abu-abu */
        .price-tag { color: #d0021b; font-weight: 700; }
        .price-strike { color: #94a3b8; text-decoration: line-through; font-weight: 400; }
        .badge-discount { background: #d0021b; color: #fff; font-weight: 700; }
        /* Bar kategori di bawah header */
        .category-bar { border-bottom: 1px solid #eef0f3; background: #fff; }
        .category-bar a { transition: color .15s ease, border-color .15s ease; }
        /* Tab & filter: garis bawah, bukan pil */
        .tab-link { border-bottom: 2px solid transparent; transition: border-color .15s ease, color .15s ease; }
        .tab-link.active { border-color: #6d28d9; color: #6d28d9; }
        @media (prefers-reduced-motion: reduce) { * { transition-duration: 0.01ms !important; } }

        /* ===== Mode Gelap ===== */
        /* Palet warna gelap asli (bukan filter invert) supaya warna brand (ungu/hijau) tetap akurat
           dan gambar produk tidak ikut terdistorsi. Aturan di bawah menimpa kelas-kelas warna Tailwind
           yang paling sering dipakai di seluruh halaman, jadi berlaku otomatis tanpa perlu menulis
           ulang setiap file blade. */
        html.dark-mode { color-scheme: dark; }
        html.dark-mode body { background: #0b1120; color: #e2e8f0; }

        /* Permukaan kartu / panel */
        html.dark-mode .bg-white { background-color: #1e293b !important; }
        html.dark-mode .bg-ink-50 { background-color: #182234 !important; }
        html.dark-mode .bg-ink-100 { background-color: #263449 !important; }
        html.dark-mode .bg-ink-200 { background-color: #334155 !important; }
        html.dark-mode .bg-ink-300 { background-color: #475569 !important; }
        html.dark-mode .bg-ink-900 { background-color: #060a14 !important; }
        html.dark-mode .glass { background: rgba(15,23,42,0.85) !important; }

        /* Teks */
        html.dark-mode .text-ink-900,
        html.dark-mode .text-ink-800 { color: #f1f5f9 !important; }
        html.dark-mode .text-ink-700,
        html.dark-mode .text-ink-600 { color: #cbd5e1 !important; }
        html.dark-mode .text-ink-500,
        html.dark-mode .text-ink-400 { color: #94a3b8 !important; }
        html.dark-mode .text-ink-300 { color: #64748b !important; }

        /* Border & divider */
        html.dark-mode .border-ink-100,
        html.dark-mode .border-ink-200,
        html.dark-mode .border-ink-300,
        html.dark-mode .divide-ink-100 > * + * { border-color: rgba(255,255,255,0.1) !important; }
        html.dark-mode .ring-ink-100 { --tw-ring-color: rgba(255,255,255,0.12) !important; }
        html.dark-mode .ring-white { --tw-ring-color: #1e293b !important; }

        /* Aksen ungu (clay) */
        html.dark-mode .bg-clay-50,
        html.dark-mode .bg-clay-100 { background-color: rgba(124,58,237,0.18) !important; }
        html.dark-mode .text-clay-600,
        html.dark-mode .text-clay-700 { color: #c4b5fd !important; }
        html.dark-mode .border-clay-200,
        html.dark-mode .border-clay-300,
        html.dark-mode .border-clay-400 { border-color: rgba(124,58,237,0.45) !important; }

        /* Aksen hijau (forest) */
        html.dark-mode .bg-forest-50,
        html.dark-mode .bg-forest-100 { background-color: rgba(16,185,129,0.16) !important; }
        html.dark-mode .text-forest-600,
        html.dark-mode .text-forest-700 { color: #6ee7b7 !important; }
        html.dark-mode .border-forest-300 { border-color: rgba(16,185,129,0.35) !important; }

        /* Kartu produk & bar kategori (class custom, bukan utility Tailwind biasa,
           jadi harus ditimpa manual — sebelumnya terlewat sehingga tetap putih terang
           saat mode gelap aktif) */
        html.dark-mode .market-card { background: #1e293b; border-color: rgba(255,255,255,0.08); }
        html.dark-mode .market-card:hover { border-color: rgba(255,255,255,0.18); box-shadow: 0 2px 14px -2px rgba(0,0,0,0.5); }
        html.dark-mode .category-bar { background: #101a2e; border-color: rgba(255,255,255,0.08); }

        /* Beberapa shade ink yang belum tercakup di atas */
        html.dark-mode .bg-ink-200 { background-color: #334155 !important; }
        html.dark-mode .text-ink-200 { color: #475569 !important; }

        /* Aksen merah (rose) — error, wishlist, dsb */
        html.dark-mode .bg-rose-50,
        html.dark-mode .bg-rose-100 { background-color: rgba(244,63,94,0.16) !important; }
        html.dark-mode .text-rose-500,
        html.dark-mode .text-rose-600,
        html.dark-mode .text-rose-700 { color: #fda4af !important; }
        html.dark-mode .border-rose-200,
        html.dark-mode .border-rose-300 { border-color: rgba(244,63,94,0.32) !important; }

        /* Aksen kuning (amber) — badge status pending/menunggu */
        html.dark-mode .bg-amber-50,
        html.dark-mode .bg-amber-100 { background-color: rgba(245,158,11,0.16) !important; }
        html.dark-mode .text-amber-600,
        html.dark-mode .text-amber-700 { color: #fcd34d !important; }
        html.dark-mode .border-amber-200,
        html.dark-mode .border-amber-300 { border-color: rgba(245,158,11,0.35) !important; }

        /* Badge status pesanan (biru/indigo/hijau tosca/abu-abu) */
        html.dark-mode .bg-blue-50 { background-color: rgba(59,130,246,0.16) !important; }
        html.dark-mode .text-blue-700 { color: #93c5fd !important; }
        html.dark-mode .bg-indigo-50 { background-color: rgba(99,102,241,0.18) !important; }
        html.dark-mode .text-indigo-700 { color: #a5b4fc !important; }
        html.dark-mode .bg-emerald-50 { background-color: rgba(16,185,129,0.16) !important; }
        html.dark-mode .text-emerald-700 { color: #6ee7b7 !important; }
        html.dark-mode .bg-slate-50 { background-color: rgba(148,163,184,0.16) !important; }
        html.dark-mode .text-slate-700 { color: #cbd5e1 !important; }

        /* Form & bayangan */
        /* Penting: elemen form tidak selalu punya class bg-white eksplisit, jadi warna latar
           belakangnya harus disamakan di sini juga — kalau tidak, teks jadi terang di atas
           latar putih bawaan browser (tidak terbaca) saat mode gelap aktif. */
        html.dark-mode input:not([type="checkbox"]):not([type="radio"]),
        html.dark-mode select,
        html.dark-mode textarea {
            background-color: #1e293b !important;
            color: #e2e8f0 !important;
            border-color: rgba(255,255,255,0.14);
        }
        html.dark-mode option { background-color: #1e293b; color: #e2e8f0; }
        html.dark-mode input::placeholder,
        html.dark-mode textarea::placeholder { color: #64748b; opacity: 1; }
        html.dark-mode .shadow-soft,
        html.dark-mode .shadow-card { box-shadow: 0 8px 24px -8px rgba(0,0,0,0.55) !important; }
        html.dark-mode ::-webkit-scrollbar-thumb { background: #334155; }
        html.dark-mode .texture-dots { background-image: radial-gradient(#a78bfa22 1px, transparent 1px); }
        html.dark-mode .hover\:bg-ink-50:hover,
        html.dark-mode .hover\:bg-ink-100:hover { background-color: rgba(255,255,255,0.08) !important; }
        html.dark-mode .hover\:border-clay-300:hover { border-color: rgba(124,58,237,0.5) !important; }

        .theme-toggle-btn { transition: background-color .2s ease, color .2s ease; }
        html.dark-mode .theme-toggle-icon-moon { display: none; }
        html:not(.dark-mode) .theme-toggle-icon-sun { display: none; }

        /* ===== Flash Sale ===== */
        /* Motif garis diagonal ala pita peringatan — cocok secara tematik dengan
           kesan "buru-buru, stok terbatas" pada flash sale, bukan sekadar dekorasi. */
        .flash-stripe { background-image: repeating-linear-gradient(135deg, rgba(255,255,255,0.07) 0 10px, transparent 10px 22px); }
        .flash-card { transition: transform .2s ease, box-shadow .2s ease; }
        .flash-card:hover { transform: translateY(-3px); box-shadow: 0 10px 28px -8px rgba(244,63,94,0.4); }
        .flash-ribbon { clip-path: polygon(0 0, 100% 0, 100% 70%, 50% 100%, 0 70%); }
        @keyframes flash-pulse { 0%, 100% { opacity: 1; } 50% { opacity: .35; } }
        .flash-pulse { animation: flash-pulse 1.4s ease-in-out infinite; }

        /* ===== Mode Cetak (struk pesanan, dsb) ===== */
        @media print {
            header, footer, #nav-progress, #promo-banner, #toast-container, nav.md\:hidden, .theme-toggle-btn { display: none !important; }
            body { background: #fff !important; padding-bottom: 0 !important; }
            #app-main { margin: 0 !important; }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<?php ($isAuthPage = request()->routeIs('login', 'register')); ?>
<body class="text-ink-800 antialiased <?php echo e($isAuthPage ? '' : 'pb-16 md:pb-0'); ?>">

    <div id="nav-progress" class="fixed top-0 left-0 h-[3px] gradient-brand z-[70] w-0 opacity-0"></div>

    <?php if (! ($isAuthPage)): ?>
    <div id="promo-banner" class="bg-ink-900 text-ink-300 text-[11px] sm:text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-8 flex items-center justify-center sm:justify-between overflow-hidden">
            <p class="font-medium tracking-wide truncate"><span class="text-forest-400">✦</span> Belanja langsung dari penjual lokal — ongkir ringan, transaksi aman.</p>
            <p class="hidden sm:flex items-center gap-4 text-ink-400">
                <span>Sudah dipercaya <?php echo e(\App\Models\User::where('role','seller')->count()); ?>+ penjual</span>
                <a href="<?php echo e(route('register')); ?>" class="hover:text-white transition-colors">Mulai Jualan</a>
            </p>
        </div>
    </div>
    <?php endif; ?>

    <header class="sticky top-0 z-40 bg-clay-600 shadow-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-3 sm:gap-5">
                <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2 shrink-0 group">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-white text-clay-700 font-serif text-lg font-bold group-hover:scale-105 transition-transform duration-300">P</span>
                    <span class="font-serif text-xl font-bold tracking-tight text-white hidden xs:inline">Pasarku</span>
                </a>

                <form action="<?php echo e(route('home')); ?>" method="GET" class="<?php if (! ($isAuthPage)): ?> hidden md:flex <?php else: ?> hidden <?php endif; ?> flex-1 max-w-2xl">
                    <div class="relative w-full search-wrap flex">
                        <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="Cari produk, toko, atau kategori..." autocomplete="off"
                            class="search-input w-full rounded-l-md rounded-r-none border-0 bg-white pl-4 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-300 transition">
                        <button type="submit" class="shrink-0 rounded-r-md bg-clay-800 hover:bg-clay-900 px-4 flex items-center justify-center transition-colors" aria-label="Cari">
                            <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" /></svg>
                        </button>
                        <div class="search-suggest absolute left-0 right-0 top-full mt-2 rounded-lg border border-ink-100 bg-white shadow-xl shadow-ink-900/10 py-2 hidden z-50 max-h-96 overflow-y-auto"></div>
                    </div>
                </form>

                <nav class="flex items-center gap-1.5 sm:gap-3 shrink-0">
                    <button type="button" class="theme-toggle-btn hidden sm:inline-flex p-2.5 rounded-full hover:bg-white/15 active:scale-90 transition-all" title="Ganti mode gelap/terang" aria-label="Ganti mode gelap/terang">
                        <svg class="theme-toggle-icon-moon h-5 w-5 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" /></svg>
                        <svg class="theme-toggle-icon-sun h-5 w-5 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-6.364-2.386 1.591-1.591M3 12h2.25m.386-6.364 1.591 1.591M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </button>
                    <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->user()->role === 'buyer'): ?>
                            <a href="<?php echo e(route('wishlist.index')); ?>" class="hidden sm:inline-flex relative p-2.5 rounded-full hover:bg-white/15 active:scale-90 transition-all" title="Wishlist">
                                <svg class="h-5 w-5 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                                <?php if(auth()->user()->wishlists()->count()): ?>
                                <span class="absolute -top-0.5 -right-0.5 h-4 w-4 rounded-full bg-rose-500 text-white text-[10px] font-bold flex items-center justify-center ring-2 ring-white"><?php echo e(auth()->user()->wishlists()->count()); ?></span>
                                <?php endif; ?>
                            </a>
                            <a href="<?php echo e(route('cart.index')); ?>" class="relative p-2.5 rounded-full hover:bg-white/15 active:scale-90 transition-all" title="Keranjang">
                                <svg class="h-5 w-5 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.836l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 1.994-4.694 2.615-7.152.083-.329-.153-.648-.494-.648H5.106M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                                <?php if(auth()->user()->cartItems()->count()): ?>
                                    <span class="absolute -top-0.5 -right-0.5 h-4 w-4 rounded-full bg-clay-500 text-white text-[10px] font-bold flex items-center justify-center ring-2 ring-white"><?php echo e(auth()->user()->cartItems()->count()); ?></span>
                                <?php endif; ?>
                            </a>
                            <a href="<?php echo e(route('orders.index')); ?>" class="hidden sm:inline-block link-underline text-sm font-semibold text-white/90 hover:text-white px-2">Pesanan</a>
                        <?php elseif(auth()->user()->role === 'seller'): ?>
                            <a href="<?php echo e(route('seller.dashboard')); ?>" class="hidden sm:inline-block link-underline text-sm font-semibold text-white/90 hover:text-white px-2">Dashboard Toko</a>
                        <?php elseif(auth()->user()->role === 'admin'): ?>
                            <a href="<?php echo e(route('admin.dashboard')); ?>" class="hidden sm:inline-block link-underline text-sm font-semibold text-white/90 hover:text-white px-2">Panel Admin</a>
                        <?php endif; ?>

                        <?php if(in_array(auth()->user()->role, ['buyer', 'seller'])): ?>
                        <?php ($unreadChatCount = auth()->user()->unreadMessagesCount()); ?>
                        <a href="<?php echo e(route('chat.index')); ?>" class="relative p-2.5 rounded-full hover:bg-white/15 active:scale-90 transition-all inline-flex" title="Pesan">
                            <svg class="h-5 w-5 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" /></svg>
                            <?php if($unreadChatCount > 0): ?>
                            <span class="absolute -top-0.5 -right-0.5 h-4 w-4 rounded-full bg-clay-500 text-white text-[10px] font-bold flex items-center justify-center ring-2 ring-white"><?php echo e($unreadChatCount > 9 ? '9+' : $unreadChatCount); ?></span>
                            <?php endif; ?>
                        </a>
                        <?php endif; ?>

                        <?php ($unreadCount = auth()->user()->unreadNotifications()->count()); ?>
                        <div class="relative group">
                            <a href="<?php echo e(route('notifications.index')); ?>" class="relative p-2.5 rounded-full hover:bg-white/15 active:scale-90 transition-all inline-flex" title="Notifikasi">
                                <svg class="h-5 w-5 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                                <?php if($unreadCount > 0): ?>
                                <span class="absolute -top-0.5 -right-0.5 h-4 w-4 rounded-full bg-rose-500 text-white text-[10px] font-bold flex items-center justify-center ring-2 ring-white"><?php echo e($unreadCount > 9 ? '9+' : $unreadCount); ?></span>
                                <?php endif; ?>
                            </a>
                        </div>

                        <div class="relative group">
                            <button class="flex items-center gap-2 rounded-md border border-white/25 bg-white/10 pl-1.5 pr-3 py-1.5 hover:bg-white/20 transition text-white">
                                <?php if(auth()->user()->avatar): ?>
                                <img src="<?php echo e(asset('storage/'.auth()->user()->avatar)); ?>" class="h-7 w-7 rounded-full object-cover" alt="Foto profil <?php echo e(auth()->user()->name); ?>">
                                <?php else: ?>
                                <span class="h-7 w-7 rounded-full bg-gradient-to-br from-forest-400 to-forest-600 text-white text-xs font-bold flex items-center justify-center"><?php echo e(strtoupper(substr(auth()->user()->name,0,1))); ?></span>
                                <?php endif; ?>
                                <span class="text-sm font-medium hidden sm:inline text-white"><?php echo e(Str::limit(auth()->user()->name, 14)); ?></span>
                                <svg class="h-3.5 w-3.5 text-white/70 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 rounded-lg border border-ink-100 bg-white shadow-xl shadow-ink-900/10 p-1.5 opacity-0 invisible translate-y-1 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-200 z-50">
                                <a href="<?php echo e(route('profile.edit')); ?>" class="block rounded-md px-3 py-2 text-sm text-ink-700 hover:bg-ink-50">Profil Saya</a>
                                <?php if(auth()->user()->role === 'seller'): ?>
                                <a href="<?php echo e(route('seller.dashboard')); ?>" class="block rounded-md px-3 py-2 text-sm text-ink-700 hover:bg-ink-50 sm:hidden">Dashboard Toko</a>
                                <a href="<?php echo e(route('chat.index')); ?>" class="flex items-center justify-between rounded-md px-3 py-2 text-sm text-ink-700 hover:bg-ink-50 sm:hidden">Pesan <?php if($unreadChatCount > 0): ?><span class="h-4 min-w-4 px-1 rounded-full bg-clay-500 text-white text-[10px] font-bold flex items-center justify-center"><?php echo e($unreadChatCount > 9 ? '9+' : $unreadChatCount); ?></span><?php endif; ?></a>
                                <?php elseif(auth()->user()->role === 'admin'): ?>
                                <a href="<?php echo e(route('admin.dashboard')); ?>" class="block rounded-md px-3 py-2 text-sm text-ink-700 hover:bg-ink-50 sm:hidden">Panel Admin</a>
                                <?php else: ?>
                                <a href="<?php echo e(route('orders.index')); ?>" class="block rounded-md px-3 py-2 text-sm text-ink-700 hover:bg-ink-50 sm:hidden">Pesanan Saya</a>
                                <a href="<?php echo e(route('chat.index')); ?>" class="flex items-center justify-between rounded-md px-3 py-2 text-sm text-ink-700 hover:bg-ink-50 sm:hidden">Pesan <?php if($unreadChatCount > 0): ?><span class="h-4 min-w-4 px-1 rounded-full bg-clay-500 text-white text-[10px] font-bold flex items-center justify-center"><?php echo e($unreadChatCount > 9 ? '9+' : $unreadChatCount); ?></span><?php endif; ?></a>
                                <a href="<?php echo e(route('addresses.index')); ?>" class="block rounded-md px-3 py-2 text-sm text-ink-700 hover:bg-ink-50">Alamat Saya</a>
                                <?php endif; ?>
                                <form action="<?php echo e(route('logout')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button class="w-full text-left rounded-md px-3 py-2 text-sm text-rose-600 hover:bg-rose-50 font-medium">Keluar</button>
                                </form>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="text-sm font-semibold text-white/90 hover:text-white px-3 py-2">Masuk</a>
                        <a href="<?php echo e(route('register')); ?>" class="btn-tactile text-sm font-semibold bg-white text-clay-700 rounded-md px-4 py-2 hover:bg-clay-50 transition">Daftar</a>
                    <?php endif; ?>
                </nav>
            </div>
            <form action="<?php echo e(route('home')); ?>" method="GET" class="md:hidden pb-3 <?php echo e($isAuthPage ? 'hidden' : ''); ?>">
                <div class="relative w-full search-wrap">
                    <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="Cari produk..." autocomplete="off"
                        class="search-input w-full rounded-md border-0 bg-white pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-300">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-ink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" /></svg>
                    <div class="search-suggest absolute left-0 right-0 top-full mt-2 rounded-lg border border-ink-100 bg-white shadow-xl shadow-ink-900/10 py-2 hidden z-50 max-h-96 overflow-y-auto"></div>
                </div>
            </form>
        </div>
    </header>

    <?php if($navCategories->isNotEmpty() && !$isAuthPage): ?>
    <div class="category-bar hidden md:block sticky top-16 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-6 h-11 overflow-x-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                <a href="<?php echo e(route('home')); ?>" class="shrink-0 text-sm font-semibold <?php echo e(!request('category') && request()->routeIs('home') ? 'text-clay-600' : 'text-ink-600 hover:text-clay-600'); ?>">Semua Kategori</a>
                <?php $__currentLoopData = $navCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $navCat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('home', ['category' => $navCat->slug])); ?>" class="shrink-0 text-sm font-medium <?php echo e(request('category') === $navCat->slug ? 'text-clay-600' : 'text-ink-600 hover:text-clay-600'); ?>">
                    <?php if($navCat->icon): ?><span class="mr-1"><?php echo e($navCat->icon); ?></span><?php endif; ?><?php echo e($navCat->name); ?>

                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div id="toast-container" class="fixed top-4 inset-x-0 z-[60] flex flex-col items-center gap-2 px-4 pointer-events-none">
        <?php if(session('success')): ?>
        <div class="toast-item pointer-events-auto max-w-sm w-full sm:w-auto flex items-center gap-2 rounded-lg gradient-brand text-white shadow-xl shadow-clay-900/20 px-4 py-3 text-sm font-medium">
            <svg class="h-4 w-4 shrink-0 text-forest-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?php echo e(session('success')); ?></span>
        </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
        <div class="toast-item pointer-events-auto max-w-sm w-full sm:w-auto flex items-center gap-2 rounded-lg bg-rose-600 text-white shadow-xl shadow-rose-900/20 px-4 py-3 text-sm font-medium">
            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
            <span><?php echo e(session('error')); ?></span>
        </div>
        <?php endif; ?>
    </div>

    <main id="app-main">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer class="mt-24 border-t border-white/5 bg-ink-900 text-ink-200 relative overflow-hidden">
        <div class="absolute inset-0 gradient-mesh opacity-60 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 grid grid-cols-2 md:grid-cols-4 gap-8 relative">
            <div class="col-span-2 md:col-span-1">
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg gradient-brand text-white font-serif font-bold shadow-glow">P</span>
                    <span class="font-serif text-lg font-bold text-white">Pasarku</span>
                </div>
                <p class="text-sm text-ink-400 leading-relaxed max-w-[22ch]">Tempat penjual lokal dan pembeli bertemu langsung, tanpa ribet.</p>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm mb-3">Belanja</h4>
                <ul class="space-y-2 text-sm text-ink-400">
                    <li><a href="<?php echo e(route('home')); ?>" class="hover:text-clay-300 transition-colors">Semua Produk</a></li>
                    <li><a href="<?php echo e(route('register')); ?>" class="hover:text-clay-300 transition-colors">Buat Akun Pembeli</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm mb-3">Jualan</h4>
                <ul class="space-y-2 text-sm text-ink-400">
                    <li><a href="<?php echo e(route('register')); ?>" class="hover:text-clay-300 transition-colors">Buka Toko</a></li>
                    <li><a href="<?php echo e(route('login')); ?>" class="hover:text-clay-300 transition-colors">Masuk Penjual</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm mb-3">Perusahaan</h4>
                <ul class="space-y-2 text-sm text-ink-400">
                    <li class="text-ink-500">Tentang Kami</li>
                    <li class="text-ink-500">Bantuan</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-white/10 py-5 text-center text-xs text-ink-500 relative">
            © <?php echo e(date('Y')); ?> Pasarku. Dibuat dengan Laravel.
        </div>
    </footer>

    <button type="button" data-fixed-escape class="theme-toggle-btn sm:hidden fixed bottom-20 right-4 z-40 h-12 w-12 rounded-full bg-ink-900 text-white shadow-card flex items-center justify-center active:scale-90 transition-all" title="Ganti mode gelap/terang" aria-label="Ganti mode gelap/terang">
        <svg class="theme-toggle-icon-moon h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" /></svg>
        <svg class="theme-toggle-icon-sun h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-6.364-2.386 1.591-1.591M3 12h2.25m.386-6.364 1.591 1.591M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
    </button>

    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 glass border-t border-ink-100 shadow-[0_-8px_24px_rgba(76,29,149,0.08)] pb-[env(safe-area-inset-bottom)] <?php echo e($isAuthPage ? 'hidden' : ''); ?>">
        <div class="grid grid-cols-4 h-16">
            <a href="<?php echo e(route('home')); ?>" class="flex flex-col items-center justify-center gap-1 active:scale-90 transition-transform <?php echo e(request()->routeIs('home') ? 'text-clay-600' : 'text-ink-400'); ?>">
                <svg class="h-5 w-5" fill="<?php echo e(request()->routeIs('home') ? 'currentColor' : 'none'); ?>" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75" /></svg>
                <span class="text-[10px] font-semibold">Beranda</span>
            </a>
            <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->user()->role === 'buyer'): ?>
                    <a href="<?php echo e(route('cart.index')); ?>" class="relative flex flex-col items-center justify-center gap-1 active:scale-90 transition-transform <?php echo e(request()->routeIs('cart.*') ? 'text-clay-600' : 'text-ink-400'); ?>">
                        <svg class="h-5 w-5" fill="<?php echo e(request()->routeIs('cart.*') ? 'currentColor' : 'none'); ?>" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.836l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 1.994-4.694 2.615-7.152.083-.329-.153-.648-.494-.648H5.106M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                        <?php if(auth()->user()->cartItems()->count()): ?>
                            <span class="absolute top-0 right-[calc(50%-16px)] h-4 w-4 rounded-full bg-clay-500 text-white text-[9px] font-bold flex items-center justify-center ring-2 ring-white"><?php echo e(auth()->user()->cartItems()->count()); ?></span>
                        <?php endif; ?>
                        <span class="text-[10px] font-semibold">Keranjang</span>
                    </a>
                    <a href="<?php echo e(route('orders.index')); ?>" class="flex flex-col items-center justify-center gap-1 active:scale-90 transition-transform <?php echo e(request()->routeIs('orders.*') ? 'text-clay-600' : 'text-ink-400'); ?>">
                        <svg class="h-5 w-5" fill="<?php echo e(request()->routeIs('orders.*') ? 'currentColor' : 'none'); ?>" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-.75c0-.621-.504-1.125-1.125-1.125H3.375C2.754 4.5 2.25 5.004 2.25 5.625v.75c0 .621.504 1.125 1.125 1.125z" /></svg>
                        <span class="text-[10px] font-semibold">Pesanan</span>
                    </a>
                <?php elseif(auth()->user()->role === 'seller'): ?>
                    <a href="<?php echo e(route('seller.dashboard')); ?>" class="flex flex-col items-center justify-center gap-1 active:scale-90 transition-transform <?php echo e(request()->routeIs('seller.products.*') ? 'text-clay-600' : 'text-ink-400'); ?>">
                        <svg class="h-5 w-5" fill="<?php echo e(request()->routeIs('seller.products.*') ? 'currentColor' : 'none'); ?>" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-.75c0-.621-.504-1.125-1.125-1.125H3.375C2.754 4.5 2.25 5.004 2.25 5.625v.75c0 .621.504 1.125 1.125 1.125z" /></svg>
                        <span class="text-[10px] font-semibold">Produk</span>
                    </a>
                    <a href="<?php echo e(route('seller.orders.index')); ?>" class="flex flex-col items-center justify-center gap-1 active:scale-90 transition-transform <?php echo e(request()->routeIs('seller.orders.*') ? 'text-clay-600' : 'text-ink-400'); ?>">
                        <svg class="h-5 w-5" fill="<?php echo e(request()->routeIs('seller.orders.*') ? 'currentColor' : 'none'); ?>" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.25h-3.75" /></svg>
                        <span class="text-[10px] font-semibold">Pesanan</span>
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex flex-col items-center justify-center gap-1 active:scale-90 transition-transform <?php echo e(request()->routeIs('admin.dashboard') ? 'text-clay-600' : 'text-ink-400'); ?>">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                        <span class="text-[10px] font-semibold">Admin</span>
                    </a>
                    <a href="<?php echo e(route('admin.orders.index')); ?>" class="flex flex-col items-center justify-center gap-1 active:scale-90 transition-transform <?php echo e(request()->routeIs('admin.orders.*') ? 'text-clay-600' : 'text-ink-400'); ?>">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.25h-3.75" /></svg>
                        <span class="text-[10px] font-semibold">Transaksi</span>
                    </a>
                <?php endif; ?>
                <div class="relative flex flex-col items-center justify-center gap-1 group">
                    <button class="flex flex-col items-center justify-center gap-1 w-full h-full active:scale-90 transition-transform text-ink-400">
                        <?php if(auth()->user()->avatar): ?>
                        <img src="<?php echo e(asset('storage/'.auth()->user()->avatar)); ?>" class="h-5 w-5 rounded-full object-cover" alt="Foto profil <?php echo e(auth()->user()->name); ?>">
                        <?php else: ?>
                        <span class="h-5 w-5 rounded-full bg-forest-500 text-white text-[10px] font-bold flex items-center justify-center"><?php echo e(strtoupper(substr(auth()->user()->name,0,1))); ?></span>
                        <?php endif; ?>
                        <span class="text-[10px] font-semibold">Akun</span>
                    </button>
                    <div class="absolute bottom-14 right-2 w-44 rounded-lg border border-ink-100 bg-white shadow-xl shadow-ink-900/10 p-1.5 opacity-0 invisible translate-y-1 group-focus-within:opacity-100 group-focus-within:visible group-focus-within:translate-y-0 transition-all duration-200">
                        <a href="<?php echo e(route('profile.edit')); ?>" class="block rounded-md px-3 py-2 text-sm text-ink-700 hover:bg-ink-50">Profil Saya</a>
                        <?php if(auth()->user()->role === 'seller'): ?>
                        <a href="<?php echo e(route('seller.dashboard')); ?>" class="block rounded-md px-3 py-2 text-sm text-ink-700 hover:bg-ink-50">Dashboard Toko</a>
                        <a href="<?php echo e(route('chat.index')); ?>" class="flex items-center justify-between rounded-md px-3 py-2 text-sm text-ink-700 hover:bg-ink-50">Pesan <?php if($unreadChatCount > 0): ?><span class="h-4 min-w-4 px-1 rounded-full bg-clay-500 text-white text-[10px] font-bold flex items-center justify-center"><?php echo e($unreadChatCount > 9 ? '9+' : $unreadChatCount); ?></span><?php endif; ?></a>
                        <?php elseif(auth()->user()->role === 'admin'): ?>
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="block rounded-md px-3 py-2 text-sm text-ink-700 hover:bg-ink-50">Panel Admin</a>
                        <?php else: ?>
                        <a href="<?php echo e(route('orders.index')); ?>" class="block rounded-md px-3 py-2 text-sm text-ink-700 hover:bg-ink-50">Pesanan Saya</a>
                        <a href="<?php echo e(route('chat.index')); ?>" class="flex items-center justify-between rounded-md px-3 py-2 text-sm text-ink-700 hover:bg-ink-50">Pesan <?php if($unreadChatCount > 0): ?><span class="h-4 min-w-4 px-1 rounded-full bg-clay-500 text-white text-[10px] font-bold flex items-center justify-center"><?php echo e($unreadChatCount > 9 ? '9+' : $unreadChatCount); ?></span><?php endif; ?></a>
                        <a href="<?php echo e(route('addresses.index')); ?>" class="block rounded-md px-3 py-2 text-sm text-ink-700 hover:bg-ink-50">Alamat Saya</a>
                        <?php endif; ?>
                        <form action="<?php echo e(route('logout')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button class="w-full text-left rounded-md px-3 py-2 text-sm text-rose-600 hover:bg-rose-50 font-medium">Keluar</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="flex flex-col items-center justify-center gap-1 active:scale-90 transition-transform text-ink-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l3 3m0 0l-3 3m3-3H2.25" /></svg>
                    <span class="text-[10px] font-semibold">Masuk</span>
                </a>
                <a href="<?php echo e(route('register')); ?>" class="flex flex-col items-center justify-center gap-1 active:scale-90 transition-transform text-ink-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z" /></svg>
                    <span class="text-[10px] font-semibold">Daftar</span>
                </a>
                <a href="<?php echo e(route('register')); ?>" class="flex flex-col items-center justify-center gap-1 active:scale-90 transition-transform text-ink-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.244a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72" /></svg>
                    <span class="text-[10px] font-semibold">Toko</span>
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <script>
    // Mode gelap: toggle class di <html>, simpan preferensi, dan "keluarkan" elemen fixed
    // dari dalam #app-main supaya posisinya tidak rusak akibat filter invert.
    (function () {
        const root = document.documentElement;
        const KEY = 'pasarku_theme';

        document.querySelectorAll('[data-fixed-escape]').forEach(function (el) {
            document.body.appendChild(el);
        });

        document.querySelectorAll('.theme-toggle-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const isDark = !root.classList.contains('dark-mode');
                root.classList.toggle('dark-mode', isDark);
                try { localStorage.setItem(KEY, isDark ? 'dark' : 'light'); } catch (e) {}
            });
        });
    })();

    // Toast auto-dismiss
    document.querySelectorAll('.toast-item').forEach((el, i) => {
        el.style.animation = 'toast-in .3s ease forwards';
        setTimeout(() => {
            el.style.animation = 'toast-out .25s ease forwards';
            setTimeout(() => el.remove(), 250);
        }, 4000 + i * 300);
    });

    // Top navigation progress bar
    (function () {
        const bar = document.getElementById('nav-progress');
        let timer;
        function start() {
            bar.style.transition = 'none';
            bar.style.opacity = '1';
            bar.style.width = '0%';
            requestAnimationFrame(() => {
                bar.style.transition = 'width 4s cubic-bezier(0.1,0.7,0.3,0.98)';
                bar.style.width = '80%';
            });
        }
        document.querySelectorAll('a[href]:not([href^="#"]):not([target="_blank"])').forEach(a => {
            a.addEventListener('click', function (e) {
                if (a.href && a.href.indexOf(window.location.origin) === 0 && !e.metaKey && !e.ctrlKey) {
                    start();
                }
            });
        });
        document.querySelectorAll('form').forEach(f => {
            f.addEventListener('submit', () => start());
        });
        window.addEventListener('pageshow', () => {
            bar.style.transition = 'none';
            bar.style.width = '0%';
            bar.style.opacity = '0';
        });
    })();

    // Wishlist toggle (used on home, product cards, product detail)
    function toggleWishlist(btn, productId) {
        const wasActive = btn.dataset.wishlisted === '1';
        fetch(`/wishlist/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        })
        .then(r => r.ok ? r.json() : Promise.reject())
        .then(data => {
            const nowActive = data.status === 'added';
            btn.dataset.wishlisted = nowActive ? '1' : '0';
            const svg = btn.querySelector('svg');
            svg.setAttribute('fill', nowActive ? 'currentColor' : 'none');
            btn.classList.toggle('text-rose-500', nowActive);
            btn.classList.toggle('bg-rose-50', nowActive);
            btn.classList.toggle('border-rose-200', nowActive);
            btn.classList.toggle('text-ink-400', !nowActive);
            btn.style.transform = 'scale(1.25)';
            setTimeout(() => { btn.style.transform = ''; }, 180);
        })
        .catch(() => {});
    }

    // Beri tahu saya toggle (used on product detail page when stock habis)
    function toggleStockAlert(btn, productId) {
        fetch(`/produk/${productId}/beri-tahu-saya`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        })
        .then(r => r.ok ? r.json() : Promise.reject())
        .then(data => {
            const nowActive = data.status === 'added';
            btn.dataset.alerted = nowActive ? '1' : '0';
            btn.classList.toggle('bg-forest-50', nowActive);
            btn.classList.toggle('border-forest-200', nowActive);
            btn.classList.toggle('text-forest-700', nowActive);
            btn.classList.toggle('border-ink-200', !nowActive);
            btn.classList.toggle('border-ink-300', !nowActive);
            btn.classList.toggle('text-ink-600', !nowActive);
            const label = btn.querySelector('.stock-alert-label');
            if (label) label.textContent = nowActive ? 'Kami akan beri tahu kamu' : 'Beri Tahu Saya Saat Tersedia';
        })
        .catch(() => {});
    }

    // Follow toko toggle (used on store page)
    function toggleFollow(btn, sellerId) {
        fetch(`/toko/${sellerId}/follow`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        })
        .then(r => r.ok ? r.json() : Promise.reject())
        .then(data => {
            const nowFollowing = data.status === 'followed';
            btn.dataset.following = nowFollowing ? '1' : '0';
            btn.classList.toggle('gradient-brand', !nowFollowing);
            btn.classList.toggle('text-white', !nowFollowing);
            btn.classList.toggle('shadow-glow', !nowFollowing);
            btn.classList.toggle('bg-ink-100', nowFollowing);
            btn.classList.toggle('text-ink-700', nowFollowing);
            btn.classList.toggle('border', nowFollowing);
            btn.classList.toggle('border-ink-200', nowFollowing);
            const label = document.getElementById('follow-btn-label');
            if (label) label.textContent = nowFollowing ? 'Mengikuti' : '+ Ikuti Toko';
            const countEl = document.getElementById('follower-count');
            if (countEl) countEl.textContent = data.follower_count;
        })
        .catch(() => {});
    }

    // Bagikan produk/toko: pakai share sheet native di HP, atau salin link di desktop
    function shareContent(title, url) {
        if (navigator.share) {
            navigator.share({ title: title, url: url }).catch(() => {});
            return;
        }
        const fallbackCopy = () => {
            const input = document.createElement('input');
            input.value = url;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
        };
        const showToast = () => {
            const el = document.createElement('div');
            el.className = 'toast-item pointer-events-none fixed top-4 left-1/2 -translate-x-1/2 z-[70] rounded-lg gradient-brand text-white shadow-xl shadow-clay-900/20 px-4 py-3 text-sm font-medium whitespace-nowrap';
            el.textContent = 'Tautan disalin ke clipboard!';
            document.body.appendChild(el);
            el.style.animation = 'toast-in .3s ease forwards';
            setTimeout(() => {
                el.style.animation = 'toast-out .25s ease forwards';
                setTimeout(() => el.remove(), 250);
            }, 2200);
        };
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(showToast).catch(() => { fallbackCopy(); showToast(); });
        } else {
            fallbackCopy();
            showToast();
        }
    }

    // Riwayat pencarian: disimpan di localStorage, muncul saat kotak pencarian kosong difokuskan.
    const SEARCH_HISTORY_KEY = 'pasarku_search_history';
    const SEARCH_HISTORY_MAX = 8;

    function getSearchHistory() {
        try { return JSON.parse(localStorage.getItem(SEARCH_HISTORY_KEY) || '[]'); } catch (e) { return []; }
    }
    function saveSearchQuery(q) {
        q = (q || '').trim();
        if (!q) return;
        let list = getSearchHistory().filter(item => item.toLowerCase() !== q.toLowerCase());
        list.unshift(q);
        localStorage.setItem(SEARCH_HISTORY_KEY, JSON.stringify(list.slice(0, SEARCH_HISTORY_MAX)));
    }
    function removeSearchHistoryItem(q, wrap) {
        localStorage.setItem(SEARCH_HISTORY_KEY, JSON.stringify(getSearchHistory().filter(item => item !== q)));
        renderSearchHistory(wrap);
    }
    function clearSearchHistory(wrap) {
        localStorage.removeItem(SEARCH_HISTORY_KEY);
        renderSearchHistory(wrap);
    }
    function escapeHtml(str) {
        return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
    function renderSearchHistory(wrap) {
        const dropdown = wrap.querySelector('.search-suggest');
        const history = getSearchHistory();
        if (!history.length) { dropdown.classList.add('hidden'); return; }
        dropdown.innerHTML = `
            <div class="flex items-center justify-between px-3 pb-1.5 mb-1 border-b border-ink-100">
                <span class="text-[11px] font-semibold text-ink-400 uppercase tracking-wide">Pencarian Terakhir</span>
                <button type="button" class="clear-history-btn text-[11px] text-clay-600 hover:text-clay-700 font-semibold">Hapus Semua</button>
            </div>
            ${history.map((q, idx) => `
                <div class="history-item flex items-center gap-2 px-3 py-2 hover:bg-ink-50 rounded-md mx-1 group" data-idx="${idx}">
                    <svg class="h-4 w-4 text-ink-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="history-query flex-1 min-w-0 truncate text-sm text-ink-700 cursor-pointer">${escapeHtml(q)}</span>
                    <button type="button" class="remove-history-item shrink-0 text-ink-300 hover:text-rose-500 opacity-0 group-hover:opacity-100 transition text-sm px-1" title="Hapus dari riwayat">&times;</button>
                </div>
            `).join('')}
        `;
        dropdown.classList.remove('hidden');
        dropdown.querySelector('.clear-history-btn').addEventListener('click', (e) => { e.stopPropagation(); clearSearchHistory(wrap); });
        dropdown.querySelectorAll('.history-item').forEach(el => {
            const q = history[Number(el.dataset.idx)];
            el.querySelector('.history-query').addEventListener('click', () => {
                const input = wrap.querySelector('.search-input');
                input.value = q;
                saveSearchQuery(q);
                wrap.closest('form').submit();
            });
            el.querySelector('.remove-history-item').addEventListener('click', (e) => {
                e.stopPropagation();
                removeSearchHistoryItem(q, wrap);
            });
        });
    }

    // Search autocomplete
    document.querySelectorAll('.search-wrap').forEach(wrap => {
        const input = wrap.querySelector('.search-input');
        const dropdown = wrap.querySelector('.search-suggest');
        const form = wrap.closest('form');
        let debounceTimer;

        if (form) {
            form.addEventListener('submit', () => saveSearchQuery(input.value));
        }
        input.addEventListener('focus', function () {
            if (input.value.trim().length === 0) renderSearchHistory(wrap);
        });
        input.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            const q = input.value.trim();
            if (q.length === 0) { renderSearchHistory(wrap); return; }
            if (q.length < 2) { dropdown.classList.add('hidden'); return; }
            debounceTimer = setTimeout(() => {
                fetch(`<?php echo e(route('search.suggest')); ?>?q=${encodeURIComponent(q)}`, { headers: { 'Accept': 'application/json' } })
                    .then(r => r.json())
                    .then(items => {
                        if (!items.length) { dropdown.classList.add('hidden'); return; }
                        dropdown.innerHTML = items.map(p => `
                            <a href="${p.url}" class="flex items-center gap-3 px-3 py-2 hover:bg-ink-50 rounded-md mx-1">
                                <img src="${p.image}" class="h-10 w-10 rounded-lg object-cover shrink-0" alt="">
                                <span class="min-w-0 flex-1">
                                    <span class="block text-sm font-medium text-ink-800 truncate">${p.name}</span>
                                    <span class="block text-xs text-ink-400">${p.price}</span>
                                </span>
                            </a>
                        `).join('');
                        dropdown.classList.remove('hidden');
                    })
                    .catch(() => {});
            }, 250);
        });
        document.addEventListener('click', (e) => {
            if (!wrap.contains(e.target)) dropdown.classList.add('hidden');
        });
    });
    </script>

    <style>
        @keyframes toast-in { from { opacity:0; transform: translateY(-10px); } to { opacity:1; transform: translateY(0); } }
        @keyframes toast-out { from { opacity:1; transform: translateY(0); } to { opacity:0; transform: translateY(-10px); } }
    </style>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\ecommerce\resources\views/layouts/app.blade.php ENDPATH**/ ?>
<?php
    // Helper aman: bila nama route belum terdaftar, tautan jatuh ke fallback (tidak melempar error).
    $r = fn (string $name, $params = [], string $fallback = '#') => \Illuminate\Support\Facades\Route::has($name) ? route($name, $params) : $fallback;

    $u = auth()->user();
    $cartCount = 0; $notifCount = 0; $msgCount = 0;
    if ($u) {
        try { if (class_exists(\App\Models\CartItem::class)) $cartCount = \App\Models\CartItem::where('user_id', $u->id)->count(); } catch (\Throwable $e) {}
        try { if (method_exists($u, 'unreadNotifications')) $notifCount = $u->unreadNotifications()->count(); } catch (\Throwable $e) {}
        try { if (method_exists($u, 'unreadMessagesCount')) $msgCount = (int) $u->unreadMessagesCount(); } catch (\Throwable $e) {}
    }
    $role = $u->role ?? null;
    $isAdmin = $u && method_exists($u, 'isAdmin') && $u->isAdmin();
    $isSeller = $role === 'seller';
    $dashUrl = $isAdmin ? $r('admin.dashboard') : ($isSeller ? $r('seller.dashboard') : (($u && method_exists($u, 'isKaryawan') && $u->isKaryawan()) ? $r('karyawan.dashboard') : null));
    $flash = array_filter(['success' => session('success'), 'error' => session('error'), 'warning' => session('warning'), 'info' => session('status')]);
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#f7f6f3">
    <title><?php echo $__env->yieldContent('title', 'Beranda'); ?> | Pasarku</title>
    <?php if (! empty(trim($__env->yieldContent('meta_description')))): ?><meta name="description" content="<?php echo $__env->yieldContent('meta_description'); ?>"><?php endif; ?>
    <?php if (! empty(trim($__env->yieldContent('meta_image')))): ?><meta property="og:image" content="<?php echo $__env->yieldContent('meta_image'); ?>"><?php endif; ?>
    <script>document.documentElement.classList.add('js');</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400..700&family=Geist+Mono:wght@400..600&display=swap" rel="stylesheet">
    <?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php else: ?>
        <?php echo $__env->make('layouts._tailwind-fallback', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
    <?php echo $__env->make('layouts._motion', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <style>
        :root { --accent: #1f6652; }
        .shop-header { background: color-mix(in srgb, #fff 86%, transparent); -webkit-backdrop-filter: saturate(1.4) blur(16px); backdrop-filter: saturate(1.4) blur(16px); }
        .menu-pop[hidden] { display: none; }
        .menu-pop { transform-origin: top right; animation: menuIn .24s var(--ease-out) both; }
        @keyframes menuIn { from { opacity: 0; translate: 0 -6px; scale: .97; } }
        .mobile-panel { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .45s var(--ease-out), opacity .3s ease; opacity: 0; }
        .mobile-panel.is-open { grid-template-rows: 1fr; opacity: 1; }
        .mobile-panel > div { overflow: hidden; }
        .burger i { display: block; height: 1.75px; width: 18px; background: currentColor; border-radius: 2px; transition: transform .4s var(--ease-out), opacity .2s ease; }
        .burger i + i { margin-top: 5px; }
        .burger[aria-expanded="true"] i:nth-child(1) { transform: translateY(6.75px) rotate(45deg); }
        .burger[aria-expanded="true"] i:nth-child(2) { opacity: 0; }
        .burger[aria-expanded="true"] i:nth-child(3) { transform: translateY(-6.75px) rotate(-45deg); }
        .shop-toast { animation: toastIn .5s var(--ease-out) both; }
        .shop-toast.is-leaving { animation: toastOut .3s var(--ease-out) both; }
        @keyframes toastIn { from { opacity: 0; transform: translateY(-10px) scale(.97); } }
        @keyframes toastOut { to { opacity: 0; transform: translateY(-6px) scale(.97); } }
        .nav-underline { position: relative; }
        .nav-underline::after { content: ''; position: absolute; left: .75rem; right: .75rem; bottom: .2rem; height: 1.5px; background: currentColor; transform: scaleX(0); transform-origin: left; transition: transform .35s var(--ease-out); }
        .nav-underline:hover::after, .nav-underline[aria-current="page"]::after { transform: scaleX(1); }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="min-h-dvh flex flex-col bg-ink-50 text-ink-800 antialiased">

<header class="shop-header sticky top-0 z-40 border-b border-ink-200">
    <div class="mx-auto flex h-14 sm:h-16 max-w-7xl items-center gap-3 px-4 sm:px-6 lg:px-8">
        <a href="<?php echo e($r('home', [], url('/'))); ?>" class="flex items-center gap-2 shrink-0 focus-ring rounded-lg" aria-label="Pasarku — beranda">
            <span class="grid h-8 w-8 place-items-center rounded-lg bg-ink-900 text-white text-sm font-semibold">P</span>
            <span class="hidden sm:block text-[15px] font-semibold tracking-tight text-ink-900">Pasarku</span>
        </a>

        <form method="GET" action="<?php echo e($r('home', [], url('/'))); ?>" class="hidden md:flex flex-1 max-w-md mx-auto" role="search">
            <label class="relative w-full">
                <span class="sr-only">Cari produk</span>
                <svg class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-ink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/></svg>
                <input type="search" name="q" value="<?php echo e(request('q')); ?>" placeholder="Cari produk atau toko"
                    class="w-full rounded-full border border-ink-200 bg-white/80 py-2 pl-10 pr-4 text-sm placeholder:text-ink-400 focus:border-clay-400 focus:outline-none focus:ring-4 focus:ring-clay-400/15">
            </label>
        </form>

        <nav class="ml-auto hidden md:flex items-center gap-1 text-sm font-medium text-ink-500" aria-label="Navigasi utama">
            <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e($r('orders.index')); ?>" class="nav-underline rounded-lg px-3 py-2 hover:text-ink-900 transition-colors" <?php if(request()->routeIs('orders.*')): ?> aria-current="page" <?php endif; ?>>Pesanan</a>
                <a href="<?php echo e($r('chat.index')); ?>" class="relative rounded-lg px-3 py-2 hover:text-ink-900 transition-colors">Pesan
                    <?php if($msgCount > 0): ?><span class="absolute -right-0.5 top-0.5 grid h-4 min-w-4 place-items-center rounded-full bg-clay-600 px-1 text-[10px] font-semibold text-white"><?php echo e($msgCount > 9 ? '9+' : $msgCount); ?></span><?php endif; ?>
                </a>
                <a href="<?php echo e($r('notifications.index', [], url('/notifications'))); ?>" class="relative grid h-9 w-9 place-items-center rounded-full hover:bg-ink-100 hover:text-ink-900 transition-colors" aria-label="Notifikasi">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                    <?php if($notifCount > 0): ?><span class="absolute right-1 top-1 h-2 w-2 rounded-full bg-rose-500 ring-2 ring-ink-50"></span><?php endif; ?>
                </a>
            <?php endif; ?>
            <a href="<?php echo e($r('cart.index', [], url('/cart'))); ?>" class="relative grid h-9 w-9 place-items-center rounded-full hover:bg-ink-100 hover:text-ink-900 transition-colors" aria-label="Keranjang">
                <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                <?php if($cartCount > 0): ?><span class="absolute -right-0.5 -top-0.5 grid h-4 min-w-4 place-items-center rounded-full bg-clay-600 px-1 text-[10px] font-semibold text-white"><?php echo e($cartCount > 9 ? '9+' : $cartCount); ?></span><?php endif; ?>
            </a>

            <?php if(auth()->guard()->check()): ?>
                <div class="relative ml-1">
                    <button type="button" id="acctBtn" aria-haspopup="true" aria-expanded="false" aria-controls="acctMenu"
                        class="flex items-center gap-2 rounded-full border border-ink-200 bg-white py-1 pl-1 pr-3 hover:border-ink-300 transition-colors focus-ring">
                        <span class="grid h-7 w-7 place-items-center rounded-full bg-clay-100 text-xs font-semibold text-clay-700"><?php echo e(mb_strtoupper(mb_substr($u->name, 0, 1))); ?></span>
                        <span class="max-w-[7rem] truncate text-[13px] text-ink-700"><?php echo e($u->name); ?></span>
                    </button>
                    <div id="acctMenu" hidden class="menu-pop absolute right-0 mt-2 w-60 rounded-2xl border border-ink-200 bg-white p-1.5 text-[13px] shadow-lg">
                        <div class="px-3 py-2">
                            <p class="truncate font-semibold text-ink-900"><?php echo e($u->name); ?></p>
                            <p class="text-xs capitalize text-ink-400"><?php echo e($role); ?></p>
                        </div>
                        <div class="my-1 h-px bg-ink-100"></div>
                        <?php if($dashUrl): ?><a href="<?php echo e($dashUrl); ?>" class="block rounded-lg px-3 py-2 text-ink-700 hover:bg-ink-50">Dashboard</a><?php endif; ?>
                        <?php if($isAdmin): ?><a href="<?php echo e($r('admin.orders.index')); ?>" class="block rounded-lg px-3 py-2 text-ink-700 hover:bg-ink-50">Panel marketplace</a><?php endif; ?>
                        <a href="<?php echo e($r('addresses.index')); ?>" class="block rounded-lg px-3 py-2 text-ink-700 hover:bg-ink-50">Alamat saya</a>
                        <a href="<?php echo e($r('orders.index')); ?>" class="block rounded-lg px-3 py-2 text-ink-700 hover:bg-ink-50">Pesanan saya</a>
                        <div class="my-1 h-px bg-ink-100"></div>
                        <form action="<?php echo e($r('logout')); ?>" method="POST"><?php echo csrf_field(); ?>
                            <button class="w-full rounded-lg px-3 py-2 text-left text-rose-600 hover:bg-rose-50">Keluar</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?php echo e($r('login')); ?>" class="rounded-lg px-3 py-2 hover:text-ink-900 transition-colors">Masuk</a>
                <a href="<?php echo e($r('register')); ?>" class="btn-tactile rounded-full bg-ink-900 px-4 py-2 text-[13px] font-medium text-white hover:bg-ink-700">Daftar</a>
            <?php endif; ?>
        </nav>

        <div class="ml-auto flex items-center gap-1 md:hidden">
            <a href="<?php echo e($r('cart.index', [], url('/cart'))); ?>" class="relative grid h-10 w-10 place-items-center rounded-full text-ink-700 active:bg-ink-100" aria-label="Keranjang">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                <?php if($cartCount > 0): ?><span class="absolute right-0.5 top-0.5 grid h-4 min-w-4 place-items-center rounded-full bg-clay-600 px-1 text-[10px] font-semibold text-white"><?php echo e($cartCount > 9 ? '9+' : $cartCount); ?></span><?php endif; ?>
            </a>
            <button type="button" id="burger" class="burger grid h-10 w-10 place-items-center rounded-full text-ink-800 active:bg-ink-100" aria-label="Buka menu" aria-expanded="false" aria-controls="mobilePanel">
                <span><i></i><i></i><i></i></span>
            </button>
        </div>
    </div>

    <div id="mobilePanel" class="mobile-panel md:hidden border-t border-ink-200/0">
        <div>
            <div class="space-y-1 px-4 pb-5 pt-3">
                <form method="GET" action="<?php echo e($r('home', [], url('/'))); ?>" role="search" class="mb-3">
                    <input type="search" name="q" value="<?php echo e(request('q')); ?>" placeholder="Cari produk atau toko"
                        class="w-full rounded-full border border-ink-200 bg-white px-4 py-2.5 text-base placeholder:text-ink-400 focus:border-clay-400 focus:outline-none focus:ring-4 focus:ring-clay-400/15">
                </form>
                <?php if(auth()->guard()->check()): ?>
                    <?php if($dashUrl): ?><a href="<?php echo e($dashUrl); ?>" class="block rounded-xl px-3 py-3 text-[15px] font-medium text-ink-800 active:bg-ink-100">Dashboard</a><?php endif; ?>
                    <a href="<?php echo e($r('orders.index')); ?>" class="block rounded-xl px-3 py-3 text-[15px] font-medium text-ink-800 active:bg-ink-100">Pesanan</a>
                    <a href="<?php echo e($r('chat.index')); ?>" class="flex items-center justify-between rounded-xl px-3 py-3 text-[15px] font-medium text-ink-800 active:bg-ink-100">Pesan <?php if($msgCount > 0): ?><span class="rounded-full bg-clay-600 px-2 text-xs text-white"><?php echo e($msgCount); ?></span><?php endif; ?></a>
                    <a href="<?php echo e($r('notifications.index', [], url('/notifications'))); ?>" class="flex items-center justify-between rounded-xl px-3 py-3 text-[15px] font-medium text-ink-800 active:bg-ink-100">Notifikasi <?php if($notifCount > 0): ?><span class="rounded-full bg-rose-500 px-2 text-xs text-white"><?php echo e($notifCount); ?></span><?php endif; ?></a>
                    <a href="<?php echo e($r('addresses.index')); ?>" class="block rounded-xl px-3 py-3 text-[15px] font-medium text-ink-800 active:bg-ink-100">Alamat saya</a>
                    <form action="<?php echo e($r('logout')); ?>" method="POST" class="pt-2"><?php echo csrf_field(); ?>
                        <button class="w-full rounded-xl border border-ink-200 bg-white px-3 py-3 text-[15px] font-medium text-rose-600 active:bg-rose-50">Keluar</button>
                    </form>
                <?php else: ?>
                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <a href="<?php echo e($r('login')); ?>" class="rounded-full border border-ink-200 bg-white py-3 text-center text-[15px] font-medium text-ink-800">Masuk</a>
                        <a href="<?php echo e($r('register')); ?>" class="rounded-full bg-ink-900 py-3 text-center text-[15px] font-medium text-white">Daftar</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<?php if(count($flash)): ?>
<div class="pointer-events-none fixed inset-x-0 top-[4.25rem] z-50 flex flex-col items-center gap-2 px-4" id="shopFlash">
    <?php $__currentLoopData = $flash; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="shop-toast pointer-events-auto flex w-full max-w-md items-start gap-3 rounded-2xl border bg-white px-4 py-3 text-sm shadow-lg <?php echo e($type === 'error' ? 'border-rose-200' : ($type === 'warning' ? 'border-amber-200' : 'border-ink-200')); ?>" role="status" style="animation-delay: <?php echo e($loop->index * 120); ?>ms">
            <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full <?php echo e($type === 'error' ? 'bg-rose-500' : ($type === 'warning' ? 'bg-amber-400' : ($type === 'info' ? 'bg-ink-400' : 'bg-clay-500'))); ?>"></span>
            <p class="flex-1 text-ink-700"><?php echo e($msg); ?></p>
            <button type="button" class="-mr-1 rounded-md p-1 text-ink-400 hover:text-ink-800" data-flash-close aria-label="Tutup">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>

<main class="shop-main page-enter flex-1">
    <?php echo $__env->yieldContent('content'); ?>
</main>

<footer class="mt-16 border-t border-ink-200">
    <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-8 text-sm text-ink-400 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <p><span class="font-medium text-ink-600">Pasarku</span> — belanja langsung dari penjual lokal.</p>
        <p>&copy; <?php echo e(date('Y')); ?></p>
    </div>
</footer>

<script>
(function () {
    // Menu akun (desktop)
    var btn = document.getElementById('acctBtn'), menu = document.getElementById('acctMenu');
    if (btn && menu) {
        function setOpen(o) { menu.hidden = !o; btn.setAttribute('aria-expanded', o ? 'true' : 'false'); }
        btn.addEventListener('click', function (e) { e.stopPropagation(); setOpen(menu.hidden); });
        document.addEventListener('click', function (e) { if (!menu.contains(e.target)) setOpen(false); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setOpen(false); });
    }
    // Menu mobile
    var burger = document.getElementById('burger'), panel = document.getElementById('mobilePanel');
    if (burger && panel) {
        burger.addEventListener('click', function () {
            var open = !panel.classList.contains('is-open');
            panel.classList.toggle('is-open', open);
            burger.setAttribute('aria-expanded', open ? 'true' : 'false');
            burger.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
        });
    }
    // Notifikasi flash
    var flash = document.getElementById('shopFlash');
    if (flash) {
        function close(el) { el.classList.add('is-leaving'); setTimeout(function () { el.remove(); }, 300); }
        flash.querySelectorAll('.shop-toast').forEach(function (t, i) {
            setTimeout(function () { if (t.isConnected) close(t); }, 5000 + i * 300);
            t.querySelector('[data-flash-close]').addEventListener('click', function () { close(t); });
        });
    }
})();
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
<?php echo $__env->make('layouts._motion-js', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\ecommerce\resources\views/layouts/shop.blade.php ENDPATH**/ ?>
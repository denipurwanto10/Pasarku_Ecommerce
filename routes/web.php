<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\StoreController as AdminStoreController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductQuestionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StockAlertController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Seller\CouponController as SellerCouponController;
use App\Http\Controllers\Seller\QuickReplyController as SellerQuickReplyController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\ShippingController as SellerShippingController;
use App\Http\Controllers\Seller\StockController as SellerStockController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cari-sugesti', [HomeController::class, 'suggest'])->name('search.suggest');
Route::get('/produk/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/toko/{seller:store_slug}', [StoreController::class, 'show'])->name('store.show');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

// Notifikasi (bersama untuk semua peran yang login)
Route::middleware('auth')->group(function () {
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifikasi/{notification}/baca', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifikasi/baca-semua', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
});

// Profil (bersama untuk semua peran yang login)
Route::middleware('auth')->group(function () {
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/kata-sandi', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

// Chat (bersama untuk buyer & seller yang login)
Route::middleware('auth')->group(function () {
    Route::get('/pesan', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/pesan/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/pesan/{conversation}', [ChatController::class, 'store'])->name('chat.store');
    Route::get('/pesan/{conversation}/cek', [ChatController::class, 'poll'])->name('chat.poll');
    Route::post('/toko/{seller}/pesan', [ChatController::class, 'start'])->name('chat.start');
});

// Buyer (butuh login)
Route::middleware('auth')->group(function () {
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/keranjang/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/kupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.coupon.apply');
    Route::delete('/checkout/kupon', [CheckoutController::class, 'removeCoupon'])->name('checkout.coupon.remove');
    Route::post('/checkout/ongkir', [CheckoutController::class, 'calculateShippingForCity'])->name('checkout.shipping.calculate');

    Route::get('/pesanan-saya', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan-saya/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/pesanan-saya/{order}/batalkan', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/pesanan-saya/{order}/beli-lagi', [OrderController::class, 'reorder'])->name('orders.reorder');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    Route::post('/produk/{product}/beri-tahu-saya', [StockAlertController::class, 'toggle'])->name('stock-alerts.toggle');

    Route::post('/toko/{seller}/follow', [FollowController::class, 'toggle'])->name('store.follow');

    Route::post('/produk/{product}/ulasan', [ReviewController::class, 'store'])->name('reviews.store');

    Route::post('/produk/{product}/tanya', [ProductQuestionController::class, 'store'])->name('questions.store');
    Route::post('/tanya-jawab/{question}/jawab', [ProductQuestionController::class, 'answer'])->name('questions.answer');
    Route::delete('/tanya-jawab/{question}', [ProductQuestionController::class, 'destroy'])->name('questions.destroy');

    Route::get('/alamat', [AddressController::class, 'index'])->name('addresses.index');
    Route::get('/alamat/tambah', [AddressController::class, 'create'])->name('addresses.create');
    Route::post('/alamat', [AddressController::class, 'store'])->name('addresses.store');
    Route::get('/alamat/{address}/ubah', [AddressController::class, 'edit'])->name('addresses.edit');
    Route::put('/alamat/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/alamat/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::patch('/alamat/{address}/utama', [AddressController::class, 'setDefault'])->name('addresses.setDefault');
});

// Seller
Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/produk', [SellerProductController::class, 'index'])->name('products.index');
    Route::get('/produk/tambah', [SellerProductController::class, 'create'])->name('products.create');
    Route::post('/produk', [SellerProductController::class, 'store'])->name('products.store');
    Route::get('/produk/{product}/ubah', [SellerProductController::class, 'edit'])->name('products.edit');
    Route::put('/produk/{product}', [SellerProductController::class, 'update'])->name('products.update');
    Route::delete('/produk/{product}', [SellerProductController::class, 'destroy'])->name('products.destroy');
    Route::patch('/produk/{product}/gambar/urutkan', [SellerProductController::class, 'reorderImages'])->name('products.images.reorder');

    Route::get('/balasan-cepat', [SellerQuickReplyController::class, 'index'])->name('quick-replies.index');
    Route::get('/balasan-cepat/tambah', [SellerQuickReplyController::class, 'create'])->name('quick-replies.create');
    Route::post('/balasan-cepat', [SellerQuickReplyController::class, 'store'])->name('quick-replies.store');
    Route::get('/balasan-cepat/{quickReply}/ubah', [SellerQuickReplyController::class, 'edit'])->name('quick-replies.edit');
    Route::put('/balasan-cepat/{quickReply}', [SellerQuickReplyController::class, 'update'])->name('quick-replies.update');
    Route::delete('/balasan-cepat/{quickReply}', [SellerQuickReplyController::class, 'destroy'])->name('quick-replies.destroy');

    Route::get('/pesanan', [SellerOrderController::class, 'index'])->name('orders.index');
    Route::patch('/pesanan/{order}/status', [SellerOrderController::class, 'updateStatus'])->name('orders.status');

    Route::get('/kupon', [SellerCouponController::class, 'index'])->name('coupons.index');
    Route::get('/kupon/tambah', [SellerCouponController::class, 'create'])->name('coupons.create');
    Route::post('/kupon', [SellerCouponController::class, 'store'])->name('coupons.store');
    Route::get('/kupon/{coupon}/ubah', [SellerCouponController::class, 'edit'])->name('coupons.edit');
    Route::put('/kupon/{coupon}', [SellerCouponController::class, 'update'])->name('coupons.update');
    Route::delete('/kupon/{coupon}', [SellerCouponController::class, 'destroy'])->name('coupons.destroy');

    Route::get('/stok', [SellerStockController::class, 'index'])->name('stock.index');
    Route::get('/stok/{product}/riwayat', [SellerStockController::class, 'history'])->name('stock.history');
    Route::post('/stok/{product}/sesuaikan', [SellerStockController::class, 'adjust'])->name('stock.adjust');

    Route::get('/ongkir', [SellerShippingController::class, 'index'])->name('shipping.index');
    Route::put('/ongkir', [SellerShippingController::class, 'update'])->name('shipping.update');
    Route::post('/ongkir/kota', [SellerShippingController::class, 'storeCityRate'])->name('shipping.city.store');
    Route::delete('/ongkir/kota/{cityRate}', [SellerShippingController::class, 'destroyCityRate'])->name('shipping.city.destroy');
});

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/toko', [AdminStoreController::class, 'index'])->name('stores.index');
    Route::get('/toko/{store}', [AdminStoreController::class, 'show'])->name('stores.show');
    Route::patch('/toko/{store}/setujui', [AdminStoreController::class, 'approve'])->name('stores.approve');
    Route::patch('/toko/{store}/nonaktifkan', [AdminStoreController::class, 'suspend'])->name('stores.suspend');

    Route::get('/produk', [AdminProductController::class, 'index'])->name('products.index');
    Route::patch('/produk/{product}/setujui', [AdminProductController::class, 'approve'])->name('products.approve');
    Route::patch('/produk/{product}/tolak', [AdminProductController::class, 'reject'])->name('products.reject');

    Route::get('/kategori', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::get('/kategori/tambah', [AdminCategoryController::class, 'create'])->name('categories.create');
    Route::post('/kategori', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::get('/kategori/{category}/ubah', [AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/kategori/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/kategori/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/banner', [AdminBannerController::class, 'index'])->name('banners.index');
    Route::get('/banner/tambah', [AdminBannerController::class, 'create'])->name('banners.create');
    Route::post('/banner', [AdminBannerController::class, 'store'])->name('banners.store');
    Route::get('/banner/{banner}/ubah', [AdminBannerController::class, 'edit'])->name('banners.edit');
    Route::put('/banner/{banner}', [AdminBannerController::class, 'update'])->name('banners.update');
    Route::delete('/banner/{banner}', [AdminBannerController::class, 'destroy'])->name('banners.destroy');

    Route::get('/pengguna', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/pengguna/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::patch('/pengguna/{user}/nonaktifkan', [AdminUserController::class, 'suspend'])->name('users.suspend');
    Route::patch('/pengguna/{user}/aktifkan', [AdminUserController::class, 'activate'])->name('users.activate');

    Route::get('/transaksi', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/transaksi/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
});

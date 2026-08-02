<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun admin demo
        User::create([
            'name' => 'Admin Pasarku',
            'email' => 'admin@pasarku.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'store_status' => 'approved',
        ]);

        // Akun demo
        $seller1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'seller@pasarku.test',
            'password' => Hash::make('password'),
            'role' => 'seller',
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 10, Bandung',
            'city' => 'Bandung',
            'store_name' => 'Budi Elektronik',
            'store_slug' => 'budi-elektronik',
            'store_description' => 'Menjual perangkat elektronik original dengan garansi resmi.',
            'store_status' => 'approved',
            'shipping_type' => 'flat',
            'shipping_flat_rate' => 12000,
        ]);

        $seller2 = User::create([
            'name' => 'Sari Wulandari',
            'email' => 'seller2@pasarku.test',
            'password' => Hash::make('password'),
            'role' => 'seller',
            'phone' => '081234567891',
            'address' => 'Jl. Kenanga No. 5, Yogyakarta',
            'city' => 'Yogyakarta',
            'store_name' => 'Sari Fashion Store',
            'store_slug' => 'sari-fashion-store',
            'store_description' => 'Fashion kekinian untuk pria dan wanita.',
            'store_status' => 'approved',
            'shipping_type' => 'per_city',
            'shipping_flat_rate' => 15000,
        ]);

        $seller2->shippingCityRates()->createMany([
            ['city' => 'Yogyakarta', 'rate' => 8000],
            ['city' => 'Jakarta Selatan', 'rate' => 18000],
            ['city' => 'Bandung', 'rate' => 16000],
        ]);

        $buyer = User::create([
            'name' => 'Andi Pratama',
            'email' => 'buyer@pasarku.test',
            'password' => Hash::make('password'),
            'role' => 'buyer',
            'phone' => '081298765432',
            'address' => 'Jl. Sudirman No. 88, Jakarta Selatan',
            'city' => 'Jakarta Selatan',
        ]);

        $buyer->addresses()->create([
            'label' => 'Rumah',
            'recipient_name' => $buyer->name,
            'phone' => $buyer->phone,
            'city' => 'Jakarta Selatan',
            'detail' => $buyer->address,
            'is_default' => true,
        ]);

        // Contoh toko yang masih menunggu persetujuan admin
        User::create([
            'name' => 'Rudi Hartono',
            'email' => 'seller3@pasarku.test',
            'password' => Hash::make('password'),
            'role' => 'seller',
            'phone' => '081211112222',
            'address' => 'Jl. Diponegoro No. 2, Semarang',
            'city' => 'Semarang',
            'store_name' => 'Rudi Sport Gear',
            'store_slug' => 'rudi-sport-gear',
            'store_description' => 'Perlengkapan olahraga baru buka, menunggu persetujuan admin.',
            'store_status' => 'pending',
        ]);

        $categories = [
            ['name' => 'Elektronik', 'icon' => '💻'],
            ['name' => 'Fashion Pria', 'icon' => '👔'],
            ['name' => 'Fashion Wanita', 'icon' => '👗'],
            ['name' => 'Rumah Tangga', 'icon' => '🏠'],
            ['name' => 'Olahraga', 'icon' => '⚽'],
            ['name' => 'Kesehatan & Kecantikan', 'icon' => '💄'],
            ['name' => 'Makanan & Minuman', 'icon' => '🍱'],
            ['name' => 'Hobi & Koleksi', 'icon' => '🎮'],
        ];

        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[] = Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'icon' => $cat['icon'],
            ]);
        }

        $products = [
            ['name' => 'Headset Bluetooth Wireless Pro', 'price' => 249000, 'stock' => 50, 'cat' => 0, 'seller' => $seller1],
            ['name' => 'Power Bank 20000mAh Fast Charging', 'price' => 189000, 'stock' => 80, 'cat' => 0, 'seller' => $seller1],
            ['name' => 'Keyboard Mechanical RGB 87 Key', 'price' => 425000, 'stock' => 30, 'cat' => 0, 'seller' => $seller1],
            ['name' => 'Mouse Wireless Ergonomis', 'price' => 95000, 'stock' => 100, 'cat' => 0, 'seller' => $seller1],
            ['name' => 'Smartwatch Fitness Tracker', 'price' => 359000, 'stock' => 40, 'cat' => 0, 'seller' => $seller1],
            ['name' => 'Kemeja Flanel Lengan Panjang', 'price' => 129000, 'stock' => 60, 'cat' => 1, 'seller' => $seller2],
            ['name' => 'Celana Chino Slim Fit', 'price' => 175000, 'stock' => 45, 'cat' => 1, 'seller' => $seller2],
            ['name' => 'Dress Midi Motif Bunga', 'price' => 199000, 'stock' => 35, 'cat' => 2, 'seller' => $seller2],
            ['name' => 'Blouse Satin Elegan', 'price' => 149000, 'stock' => 55, 'cat' => 2, 'seller' => $seller2],
            ['name' => 'Tumbler Stainless Steel 500ml', 'price' => 79000, 'stock' => 90, 'cat' => 3, 'seller' => $seller1],
            ['name' => 'Set Peralatan Masak Anti Lengket', 'price' => 289000, 'stock' => 25, 'cat' => 3, 'seller' => $seller1],
            ['name' => 'Matras Yoga Anti Slip', 'price' => 119000, 'stock' => 70, 'cat' => 4, 'seller' => $seller2],
            ['name' => 'Sepatu Lari Ringan Breathable', 'price' => 315000, 'stock' => 38, 'cat' => 4, 'seller' => $seller2],
            ['name' => 'Serum Wajah Vitamin C', 'price' => 89000, 'stock' => 120, 'cat' => 5, 'seller' => $seller2],
            ['name' => 'Paket Kopi Arabika Premium 500gr', 'price' => 65000, 'stock' => 150, 'cat' => 6, 'seller' => $seller1],
            ['name' => 'Action Figure Koleksi Edisi Terbatas', 'price' => 275000, 'stock' => 20, 'cat' => 7, 'seller' => $seller1],
        ];

        $productModels = [];
        foreach ($products as $p) {
            $productModels[] = $product = Product::create([
                'seller_id' => $p['seller']->id,
                'category_id' => $categoryModels[$p['cat']]->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']).'-'.Str::random(5),
                'description' => 'Produk berkualitas tinggi dari '.$p['seller']->store_name.'. Dikirim dengan packing aman dan rapi, siap membantu aktivitas harianmu.',
                'price' => $p['price'],
                'stock' => $p['stock'],
                'is_active' => true,
                'sold_count' => rand(5, 250),
            ]);

            \App\Models\StockHistory::create([
                'product_id' => $product->id,
                'user_id' => $product->seller_id,
                'type' => 'restock',
                'quantity_change' => $p['stock'],
                'stock_after' => $p['stock'],
                'note' => 'Stok awal (seeder demo)',
            ]);
        }

        // Contoh produk dengan harga diskon (harga coret)
        $productModels[0]->update([
            'discount_price' => (int) ($productModels[0]->price * 0.85),
            'discount_starts_at' => now()->subDay(),
            'discount_ends_at' => now()->addDays(14),
        ]);
        $productModels[7]->update([
            'discount_price' => (int) ($productModels[7]->price * 0.7),
            'discount_starts_at' => now()->subDay(),
            'discount_ends_at' => now()->addDays(7),
        ]);

        // Contoh produk dengan stok menipis / habis untuk demo badge stok
        $productModels[3]->update(['stock' => 3]);
        $productModels[9]->update(['stock' => 0]);

        // Kupon demo per toko
        Coupon::create([
            'seller_id' => $seller1->id,
            'code' => 'ELEKTRONIK10',
            'type' => 'percent',
            'value' => 10,
            'max_discount' => 50000,
            'min_purchase' => 100000,
            'usage_limit' => 100,
            'is_active' => true,
        ]);
        Coupon::create([
            'seller_id' => $seller1->id,
            'code' => 'HEMAT20K',
            'type' => 'fixed',
            'value' => 20000,
            'min_purchase' => 150000,
            'usage_limit' => null,
            'is_active' => true,
        ]);
        Coupon::create([
            'seller_id' => $seller2->id,
            'code' => 'FASHIONDAY',
            'type' => 'percent',
            'value' => 15,
            'max_discount' => 40000,
            'min_purchase' => 100000,
            'usage_limit' => 50,
            'is_active' => true,
        ]);

        // Pesanan demo yang sudah selesai, agar fitur ulasan bisa langsung dicoba
        $reviewableProducts = collect($productModels)->random(4)->values();

        $order = Order::create([
            'buyer_id' => $buyer->id,
            'order_number' => 'PSK-'.strtoupper(Str::random(8)),
            'total_amount' => $reviewableProducts->sum('price'),
            'discount_amount' => 0,
            'status' => 'completed',
            'shipping_address' => $buyer->address,
            'shipping_phone' => $buyer->phone,
            'payment_method' => 'transfer_bank',
            'courier' => 'jne_reg',
            'tracking_number' => 'JNE'.rand(100000000, 999999999),
        ]);

        foreach ($reviewableProducts as $product) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'seller_id' => $product->seller_id,
                'product_name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'subtotal' => $product->price,
            ]);
        }

        // Ulasan demo untuk sebagian produk yang baru saja "dibeli"
        $sampleComments = [
            5 => 'Kualitasnya bagus banget, sesuai foto dan pengiriman cepat!',
            4 => 'Produknya oke, cuma packing bisa lebih rapi lagi.',
        ];

        foreach ($reviewableProducts as $i => $product) {
            $rating = [5, 4, 5, 4][$i] ?? 5;

            Review::create([
                'product_id' => $product->id,
                'user_id' => $buyer->id,
                'order_item_id' => OrderItem::where('order_id', $order->id)->where('product_id', $product->id)->value('id'),
                'rating' => $rating,
                'comment' => $sampleComments[$rating] ?? 'Produk sesuai deskripsi, terima kasih!',
            ]);

            $product->refreshRatingCache();
        }
    }
}

@extends('layouts.app')
@section('title', 'Tambah Produk')
@section('content')
<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <a href="{{ route('seller.products.index') }}" class="text-sm text-ink-400 hover:text-clay-600 mb-4 inline-block">&larr; Kembali</a>
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 mb-8">Tambah Produk Baru</h1>

    @if($errors->any())
    <div class="mb-5 rounded-md bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3">
        <ul class="list-disc pl-4 space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" class="rounded-lg border border-ink-100 bg-white shadow-card p-5 sm:p-8 space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Nama Produk</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Kategori</label>
                <select name="category_id" class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
                    <option value="">Pilih kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id')==$cat->id?'selected':'' }}>{{ $cat->icon }} {{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Foto Produk (Cover)</label>
                <input type="file" name="image" accept="image/*" class="w-full rounded-md border border-ink-200 px-4 py-2.5 text-sm file:mr-3 file:rounded-full file:border-0 file:gradient-brand file:text-white file:text-xs file:font-semibold file:px-3 file:py-1.5 file:shadow-glow">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Galeri Foto Tambahan (opsional, maks 6)</label>
            <input type="file" name="images[]" accept="image/*" multiple class="w-full rounded-md border border-ink-200 px-4 py-2.5 text-sm file:mr-3 file:rounded-full file:border-0 file:bg-ink-800 file:text-white file:text-xs file:font-semibold file:px-3 file:py-1.5">
            <p class="mt-1 text-xs text-ink-400">Tambahkan beberapa foto agar pembeli bisa melihat produk dari berbagai sudut.</p>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price') }}" min="0" required class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Stok</label>
                <input type="number" name="stock" value="{{ old('stock') }}" min="0" required class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
            </div>
        </div>
        <div class="rounded-lg border border-dashed border-rose-200 bg-rose-50/40 p-4 space-y-4">
            <p class="text-sm font-semibold text-rose-700">Diskon Produk (opsional, harga coret — terpisah dari kupon)</p>
            <div class="grid sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-ink-700 mb-1.5">Harga Diskon (Rp)</label>
                    <input type="number" name="discount_price" value="{{ old('discount_price') }}" min="0" placeholder="Kosongkan jika tidak ada diskon"
                        class="w-full rounded-md border border-ink-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-ink-700 mb-1.5">Mulai</label>
                    <input type="datetime-local" name="discount_starts_at" value="{{ old('discount_starts_at') }}" class="w-full rounded-md border border-ink-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-ink-700 mb-1.5">Berakhir</label>
                    <input type="datetime-local" name="discount_ends_at" value="{{ old('discount_ends_at') }}" class="w-full rounded-md border border-ink-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-ink-700 mb-1.5">Kuota Flash Sale (opsional)</label>
                <input type="number" name="flash_sale_stock" value="{{ old('flash_sale_stock') }}" min="1" placeholder="Kosongkan jika tidak dibatasi kuota"
                    class="w-full sm:w-1/3 rounded-md border border-ink-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
                <p class="mt-1 text-xs text-ink-400">Isi untuk membuat diskon di atas jadi flash sale dengan jumlah unit terbatas & progress bar "terjual".</p>
            </div>
        </div>

        <div class="rounded-lg border border-dashed border-ink-200 bg-ink-50/40 p-4 space-y-3">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold text-ink-700">Varian Produk (opsional — ukuran, warna, dll.)</p>
                <button type="button" onclick="addVariantRow()" class="text-xs font-semibold text-clay-600 hover:text-clay-700">+ Tambah Varian</button>
            </div>
            <p class="text-xs text-ink-400 -mt-1">Kosongkan jika produk tidak punya varian. Jika diisi, pembeli wajib memilih salah satu varian sebelum membeli.</p>
            <input type="hidden" name="variants_present" value="1">
            <div id="variant-rows" class="space-y-2"></div>
        </div>

        <template id="variant-row-template">
            <div class="variant-row grid grid-cols-12 gap-2 items-start">
                <input type="text" name="variant_name[]" placeholder="Nama varian, mis. Merah / L" class="col-span-5 rounded-md border border-ink-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
                <input type="number" name="variant_price_adjustment[]" value="0" placeholder="+/- harga" class="col-span-3 rounded-md border border-ink-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
                <input type="number" name="variant_stock[]" value="0" min="0" placeholder="Stok" class="col-span-2 rounded-md border border-ink-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
                <input type="text" name="variant_sku[]" placeholder="SKU (opsional)" class="col-span-1 hidden">
                <button type="button" onclick="this.closest('.variant-row').remove()" class="col-span-2 sm:col-span-2 h-full rounded-md border border-rose-200 text-rose-500 text-xs font-semibold py-2.5 hover:bg-rose-50">Hapus</button>
            </div>
        </template>
        <script>
        function addVariantRow() {
            const tpl = document.getElementById('variant-row-template');
            const clone = tpl.content.cloneNode(true);
            document.getElementById('variant-rows').appendChild(clone);
        }
        </script>
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">{{ old('description') }}</textarea>
        </div>
        <label class="flex items-center gap-2 text-sm text-ink-600">
            <input type="checkbox" name="is_active" value="1" checked class="rounded border-ink-300 text-clay-600 focus:ring-clay-400"> Tampilkan produk ini di toko
        </label>
        <button type="submit" class="btn-tactile rounded-md bg-clay-600 text-white font-semibold px-6 py-3.5 text-sm hover:bg-clay-700 hover:brightness-110 transition">Simpan Produk</button>
    </form>
</section>
@endsection

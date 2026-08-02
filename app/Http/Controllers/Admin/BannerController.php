<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::ordered()->paginate(15);

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateBanner($request);

        $data['image'] = $request->file('image')->store('banners', 'public');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = (int) ($request->input('sort_order') ?? (Banner::max('sort_order') + 1));

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil ditambahkan.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $this->validateBanner($request, true);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = (int) ($request->input('sort_order') ?? $banner->sort_order);

        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil diperbarui.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return back()->with('success', 'Banner berhasil dihapus.');
    }

    private function validateBanner(Request $request, bool $isUpdate = false): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'link_url' => ['nullable', 'string', 'max:255'],
            'button_label' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => [$isUpdate ? 'nullable' : 'required', 'image', 'max:2048'],
        ]);
    }
}

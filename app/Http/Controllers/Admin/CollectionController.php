<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Support\UploadsImages;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CollectionController extends Controller
{
    use UploadsImages;

    public function index()
    {
        $collections = Collection::withCount('products')->orderBy('sort_order')->get();

        $stats = [
            ['label' => 'إجمالي المجموعات', 'value' => $collections->count(), 'icon' => 'bi-layers', 'tone' => 'brand'],
            ['label' => 'نشطة', 'value' => $collections->where('is_active', true)->count(), 'icon' => 'bi-check-circle', 'tone' => 'success'],
            ['label' => 'مخفية', 'value' => $collections->where('is_active', false)->count(), 'icon' => 'bi-eye-slash', 'tone' => 'muted'],
            ['label' => 'منتجات مربوطة', 'value' => $collections->sum('products_count'), 'icon' => 'bi-box-seam', 'tone' => 'warn'],
        ];

        return view('admin.collections.index', compact('collections', 'stats'));
    }

    public function create()
    {
        return view('admin.collections.form', ['collection' => new Collection()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->uploadPublicImage($request, 'image', 'assets/images/collections') ?? '';

        Collection::create($data);

        return redirect()->route('admin.collections.index')->with('success', 'تم إضافة المجموعة');
    }

    public function edit(Collection $collection)
    {
        return view('admin.collections.form', compact('collection'));
    }

    public function update(Request $request, Collection $collection)
    {
        $data = $this->validated($request, $collection);

        if ($request->hasFile('image')) {
            $this->deletePublicImage($collection->image, 'assets/images/collections/');
            $data['image'] = $this->uploadPublicImage($request, 'image', 'assets/images/collections');
        }

        $collection->update($data);

        return redirect()->route('admin.collections.index')->with('success', 'تم تحديث المجموعة');
    }

    public function destroy(Collection $collection)
    {
        $this->deletePublicImage($collection->image, 'assets/images/collections/');
        $collection->delete();

        return back()->with('success', 'تم حذف المجموعة');
    }

    private function validated(Request $request, ?Collection $collection = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140', 'unique:collections,slug,' . ($collection?->id ?: 'NULL')],
            'description' => ['nullable', 'string', 'max:255'],
            'label' => ['nullable', 'string', 'max:40'],
            'image' => ['nullable', 'image', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['label'] = $data['label'] ?: 'مجموعة';
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}

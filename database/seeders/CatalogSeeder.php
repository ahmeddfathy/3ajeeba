<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'عبايات', 'slug' => 'abayas', 'sort_order' => 1],
            ['name' => 'حجابات', 'slug' => 'hijabs', 'sort_order' => 2],
            ['name' => 'خمر', 'slug' => 'khimar', 'sort_order' => 3],
            ['name' => 'إكسسوارات', 'slug' => 'accessories', 'sort_order' => 4],
        ];

        foreach ($categories as $row) {
            Category::updateOrCreate(['slug' => $row['slug']], $row + ['is_active' => true]);
        }

        $collections = [
            ['name' => 'مجموعة رمضانية', 'slug' => 'ramadan', 'label' => 'مجموعة', 'sort_order' => 1],
            ['name' => 'مجموعة الربيع', 'slug' => 'spring', 'label' => 'مجموعة', 'sort_order' => 2],
            ['name' => 'مجموعة المناسبات', 'slug' => 'occasions', 'label' => 'مجموعة', 'sort_order' => 3],
        ];

        foreach ($collections as $row) {
            Collection::updateOrCreate(['slug' => $row['slug']], $row + ['is_active' => true]);
        }

        $abayas = Category::where('slug', 'abayas')->first();
        $hijabs = Category::where('slug', 'hijabs')->first();
        $khimar = Category::where('slug', 'khimar')->first();
        $accessories = Category::where('slug', 'accessories')->first();
        $occasions = Collection::where('slug', 'occasions')->first();

        foreach (Product::all() as $product) {
            if (str_contains($product->name, 'عباية') && $abayas) {
                $product->categories()->syncWithoutDetaching([$abayas->id]);
                if ($occasions) {
                    $product->collections()->syncWithoutDetaching([$occasions->id]);
                }
            } elseif (str_contains($product->name, 'حجاب') && $hijabs) {
                $product->categories()->syncWithoutDetaching([$hijabs->id]);
            } elseif (str_contains($product->name, 'خمار') && $khimar) {
                $product->categories()->syncWithoutDetaching([$khimar->id]);
            } elseif ((str_contains($product->name, 'وشاح') || str_contains($product->name, 'إكسسوار')) && $accessories) {
                $product->categories()->syncWithoutDetaching([$accessories->id]);
            }

            if (! $product->details) {
                $product->update([
                    'details' => "تفاصيل المنتج:\n• خامة فاخرة مريحة للاستخدام اليومي\n• قصة محتشمة وأنيقة\n• يُنصح بالغسيل حسب تعليمات العناية\n• متوفرة بمقاسات وألوان متعددة",
                ]);
            }
        }
    }
}

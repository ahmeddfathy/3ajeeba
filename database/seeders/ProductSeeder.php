<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'عباية كريب مطرزة',
                'description' => 'عباية كريب ناعمة بتطريز أنيق، مناسبة للمناسبات اليومية والرسمية.',
                'image' => '',
                'price' => 599,
                'original_price' => null,
                'discount_type' => null,
                'discount_value' => null,
                'ribbon_label' => null,
                'is_featured' => true,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'عباية كتان فاخرة',
                'description' => 'عباية كتان خفيفة بقصة مريحة ولمسة فاخرة لإطلالة صيفية.',
                'image' => '',
                'price' => 549,
                'original_price' => 620,
                'discount_type' => 'percentage',
                'discount_value' => 11,
                'ribbon_label' => 'جديد',
                'is_featured' => true,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'عباية حرير مغسول',
                'description' => 'حرير مغسول بانسيابية عالية ولمسة ناعمة على البشرة.',
                'image' => '',
                'price' => 629,
                'original_price' => null,
                'discount_type' => null,
                'discount_value' => null,
                'ribbon_label' => null,
                'is_featured' => true,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'عباية بتطريز فاخر',
                'description' => 'تطريز فاخر يدوي الطابع يمنحكِ حضوراً مميزاً في المناسبات.',
                'image' => '',
                'price' => 679,
                'original_price' => 799,
                'discount_type' => 'fixed',
                'discount_value' => 120,
                'ribbon_label' => 'الأكثر مبيعًا',
                'is_featured' => true,
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'عباية دبل فيس',
                'description' => 'وجهان بألوان متناسقة لإطلالتين في قطعة واحدة.',
                'image' => '',
                'price' => 559,
                'original_price' => null,
                'discount_type' => null,
                'discount_value' => null,
                'ribbon_label' => null,
                'is_featured' => true,
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'حجاب شيفون ناعم',
                'description' => 'حجاب شيفون خفيف بملمس ناعم وألوان هادئة.',
                'image' => '',
                'price' => 89,
                'original_price' => null,
                'discount_type' => null,
                'discount_value' => null,
                'ribbon_label' => 'جديد',
                'is_featured' => false,
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'خمار فرنسي أنيق',
                'description' => 'خمار بستايل فرنسي عملي وأنيق للاستخدام اليومي.',
                'image' => '',
                'price' => 129,
                'original_price' => 149,
                'discount_type' => null,
                'discount_value' => null,
                'ribbon_label' => null,
                'is_featured' => false,
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'وشاح إكسسوار مطرز',
                'description' => 'إكسسوار مطرز يكمّل إطلالتكِ بتفاصيل ناعمة.',
                'image' => '',
                'price' => 79,
                'original_price' => null,
                'discount_type' => null,
                'discount_value' => null,
                'ribbon_label' => null,
                'is_featured' => false,
                'sort_order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::updateOrCreate(
                ['name' => $productData['name']],
                $productData
            );

            // فاريانتس تجريبية للمنتجات الأولى
            if (in_array($product->name, ['عباية كريب مطرزة', 'عباية كتان فاخرة', 'عباية بتطريز فاخر'], true)) {
                $base = (int) $product->price;
                $rows = [
                    ['size' => '52', 'color' => 'أسود', 'color_hex' => '#1f1a17', 'price' => $base, 'original_price' => $product->original_price, 'sort_order' => 0],
                    ['size' => '54', 'color' => 'أسود', 'color_hex' => '#1f1a17', 'price' => $base + 20, 'original_price' => $product->original_price ? $product->original_price + 20 : null, 'sort_order' => 1],
                    ['size' => '52', 'color' => 'بيج', 'color_hex' => '#D7C0AD', 'price' => $base + 10, 'original_price' => null, 'sort_order' => 2],
                    ['size' => '54', 'color' => 'بيج', 'color_hex' => '#D7C0AD', 'price' => $base + 30, 'original_price' => null, 'sort_order' => 3],
                    ['size' => '56', 'color' => 'بني', 'color_hex' => '#76503C', 'price' => $base + 40, 'original_price' => null, 'sort_order' => 4],
                ];

                foreach ($rows as $row) {
                    $product->variants()->updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'size' => $row['size'],
                            'color' => $row['color'],
                        ],
                        [
                            'color_hex' => $row['color_hex'],
                            'price' => $row['price'],
                            'original_price' => $row['original_price'],
                            'is_active' => true,
                            'sort_order' => $row['sort_order'],
                        ]
                    );
                }

                $product->update(['price' => $product->variants()->min('price') ?: $product->price]);
            }
        }
    }
}

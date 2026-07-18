<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Database\Seeder;

class BrandImagesSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            'عباية كريب مطرزة' => 'assets/images/products/abaya-1.jpg',
            'عباية كتان فاخرة' => 'assets/images/products/abaya-2.jpg',
            'عباية حرير مغسول' => 'assets/images/products/abaya-3.jpg',
            'عباية بتطريز فاخر' => 'assets/images/products/abaya-4.jpg',
            'عباية دبل فيس' => 'assets/images/products/abaya-1.jpg',
            'حجاب شيفون ناعم' => 'assets/images/categories/hijabs.jpg',
            'خمار فرنسي أنيق' => 'assets/images/categories/khimar.jpg',
            'وشاح إكسسوار مطرز' => 'assets/images/categories/accessories.jpg',
        ];

        foreach ($products as $name => $image) {
            Product::where('name', $name)->update(['image' => $image]);
        }

        foreach ([
            'abayas' => 'assets/images/categories/abayas.jpg',
            'hijabs' => 'assets/images/categories/hijabs.jpg',
            'khimar' => 'assets/images/categories/khimar.jpg',
            'accessories' => 'assets/images/categories/accessories.jpg',
        ] as $slug => $image) {
            Category::where('slug', $slug)->update(['image' => $image]);
        }

        foreach ([
            'ramadan' => 'assets/images/collections/ramadan.jpg',
            'spring' => 'assets/images/collections/spring.jpg',
            'occasions' => 'assets/images/collections/occasions.jpg',
        ] as $slug => $image) {
            Collection::where('slug', $slug)->update(['image' => $image]);
        }
    }
}

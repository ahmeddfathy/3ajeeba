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
        // Use tracked store assets — products/ uploads folder is gitignored.
        $products = [
            'عباية كريب مطرزة' => 'assets/images/store/p1.jpg',
            'عباية كتان فاخرة' => 'assets/images/store/p2.jpg',
            'عباية حرير مغسول' => 'assets/images/store/p3.jpg',
            'عباية بتطريز فاخر' => 'assets/images/store/p4.jpg',
            'عباية دبل فيس' => 'assets/images/store/p1.jpg',
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

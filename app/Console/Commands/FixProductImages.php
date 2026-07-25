<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class FixProductImages extends Command
{
    protected $signature = 'store:fix-product-images';

    protected $description = 'Remap missing product image paths to tracked store assets';

    public function handle(): int
    {
        $map = [
            'assets/images/products/abaya-1.jpg' => 'assets/images/store/p1.jpg',
            'assets/images/products/abaya-2.jpg' => 'assets/images/store/p2.jpg',
            'assets/images/products/abaya-3.jpg' => 'assets/images/store/p3.jpg',
            'assets/images/products/abaya-4.jpg' => 'assets/images/store/p4.jpg',
        ];

        $fixed = 0;
        $missing = 0;

        Product::query()->whereNotNull('image')->each(function (Product $product) use ($map, &$fixed, &$missing) {
            $current = ltrim(str_replace('\\', '/', (string) $product->image), '/');

            if (is_file(public_path($current))) {
                return;
            }

            $target = $map[$current] ?? null;

            if (! $target && preg_match('/abaya-([1-4])\.jpe?g$/i', $current, $matches)) {
                $target = 'assets/images/store/p' . $matches[1] . '.jpg';
            }

            if ($target && is_file(public_path($target))) {
                $product->update(['image' => $target]);
                $this->line("✔ {$product->name}: {$current} → {$target}");
                $fixed++;

                return;
            }

            $this->warn("✖ {$product->name}: missing {$current}");
            $missing++;
        });

        $this->info("Done. Fixed: {$fixed}, still missing: {$missing}");

        return self::SUCCESS;
    }
}

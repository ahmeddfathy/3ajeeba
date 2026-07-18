<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer(['layouts.store', 'partials.store.overlays', 'partials.store.bottom-nav'], function ($view) {
            try {
                if (! Schema::hasTable('categories')) {
                    $view->with('storeCategories', collect());
                    $view->with('storeCollections', collect());

                    return;
                }

                $view->with('storeCategories', Category::active()->get());
                $view->with('storeCollections', Collection::active()->get());
            } catch (\Throwable) {
                $view->with('storeCategories', collect());
                $view->with('storeCollections', collect());
            }
        });
    }
}

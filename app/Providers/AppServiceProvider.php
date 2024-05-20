<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Repositories\Interfaces\BrandRepositoryInterface;
use App\Repositories\BrandRepository;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\CategoryRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(BrandRepositoryInterface::class, BrandRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

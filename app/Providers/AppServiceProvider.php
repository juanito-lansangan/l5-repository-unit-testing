<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Repositories\User\UserRepositoryInterface', 'App\Repositories\User\UserRepository');
        $this->app->bind('App\Repositories\Post\PostRepositoryInterface', 'App\Repositories\Post\PostRepository');
        $this->app->bind('App\Repositories\PostTag\PostTagRepositoryInterface', 'App\Repositories\PostTag\PostTagRepository');
        $this->app->bind('App\Repositories\Tag\TagRepositoryInterface', 'App\Repositories\Tag\TagRepository');
    }
}

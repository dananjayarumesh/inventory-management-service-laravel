<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\DispatchNoteRepository;
use App\Repositories\DispatchNoteRepositoryInterface;
use App\Repositories\ItemRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\ItemRepositoryInterface;
use App\Repositories\ReceiveNoteRepository;
use App\Repositories\ReceiveNoteRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Services\AuthService;
use App\Services\AuthServiceInterface;
use App\Services\UserService;
use App\Services\UserServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Repositories
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(DispatchNoteRepositoryInterface::class, DispatchNoteRepository::class);
        $this->app->bind(ItemRepositoryInterface::class, ItemRepository::class);
        $this->app->bind(ReceiveNoteRepositoryInterface::class, ReceiveNoteRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

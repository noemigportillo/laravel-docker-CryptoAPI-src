<?php

namespace App\Infrastructure\Providers;

use App\DataSource\Database\EloquentUserDataSource;
use Illuminate\Support\ServiceProvider;
use App\Application\WalletDataSource\WalletDataSource;
use App\Infrastructure\Persistence\FileWalletDataSource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(WalletDataSource::class, FileWalletDataSource::class);
//        $this->app->bind(UserDataSource::class, function () {
//            return new EloquentUserDataSource();
//        });
    }
}

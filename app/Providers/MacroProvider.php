<?php

declare(strict_types=1);

namespace App\Providers;

use App\Types\TransactionCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

final class MacroProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Collection::macro('transactions', fn () => TransactionCollection::from($this));
    }
}

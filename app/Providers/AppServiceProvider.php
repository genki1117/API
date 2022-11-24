<?php
declare(strict_types=1);
namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Database\Events\TransactionCommitted;
use Illuminate\Database\Events\TransactionRolledBack;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (app()->environment() === 'develop' || app()->environment() === 'local') {
//            \DB::listen(function ($query) {
//                $sql = $query->sql;
//                foreach ($query->bindings as $binding) {
//                    if (is_string($binding)) {
//                        $binding = "'{$binding}'";
//                    } elseif ($binding === null) {
//                        $binding = 'NULL';
//                    } elseif ($binding instanceof Carbon) {
//                        $binding = "'{$binding->toDateTimeString()}'";
//                    }
//
//                    $sql = preg_replace("/\?/", $binding, $sql, 1);
//                }
//                \Log::debug($sql, ['time' => "{$query->time}ms"]);
//            });

            // トランザクション開始・終了
            \Event::listen(TransactionBeginning::class, function () {
                \Log::debug('START TRANSACTION');
            });

            \Event::listen(TransactionCommitted::class, function () {
                \Log::debug('COMMIT');
            });

            \Event::listen(TransactionRolledBack::class, function () {
                \Log::debug('ROLLBACK');
            });
        }
    }
}

<?php
declare(strict_types=1);
namespace App\Providers;

use App\Foundations\Context\LoggedInUserContext;
use Illuminate\Support\ServiceProvider;

class ContextServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * サンプルのログインユーザー情報を格納するオブジェクトをサービスコンテナに登録
         */
        $this->app->singleton(LoggedInUserContext::class, function () {
            return new LoggedInUserContext();
        });
    }
}

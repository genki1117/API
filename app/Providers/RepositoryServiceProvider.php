<?php
declare(strict_types=1);
namespace App\Providers;

use App\Domain\Repositories\Document\DocumentSignOrderRepository;
use App\Domain\Repositories\Interface\Document\DocumentSignOrderRepositoryInterface;
use App\Domain\Repositories\Document\DocumentDetailRepository;
use App\Domain\Repositories\Interface\Document\DocumentDetailRepositoryInterface;
use App\Domain\Repositories\Interface\Document\DocumentGetListRepositoryInterface;
use App\Domain\Repositories\Document\DocumentGetListRepository;
use App\Domain\Repositories\Interface\Document\DocumentListRepositoryInterface;
use App\Domain\Repositories\Document\DocumentListRepository;
use App\Domain\Repositories\Common\LoginUserRepository;
use App\Domain\Repositories\Interface\Common\LoginUserRepositoryInterface;
use App\Domain\Repositories\Common\SystemAccessLogRepository;
use App\Domain\Repositories\Interface\Common\SystemAccessLogRepositoryInterface;
use App\Domain\Repositories\Interface\Sample\UserRepositoryInterface;
use App\Domain\Repositories\Sample\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * 抽象クラスと具象クラスの結合
     * [
     *   抽象クラス => 具象クラス
     * ]
     *
     * @var array
     */
    public array $bindings = [
        UserRepositoryInterface::class => UserRepository::class,
        LoginUserRepositoryInterface::class => LoginUserRepository::class,
        SystemAccessLogRepositoryInterface::class => SystemAccessLogRepository::class,
        DocumentListRepositoryInterface::class => DocumentListRepository::class,
        DocumentDetailRepositoryInterface::class => DocumentDetailRepository::class,
        DocumentGetListRepositoryInterface::class => DocumentGetListRepository::class,
        DocumentSignOrderRepositoryInterface::class => DocumentSignOrderRepository::class,
    ];

    /**
     * DIコンテナ内でのシングルトンで解決
     *　$bindingsの結合をシングルトンパターンで解決する
     * @var array
     */
    public array $singletons = [];
}

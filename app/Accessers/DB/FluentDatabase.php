<?php
declare(strict_types=1);
namespace App\Accessers\DB;

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Query\Builder;

abstract class FluentDatabase
{
    protected const DELETED_FLAG_OFF = 0;

    /** @var DatabaseManager */
    private DatabaseManager $manager;
    /** @var string */
    protected ?string $connection;
    /** @var string*/
    protected string $table;

    /**
     * @param DatabaseManager $databaseManager
     */
    public function __construct(
        DatabaseManager $databaseManager,
    ) {
        $this->manager = $databaseManager;
    }

    /**
     * @param null|string $table
     * @return Builder
     */
    public function builder(?string $table = null): Builder
    {
        $selectTable = $table ?? $this->table;
        $connection = $this->connection ?? null;
        return $this->manager->connection($connection)->table($selectTable);
    }

    /**
     * @param $connection
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }


    /**
     * @return DatabaseManager
     */
    protected function getManager(): DatabaseManager
    {
        return $this->manager;
    }
}

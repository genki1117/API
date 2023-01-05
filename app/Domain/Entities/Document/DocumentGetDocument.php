<?php

declare(strict_types=1);
namespace App\Domain\Entities\Document;

class DocumentGetDocument
{
    /** @var array|null */
    private ?array $getList;

    /** @var int|null */
    private ?int $listCount;

    /**
     * @param array|null $getList
     * @param int|null $listCount
     */
    public function __construct(?array $getList = null, ?int $listCount = null)
    {
        $this->getList = $getList;
        $this->listCount = $listCount;
    }

    /** @return array|null */
    public function getList(): ?array
    {
        return $this->getList;
    }

    /** @return int|null */
    public function getListCount(): ?int
    {
        return $this->listCount;
    }
}

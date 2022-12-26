<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Interface\Document;

interface DocumentSaveRepositoryInterface
{
    // 契約書類
    public function contractInsert(array $requestContent);

    public function contractUpdate(array $requestContent);

    // 取引書類
    public function dealInsert(array $requestContent);

    public function dealUpdate(array $requestContent);

    // 社内書類
    public function internalInsert(array $requestContent);

    public function internalUpdate(array $requestContent);

    // 登録書類
    public function archiveInsert(array $requestContent);

    public function archiveUpdate(array $requestContent);
}

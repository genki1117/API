<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Interface\Document;

interface DocumentSaveRepositoryInterface
{
    public function contractInsert(array $requestContent): bool;


    public function contractUpdate(array $requestContent): bool;


    public function dealInsert($requestContent);


    public function internalInsert($requestContent);


    public function archiveInsert($requestContent);


    public function dealUpdate($requestContent);


    public function internalUpdate($requestContent);


    public function archiveUpdate($requestContent);

}

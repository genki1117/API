<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Interface\Document;

use App\Domain\Entities\Document\DocumentGetList as DocumentGetListEntity;

interface DocumentGetListRepositoryInterface
{
    /**
     * @param array $mUser
     * @param array $condition
     * @param array $sort
     * @param array $page
     * @return DocumentGetListEntity
     */
    public function getListContract(array $mUser, array $condition, array $sort, array $page): DocumentGetListEntity;

    /**
     * @param array $mUser
     * @param array $condition
     * @param array $sort
     * @param array $page
     * @return DocumentGetListEntity
     */
    public function getListDeal(array $mUser, array $condition, array $sort, array $page): DocumentGetListEntity;

    /**
     * @param array $mUser
     * @param array $condition
     * @param array $sort
     * @param array $page
     * @return DocumentGetListEntity
     */
    public function getListInternal(array $mUser, array $condition, array $sort, array $page): DocumentGetListEntity;

    /**
     * @param array $mUser
     * @param array $condition
     * @param array $sort
     * @param array $page
     * @return DocumentGetListEntity
     */
    public function getListArchive(array $mUser, array $condition, array $sort, array $page): DocumentGetListEntity;
}

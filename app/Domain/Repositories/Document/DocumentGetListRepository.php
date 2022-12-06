<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Document;

use App\Accessers\DB\Document\DocumentContract;
use App\Accessers\DB\Document\DocumentDeal;
use App\Accessers\DB\Document\DocumentInternal;
use App\Accessers\DB\Document\DocumentArchive;
use App\Domain\Entities\Document\DocumentGetList as DocumentGetListEntity;
use App\Domain\Repositories\Interface\Document\DocumentGetListRepositoryInterface;

class DocumentGetListRepository implements DocumentGetListRepositoryInterface
{
    private DocumentDeal $docDeal;
    private DocumentArchive $docArchive;
    private DocumentContract $docContract;
    private DocumentInternal $docInternal;

    /**
     * @param DocumentDeal $docDeal
     * @param DocumentArchive $docArchive
     * @param DocumentContract $docContract
     * @param DocumentInternal $docInternal
     */
    public function __construct(DocumentDeal $docDeal,
                                DocumentArchive $docArchive,
                                DocumentContract $docContract,
                                DocumentInternal $docInternal)
    {
        $this->docDeal = $docDeal;
        $this->docArchive = $docArchive;
        $this->docContract = $docContract;
        $this->docInternal = $docInternal;
    }

    /**
     * @param array $mUser
     * @param array $condition
     * @param array $sort
     * @param array $page
     * @return DocumentGetListEntity
     */
    public function getListContract(array $mUser, array $condition, array $sort, array $page): DocumentGetListEntity
    {
        $listConstract = $this->docContract->getDocList($mUser, $condition, $sort, $page)->all();
        $countListConstract = $this->docContract->getDocListCount($mUser, $condition, $sort);
        
        if(empty($listConstract)) {
            return new DocumentGetListEntity();
        }

        return new DocumentGetListEntity($listConstract, $countListConstract);
    }

    /**
     * @param array $mUser
     * @param array $condition
     * @param array $sort
     * @param array $page
     * @return DocumentGetListEntity
     */
    public function getListDeal(array $mUser, array $condition, array $sort, array $page): DocumentGetListEntity
    {
        $listDeal = $this->docDeal->getDocList($mUser, $condition, $sort, $page)->all();
        $countListDeal = $this->docDeal->getDocListCount($mUser, $condition, $sort);

        if(empty($listDeal)) {
            return new DocumentGetListEntity();
        }

        return new DocumentGetListEntity($listDeal, $countListDeal);
    }

    /**
     * @param array $mUser
     * @param array $condition
     * @param array $sort
     * @param array $page
     * @return DocumentGetListEntity
     */
    public function getListInternal(array $mUser, array $condition, array $sort, array $page): DocumentGetListEntity
    {
        $listInternal = $this->docInternal->getDocList($mUser, $condition, $sort, $page)->all();
        $countListInternal = $this->docInternal->getDocListCount($mUser, $condition, $sort);

        if(empty($listInternal)) {
            return new DocumentGetListEntity();
        }

        return new DocumentGetListEntity($listInternal, $countListInternal);
    }

    /**
     * @param array $mUser
     * @param array $condition
     * @param array $sort
     * @param array $page
     * @return DocumentGetListEntity
     */
    public function getListArchive(array $mUser, array $condition, array $sort, array $page): DocumentGetListEntity
    {
        $listArchive = $this->docArchive->getDocList($mUser, $condition, $sort, $page)->all();
        $countListArchive = $this->docArchive->getDocListCount($mUser, $condition, $sort);

        if(empty($listArchive)) {
            return new DocumentGetListEntity();
        }

        return new DocumentGetListEntity($listArchive, $countListArchive);
    }
}

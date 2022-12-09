<?php
declare(strict_types=1);
namespace App\Domain\Services\Document;

use App\Domain\Consts\DocumentConst;
use App\Domain\Entities\Document\DocumentGetList;
use App\Domain\Repositories\Interface\Document\DocumentGetListRepositoryInterface;

class DocumentGetListService
{
    /** @var DocumentGetListRepositoryInterface */
    private DocumentGetListRepositoryInterface $docGetListRepository;

    /** @var DocumentConst */
    private DocumentConst $docConst;

    /**
     * @param DocumentGetListRepositoryInterface $docGetListRepository
     */
    public function __construct(DocumentGetListRepositoryInterface $docGetListRepository, DocumentConst $docConst)
    {
        $this->docConst = $docConst;
        $this->docGetListRepository = $docGetListRepository;
    }

    /**
     * @param array $mUser
     * @param array $condition
     * @param array $sort
     * @param array $page
     * @return DocumentGetList|null
     */
    public function getList(array $mUser, array $condition, array $sort, array $page): ?DocumentGetList
    {
        switch($condition['category_id']) {
            case $this->docConst::DOCUMENT_CONTRACT:
                $data = $this->docGetListRepository->getListContract($mUser, $condition, $sort, $page);
                break;
            case $this->docConst::DOCUMENT_DEAL:
                $data = $this->docGetListRepository->getListDeal($mUser, $condition, $sort, $page);
                break;
            case $this->docConst::DOCUMENT_INTERNAL:
                $data = $this->docGetListRepository->getListInternal($mUser, $condition, $sort, $page);
                break;
            case $this->docConst::DOCUMENT_ARCHIVE:
                $data = $this->docGetListRepository->getListArchive($mUser, $condition, $sort, $page);
                break;
            default:
                return null;
                break;
        }
        
        if (empty($data->getList())) {
            return null;
        }

        return $data;
    }
}

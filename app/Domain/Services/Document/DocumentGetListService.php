<?php
declare(strict_types=1);
namespace App\Domain\Services\Document;

use App\Domain\Entities\Document\DocumentGetList;
use App\Domain\Repositories\Interface\Document\DocumentGetListRepositoryInterface;

class DocumentGetListService
{
    /** @var int */
    protected const DOC_CONTRACT_TYPE = 0;
    /** @var int */
    protected const DOC_DEAL_TYPE = 1;
    /** @var int */
    protected const DOC_INTERNAL_TYPE = 2;
    /** @var int */
    protected const DOC_ARCHIVE_TYPE = 3;

    /** @var DocumentGetListRepositoryInterface */
    private DocumentGetListRepositoryInterface $docGetListRepository;

    /**
     * @param DocumentGetListRepositoryInterface $docGetListRepository
     */
    public function __construct(DocumentGetListRepositoryInterface $docGetListRepository)
    {
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
            case self::DOC_CONTRACT_TYPE:
                $data = $this->docGetListRepository->getListContract($mUser, $condition, $sort, $page);
                break;
            case self::DOC_DEAL_TYPE:
                $data = $this->docGetListRepository->getListDeal($mUser, $condition, $sort, $page);
                break;
            case self::DOC_INTERNAL_TYPE:
                $data = $this->docGetListRepository->getListInternal($mUser, $condition, $sort, $page);
                break;
            case self::DOC_ARCHIVE_TYPE:
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

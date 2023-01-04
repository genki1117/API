<?php
declare(strict_types=1);
namespace App\Domain\Services\Document;

use App\Accessers\DB\Log\System\LogDocAccess;
use App\Accessers\DB\Log\System\LogDocOperation;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Domain\Entities\Document\DocumentDetail;
use App\Domain\Repositories\Interface\Document\DocumentSaveRepositoryInterface;

class DocumentSaveService
{
    /** @var int */
    protected const DOC_CONTRACT_TYPE = 0;
    /** @var int */
    protected const DOC_DEAL_TYPE = 1;
    /** @var int */
    protected const DOC_INTERNAL_TYPE = 2;
    /** @var int */
    protected const DOC_ARCHIVE_TYPE = 3;



    /** @var DocumentSaveRepositoryInterface */
    private DocumentSaveRepositoryInterface $documentRepository;

    /**
     * @param DocumentSaveRepositoryInterface $documentRepository
     */
    public function __construct(DocumentSaveRepositoryInterface $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }

    /**
     * Undocumented function
     *
     * @param array $requestContent
     * @return boolean
     */
    public function saveDocument(array $requestContent): ?bool
    {
        try {
            switch ($requestContent['category_id']) {
                // 契約書類の登録、更新
                case Self::DOC_CONTRACT_TYPE:
                    // 新規登録
                    if (!$requestContent['document_id']) {
                        $documentSaveResult = $this->documentRepository->contractInsert($requestContent);
                    }
                    // 更新登録
                    if ($requestContent['document_id']) {
                        //更新前の情報を取得する
                        $beforeList = $this->documentRepository->getBeforOrAfterUpdateContract($requestContent);

                        // 契約書類更新
                        $documentSaveResult = $this->documentRepository->contractUpdate($requestContent);

                        // 更新後の情報を取得する
                        $afterList = $this->documentRepository->getBeforOrAfterUpdateContract($requestContent);

                        // ログ出力を行う
                        $this->documentRepository->getUpdateLog(
                            $requestContent,
                            $beforeList->getUpdateDocument(),
                            $afterList->getUpdateDocument(),
                        );
                    }
                    break;

                    // 取引書類の登録、更新
                case Self::DOC_DEAL_TYPE:
                    // 新規登録
                    if (!$requestContent['document_id']) {
                        $documentSaveResult = $this->documentRepository->dealInsert($requestContent);
                    }
                    // 更新登録
                    if ($requestContent['document_id']) {
                        //更新前の情報を取得する
                        $beforeList = $this->documentRepository->getBeforOrAfterUpdateDeal($requestContent);

                        // 取引書類更新
                        $documentSaveResult = $this->documentRepository->dealUpdate($requestContent);

                        // 更新後の情報を取得する
                        $afterList = $this->documentRepository->getBeforOrAfterUpdateDeal($requestContent);

                        // ログ出力を行う
                        $this->documentRepository->getUpdateLog(
                            $requestContent,
                            $beforeList->getUpdateDocument(),
                            $afterList->getUpdateDocument(),
                        );
                    }
                    break;

                    // 社内書類の登録、更新
                case Self::DOC_INTERNAL_TYPE:
                    // 新規登録
                    if (!$requestContent['document_id']) {
                        $documentSaveResult = $this->documentRepository->internalInsert($requestContent);
                    }
                    // 更新登録
                    if ($requestContent['document_id']) {
                        //更新前の情報を取得する
                        $beforeList = $this->documentRepository->getBeforOrAfterUpdateInternal($requestContent);
                        
                        // 社内書類更新
                        $documentSaveResult = $this->documentRepository->internalUpdate($requestContent);

                        // 更新後の情報を取得する
                        $afterList = $this->documentRepository->getBeforOrAfterUpdateInternal($requestContent);

                        // ログ出力を行う
                        $this->documentRepository->getUpdateLog(
                            $requestContent,
                            $beforeList->getUpdateDocument(),
                            $afterList->getUpdateDocument(),
                        );
                    }
                    break;

                    // 登録書類の登録、更新
                case Self::DOC_ARCHIVE_TYPE:
                    // 新規登録
                    if (!$requestContent['document_id']) {
                        $documentSaveResult = $this->documentRepository->archiveInsert($requestContent);
                    }
                    // 更新登録
                    if ($requestContent['document_id']) {
                        //更新前の情報を取得する
                        $beforeList = $this->documentRepository->getBeforOrAfterUpdateARchive($requestContent);

                        // 登録書類更新
                        $documentSaveResult = $this->documentRepository->archiveUpdate($requestContent);

                        // 更新後の情報を取得する
                        $afterList = $this->documentRepository->getBeforOrAfterUpdateArchive($requestContent);

                        // ログ出力を行う
                        $this->documentRepository->getUpdateLog(
                            $requestContent,
                            $beforeList->getUpdateDocument(),
                            $afterList->getUpdateDocument(),
                        );
                    }
                    break;
            }
            return true;
        } catch (Exception $e) {
            throw $e;
        }
        return $documentSaveResult;
    }
}

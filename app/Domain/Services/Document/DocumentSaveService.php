<?php
declare(strict_types=1);
namespace App\Domain\Services\Document;

use App\Domain\Consts\DocumentConst;
use Exception;
use App\Domain\Repositories\Interface\Document\DocumentSaveRepositoryInterface;

class DocumentSaveService
{
    /** @var DocumentConst */
    private DocumentConst $docConst;

    /** @var DocumentSaveRepositoryInterface */
    private DocumentSaveRepositoryInterface $documentRepository;

    /**
     * @param DocumentConst $docConst
     * @param DocumentSaveRepositoryInterface $documentRepository
     */
    public function __construct(
            DocumentSaveRepositoryInterface $documentRepository,
            DocumentConst $docConst
        )
    {
        $this->docConst           = $docConst;
        $this->documentRepository = $documentRepository;
    }

    /**
     * @param array $requestContent
     * @return boolean
     */
    public function saveDocument(array $requestContent): ?bool
    {
        // TODO: AzurePDF保存処理
        try {
            switch ($requestContent['category_id']) {
                // 契約書類の登録、更新
                case $this->docConst::DOCUMENT_CONTRACT:
                    // 新規登録
                    if (!$requestContent['document_id']) {
                        $documentSaveResult = $this->documentRepository->contractInsert(requestContent: $requestContent);
                        if (!$documentSaveResult) {
                            throw new Exception('common.message.permission');
                        }
                    }
                    // 更新登録
                    if ($requestContent['document_id']) {
                        //更新前の情報を取得する
                        $beforeList = $this->documentRepository->getBeforeOrAfterUpdateContract(requestContent: $requestContent);

                        // 契約書類更新
                        $documentSaveResult = $this->documentRepository->contractUpdate(requestContent: $requestContent);
                        if (!$documentSaveResult) {
                            throw new Exception('common.message.permission');
                        }

                        // 更新後の情報を取得する
                        $afterList = $this->documentRepository->getBeforeOrAfterUpdateContract(requestContent: $requestContent);

                        // ログ出力を行う
                        $this->documentRepository->getUpdateLogContract(
                            $requestContent,
                            $beforeList->getUpdateDocument(),
                            $afterList->getUpdateDocument(),
                        );
                    }
                    break;

                    // 取引書類の登録、更新
                case $this->docConst::DOCUMENT_DEAL:
                    // 新規登録
                    if (!$requestContent['document_id']) {
                        $documentSaveResult = $this->documentRepository->dealInsert(requestContent: $requestContent);
                        if (!$documentSaveResult) {
                            throw new Exception('common.message.permission');
                        }
                    }
                    // 更新登録
                    if ($requestContent['document_id']) {
                        //更新前の情報を取得する
                        $beforeList = $this->documentRepository->getBeforeOrAfterUpdateDeal(requestContent: $requestContent);

                        // 取引書類更新
                       $documentSaveResult = $this->documentRepository->dealUpdate(requestContent: $requestContent);
                       if (!$documentSaveResult) {
                        throw new Exception('common.message.permission');
                    }

                        // 更新後の情報を取得する
                        $afterList = $this->documentRepository->getBeforeOrAfterUpdateDeal(requestContent: $requestContent);

                        // ログ出力を行う
                        $this->documentRepository->getUpdateLogDeal(
                            $requestContent,
                            $beforeList->getUpdateDocument(),
                            $afterList->getUpdateDocument(),
                        );
                    }
                    break;

                    // 社内書類の登録、更新
                case $this->docConst::DOCUMENT_INTERNAL:
                    // 新規登録
                    if (!$requestContent['document_id']) {
                        $documentSaveResult = $this->documentRepository->internalInsert(requestContent: $requestContent);
                        if (!$documentSaveResult) {
                            throw new Exception('common.message.permission');
                        }
                    }
                    // 更新登録
                    if ($requestContent['document_id']) {
                        //更新前の情報を取得する
                        $beforeList = $this->documentRepository->getBeforeOrAfterUpdateInternal(requestContent: $requestContent);
                        
                        // 社内書類更新
                        $documentSaveResult = $this->documentRepository->internalUpdate(requestContent: $requestContent);
                        if (!$documentSaveResult) {
                            throw new Exception('common.message.permission');
                        }

                        // 更新後の情報を取得する
                        $afterList = $this->documentRepository->getBeforeOrAfterUpdateInternal(requestContent: $requestContent);

                        // ログ出力を行う
                        $this->documentRepository->getUpdateLogInternal(
                            $requestContent,
                            $beforeList->getUpdateDocument(),
                            $afterList->getUpdateDocument(),
                        );
                    }
                    break;

                    // 登録書類の登録、更新
                case $this->docConst::DOCUMENT_ARCHIVE:
                    // 新規登録
                    if (!$requestContent['document_id']) {
                        $documentSaveResult = $this->documentRepository->archiveInsert(requestContent: $requestContent);
                        if (!$documentSaveResult) {
                            throw new Exception('common.message.permission');
                        }
                    }
                    // 更新登録
                    if ($requestContent['document_id']) {
                        //更新前の情報を取得する
                        $beforeList = $this->documentRepository->getBeforeOrAfterUpdateARchive(requestContent: $requestContent);

                        // 登録書類更新
                        $documentSaveResult = $this->documentRepository->archiveUpdate(requestContent: $requestContent);
                        if (!$documentSaveResult) {
                            throw new Exception('common.message.permission');
                        }

                        // 更新後の情報を取得する
                        $afterList = $this->documentRepository->getBeforeOrAfterUpdateArchive(requestContent: $requestContent);

                        // ログ出力を行う
                        $this->documentRepository->getUpdateLogArchive(
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

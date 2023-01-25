<?php
declare(strict_types=1);
namespace App\Domain\Services\Batch;

use App\Domain\Consts\DocumentConst;
use App\Domain\Repositories\Interface\Batch\ExpiryDocumentUpdateInterface;


class ExpiryDocumentUpdateBatchService
{

    /** @var $ExpiryDocumentUpdateRepository */
    private $ExpiryDocumentUpdateRepository;

    public function __construct(ExpiryDocumentUpdateInterface $ExpiryDocumentUpdateRepository)
    {
        $this->ExpiryDocumentUpdateRepository = $ExpiryDocumentUpdateRepository;
    }

    public function expiryDocumentUpdate()
    {
        // 各書類の期限切れ状態のデータを取得
        $expiryTokenData = $this->ExpiryDocumentUpdateRepository->getExpiryTokenData();

        // 取得したトークンとトークンから取得した書類を更新
        // 書類は「期限切れ」
        // トークンは「削除更新」
        foreach($expiryTokenData as $data) {

            switch ($data->category_id) {
                // 契約書類
                case DocumentConst::DOCUMENT_CONTRACT:
                    $this->ExpiryDocumentUpdateRepository->expriyUpdateContract(data: $data);
                    break;

                 // 取引書類
                 case DocumentConst::DOCUMENT_DEAL:
                    $this->ExpiryDocumentUpdateRepository->expriyUpdateDeal(data: $data);
                    break;

                // 社内書類
                case DocumentConst::DOCUMENT_INTERNAL:
                    $this->ExpiryDocumentUpdateRepository->expriyUpdateInternal(data: $data);
                    break;

                // 登録書類
                case DocumentConst::DOCUMENT_ARCHIVE:
                    $this->ExpiryDocumentUpdateRepository->expriyUpdateArchive(data: $data);
                    break;
            }
        }
        return true;
    }
}


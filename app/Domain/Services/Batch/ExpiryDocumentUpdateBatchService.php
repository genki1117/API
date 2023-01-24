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

    public function expiryDcoumentUpdate()
    {
        // 各書類の期限切れ状態のデータを取得する。
        $expiryTokenData = $this->ExpiryDocumentUpdateRepository->getExpiryTokenData();

        // 取得件数分下記処理を実施する。
        // 取得した期限切れの書類のステータスを「期限切れ」に変更する。
        // ※更新内容は、データ更新を参照。

        foreach($expiryTokenData as $data) {

            switch ($data->category_id) {
                // 契約書類
                case DocumentConst::DOCUMENT_CONTRACT:
                    $documentUpdateResult = $this->ExpiryDocumentUpdateRepository->expriyUpdateContract(data: $data);
                    break;

                 // 取引書類
                 case DocumentConst::DOCUMENT_DEAL:
                    $documentUpdateResult = $this->ExpiryDocumentUpdateRepository->expriyUpdateDeal(data: $data);
                    break;

                // 社内書類
                case DocumentConst::DOCUMENT_INTERNAL:
                    $documentUpdateResult = $this->ExpiryDocumentUpdateRepository->expriyUpdateInternal(data: $data);
                    break;

                // 登録書類
                case DocumentConst::DOCUMENT_ARCHIVE:
                    $documentUpdateResult = $this->ExpiryDocumentUpdateRepository->expriyUpdateArchive(data: $data);
                    break;

                return $documentUpdateResult;
            }
        }
    }
}


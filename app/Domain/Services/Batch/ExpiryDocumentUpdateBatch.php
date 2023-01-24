<?php
declare(strict_types=1);
namespace App\Domain\Services\Batch;

use App\Domain\Repositories\Interface\Batch\ExpiryDocumentUpdateInterface;


class ExpiryDocumentUpdateBatch
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
        //var_export($expiryTokenData);

        // 取得件数分下記処理を実施する。
        // 取得した期限切れの書類のステータスを「期限切れ」に変更する。
        // ※更新内容は、データ更新を参照。

        foreach($expiryTokenData as $data) {

            switch ($data->category_id) {
                case 0:
                    $this->ExpiryDocumentUpdateRepository->expriyUpdateContract($data);


            }
    
        }

    }

    
    
}


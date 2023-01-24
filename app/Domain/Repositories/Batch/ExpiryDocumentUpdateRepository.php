<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Batch;

use App\Accessers\DB\Document\DocumentArchive;
use App\Accessers\DB\Document\DocumentInternal;
use App\Accessers\DB\Document\DocumentDeal;
use App\Accessers\DB\Document\DocumentContract;
use App\Accessers\DB\TempToken;
use Illuminate\Support\Collection;
use App\Domain\Repositories\Interface\Batch\ExpiryDocumentUpdateInterface;


class ExpiryDocumentUpdateRepository implements ExpiryDocumentUpdateInterface

{
    /** @var $docArchive */
    private $docArchive;

    /** @var $docInternal */
    private $docInternal;

    /** @var $docDeal */
    private $docDeal;

    /** @var $docContract */
    private $docContract;

    /** @var $tempToken */
    private $tempToken;


    public function __construct(
            DocumentArchive $docArchive,
            DocumentInternal $docInternal,
            DocumentDeal $docDeal,
            DocumentContract $docContract,
            TempToken $tempToken
        )
    {   
        $this->docArchive  = $docArchive;
        $this->docInternal = $docInternal;
        $this->docDeal     = $docDeal;
        $this->docContract = $docContract;
        $this->tempToken   = $tempToken;
    }

    /**
     * 期限切れトークン取得
     *
     * @return collection
     */
    public function getExpiryTokenData(): collection
    {
        return  $this->tempToken->getExpiryToken();        
    }

    /**
     * 契約書類期限切れ更新
     *
     * @param object $data
     * @return boolean
     */
    public function expriyUpdateContract(object $data): bool
    {
        // 期限切れ更新（契約書類）
        $updateDocumentResult = $this->docContract->expiryUpdate(data: $data);

        // トークン削除更新
        $deleteTokenResult = $this->tempToken->deleteUpdate(data: $data);

        if (empty($updateDocumentResult) && empty($deleteTokenResult)) {
            return false;
        }
        return true;
    }

    /**
     * 取引書類期限切れ更新
     *
     * @param object $data
     * @return boolean
     */
    public function expriyUpdateDeal(object $data): bool
    {
        // 期限切れ更新（取引書類）
        $updateDocumentResult = $this->docDeal->expiryUpdate(data: $data);

        // トークン削除更新
        $deleteTokenResult = $this->tempToken->deleteUpdate(data: $data);

        if (empty($updateDocumentResult) && empty($deleteTokenResult)) {
            return false;
        }
        return true;
    }

    /**
     * 社内書類期限切れ更新
     *
     * @param object $data
     * @return boolean
     */
    public function expriyUpdateInternal(object $data): bool
    {
        // 期限切れ更新（社内書類）
        $updateDocumentResult = $this->docInternal->expiryUpdate(data: $data);
        
        // トークン削除更新
        $deleteTokenResult    = $this->tempToken->deleteUpdate(data: $data);

        if (empty($updateDocumentResult) && empty($deleteTokenResult)){
            return false;
        }
        return true;
    }

    /**
     * 登録書類期限切れ更新
     *
     * @param object $data
     * @return boolean
     */
    public function expriyUpdateArchive(object $data): bool
    {
        // 期限切れ更新（登録書類）
        $updateDocumentResult = $this->docArchive->expiryUpdate(data: $data);

        // トークン削除更新
        $deleteTokenResult    = $this->tempToken->deleteUpdate(data: $data);

        if (empty($updateDocumentResult) && empty($deleteTokenResult)){
            return false;
        }
        return true;
    }
    
}

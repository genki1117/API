<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Interface\Batch;

use Illuminate\Support\Collection;
use App\Domain\Entities\Common\TempToken;
use App\Domain\Entities\Users\User;

interface ExpiryDocumentUpdateInterface
{
    /**
     * 期限切れトークン取得
     *
     * @return collection
     */
    public function getExpiryTokenData(): collection;

    /**
     * 契約書類期限切れ更新
     *
     * @param object $data
     * @return boolean
     */
    public function expriyUpdateContract(object $data): bool;

    /**
     * 取引書類期限切れ更新
     *
     * @param object $data
     * @return boolean
     */
    public function expriyUpdateDeal(object $data): bool;

    /**
     * 社内書類期限切れ更新
     *
     * @param object $data
     * @return boolean
     */
    public function expriyUpdateInternal(object $data): bool;

    /**
     * 登録書類期限切れ更新
     *
     * @param object $data
     * @return boolean
     */
    public function expriyUpdateArchive(object $data): bool;
}

<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Interface\Document;

use App\Domain\Entities\Document\Document;
use App\Domain\Entities\Organization\User\AccessUser;
use App\Domain\Entities\Organization\User\OperationUser;
use App\Domain\Entities\Organization\User\SelectSignGuestUser;
use App\Domain\Entities\Organization\User\SelectSignUser;
use App\Domain\Entities\Organization\User\SelectViewUser;

/**
 * 書類詳細リポジトリインターフェイス
 */
interface DocumentDetailRepositoryInterface
{
    /**
     * 書類詳細取得
     * @param int $categoryId
     * @param int $documentId
     * @param int $companyId
     * @param int $userId
     * @return Document
     */
    public function getDetail(int $categoryId, int $documentId, int $companyId, int $userId): Document;

    /**
     * アクセス履歴情報取得
     * @param int $categoryId
     * @param int $documentId
     * @param int $companyId
     * @return array<AccessUser>
     */
    public function getAccessLog(int $categoryId, int $documentId, int $companyId): array;

    /**
     * 変更履歴情報取得
     * @param int $categoryId
     * @param int $documentId
     * @param int $companyId
     * @return array<OperationUser>
     */
    public function getOperationLog(int $categoryId, int $documentId, int $companyId): array;

    /**
     * 選択署名者（ゲスト）取得
     * @param int $documentId
     * @param int $categoryId
     * @param int $companyId
     * @return array<SelectSignGuestUser>
     */
    public function getSelectSignGuestUsers(int $documentId, int $categoryId, int $companyId): array;

    /**
     * 選択署名者（ホスト）取得
     * @param int $documentId
     * @param int $categoryId
     * @param int $companyId
     * @return array<SelectSignUser>
     */
    public function getSelectSignUser(int $documentId, int $categoryId, int $companyId): array;

    /**
     * 選択署名者（閲覧者）取得
     * @param int $documentId
     * @param int $categoryId
     * @param int $companyId
     * @return array<SelectViewUser>
     */
    public function getSelectViewUser(int $documentId, int $categoryId, int $companyId): array;
}

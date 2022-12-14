<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Interface\Document;

use App\Domain\Entities\Document\Document;

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
     * @return array
     */
    public function getAccessLog(int $categoryId, int $documentId, int $companyId): array;

    /**
     * 変更履歴情報取得
     * @param int $categoryId
     * @param int $documentId
     * @param int $companyId
     * @return array
     */
    public function getOperationLog(int $categoryId, int $documentId, int $companyId): array;

    /**
     * 選択署名者（ゲスト）取得
     * @param int $documentId
     * @param int $categoryId
     * @param int $companyId
     * @return array
     */
    public function getSelectSignGuestUsers(int $documentId, int $categoryId, int $companyId): array;

    /**
     * 選択署名者（ホスト）取得
     * @param int $documentId
     * @param int $categoryId
     * @param int $companyId
     * @return array
     */
    public function getSelectSignUser(int $documentId, int $categoryId, int $companyId): array;

    /**
     * 選択署名者（閲覧者）取得
     * @param int $documentId
     * @param int $categoryId
     * @param int $companyId
     * @return array
     */
    public function getSelectViewUser(int $documentId, int $categoryId, int $companyId): array;
}

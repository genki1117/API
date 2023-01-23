<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Interface\Document;

use App\Domain\Entities\Document\DocumentUpdate as DocumentUpdateEntity;

interface DocumentSaveRepositoryInterface
{
    /**
     * 契約書類登録
     *
     * @param array $requestContent
     * @return boolean|null
     */
    public function contractInsert(array $requestContent): ?bool;

    /**
     * 契約書類更新
     *
     * @param array $requestContent
     * @return boolean|null
     */
    public function contractUpdate(array $requestContent): ?bool;

    
    /**
     * 取引書類登録
     *
     * @param array $requestContent
     * @return boolean|null
     */
    public function dealInsert(array $requestContent): ?bool;

    /**
     * 取引書類更新
     *
     * @param array $requestContent
     * @return boolean|null
     */
    public function dealUpdate(array $requestContent): ?bool;

    /**
     * 社内書類登録
     *
     * @param array $requestContent
     * @return boolean|null
     */
    public function internalInsert(array $requestContent): ?bool;

    /**
     * 社内書類更新
     *
     * @param array $requestContent
     * @return boolean|null
     */
    public function internalUpdate(array $requestContent): ?bool;

    /**
     * 登録書類登録
     *
     * @param array $requestContent
     * @return boolean|null
     */
    public function archiveInsert(array $requestContent): ?bool;

    /**
     * 登録書類更新
     *
     * @param array $requestContent
     * @return boolean|null
     */
    public function archiveUpdate(array $requestContent): ?bool;

    /**
     * 契約書類ログ取得
     *
     * @param array $requestContent
     * @return DocumentUpdateEntity
     */
    public function getBeforOrAfterUpdateContract(array $requestContent): DocumentUpdateEntity;

    /**
     * 取引書類ログ取得
     *
     * @param array $requestContent
     * @return DocumentUpdateEntity
     */
    public function getBeforOrAfterUpdateDeal(array $requestContent): DocumentUpdateEntity;

    /**
     * 社内書類のログ取得
     *
     * @param array $requestContent
     * @return DocumentUpdateEntity
     */
    public function getBeforOrAfterUpdateInternal(array $requestContent): DocumentUpdateEntity;

    /**
     * 登録書類ログ取得
     *
     * @param array $requestContent
     * @return DocumentUpdateEntity
     */
    public function getBeforOrAfterUpdateArchive(array $requestContent): DocumentUpdateEntity;

    /**
     * 契約書類ログ登録
     *
     * @param array $requestContent
     * @param object $beforeList
     * @param object $afterList
     * @return boolean|null
     */
    public function getUpdateLogContract(array $requestContent, object $beforeList, object $afterList): ?bool;

    /**
     * 取引書類ログ登録
     *
     * @param array $requestContent
     * @param object $beforeList
     * @param object $afterList
     * @return boolean|null
     */
    public function getUpdateLogDeal(array $requestContent, object $beforeList, object $afterList): ?bool;

    /**
     * 社内書類ログ登録
     *
     * @param array $requestContent
     * @param object $beforeList
     * @param object $afterList
     * @return boolean|null
     */
    public function getUpdateLogInternal(array $requestContent, object $beforeList, object $afterList): ?bool;

    /**
     * 登録書類ログ登録
     *
     * @param array $requestContent
     * @param object $beforeList
     * @param object $afterList
     * @return boolean|null
     */
    public function getUpdateLogArchive(array $requestContent, object $beforeList, object $afterList): ?bool;
}

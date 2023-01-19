<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Interface\Document;

use App\Domain\Entities\Document\DocumentUpdate as DocumentUpdateEntity;

interface DocumentSaveRepositoryInterface
{
    // 契約書類登録
    public function contractInsert(array $requestContent): ?bool;

    // 契約書類更新
    //: ?bool
    public function contractUpdate(array $requestContent);


    // 取引書類登録
    public function dealInsert(array $requestContent): ?bool;

    // 取引書類更新
    public function dealUpdate(array $requestContent): ?bool;


    // 社内書類登録
    public function internalInsert(array $requestContent): ?bool;

    // 社内書類更新
    public function internalUpdate(array $requestContent): ?bool;


    // 登録書類登録
    public function archiveInsert(array $requestContent): ?bool;

    // 登録書類更新
    public function archiveUpdate(array $requestContent): ?bool;


    // 契約書類ログ取得
    public function getBeforOrAfterUpdateContract(array $requestContent);

    // 取引書類ログ取得
    public function getBeforOrAfterUpdateDeal(array $requestContent);

    // 社内書類のログ取得
    public function getBeforOrAfterUpdateInternal(array $requestContent);

    // 登録書類のログ取得
    public function getBeforOrAfterUpdateArchive(array $requestContent);


    // 契約書類ログ登録
    public function getUpdateLogContract(array $requestContent, $beforeList, $afterList): ?bool;

    // 登録書類ログ登録
    public function getUpdateLogDeal(array $requestContent, $beforeList, $afterList): ?bool;

    //  社内書類ログ登録
    public function getUpdateLogInternal(array $requestContent, $beforeList, $afterList): ?bool;

    // 登録書類ログ登録
    public function getUpdateLogArchive(array $requestContent, $beforeList, $afterList): ?bool;
}

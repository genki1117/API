<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Interface\Document;

interface DocumentSaveRepositoryInterface
{
    // 契約書類登録
    public function contractInsert(array $requestContent);

    // 契約書類更新
    public function contractUpdate(array $requestContent);


    // 取引書類登録
    public function dealInsert(array $requestContent);

    // 取引書類更新
    public function dealUpdate(array $requestContent);


    // 社内書類登録
    public function internalInsert(array $requestContent);

    // 社内書類更新
    public function internalUpdate(array $requestContent);


    // 登録書類登録
    public function archiveInsert(array $requestContent);

    // 登録書類更新
    public function archiveUpdate(array $requestContent);


    // 契約書類ログ取得
    public function getBeforOrAfterUpdateContract(array $requestContent);

    // 取引書類ログ取得
    public function getBeforOrAfterUpdateDeal(array $requestContent);

    // 社内書類のログ取得
    public function getBeforOrAfterUpdateInternal(array $requestContent);

    // 登録書類のログ取得
    public function getBeforOrAfterUpdateArchive(array $requestContent);

    // ログ登録
    public function getUpdateLog(array $requestContent, $beforeList, $afterList);
}

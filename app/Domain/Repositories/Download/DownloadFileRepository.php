<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Download;

use App\Domain\Entities\Download\DownloadFile as DownloadFileEntity;
use Exception;
use App\Accessers\DB\DownloadFile;
use App\Accessers\DB\TempToken;
use App\Domain\Repositories\Interface\Download\DownloadFileServiceInterface;


class DownloadFileRepository implements DownloadFileServiceInterface
{
    /** @var TempToken */
    private TempToken $tempToken;

    /** @var DownloadFileEntity */
    private DownloadFile $dlFile;

    /**
     * @param TempToken $tempToken
     */
    public function __construct(
        TempToken $tempToken,
        DownloadFile $dlFile
    )
    {
        $this->tempToken = $tempToken;
        $this->dlFile    = $dlFile;
    }

    /**
     * 書類詳細を取得
     * @param int $categoryId
     * @param int $documentId
     * @param int $companyId
     * @param int $userId
     * @return Document
     */
    public function getToken(string $token): ?DownloadFileEntity
    {
        try {
            $getTokenDataResult = $this->tempToken->getTokenData(token: $token);
            if (empty($getTokenDataResult)) {
                throw new Exception('トークン情報が取得出来ません。');
            }

            return new DownloadFileEntity($getTokenDataResult);

        } catch (Exception $e) {
            throw $e;
            return false;
        }
    }

    public function getDlFilePath(int $mUserId, int $mUserCompanyId, int $getTokenDlFileId): ?DownloadFileEntity
    {
        try {
            $getDlFileResult = $this->dlFile->getPath(mUserId: $mUserId, mUserCompanyId: $mUserCompanyId, getTokenDlFileId: $getTokenDlFileId);
            // return var_export($getDlFileResult);
            if (empty($getDlFileResult)) {
                throw new Exception('ファイル情報が取得できませんでした。');
            }

            return new DownloadFileEntity($getDlFileResult);
        } catch (Exception $e) {
            throw $e;
            
        }
        
    }
}

<?php
declare(strict_types=1);
namespace App\Domain\Repositories\Interface\Download;

use App\Domain\Entities\Download\DownloadFile as DownloadFileEntity;

interface DownloadFileServiceInterface
{
    public function getToken(string $token): ?DownloadFileEntity;

    public function getDlFilePath(int $mUserId, int $mUserCompanyId, int $getTokenDlFileId): ?DownloadFileEntity;
    
}

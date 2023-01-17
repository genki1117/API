<?php
declare(strict_types=1);
namespace App\Accessers\DB;

use Carbon\CarbonImmutable;
use App\Accessers\DB\FluentDatabase;

class DownloadFile extends FluentDatabase
{
    protected string $table = "t_download_file";

    public function getPath (int $mUserId, int $mUserCompanyId, int $getTokenDlFileId): \stdClass
    {
        return $this->builder()
            ->select([
                "dl_file_path",
                "dl_file_name"
            ])
            ->where("dl_file_id", $getTokenDlFileId)
            ->where("user_id", $mUserId)
            ->where("company_id", $mUserCompanyId)
            ->whereNull("delete_datetime")
            ->first();
    }

}

<?php
declare(strict_types=1);
namespace App\Accessers\DB;

use Carbon\CarbonImmutable;
use App\Accessers\DB\FluentDatabase;

class TempToken extends FluentDatabase
{
    protected string $table = "temp_token";

    /**
     * @param string $mailAddress
     * @return \stdClass|null
     */
    public function getToken(string $token, string $expiryDate = null): ?\stdClass
    {
        return $this->builder()
            ->select([
                "token",
                "type",
                "data",
                "expiry_date",
            ])
            ->where("token", $token)
            ->when($expiryDate, function ($query) use ($expiryDate) {
                return $query->whereDate('expiry_date', '>=', $expiryDate);
            })
            ->first();
    }

    /**
     * ------------------------------------
     * トークンの登録
     * ------------------------------------
     *
     * @param string $token
     * @param array $dataContent
     * @return bool
     */
    public function insertToken(string $token, array $dataContent): bool
    {
        $carbon = new CarbonImmutable;
        $data = [
            "token" => $token,
            "type" => "承認依頼",
            "data" => json_encode($dataContent, JSON_UNESCAPED_UNICODE),
            "expiry_date" => $carbon->addDays(32)
        ]; 
        return $this->builder()->insert($data);
            
    }
}

<?php
declare(strict_types=1);
namespace App\Accessers\DB;

use Illuminate\Support\Collection;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use App\Accessers\DB\FluentDatabase;
use stdClass;

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
     * 期限切れトークン取得
     *
     * @return Collection
     */
    public function getExpiryToken(): array
    {
        return $this->builder($this->table)
                    ->select(DB::raw(
                            'token AS t_token,
                            `data`->"$.document_id" AS document_id,
                            `data`->"$.category_id" AS category_id,
                            `data`->"$.company_id" AS company_id'
                        ))
                    ->whereNull('delete_datetime')
                    ->where('expiry_date', '<', CarbonImmutable::now())
                    ->where('type', '=', '承諾依頼')
                    ->get()
                    ->all();
    }

    /**
     * トークン削除更新
     *
     * @param object $data
     * @return integer
     */
    public function deleteUpdate(object $data): int
    {
        return $this->builder($this->table)
                    ->where('token', '=', $data->t_token)
                    ->update([
                        'delete_user' => 0,
                        'delete_datetime' => CarbonImmutable::now()
                    ]);
    }
}

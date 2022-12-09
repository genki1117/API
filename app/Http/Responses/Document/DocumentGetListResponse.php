<?php
declare(strict_types=1);
namespace App\Http\Responses\Document;

use Illuminate\Http\JsonResponse;

class DocumentGetListResponse
{
    /**
     * エラー時、JSON形式を作成
     */
    public function faildDocumentGetList()
    {
        return new JsonResponse([
            "status" => "200",
            "message" => "入力した検索条件に該当する情報は存在しません。"
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * 取得した結果をJSON形式に作成
     * @param $data 各種類の検索結果一覧
     * @param int $total 各種類の検索結果のレコード数
     * @param int $dispPage 表示ページ数
     * @param int $dispCount 表示件数
     */
    public function successDocumentGetList(array $data, int $total, int $dispPage, int $dispCount)
    {
        $responseData = [
            "data" => (object)[
                "contents" => $data
            ],
            "total" => $total,
            "per_page" => $dispPage,
            "current_page" => $dispCount,
        ];
        return new JsonResponse($responseData, 200, [], JSON_UNESCAPED_UNICODE);
    }
}

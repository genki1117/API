<?php
declare(strict_types=1);
namespace App\Http\Responses\Document;

use App\Domain\Constant;
use App\Domain\Entities\Document\Document;
use App\Domain\Entities\Document\DocumentDetail;
use App\Domain\Entities\Document\DocumentInfo;
use App\Domain\Entities\Organization\User\SelectSignUser;
use App\Domain\Entities\Organization\User\SignedUser;
use Illuminate\Http\JsonResponse;

class DocumentGetDetailResponse
{
    /**
     * 書類詳細が存在しない場合のレスポンス
     * @return JsonResponse
     */
    public function notFound(): JsonResponse
    {
        return $this->empty();
    }

    public function emitContract(
        Document $document,
        array $selectSignUser,
        array $selectSignGuestUser,
        array $selectViewUser,
        array $operationData,
        array $accessData
    ): JsonResponse {
        $response = [
            'data' => [
                'document_id' => $document->getDocumentId(),
                'category_id' => $document->getCategoryId(),
                'status_id' => $document->getStatusId(),
                'doc_type_id' => $document->getDocTypeId(),
                'title' => $document->getTitle(),
                'amount' => $document->getAmount(),
                'currency_id' => $document->getCurrencyId(),
                'cont_start_date' => $document->getContStartDate(),
                'cont_end_date' => $document->getContEndDate(),
                'conc_date' => $document->getConcDate(),
                'effective_date' => $document->getEffectiveDate(),
                'doc_no' => $document->getDocNo(),
                'ref_doc_no' => $document->getRefDocNo(),
                'counter_party_id' => $document->getCounterPartyId(),
                'counter_party_name' => $document->getCounterPartyName(),
                'remarks' => $document->getRemarks(),
                'doc_info' => !empty($document->getDocInfo()) ? array_map(callback: function (DocumentInfo $documentInfo) {
                    return [
                        'title' => $documentInfo->getTitle(),
                        'content' => $documentInfo->getContent()
                    ];
                }, array: $document->getDocInfo()) : [],
                'sign_level' => $document->getSignLevel(),
                'app_user' => !empty($document->getAppUser()) ? array_map(callback: function (SignedUser $user) {
                    return [
                        'family_name' => $user->getFamilyName(),
                        'first_name' => $user->getFirstName()
                    ];
                }, array: $document->getAppUser()): [],
                'update_datetime' => $document->getUpdateDatetime()
            ],
        ];
    }

    public function emitDeal(): JsonResponse
    {

    }

    public function emitInternal(): JsonResponse
    {

    }

    public function emitArchive(): JsonResponse
    {

    }

    private function getSelectSignUserResponseBody(SelectSignUser $selectSignUser): array
    {
        return [
            'group_array' =>
        ];
    }

    /**
     * ---------------------------------------------
     * 書類データが正常状態
     * ---------------------------------------------
     * @param int $categoryId
     * @param ?DocumentDetail|null $docDetailList
     * @return JsonResponse
     */
    public function detail(int $categoryId, ?DocumentDetail $docDetailList = null): JsonResponse
    {
        $document = $docDetailList->getDocumentList();
        $docPermission = $docDetailList->getDocumentPermissionList();
        $workFlow = $docDetailList->getDocumentWorkFlow();
        $logAccess = $docDetailList->getLogDocAccess();
        $logOperation = $docDetailList->getLogDocOperation();
        if ($categoryId === Constant::DOCUMENT_TYPE_CONTRACT) {
            return $this->detailContract($document, $docPermission, $workFlow, $logAccess, $logOperation);
        }
        if ($categoryId === Constant::DOCUMENT_TYPE_DEAL) {
            return $this->detailDeal($document, $docPermission, $workFlow, $logAccess, $logOperation);
        }
        if ($categoryId === Constant::DOCUMENT_TYPE_INTERNAL) {
            return $this->detailInternal($document, $docPermission, $workFlow, $logAccess, $logOperation);
        }
        if ($categoryId === Constant::DOCUMENT_TYPE_ARCHIVE) {
            return $this->detailArchive($document, $docPermission, $workFlow, $logAccess, $logOperation);
        }

        return $this->empty();
    }

    /**
     * 空の返却
     * @return JsonResponse
     */
    private function empty(): JsonResponse
    {
        return (new JsonResponse(data: [], status:404));
    }

    /**
     * @param \stdClass|null $document
     * @param \stdClass|null $docPermission
     * @param \stdClass|null $workFlow
     * @param \stdClass|null $logAccess
     * @param \stdClass|null $logOperation
     * @return JsonResponse
     */
    private function detailContract(?\stdClass $document, ?\stdClass $docPermission, ?\stdClass $workFlow, ?\stdClass $logAccess, ?\stdClass $logOperation): JsonResponse
    {
        return new JsonResponse([
            "data" => [
                "document_id" => $document->document_id ?? null,
                "category_id" => $document->category_id ?? null,
                "status_id" => $document->status_id ?? null,
                "doc_type_id" => $document->doc_type_id ?? null,
                "title" => $document->title ?? null,
                "amount" => $document->amount ?? null,
                "currency_id" => $document->currency_id ?? null,
                "cont_start_date" => $document->cont_start_date ?? null,
                "cont_end_date" => $document->cont_end_date ?? null,
                "conc_date" => $document->conc_date ?? null,
                "effective_date" => $document->effective_date ?? null,
                "doc_no" =>  $document->doc_no ?? null,
                "ref_doc_no" =>  $document->ref_doc_no ?? null,
                "counter_party_id" =>  $document->counter_party_id ?? null,
                "counter_party_name" =>  $document->counter_party_name ?? null,
                "remarks" =>  $document->remarks ?? null,
                "doc_info" =>  $document->doc_info ?? null,
                "sign_level" =>  $document->sign_level ?? null,
                "product_name" =>  $document->product_name ?? null,
                "file_path" =>  $document->file_path ?? null,
                "pdf" =>  $document->pdf ?? null,
                "total_pages" =>  $document->total_pages ?? null,
                "app_user" => [
                    "family_name" =>  $docPermission->family_name ?? null,
                    "first_name" =>  $docPermission->first_name ?? null,
                ],
                "update_datetime" => $document->update_datetime ?? null,
            ],
            "select_sign_user" => [
                "group_array" => $workFlow->group_array ?? null,
                "user_id" => $workFlow->user_id ?? null,
                "family_name" => $workFlow->family_name ?? null,
                "first_name" => $workFlow->first_name ?? null,
                "email" => $workFlow->email ?? null,
                "wf_sort" => $workFlow->wf_sort ?? null,
            ],
            "select_sign_guest_user" => [
                "counter_party_name" => $workFlow->counter_party_name ?? null,
                "group_array" => $workFlow->group_array ?? null,
                "user_id" => $workFlow->user_id ?? null,
                "family_name" => $workFlow->family_name ?? null,
                "first_name" => $workFlow->first_name ?? null,
                "email" => $workFlow->email ?? null,
                "wf_sort" => $workFlow->wf_sort ?? null,
            ],
            "select_view_user" => [
                "group_array" => $workFlow->group_array ?? null,
                "user_id" => $workFlow->user_id ?? null,
                "family_name" => $workFlow->family_name ?? null,
                "first_name" => $workFlow->first_name ?? null,
                "email" => $workFlow->email ?? null,
            ],
            "operation_data" => [
                "create_datetime" => $logOperation->create_datetime ?? null,
                "family_name" => $logOperation->family_name ?? null,
                "first_name" => $logOperation->first_name ?? null,
                "content" => $logOperation->content ?? null,
            ],
            "access_data" => [
                "create_datetime" => $logAccess->create_datetime ?? null,
                "family_name" => $logAccess->family_name ?? null,
                "first_name" => $logAccess->first_name ?? null,
            ],
        ], 200);
    }

    /**
     * @param \stdClass|null $document
     * @param \stdClass|null $docPermission
     * @param \stdClass|null $workFlow
     * @param \stdClass|null $logAccess
     * @param \stdClass|null $logOperation
     * @return JsonResponse
     */
    private function detailDeal(?\stdClass $document, ?\stdClass $docPermission, ?\stdClass $workFlow, ?\stdClass $logAccess, ?\stdClass $logOperation): JsonResponse
    {
        return new JsonResponse([
            "data" => [
                "document_id" => $document->document_id ?? null,
                "category_id" => $document->category_id ?? null,
                "status_id" => $document->status_id ?? null,
                "doc_type_id" => $document->doc_type_id ?? null,
                "title" => $document->title ?? null,
                "amount" => $document->amount ?? null,
                "currency_id" => $document->currency_id ?? null,
                "download_date" => $document->download_date ?? null,
                "issue_date" => $document->issue_date ?? null,
                "expiry_date" => $document->expiry_date ?? null,
                "payment_date" => $document->payment_date ?? null,
                "doc_no" => $document->doc_no ?? null,
                "ref_doc_no" => $document->ref_doc_no ?? null,
                "counter_party_id" => $document->counter_party_id ?? null,
                "counter_party_name" => $document->counter_party_name ?? null,
                "remarks" => $document->remarks ?? null,
                "transaction_date" => $document->transaction_date ?? null,
                "doc_info" => $document->doc_info ?? null,
                "sign_level" => $document->sign_level ?? null,
                "file_path" => $document->file_path ?? null,
                "pdf" => $document->pdf ?? null,
                "total_pages" => $document->total_pages ?? null,
                "app_user" => [
                    "family_name" => $docPermission->family_name ?? null,
                    "first_name" => $docPermission->first_name ?? null,
                ],
                "update_datetime" => $document->update_datetime ?? null,
            ],
            "select_sign_user" => [
                "group_array" => $workFlow->group_array ?? null,
                "user_id" => $workFlow->user_id ?? null,
                "family_name" => $workFlow->family_name ?? null,
                "first_name" => $workFlow->first_name ?? null,
                "email" => $workFlow->email ?? null,
                "wf_sort" => $workFlow->wf_sort ?? null,
            ],
            "select_sign_guest_user" => [
                "counter_party_name" => $workFlow->counter_party_name ?? null,
                "group_array" => $workFlow->group_array ?? null,
                "user_id" => $workFlow->user_id ?? null,
                "family_name" => $workFlow->family_name ?? null,
                "first_name" => $workFlow->first_name ?? null,
                "email" => $workFlow->email ?? null,
                "wf_sort" => $workFlow->wf_sort ?? null,
            ],
            "select_view_user" => [
                "group_array" => $workFlow->group_array ?? null,
                "user_id" => $workFlow->user_id ?? null,
                "family_name" => $workFlow->family_name ?? null,
                "first_name" => $workFlow->first_name ?? null,
                "email" => $workFlow->email ?? null,
            ],
            "operation_data" => [
                "create_datetime" => $logOperation->create_datetime ?? null,
                "family_name" => $logOperation->family_name ?? null,
                "first_name" => $logOperation->first_name ?? null,
                "content" => $logOperation->content ?? null,
            ],
            "access_data" => [
                "create_datetime" => $logAccess->create_datetime ?? null,
                "family_name" => $logAccess->family_name ?? null,
                "first_name" => $logAccess->first_name ?? null,
            ],
        ], 200);
    }

    /**
     * @param \stdClass|null $document
     * @param \stdClass|null $docPermission
     * @param \stdClass|null $workFlow
     * @param \stdClass|null $logAccess
     * @param \stdClass|null $logOperation
     * @return JsonResponse
     */
    private function detailInternal(?\stdClass $document, ?\stdClass $docPermission, ?\stdClass $workFlow, ?\stdClass $logAccess, ?\stdClass $logOperation): JsonResponse
    {
        return new JsonResponse([
            "data" => [
                "document_id" => $document->document_id ?? null,
                "category_id" => $document->category_id ?? null,
                "status_id" => $document->status_id ?? null,
                "doc_type_id" => $document->doc_type_id ?? null,
                "title" => $document->title ?? null,
                "amount" => $document->amount ?? null,
                "currency_id" => $document->currency_id ?? null,
                "doc_create_date" => $document->doc_create_date ?? null,
                "sign_finish_date" => $document->sign_finish_date ?? null,
                "doc_no" => $document->doc_no ?? null,
                "ref_doc_no" => $document->ref_doc_no ?? null,
                "content" => $document->content ?? null,
                "counter_party_id" => $document->counter_party_id ?? null,
                "counter_party_name" => $document->counter_party_name ?? null,
                "remarks" => $document->remarks ?? null,
                "doc_info" => $document->doc_info ?? null,
                "sign_level" => $document->sign_level ?? null,
                "file_path" => $document->file_path ?? null,
                "pdf" => $document->pdf ?? null,
                "total_pages" => $document->total_pages ?? null,
                "app_user" => [
                    "family_name" => $docPermission->family_name ?? null,
                    "first_name" => $docPermission->first_name ?? null,
                ],
                "update_datetime" => $document->update_datetime ?? null,
            ],
            "select_sign_user" => [
                "group_array" => $workFlow->group_array ?? null,
                "user_id" => $workFlow->user_id ?? null,
                "family_name" => $workFlow->family_name ?? null,
                "first_name" => $workFlow->first_name ?? null,
                "email" => $workFlow->email ?? null,
                "wf_sort" => $workFlow->wf_sort ?? null,
            ],
            "select_sign_guest_user" => [
                "counter_party_name" => $workFlow->counter_party_name ?? null,
                "group_array" => $workFlow->group_array ?? null,
                "user_id" => $workFlow->user_id ?? null,
                "family_name" => $workFlow->family_name ?? null,
                "first_name" => $workFlow->first_name ?? null,
                "email" => $workFlow->email ?? null,
                "wf_sort" => $workFlow->wf_sort ?? null,
            ],
            "select_view_user" => [
                "group_array" => $workFlow->group_array ?? null,
                "user_id" => $workFlow->user_id ?? null,
                "family_name" => $workFlow->family_name ?? null,
                "first_name" => $workFlow->first_name ?? null,
                "email" => $workFlow->email ?? null,
            ],
            "operation_data" => [
                "create_datetime" => $logOperation->create_datetime ?? null,
                "family_name" => $logOperation->family_name ?? null,
                "first_name" => $logOperation->first_name ?? null,
                "content" => $logOperation->content ?? null,
            ],
            "access_data" => [
                "create_datetime" => $logAccess->create_datetime ?? null,
                "family_name" => $logAccess->family_name ?? null,
                "first_name" => $logAccess->first_name ?? null,
            ],
        ], 200);
    }

    /**
     * @param \stdClass|null $document
     * @param \stdClass|null $docPermission
     * @param \stdClass|null $workFlow
     * @param \stdClass|null $logAccess
     * @param \stdClass|null $logOperation
     * @return JsonResponse
     */
    private function detailArchive(?\stdClass $document, ?\stdClass $docPermission, ?\stdClass $workFlow, ?\stdClass $logAccess, ?\stdClass $logOperation): JsonResponse
    {
        return new JsonResponse([
            "data" => [
                "document_id" => $document->docArchive->document_id ?? null,
                "category_id" => $document->docArchive->category_id ?? null,
                "status_id" => $document->docArchive->status_id ?? null,
                "doc_type_id" => $document->docArchive->doc_type_id ?? null,
                "title" => $document->docArchive->title ?? null,
                "amount" => $document->docArchive->amount ?? null,
                "currency_id" => $document->docArchive->currency_id ?? null,
                "issue_date" => $document->docArchive->issue_date ?? null,
                "doc_no" => $document->docArchive->doc_no ?? null,
                "ref_doc_no" => $document->docArchive->ref_doc_no ?? null,
                "counter_party_id" => $document->docArchive->counter_party_id ?? null,
                "counter_party_name" => $document->docArchive->counter_party_name ?? null,
                "remarks" => $document->docArchive->remarks ?? null,
                "transaction_date" => $document->docArchive->transaction_date ?? null,
                "doc_info" => $document->docArchive->doc_info ?? null,
                "sign_level" => $document->docArchive->sign_level ?? null,
                "product_name" => $document->docArchive->product_name ?? null,
                "scan_doc_flg" => $document->docArchive->scan_doc_flg ?? null,
                "file_path" => $document->docArchive->file_path ?? null,
                "pdf" => $document->docArchive->pdf ?? null,
                "total_pages" => $document->docArchive->total_pages ?? null,
                "timestamp_user" => [
                    "family_name" => $document->docPermissionArchive->family_name ?? null,
                    "first_name" => $document->docPermissionArchive->first_name ?? null,
                ],
                "update_datetime" => $document->docArchive->update_datetime ?? null,
            ],
            "select_sign_user" => [
                "group_array" => $workFlow->group_array ?? null,
                "user_id" => $workFlow->user_id ?? null,
                "family_name" => $workFlow->family_name ?? null,
                "first_name" => $workFlow->first_name ?? null,
                "email" => $workFlow->email ?? null,
                "wf_sort" => $workFlow->wf_sort ?? null,
            ],
            "select_sign_guest_user" => [
                "counter_party_name" => $workFlow->counter_party_name ?? null,
                "group_array" => $workFlow->group_array ?? null,
                "user_id" => $workFlow->user_id ?? null,
                "family_name" => $workFlow->family_name ?? null,
                "first_name" => $workFlow->first_name ?? null,
                "email" => $workFlow->email ?? null,
                "wf_sort" => $workFlow->wf_sort ?? null,
            ],
            "select_view_user" => [
                "group_array" => $workFlow->group_array ?? null,
                "user_id" => $workFlow->user_id ?? null,
                "family_name" => $workFlow->family_name ?? null,
                "first_name" => $workFlow->first_name ?? null,
                "email" => $workFlow->email ?? null,
            ],
            "operation_data" => [
                "create_datetime" => $logOperation->create_datetime ?? null,
                "family_name" => $logOperation->family_name ?? null,
                "first_name" => $logOperation->first_name ?? null,
                "content" => $logOperation->content ?? null,
            ],
            "access_data" => [
                "create_datetime" => $logAccess->create_datetime ?? null,
                "family_name" => $logAccess->family_name ?? null,
                "first_name" => $logAccess->first_name ?? null,
            ],
        ], 200);
    }
}

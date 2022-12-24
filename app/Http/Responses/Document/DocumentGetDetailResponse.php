<?php
declare(strict_types=1);
namespace App\Http\Responses\Document;

use App\Domain\Constant;
use App\Domain\Entities\Document\Document;
use App\Domain\Entities\Document\DocumentInfo;
use App\Domain\Entities\Document\Sign;
use App\Domain\Entities\Organization\User\SignedUser;
use App\Http\Responses\SystemResponseFunc;
use App\Libraries\TimeFunc;
use Illuminate\Http\JsonResponse;

class DocumentGetDetailResponse
{
    /**
     * システム共通レスポンスボディ生成関数郡の読み込み
     */
    use SystemResponseFunc;

    /**
     * 時間操作をする関数群の読み込み
     */
    use TimeFunc;

    /**
     * 書類詳細が存在しない場合のレスポンス
     * @return JsonResponse
     */
    public function notFound(): JsonResponse
    {
        return (new JsonResponse(data: [], status:404));
    }

    /**
     * 契約書類カテゴリの書類詳細返却
     * @param Document $document
     * @param array $selectSignUser
     * @param array $selectSignGuestUser
     * @param array $selectViewUser
     * @param array $operationData
     * @param array $accessData
     * @return JsonResponse
     */
    public function emitContract(
        Document $document,
        array $selectSignUser,
        array $selectSignGuestUser,
        array $selectViewUser,
        array $operationData,
        array $accessData
    ): JsonResponse {
        return new  JsonResponse(data: [
            'data' => [
                'document_id' => $document->getDocumentId(),
                'category_id' => $document->getCategoryId(),
                'status_id' => $document->getStatusId(),
                'doc_type_id' => $document->getDocTypeId(),
                'title' => $document->getTitle(),
                'amount' => $document->getAmount(),
                'currency_id' => $document->getCurrencyId(),
                'cont_start_date' => $this->convertCarbonToString($document->getContStartDate()),
                'cont_end_date' => $this->convertCarbonToString($document->getContStartDate()),
                'conc_date' => $this->convertCarbonToString($document->getConcDate()),
                'effective_date' => $this->convertCarbonToString($document->getEffectiveDate()),
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
                'product_name' => $document->getProductName(),
                'file_path' => $document->getFilePath(),
                'pdf' => $document->getPdf(),
                'sign_position' => !empty($document->getSignPosition()) ? array_map(callback: function (Sign $signatureStamp) {
                    return [
                        'user_id' => $signatureStamp->getUserId(),
                        'x' => $signatureStamp->getXAxis(),
                        'y' => $signatureStamp->getYAxis(),
                    ];
                }, array: $document->getSignPosition()) : [],
                'total_pages' => $document->getTotalPages(),
                'app_user' => !empty($document->getAppUser()) ? array_map(callback: function (SignedUser $user) {
                    return [
                        'family_name' => $user->getFamilyName(),
                        'first_name' => $user->getFirstName()
                    ];
                }, array: $document->getAppUser()): [],
                'update_datetime' => $document->getUpdateDatetime()
            ],
            'select_sign_user' => $this->getSelectSignUserResponseBody($selectSignUser),
            'select_sign_guest_user' => $this->getSelectSignGuestUserResponseBody($selectSignGuestUser),
            'select_view_user' => $this->getSelectViewUserResponseBody($selectViewUser),
            'operation_data' => $this->getOperationDataResponseBody($operationData),
            'access_data' => $this->getAccessDataResponseBody($accessData),
        ]);
    }

    /**
     * 取引書類カテゴリの書類詳細返却
     * @param Document $document
     * @param array $selectSignUser
     * @param array $selectSignGuestUser
     * @param array $selectViewUser
     * @param array $operationData
     * @param array $accessData
     * @return JsonResponse
     */
    public function emitDeal(
        Document $document,
        array $selectSignUser,
        array $selectSignGuestUser,
        array $selectViewUser,
        array $operationData,
        array $accessData
    ): JsonResponse {
        return new  JsonResponse(data: [
            'data' => [
                'document_id' => $document->getDocumentId(),
                'category_id' => $document->getCategoryId(),
                'status_id' => $document->getStatusId(),
                'doc_type_id' => $document->getDocTypeId(),
                'title' => $document->getTitle(),
                'amount' => $document->getAmount(),
                'currency_id' => $document->getCurrencyId(),
                'download_date' => $this->convertCarbonToString($document->getDownloadDate()),
                'issue_date' => $this->convertCarbonToString($document->getIssueDate()),
                'expiry_date' => $this->convertCarbonToString($document->getExpiryDate()),
                'payment_date' => $this->convertCarbonToString($document->getPaymentDate()),
                'doc_no' => $document->getDocNo(),
                'ref_doc_no' => $document->getRefDocNo(),
                'counter_party_id' => $document->getCounterPartyId(),
                'counter_party_name' => $document->getCounterPartyName(),
                'remarks' => $document->getRemarks(),
                'transaction_date' => $this->convertCarbonToString($document->getTransactionDate()),
                'doc_info' => !empty($document->getDocInfo()) ? array_map(callback: function (DocumentInfo $documentInfo) {
                    return [
                        'title' => $documentInfo->getTitle(),
                        'content' => $documentInfo->getContent()
                    ];
                }, array: $document->getDocInfo()) : [],
                'sign_level' => $document->getSignLevel(),
                'file_path' => $document->getFilePath(),
                'pdf' => $document->getPdf(),
                'sign_position' => !empty($document->getSignPosition()) ? array_map(callback: function (Sign $signatureStamp) {
                    return [
                        'user_id' => $signatureStamp->getUserId(),
                        'x' => $signatureStamp->getXAxis(),
                        'y' => $signatureStamp->getYAxis(),
                    ];
                }, array: $document->getSignPosition()) : [],
                'total_pages' => $document->getTotalPages(),
                'app_user' => !empty($document->getAppUser()) ? array_map(callback: function (SignedUser $user) {
                    return [
                        'family_name' => $user->getFamilyName(),
                        'first_name' => $user->getFirstName()
                    ];
                }, array: $document->getAppUser()): [],
                'update_datetime' => $document->getUpdateDatetime()
            ],
            'select_sign_user' => $this->getSelectSignUserResponseBody($selectSignUser),
            'select_sign_guest_user' => $this->getSelectSignGuestUserResponseBody($selectSignGuestUser),
            'select_view_user' => $this->getSelectViewUserResponseBody($selectViewUser),
            'operation_data' => $this->getOperationDataResponseBody($operationData),
            'access_data' => $this->getAccessDataResponseBody($accessData),
        ]);
    }

    /**
     * 社内書類カテゴリの書類詳細を返却
     * @param Document $document
     * @param array $selectSignUser
     * @param array $selectSignGuestUser
     * @param array $selectViewUser
     * @param array $operationData
     * @param array $accessData
     * @return JsonResponse
     */
    public function emitInternal(
        Document $document,
        array $selectSignUser,
        array $selectSignGuestUser,
        array $selectViewUser,
        array $operationData,
        array $accessData
    ): JsonResponse {
        return new  JsonResponse(data: [
            'data' => [
                'document_id' => $document->getDocumentId(),
                'category_id' => $document->getCategoryId(),
                'status_id' => $document->getStatusId(),
                'doc_type_id' => $document->getDocTypeId(),
                'title' => $document->getTitle(),
                'amount' => $document->getAmount(),
                'currency_id' => $document->getCurrencyId(),
                'doc_create_date' => $this->convertCarbonToString($document->getDocCreateDate()),
                'sign_finish_date' => $this->convertCarbonToString($document->getSignFinishDate()),
                'doc_no' => $document->getDocNo(),
                'ref_doc_no' => $document->getRefDocNo(),
                'content' => $document->getContent(),
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
                'file_path' => $document->getFilePath(),
                'pdf' => $document->getPdf(),
                'sign_position' => !empty($document->getSignPosition()) ? array_map(callback: function (Sign $signatureStamp) {
                    return [
                        'user_id' => $signatureStamp->getUserId(),
                        'x' => $signatureStamp->getXAxis(),
                        'y' => $signatureStamp->getYAxis(),
                    ];
                }, array: $document->getSignPosition()) : [],
                'total_pages' => $document->getTotalPages(),
                'app_user' => !empty($document->getAppUser()) ? array_map(callback: function (SignedUser $user) {
                    return [
                        'family_name' => $user->getFamilyName(),
                        'first_name' => $user->getFirstName()
                    ];
                }, array: $document->getAppUser()): [],
                'update_datetime' => $document->getUpdateDatetime()
            ],
            'select_sign_user' => $this->getSelectSignUserResponseBody($selectSignUser),
            'select_sign_guest_user' => $this->getSelectSignGuestUserResponseBody($selectSignGuestUser),
            'select_view_user' => $this->getSelectViewUserResponseBody($selectViewUser),
            'operation_data' => $this->getOperationDataResponseBody($operationData),
            'access_data' => $this->getAccessDataResponseBody($accessData),
        ]);
    }

    /**
     * 登録書類カテゴリの書類詳細を返却
     * @param Document $document
     * @param array $selectSignUser
     * @param array $selectSignGuestUser
     * @param array $selectViewUser
     * @param array $operationData
     * @param array $accessData
     * @return JsonResponse
     */
    public function emitArchive(
        Document $document,
        array $selectSignUser,
        array $selectSignGuestUser,
        array $selectViewUser,
        array $operationData,
        array $accessData
    ): JsonResponse {
        return new  JsonResponse(data: [
            'data' => [
                'document_id' => $document->getDocumentId(),
                'category_id' => $document->getCategoryId(),
                'status_id' => $document->getStatusId(),
                'doc_type_id' => $document->getDocTypeId(),
                'title' => $document->getTitle(),
                'amount' => $document->getAmount(),
                'currency_id' => $document->getCurrencyId(),
                'issue_date' => $this->convertCarbonToString($document->getIssueDate()),
                'doc_no' => $document->getDocNo(),
                'ref_doc_no' => $document->getRefDocNo(),
                'counter_party_id' => $document->getCounterPartyId(),
                'counter_party_name' => $document->getCounterPartyName(),
                'remarks' => $document->getRemarks(),
                'transaction_date' => $this->convertCarbonToString($document->getTransactionDate()),
                'doc_info' => !empty($document->getDocInfo()) ? array_map(callback: function (DocumentInfo $documentInfo) {
                    return [
                        'title' => $documentInfo->getTitle(),
                        'content' => $documentInfo->getContent()
                    ];
                }, array: $document->getDocInfo()) : [],
                'sign_level' => $document->getSignLevel(),
                'product_name' => $document->getProductName(),
                'scan_doc_flg' => $document->getScanDocFlg(),
                'file_path' => $document->getFilePath(),
                'pdf' => $document->getPdf(),
                'sign_position' => !empty($document->getSignPosition()) ? array_map(callback: function (Sign $signatureStamp) {
                    return [
                        'user_id' => $signatureStamp->getUserId(),
                        'x' => $signatureStamp->getXAxis(),
                        'y' => $signatureStamp->getYAxis(),
                    ];
                }, array: $document->getSignPosition()) : [],
                'total_pages' => $document->getTotalPages(),
                'timestamp_user' => !empty($document->getTimestampUser()) ? [
                    'family_name' => $document->getTimestampUser()->getFamilyName(),
                    'first_name' => $document->getTimestampUser()->getFamilyName(),
                ] : [],
                'update_datetime' => $document->getUpdateDatetime()
            ],
            'select_sign_user' => $this->getSelectSignUserResponseBody($selectSignUser),
            'select_sign_guest_user' => $this->getSelectSignGuestUserResponseBody($selectSignGuestUser),
            'select_view_user' => $this->getSelectViewUserResponseBody($selectViewUser),
            'operation_data' => $this->getOperationDataResponseBody($operationData),
            'access_data' => $this->getAccessDataResponseBody($accessData),
        ]);
    }
}

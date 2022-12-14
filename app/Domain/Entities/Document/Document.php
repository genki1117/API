<?php
declare(strict_types=1);
namespace App\Domain\Entities\Document;

use App\Domain\Entities\Organization\User\TimestampUser;
use Carbon\Carbon;

/**
 * 書類エンティティ
 */
class Document
{
    /** @var int|null 書類ID */
    private ?int $documentId;
    /** @var int|null 書類カテゴリ */
    private ?int $categoryId;
    /** @var int|null ステータス */
    private ?int $statusId;
    /** @var int|null 書類種別 */
    private ?int $docTypeId;
    /** @var string|null タイトル */
    private ?string $title;
    /** @var int|null 金額 */
    private ?int $amount;
    /** @var int|null 通貨 */
    private ?int $currencyId;
    /** @var Carbon|null 契約開始日 */
    private ?Carbon $contStartDate;
    /** @var Carbon|null 契約終了日 */
    private ?Carbon $contEndDate;
    /** @var Carbon|null 契約締結日 */
    private ?Carbon $concDate;
    /** @var Carbon|null 効力発生日 */
    private ?Carbon $effectiveDate;
    /** @var Carbon|null ダウンロード日 */
    private ?Carbon $downloadDate;
    /** @var Carbon|null 書類作成日 */
    private ?Carbon $docCreateDate;
    /** @var Carbon|null 署名完了日 */
    private ?Carbon $signFinishDate;
    /** @var Carbon|null 発行日 */
    private ?Carbon $issueDate;
    /** @var Carbon|null 有効期限 */
    private ?Carbon $expiryDate;
    /** @var Carbon|null 支払期日 */
    private ?Carbon $paymentDate;
    /** @var int|null 書類No */
    private ?int $docNo;
    /** @var string|null 参考書類No */
    private ?string $refDocNo; //TODO 「1,2,3」の文字列で返却
    /** @var string|null 内容 */
    private ?string $content;
    /** @var int|null 相手先ID */
    private ?int $counterPartyId;
    /** @var string|null 相手先名 */
    private ?string $counterPartyName;
    /** @var string|null 備考 */
    private ?string $remarks;
    /** @var Carbon|null 取引年月日 */
    private ?Carbon $transactionDate;
    /** @var array|null 摘要 */
    private ?array $docInfo;
    /** @var int|null 署名方法 */
    private ?int $signLevel;
    /** @var string|null 商品名 */
    private ?string $productName;
    /** @var int|null 保存要件 */
    private ?int $scanDocFlg;
    /** @var string|null PDF（ファイルパス） */
    private ?string $filePath;
    /** @var string|null PDF（base64エンコードしたファイル） */
    private ?string $pdf;
    /** @var array|null 印影コメント位置 */
    private ?array $signPosition;
    /** @var int|null PDF合計ページ数 */
    private ?int $totalPages;
    /** @var array|null 署名済みユーザ */
    private ?array $appUser;
    /** @var TimestampUser|null タイムスタンプ付与者 */
    private ?TimestampUser $timestampUser;
    /** @var int|null 更新日時 */
    private ?int $updateDatetime;

    /**
     * @param array $properties
     */
    public function __construct(array $properties = [
        'documentId' => null,
        'categoryId' => null,
        'statusId'   => null,
        'docTypeId'  => null,
        'title' => null,
        'amount' => null,
        'currencyId' => null,
        'contStartDate' => null,
        'contEndDate' => null,
        'concDate' => null,
        'effectiveDate' => null,
        'docNo' => null,
        'refDocNo' => null,
        'counterPartyId' => null,
        'counterPartyName' => null,
        'remarks' => null,
        'docInfo' => null,
        'signLevel' => null,
        'productName' => null,
        'filePath' => null,
        'pdf' => null,
        'sign_position' => null,
        'totalPages' => null,
        'appUser' => null,
        'timestampUser' => null,
        'updateDatetime' =>  null
    ]) {
        foreach ($properties as $property => $data) {
            $this->{$property} = $data;
        }
    }

    /**
     * 書類IDを返却
     * @return int|null
     */
    public function getDocumentId(): ?int
    {
        return $this->documentId;
    }

    /**
     * 書類カテゴリを返却
     * @return int|null
     */
    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    /**
     * ステータスIDを返却
     * @return int|null
     */
    public function getStatusId(): ?int
    {
        return $this->statusId;
    }

    /**
     * 書類種別を返却
     * @return int|null
     */
    public function getDocTypeId(): ?int
    {
        return $this->docTypeId;
    }

    /**
     * タイトルを返却
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * 金額を返却
     * @return int|null
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * 通貨
     * @return int|null
     */
    public function getCurrencyId(): ?int
    {
        return $this->currencyId;
    }

    /**
     * 契約開始日を返却
     * @return Carbon|null
     */
    public function getContStartDate(): ?Carbon
    {
        return $this->contStartDate;
    }

    /**
     * 契約終了日を返却
     * @return Carbon|null
     */
    public function getContEndDate(): ?Carbon
    {
        return $this->contEndDate;
    }

    /**
     * 契約締結日を返却
     * @return Carbon|null
     */
    public function getConcDate(): ?Carbon
    {
        return $this->concDate;
    }

    /**
     * 効力発生日を返却
     * @return Carbon|null
     */
    public function getEffectiveDate(): ?Carbon
    {
        return $this->effectiveDate;
    }

    /**
     * ダウンロード日を返却
     * @return Carbon|null
     */
    public function getDownloadDate(): ?Carbon
    {
        return $this->downloadDate;
    }

    /**
     * 書類作成日を返却
     * @return Carbon|null
     */
    public function getDocCreateDate(): ?Carbon
    {
        return $this->docCreateDate;
    }

    /**
     * 署名完了日を返却
     * @return Carbon|null
     */
    public function getSignFinishDate(): ?Carbon
    {
        return $this->signFinishDate;
    }

    /**
     * 発行日を返却
     * @return Carbon|null
     */
    public function getIssueDate(): ?Carbon
    {
        return $this->issueDate;
    }

    /**
     * 有効期限を返却
     * @return Carbon|null
     */
    public function getExpiryDate(): ?Carbon
    {
        return $this->expiryDate;
    }

    /**
     * 支払期日を返却
     * @return Carbon|null
     */
    public function getPaymentDate(): ?Carbon
    {
        return $this->paymentDate;
    }

    /**
     * 書類Noを返却
     * @return int|null
     */
    public function getDocNo(): ?int
    {
        return $this->docNo;
    }

    /**
     * 参考書類Noを返却
     * @return int|null
     */
    public function getRefDocNo(): ?int
    {
        return $this->refDocNo;
    }

    /**
     * 内容を返却
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * 会社IDを返却
     * @return int|null
     */
    public function getCounterPartyId(): ?int
    {
        return $this->counterPartyId;
    }

    /**
     * 会社名を返却
     * @return string|null
     */
    public function getCounterPartyName(): ?string
    {
        return $this->counterPartyName;
    }

    /**
     * 備考を返却
     * @return string|null
     */
    public function getRemarks(): ?string
    {
        return $this->remarks;
    }

    /**
     * 取引年月日を返却
     * @return Carbon|null
     */
    public function getTransactionDate(): ?Carbon
    {
        return $this->transactionDate;
    }

    /**
     * 摘要を返却
     * @return array|null
     */
    public function getDocInfo(): ?array
    {
        return $this->docInfo;
    }

    /**
     * 署名方法を返却
     * @return int|null
     */
    public function getSignLevel(): ?int
    {
        return $this->signLevel;
    }

    /**
     * 商品名を返却
     * @return string|null
     */
    public function getProductName(): ?string
    {
        return $this->productName;
    }

    /**
     * 保存要件を返却
     * @return int|null
     */
    public function getScanDocFlg(): ?int
    {
        return $this->scanDocFlg;
    }

    /**
     * PDF（ファイルパス）を返却
     * @return string|null
     */
    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    /**
     * PDF（ファイル）を返却
     * @return string|null
     */
    public function getPdf(): ?string
    {
        return $this->pdf;
    }

    /**
     * 印影コメント位置を返却
     * @return array|null
     */
    public function getSignPosition(): ?array
    {
        return $this->signPosition;
    }

    /**
     * PDF合計ページ数取得
     * @return int|null
     */
    public function getTotalPages(): ?int
    {
        return $this->totalPages;
    }

    /**
     * 署名済みユーザを返却
     * @return array|null
     */
    public function getAppUser(): ?array
    {
        return $this->appUser;
    }

    /**
     * タイムスタンプ付与者を返却
     * @return TimestampUser|null
     */
    public function getTimestampUser(): ?TimestampUser
    {
        return $this->timestampUser;
    }

    /**
     * 更新日時（UNIXタイムスタンプ）を返却
     * @return string|null
     */
    public function getUpdateDatetime(): ?string
    {
        return $this->updateDatetime;
    }
}

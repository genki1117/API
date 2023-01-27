<?php
declare(strict_types=1);
namespace App\Domain\Consts;

/**
 * 定数の設定
 */
class DocumentConst
{
    // 書類カテゴリマスタ
    /** @var int */
    const DOCUMENT_CONTRACT = 0;

    /** @var string */
    const DOCUMENT_CONTRACT_NAME = '契約書類';

    /** @var int */
    const DOCUMENT_DEAL = 1;

    /** @var string */
    const DOCUMENT_DEAL_NAME = '取引書類';

    /** @var int */
    const DOCUMENT_INTERNAL = 2;

    /** @var string */
    const DOCUMENT_INTERNAL_NAME = '社内書類';

    /** @var int */
    const DOCUMENT_ARCHIVE = 3;

    /** @var string */
    const DOCUMENT_ARCHIVE_NAME = '登録書類';

    /** @var string */
    const CSV_DOWNLOAD_PASS = 'Storage/UploadCsvFile/';

    const DOCUMENT_LIST = [
        self::DOCUMENT_CONTRACT => self::DOCUMENT_CONTRACT_NAME,
        self::DOCUMENT_DEAL => self::DOCUMENT_DEAL_NAME,
        self::DOCUMENT_INTERNAL => self::DOCUMENT_INTERNAL_NAME,
        self::DOCUMENT_ARCHIVE => self::DOCUMENT_ARCHIVE_NAME
    ];

    // 書類ステータスマスタ
    /** @var int */
    const DOCUMENT_STATUS_DRAFT = 0;

    /** @var string */
    const DOCUMENT_STATUS_DRAFT_NAME ='下書き';

    /** @var int */
    const DOCUMENT_STATUS_APPROVALREQUEST = 1;

    /** @var string */
    const DOCUMENT_STATUS_APPROVALREQUEST_NAME = '承認依頼中';

    /** @var int */
    const DOCUMENT_STATUS_SENT = 2;

    /** @var string */
    const DOCUMENT_STATUS_SENT_NAME = '送付済';

    /** @var int */
    const DOCUMENT_STATUS_WAITINGSIGN = 3;

    /** @var string */
    const DOCUMENT_STATUS_WAITINGSIGN_NAME = '署名待ち';

    /** @var int */
    const DOCUMENT_STATUS_CANCEL = 4;

    /** @var string */
    const DOCUMENT_STATUS_CANCEL_NAME = 'キャンセル';

    /** @var int */
    const DOCUMENT_STATUS_SENDBACK = 5;

    /** @var string */
    const DOCUMENT_STATUS_SENDBACK_NAME = '差戻';

    /** @var int */
    const DOCUMENT_STATUS_CONCLUDED = 6;

    /** @var string */
    const DOCUMENT_STATUS_CONCLUDED_NAME = '締結済';

    /** @var int */
    const DOCUMENT_STATUS_CONTRACTEXPIRATION = 7;

    /** @var string */
    const DOCUMENT_STATUS_CONTRACTEXPIRATION_NAME = '契約満了';

    /** @var int */
    const DOCUMENT_STATUS_CONTRACTBREAK = 8;

    /** @var string */
    const DOCUMENT_STATUS_CONTRACTBREAK_NAME = '契約破棄';

    /** @var int */
    const DOCUMENT_STATUS_VIEWEDCOUNTERPARTY = 9;

    /** @var string */
    const DOCUMENT_STATUS_VIEWEDCOUNTERPARTY_NAME = '相手先閲覧済';

    /** @var int */
    const DOCUMENT_STATUS_CIRCULATION = 10;

    /** @var string */
    const DOCUMENT_STATUS_CIRCULATION_NAME = '回覧中';

    /** @var int */
    const DOCUMENT_STATUS_TIMESTAMP = 11;

    /** @var string */
    const DOCUMENT_STATUS_TIMESTAMP_NAME = 'タイムスタンプ済';

    /** @var int */
    const DOCUMENT_STATUS_BULKVARIDATIONNEEDLESS = 12;

    /** @var string */
    const DOCUMENT_STATUS_BULKVARIDATIONNEEDLESS_NAME = '一括検証不要';

    /** @var int */
    const DOCUMENT_STATUS_EXPIRATION = 13;

    /** @var string */
    const DOCUMENT_STATUS_EXPIRATION_NAME = '期限切れ';

    /** @var int */
    const DOCUMENT_STATUS_SIGNED = 14;

    /** @var string */
    const DOCUMENT_STATUS_SIGNED_NAME = '署名済';

    /** @var int */
    const DOCUMENT_STATUS_APPROVED = 15;

    /** @var string */
    const DOCUMENT_STATUS_APPROVED_NAME = '承認済';


    const DOCUMENT_STATUS_LIST = [
        self::DOCUMENT_STATUS_DRAFT => self::DOCUMENT_STATUS_DRAFT_NAME,
        self::DOCUMENT_STATUS_APPROVALREQUEST => self::DOCUMENT_STATUS_APPROVALREQUEST_NAME,
        self::DOCUMENT_STATUS_SENT => self::DOCUMENT_STATUS_SENT_NAME,
        self::DOCUMENT_STATUS_WAITINGSIGN => self::DOCUMENT_STATUS_WAITINGSIGN_NAME,
        self::DOCUMENT_STATUS_CANCEL => self::DOCUMENT_STATUS_CANCEL_NAME,
        self::DOCUMENT_STATUS_SENDBACK => self::DOCUMENT_STATUS_SENDBACK_NAME,
        self::DOCUMENT_STATUS_CONCLUDED => self::DOCUMENT_STATUS_CONCLUDED_NAME,
        self::DOCUMENT_STATUS_CONTRACTEXPIRATION => self::DOCUMENT_STATUS_CONTRACTEXPIRATION_NAME,
        self::DOCUMENT_STATUS_CONTRACTBREAK => self::DOCUMENT_STATUS_CONTRACTBREAK_NAME,
        self::DOCUMENT_STATUS_VIEWEDCOUNTERPARTY => self::DOCUMENT_STATUS_VIEWEDCOUNTERPARTY_NAME,
        self::DOCUMENT_STATUS_CIRCULATION => self::DOCUMENT_STATUS_CIRCULATION_NAME,
        self::DOCUMENT_STATUS_TIMESTAMP => self::DOCUMENT_STATUS_TIMESTAMP_NAME,
        self::DOCUMENT_STATUS_BULKVARIDATIONNEEDLESS => self::DOCUMENT_STATUS_BULKVARIDATIONNEEDLESS_NAME,
        self::DOCUMENT_STATUS_EXPIRATION => self::DOCUMENT_STATUS_EXPIRATION_NAME,
        self::DOCUMENT_STATUS_SIGNED => self::DOCUMENT_STATUS_SIGNED_NAME,
        self::DOCUMENT_STATUS_APPROVED => self::DOCUMENT_STATUS_APPROVED_NAME
    ];

    // 保存要件マスタ
    /** @var int */
    const DOCUMENT_SCAN_ELECTRONICTRADING = 0;

    /** @var string */
    const DOCUMENT_SCAN_ELECTRONICTRADING_NAME = '電子取引';

    /** @var int */
    const DOCUMENT_SCAN_SAVE = 1;

    /** @var string */
    const DOCUMENT_SCAN_SAVE_NAME = 'スキャン保存';

    /** @var int */
    const DOCUMENT_SCAN_OTHER = 2;

    /** @var string */
    const DOCUMENT_SCAN_OTHER_NAME = 'その他';

    const DOCUMENT_SCAN_LIST = [
        self::DOCUMENT_SCAN_ELECTRONICTRADING => self::DOCUMENT_SCAN_ELECTRONICTRADING_NAME,
        self::DOCUMENT_SCAN_SAVE => self::DOCUMENT_SCAN_SAVE_NAME,
        self::DOCUMENT_SCAN_OTHER => self::DOCUMENT_SCAN_OTHER_NAME
    ];


    // 書類種類マスタ
    /** @var int */
    const DOCUMENT_TYPE_CONTRACT = 0;

    /** @var string */
    const DOCUMENT_TYPE_CONTRACT_NAME = '契約書';

    /** @var int */
    const DOCUMENT_TYPE_QUOTATION = 1;

    /** @var string */
    const DOCUMENT_TYPE_QUOTATION_NAME = '見積書';

    /** @var int */
    const DOCUMENT_TYPE_DELIVERYNOTE = 2;

    /** @var string */
    const DOCUMENT_TYPE_DELIVERYNOTE_NAME = '納品書';

    /** @var int */
    const DOCUMENT_TYPE_INVOICES = 3;

    /** @var string */
    const DOCUMENT_TYPE_INVOICES_NAME = '請求書';

    /** @var int */
    const DOCUMENT_TYPE_RECEIPT = 4;

    /** @var string */
    const DOCUMENT_TYPE_RECEIPT_NAME = '領収書';

    /** @var int */
    const DOCUMENT_TYPE_PURCHASEORDERS = 5;

    /** @var string */
    const DOCUMENT_TYPE_PURCHASEORDERS_NAME = '発注書';

    /** @var int */
    const DOCUMENT_TYPE_MINUTES = 6;

    /** @var string */
    const DOCUMENT_TYPE_MINUTES_NAME = '議事録';

    /** @var int */
    const DOCUMENT_TYPE_OTHER = 7;

    /** @var string */
    const DOCUMENT_TYPE_OTHER_NAME = 'その他';

    const DOCUMENT_TYPE_LIST = [
        self::DOCUMENT_TYPE_CONTRACT => self::DOCUMENT_TYPE_CONTRACT_NAME,
        self::DOCUMENT_TYPE_QUOTATION => self::DOCUMENT_TYPE_QUOTATION_NAME,
        self::DOCUMENT_TYPE_DELIVERYNOTE => self::DOCUMENT_TYPE_DELIVERYNOTE_NAME,
        self::DOCUMENT_TYPE_INVOICES => self::DOCUMENT_TYPE_INVOICES_NAME,
        self::DOCUMENT_TYPE_RECEIPT => self::DOCUMENT_TYPE_RECEIPT_NAME,
        self::DOCUMENT_TYPE_PURCHASEORDERS => self::DOCUMENT_TYPE_PURCHASEORDERS_NAME,
        self::DOCUMENT_TYPE_MINUTES => self::DOCUMENT_TYPE_MINUTES_NAME,
        self::DOCUMENT_TYPE_OTHER => self::DOCUMENT_TYPE_OTHER_NAME
    ];
}

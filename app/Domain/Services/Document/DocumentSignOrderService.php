<?php
declare(strict_types=1);
namespace App\Domain\Services\Document;

use Illuminate\Support\Facades\Log;
use App\Domain\Consts\QueueConst as QueueConstant;
use App\Accessers\Queue\QueueUtility;
use Exception;
use App\Domain\Consts\UserTypeConst;
use App\Domain\Consts\DocumentConst;
use App\Domain\Repositories\Interface\Document\DocumentSignOrderRepositoryInterface;

class DocumentSignOrderService
{

    /** @var QueueUtility */
    public QueueUtility $queueUtility;

    /** @var UserTypeConst */
    private UserTypeConst $userTypeConst;

    /** @var DocumentConst */
    private DocumentConst $docConst;

    /** @var  DocumentSignOrderRepositoryInterface*/
    private DocumentSignOrderRepositoryInterface $documentSignOrderRepositoryInterface;

    public function __construct(
        QueueUtility $queueUtility,
        UserTypeConst $userTypeConst,
        DocumentConst $docConst,
        DocumentSignOrderRepositoryInterface $documentSignOrderRepositoryInterface
    )
    {
        $this->queueUtility  = $queueUtility;
        $this->userTypeConst = $userTypeConst;
        $this->docConst      = $docConst;
        $this->documentSignOrderRepositoryInterface = $documentSignOrderRepositoryInterface;
    }
    

    public function signOrder(int $mUserId, int $mUserCompanyId, int $mUserTypeId, string $ipAddress, int $documentId, int $docTypeId, int $categoryId, string $updateDatetime, string $systemUrl)
    {
        try {
            switch($categoryId) {
                // 契約書類
                case $this->docConst::DOCUMENT_CONTRACT:
                    // 次の署名書類と送信者取得と起票者を取得
                    $contractIssueAndNextSignUser = $this->documentSignOrderRepositoryInterface
                                                        ->getContractIssueAndNextSignUserInfo(
                                                            documentId: $documentId,
                                                            categoryId: $categoryId,
                                                            mUserId: $mUserId
                                                        );

                    if (empty($contractIssueAndNextSignUser->getSignDoc()) || empty($contractIssueAndNextSignUser->getNextSignUser()) || empty($contractIssueAndNextSignUser->getIssueUser())) {
                    throw new Exception('common.message.permission');
                    }

                    // file_prot_pw_flgがtrueの場合、メール送信しない旨のエラーを返却し処理を終了する。0 true 1 false
                    if ($contractIssueAndNextSignUser->getSignDoc()->file_prot_pw_flg === 0) {
                        throw new Exception("common.message.permission");
                    }
                    
                    // 次の署名者のメールアドレス取得
                    $emailAddress = $contractIssueAndNextSignUser->getNextSignUser()->email;

                    // 次の署名者のIDを取得
                    $nextSignUserId = $contractIssueAndNextSignUser->getNextSignUser()->user_id;

                    // 次の署名者の名前を取得
                    $nextSignUserName = $contractIssueAndNextSignUser->getNextSignUser()->full_name;

                    // メールタイトル作成
                    $emailTitle = $this->getMailTitle($contractIssueAndNextSignUser->getSignDoc()->title);

                    // 次の署名者がゲストの場合 user_type_idが1の場合
                    if ($contractIssueAndNextSignUser->getNextSignUser()->user_type_id === $this->userTypeConst::USER_TYPE_HOST_NO) {

                        // メールアドレスからハッシュ作成
                        $token = $this->getHash($contractIssueAndNextSignUser->getNextSignUser()->email, $mUserCompanyId, $categoryId, $documentId);

                        // トークン情報を取得
                        $dataContent['company_id']  = $contractIssueAndNextSignUser->getNextSignUser()->counter_party_id;
                        $dataContent['category_id'] = $contractIssueAndNextSignUser->getNextSignUser()->category_id;
                        $dataContent['document_id'] = $contractIssueAndNextSignUser->getSignDoc()->document_id;
                        $dataContent['user_id']     = $contractIssueAndNextSignUser->getNextSignUser()->user_id;

                        // トークン新規登録
                        $this->documentSignOrderRepositoryInterface->insertToken($token, $dataContent);
                        
                        // ユーザに送付するURL作成
                        $emailUrl = $this->getUrlGuest(
                            $systemUrl,
                            $this->docConst::DOCUMENT_DETAIL_ENDPOINT,
                            $categoryId,
                            $documentId,
                            $token
                        );   

                        // メール本文作成
                        $emailContent = $this->getContractMailContent(
                            $contractIssueAndNextSignUser->getNextSignUser()->counter_party_name,
                            $contractIssueAndNextSignUser->getNextSignUser()->full_name,
                            $contractIssueAndNextSignUser->getIssueUser()->full_name,
                            $emailUrl
                        );

                    } elseif ($contractIssueAndNextSignUser->getNextSignUser()->user_type_id === $this->userTypeConst::USER_TYPE_GUEST_NO) {
                        // 次の署名者がホストの場合 user_type_idが1の場合
                        
                        // ユーザに送付するURL作成
                        $emailUrl = $this->getUrlHost($systemUrl, $this->docConst::DOCUMENT_DETAIL_ENDPOINT, $categoryId, $documentId);

                        // メール本文作成
                        $emailContent = $this->getContractMailContent(
                            $contractIssueAndNextSignUser->getNextSignUser()->counter_party_name,
                            $contractIssueAndNextSignUser->getNextSignUser()->full_name,
                            $contractIssueAndNextSignUser->getIssueUser()->full_name,
                            $emailUrl
                        );
                    }
                    break;
                
                    // 取引書類
                case $this->docConst::DOCUMENT_DEAL:
                    // 次の送信者取得と起票者を取得
                    $dealIssueAndNextSignUser = $this->documentSignOrderRepositoryInterface
                                                        ->getDealIssueAndNextSignUserInfo(
                                                            documentId: $documentId,
                                                            categoryId: $categoryId,
                                                            mUserId: $mUserId
                                                        );

                    if (empty($dealIssueAndNextSignUser->getSignDoc()) || empty($dealIssueAndNextSignUser->getNextSignUser()) || empty($dealIssueAndNextSignUser->getIssueUser())) {
                        throw new Exception('common.message.permission');
                    }

                    // file_prot_pw_flgがtrueの場合、メール送信しない旨のエラーを返却し処理を終了する。0 true 1 false
                    if ($dealIssueAndNextSignUser->getSignDoc()->file_prot_pw_flg === 0) {
                        throw new Exception("common.message.permission");
                        exit;
                    }
                    
                    // 次の署名者のメールアドレス取得
                    $emailAddress = $dealIssueAndNextSignUser->getNextSignUser()->email;

                    // 次の署名者のIDを取得
                    $nextSignUserId = $dealIssueAndNextSignUser->getNextSignUser()->user_id;

                    // 次の署名者の名前を取得
                    $nextSignUserName = $dealIssueAndNextSignUser->getNextSignUser()->full_name;

                    // メールタイトル作成
                    $emailTitle = $this->getMailTitle($dealIssueAndNextSignUser->getSignDoc()->title);
                    
                    // 次の署名者がゲストの場合 user_type_idが1の場合
                    if ($dealIssueAndNextSignUser->getNextSignUser()->user_type_id === $this->userTypeConst::USER_TYPE_GUEST_NO) { // ゲストの場合 user_type_idが1の場合
                        
                        // メールアドレスからハッシュ作成
                        $token = $this->getHash($dealIssueAndNextSignUser->getNextSignUser()->email, $mUserCompanyId, $categoryId, $documentId);

                        // トークン情報を取得
                        $dataContent['company_id']  = $dealIssueAndNextSignUser->getNextSignUser()->counter_party_id;
                        $dataContent['category_id'] = $dealIssueAndNextSignUser->getNextSignUser()->category_id;
                        $dataContent['document_id'] = $dealIssueAndNextSignUser->getSignDoc()->document_id;
                        $dataContent['user_id']     = $dealIssueAndNextSignUser->getNextSignUser()->user_id;

                        // トークン新規登録
                        $this->documentSignOrderRepositoryInterface->insertToken($token, $dataContent);
                        
                        // ユーザに送付するURL作成
                        $emailUrl = $this->getUrlGuest(
                            $systemUrl,
                            $this->docConst::DOCUMENT_DETAIL_ENDPOINT,
                            $categoryId,
                            $documentId,
                            $token
                        );

                        // メール本文作成
                        $emailContent = $this->getDealMailContent(
                            $dealIssueAndNextSignUser->getNextSignUser()->counter_party_name,
                            $dealIssueAndNextSignUser->getNextSignUser()->full_name,
                            $dealIssueAndNextSignUser->getIssueUser()->full_name,
                            $emailUrl
                        );

                    } elseif ($dealIssueAndNextSignUser->getNextSignUser()->user_type_id === $this->userTypeConst::USER_TYPE_HOST_NO) { // ホストの場合 user_type_idが1の場合
                        // ユーザに送付するURL作成
                        $emailUrl = $this->getUrlHost($systemUrl, $this->docConst::DOCUMENT_DETAIL_ENDPOINT, $categoryId, $documentId);

                        // メール本文作成
                        $emailContent = $this->getDealMailContent(
                            $dealIssueAndNextSignUser->getNextSignUser()->counter_party_name,
                            $dealIssueAndNextSignUser->getNextSignUser()->full_name,
                            $dealIssueAndNextSignUser->getIssueUser()->full_name,
                            $emailUrl
                        );
                    }
                    break;

                    // 社内書類
                case $this->docConst::DOCUMENT_INTERNAL:
                    // 次の署名書類と送信者取得と起票者を取得
                    $internalIssueAndNextSignUser = $this->documentSignOrderRepositoryInterface
                                                        ->getInternalSignUserListInfo(
                                                            documentId: $documentId,
                                                            categoryId: $categoryId,
                                                            mUserCompanyId: $mUserCompanyId
                                                        );

                    if (empty($internalIssueAndNextSignUser->getSignDoc()) || empty($internalIssueAndNextSignUser->getNextSignUser()) || empty($internalIssueAndNextSignUser->getIssueUser())) {
                        throw new Exception('common.message.permission');
                    }

                    // file_prot_pw_flgがtrueの場合、メール送信しない旨のエラーを返却し処理を終了する。0 true 1 false
                    if ($internalIssueAndNextSignUser->getSignDoc()->file_prot_pw_flg === 0) {
                        throw new Exception("common.message.permission");
                        exit;
                    }

                    foreach ($internalIssueAndNextSignUser->getNextSignUser() as $signUser) {
                        
                        // ユーザに送付するURL作成
                        $emailUrl = $this->getUrlHost($systemUrl, $this->docConst::DOCUMENT_DETAIL_ENDPOINT, $categoryId, $documentId);

                         // 次の署名者のIDを取得
                        $nextSignUserId = $signUser->user_id;

                        // 次の署名者の名前を取得
                        $nextSignUserName = $signUser->full_name;

                        // 次の署名者のメールアドレス取得
                        $emailAddress = $signUser->email;

                        // メールタイトル作成
                        $emailTitle = $this->getMailTitle($internalIssueAndNextSignUser->getSignDoc()->title);

                        $emailContent = $this->getInternalMailContent(
                            $signUser->full_name,
                            $internalIssueAndNextSignUser->getIssueUser()->full_name,
                            $emailUrl
                        );

                        $paramdata = [];
                        $paramdata['email']['address']    = $emailAddress;
                        $paramdata['email']['title']      = $emailTitle;
                        $paramdata['email']['content']    = $emailContent;

                        // キューをJSON形式に返却
                        $param =json_encode($paramdata, JSON_UNESCAPED_UNICODE);
                        // キューへ登録
                        $ret = $this->queueUtility->createMessage(QueueConstant::QUEUE_NAME_SENDMAIL, $param);
                        if ($ret === -1) {
                            throw new Exception('common.message.permission');
                        }
                    }
                    return true;
                    exit;
                    break;

                    // 登録書類
                case $this->docConst::DOCUMENT_ARCHIVE:
                    // 次の署名書類と送信者取得と起票者を取得
                    $archiveIssueAndNextSignUser = $this->documentSignOrderRepositoryInterface
                                                        ->getArchiveIssueAndNextSignUserInfo(
                                                            documentId: $documentId,
                                                            categoryId: $categoryId,
                                                            mUserCompanyId: $mUserCompanyId
                                                        );

                    if (empty($archiveIssueAndNextSignUser->getSignDoc()) || empty($archiveIssueAndNextSignUser->getNextSignUser()) || empty($archiveIssueAndNextSignUser->getIssueUser())) {
                        throw new Exception('common.message.permission');
                    }

                    // file_prot_pw_flgがtrueの場合、メール送信しない旨のエラーを返却し処理を終了する。0 true 1 false
                    if ($archiveIssueAndNextSignUser->getSignDoc()->file_prot_pw_flg === 0) {
                        throw new Exception("common.message.permission");
                        exit;
                    }
                    
                    // 次の署名者のメールアドレス取得
                    $emailAddress = $archiveIssueAndNextSignUser->getNextSignUser()->email;

                    // 次の署名者のIDを取得
                    $nextSignUserId = $archiveIssueAndNextSignUser->getNextSignUser()->user_id;

                    // 次の署名者の名前を取得
                    $nextSignUserName = $archiveIssueAndNextSignUser->getNextSignUser()->full_name;

                    // メールタイトル作成
                    $emailTitle = $this->getMailTitle($archiveIssueAndNextSignUser->getSignDoc()->title);
                    
                    // ユーザに送付するURL作成
                    $emailUrl = $this->getUrlHost($systemUrl, $this->docConst::DOCUMENT_DETAIL_ENDPOINT, $categoryId, $documentId);

                    // メール本文作成
                    $emailContent = $this->getArchiveMailContent(
                        $archiveIssueAndNextSignUser->getNextSignUser()->full_name,
                        $archiveIssueAndNextSignUser->getIssueUser()->full_name,
                        $emailUrl
                    );

                    break;
            }

            // キューの設定
            $paramdata = []; 
            $paramdata['email']['address']    = $emailAddress;
            $paramdata['email']['title']      = $emailTitle;
            $paramdata['email']['content']    = $emailContent;


            // キューをJSON形式に返却
            $param =json_encode($paramdata, JSON_UNESCAPED_UNICODE);
            
            // キューへ登録
            $ret = $this->queueUtility->createMessage(QueueConstant::QUEUE_NAME_SENDMAIL, $param);
            if ($ret === -1) {
                throw new Exception('common.message.permission');
            }

            // 操作ログ登録
            $this->documentSignOrderRepositoryInterface->insertOperationLog(mUserCompanyId: $mUserCompanyId, categoryId: $categoryId, documentId: $documentId, mUserId: $mUserId, nextSignUserId: $nextSignUserId, nextSignUserName:$nextSignUserName, ipAddress:$ipAddress);
            
            return true;
        } catch (Exception $e) {
            throw $e;
            Log::error($e);
            return false;
        }
    }

    /**
     * ホストURL作成
     *
     * @param string $systemUrl
     * @param string $endPoint
     * @param integer $categoryId
     * @param integer $documentId
     * @return string
     */
    public function getUrlHost(string $systemUrl, string $endPoint, int $categoryId, int $documentId): string
    {
        return $systemUrl . $endPoint . $categoryId . "/" . $documentId;
    }

    /**
     * ゲストURL作成
     *
     * @param string $systemUrl
     * @param string $endPoint
     * @param integer $categoryId
     * @param integer $documentId
     * @param string $token
     * @return string
     */
    public function getUrlGuest (string $systemUrl, string $endPoint, int $categoryId, int $documentId, string $token): string
    {
        return $systemUrl . $endPoint . $categoryId . "/" . $documentId . "/&hash=" . $token;
    }

    /**
     * ハッシュ作成
     *
     * @param string $email
     * @param integer $mUserCompanyId
     * @param integer $categoryId
     * @param integer $documentId
     * @return string
     */
    public function getHash(string $email, int $mUserCompanyId, int $categoryId, int $documentId): string
    {
        return hash("sha256", $email . '@' . $mUserCompanyId . '@' . $categoryId . '@' . $documentId);
    }

    /**
     * メールタイトル
     * @param string $titleName
     * @return string
     */
    public function getMailTitle(string $titleName): string
    {
        return "[KOT電子契約]「{$titleName}」の署名依頼";
    }

    /**
     * 契約書類メール内容
     *
     * @param string|null $counterPartyName
     * @param string $nextSignUserName
     * @param string $issueUserName
     * @param string $emailUrl
     * @return string
     */
    public function getContractMailContent (string $counterPartyName = null, string $nextSignUserName, string $issueUserName, string $emailUrl): string
    {
        return $emailContent =
                            "{$counterPartyName} {$nextSignUserName} 様\n
                            {$issueUserName} 様から契約書類の署名依頼が送信されました。\n
                            以下のURLをクリックして書類の詳細確認、および署名を行ってください。\n
                            {$emailUrl}\n
                            このメールにお心当たりがない場合は、誤ってメールが送信された可能性があります。\n
                            お手数ですが support@huubhr.comまでご連絡をお願い致します。";
    }

    /**
     * 取引書類メール内容
     *
     * @param string|null $counterPartyName
     * @param string $nextSignUserName
     * @param string $issueUserName
     * @param string $emailUrl
     * @return string
     */
    public function getDealMailContent (string $counterPartyName = null, string $nextSignUserName, string $issueUserName, string $emailUrl): string
    {
        return $emailContent =
                            "{$counterPartyName} {$nextSignUserName} 様\n
                            {$issueUserName} 様から取引書類の署名依頼が送信されました。\n
                            以下のURLをクリックして書類の詳細確認、および署名を行ってください。\n
                            {$emailUrl}\n
                            このメールにお心当たりがない場合は、誤ってメールが送信された可能性があります。\n
                            お手数ですが support@huubhr.comまでご連絡をお願い致します。";
    }

    /**
     * 社内書類メール内容
     *
     * @param string $nextSignUserName
     * @param string $issueUserName
     * @param string $emailUrl
     * @return string
     */
    public function getInternalMailContent (string $nextSignUserName, string $issueUserName, string $emailUrl): string
    {
        return $emailContent =
                            "{$nextSignUserName} 様\n
                            {$issueUserName} 様から 社内書類の署名依頼が送信されました。\n
                            以下のURLをクリックして書類の詳細確認、および署名を行ってください。\n
                            {$emailUrl}\n
                            このメールにお心当たりがない場合は、誤ってメールが送信された可能性があります。\n
                            お手数ですが support@huubhr.comまでご連絡をお願い致します。";
    }

    /**
     * 登録書類メール内容
     *
     * @param string $nextSignUserName
     * @param string $issueUserName
     * @param string $emailUrl
     * @return string
     */
    public function getArchiveMailContent (string $nextSignUserName, string $issueUserName, string $emailUrl): string
    {
        return $emailContent =
                            "{$nextSignUserName} 様\n
                            {$issueUserName} 様から 登録書類の署名依頼が送信されました。\n
                            以下のURLをクリックして書類の詳細確認、および署名を行ってください。\n
                            {$emailUrl}\n
                            このメールにお心当たりがない場合は、誤ってメールが送信された可能性があります。\n
                            お手数ですが support@huubhr.comまでご連絡をお願い致します。";
    }
}


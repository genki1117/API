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
    

    public function signOrder(int $mUserId, int $mUserCompanyId, int $mUserTypeId, int $documentId, int $docTypeId, int $categoryId, string $updateDatetime, string $systemUrl)
    {
        try {
            switch($categoryId) {
                // 契約書類
                case $this->docConst::DOCUMENT_CONTRACT:
                    // 次の署名書類と送信者取得と起票者を取得
                    $contractIsseuAndNextSignUser = $this->documentSignOrderRepositoryInterface
                                                        ->getContractIsseuAndNextSignUserInfo(
                                                            documentId: $documentId,
                                                            categoryId: $categoryId,
                                                            mUserId: $mUserId
                                                        );

                    if (empty($contractIsseuAndNextSignUser->getSignDoc()) || empty($contractIsseuAndNextSignUser->getNextSignUser()) || empty($contractIsseuAndNextSignUser->getIssueUser())) {
                        throw new Exception('common.message.permission');
                    }

                    // file_prot_pw_flgがtrueの場合、メール送信しない旨のエラーを返却し処理を終了する。0 true 1 fals
                    if ($contractIsseuAndNextSignUser->getSignDoc()->file_prot_pw_flg === 0) {
                        throw new Exception("common.message.permission");
                    }
                    
                    // 次の署名者のメールアドレス取得
                    $emailAddress = $contractIsseuAndNextSignUser->getNextSignUser()->email;
                    
                    // メールタイトル作成
                    $emailTitle = $this->getMailTitle($contractIsseuAndNextSignUser->getSignDoc()->title);

                    // 次の署名者がゲストの場合 user_type_idが1の場合
                    if ($contractIsseuAndNextSignUser->getNextSignUser()->user_type_id === $this->userTypeConst::USER_TYPE_GUEST_NO) {

                        // メールアドレスからハッシュ作成
                        $token = $this->getToken($contractIsseuAndNextSignUser->getNextSignUser()->email);

                        // トークン情報を取得
                        $dataContent['company_id']  = $contractIsseuAndNextSignUser->getNextSignUser()->counter_party_id;
                        $dataContent['category_id'] = $contractIsseuAndNextSignUser->getNextSignUser()->category_id;
                        $dataContent['document_id'] = $contractIsseuAndNextSignUser->getSignDoc()->document_id;
                        $dataContent['user_id']     = $contractIsseuAndNextSignUser->getNextSignUser()->user_id;

                        // トークン新規登録
                        $this->documentSignOrderRepositoryInterface->insertToken($token, $dataContent);
                        
                        // ユーザに送付するURL作成
                        $emailUrl = $systemUrl . $this->docConst::DOCUMENT_DETAIL_ENDPOINT . $contractIsseuAndNextSignUser->getSignDoc()->document_id . "/&token=" . $token;
                        
                        // メール本文作成
                        $emailContent = $this->getContractMailContent(
                            $contractIsseuAndNextSignUser->getNextSignUser()->counter_party_name,
                            $contractIsseuAndNextSignUser->getNextSignUser()->full_name,
                            $contractIsseuAndNextSignUser->getIssueUser()->full_name,
                            $emailUrl
                        );

                    } elseif ($contractIsseuAndNextSignUser->getNextSignUser()->user_type_id === $this->userTypeConst::USER_TYPE_HOST_NO) {
                        // 次の署名者がホストの場合 user_type_idが1の場合
                        
                        // ユーザに送付するURL作成
                        $emailUrl = $systemUrl . $this->docConst::DOCUMENT_DETAIL_ENDPOINT . $contractIsseuAndNextSignUser->getSignDoc()->document_id;

                        // メール本文作成
                        $emailContent = $this->getContractMailContent(
                            $contractIsseuAndNextSignUser->getNextSignUser()->counter_party_name,
                            $contractIsseuAndNextSignUser->getNextSignUser()->full_name,
                            $contractIsseuAndNextSignUser->getIssueUser()->full_name,
                            $emailUrl
                        );
                    }
                    break;
                
                    // 取引書類
                case $this->docConst::DOCUMENT_DEAL:
                    // 次の送信者取得と起票者を取得
                    $dealIsseuAndNextSignUser = $this->documentSignOrderRepositoryInterface
                                                        ->getDealIsseuAndNextSignUserInfo(
                                                            documentId: $documentId,
                                                            categoryId: $categoryId,
                                                            mUserId: $mUserId
                                                        );

                    if (empty($dealIsseuAndNextSignUser->getSignDoc()) || empty($dealIsseuAndNextSignUser->getNextSignUser()) || empty($dealIsseuAndNextSignUser->getIssueUser())) {
                        throw new Exception('common.message.permission');
                    }

                    // file_prot_pw_flgがtrueの場合、メール送信しない旨のエラーを返却し処理を終了する。0 true 1 fals
                    if ($dealIsseuAndNextSignUser->getSignDoc()->file_prot_pw_flg === 0) {
                        throw new Exception("common.message.permission");
                        exit;
                    }
                    
                    // 次の署名者のメールアドレス取得
                    $emailAddress = $dealIsseuAndNextSignUser->getNextSignUser()->email;

                    // メールタイトル作成
                    $emailTitle = $this->getMailTitle($dealIsseuAndNextSignUser->getSignDoc()->title);
                    
                    if ($dealIsseuAndNextSignUser->getNextSignUser()->user_type_id === $this->userTypeConst::USER_TYPE_GUEST_NO) { // ゲストの場合 user_type_idが1の場合
                        
                        // メールアドレスからハッシュ作成
                        $token = $this->getToken($dealIsseuAndNextSignUser->getNextSignUser()->email);

                        // トークン情報を取得
                        $dataContent['company_id']  = $dealIsseuAndNextSignUser->getNextSignUser()->counter_party_id;
                        $dataContent['category_id'] = $dealIsseuAndNextSignUser->getNextSignUser()->category_id;
                        $dataContent['document_id'] = $dealIsseuAndNextSignUser->getSignDoc()->document_id;
                        $dataContent['user_id']     = $dealIsseuAndNextSignUser->getNextSignUser()->user_id;

                        // トークン新規登録
                        $this->documentSignOrderRepositoryInterface->insertToken($token, $dataContent);
                        
                        // ユーザに送付するURL作成
                        $emailUrl = $systemUrl . $this->docConst::DOCUMENT_DETAIL_ENDPOINT . $dealIsseuAndNextSignUser->getSignDoc()->document_id . "/&token=" . $token;

                        // メール本文作成
                        $emailContent = $this->getDealMailContent(
                            $dealIsseuAndNextSignUser->getNextSignUser()->counter_party_name,
                            $dealIsseuAndNextSignUser->getNextSignUser()->full_name,
                            $dealIsseuAndNextSignUser->getIssueUser()->full_name,
                            $emailUrl
                        );

                    } elseif ($dealIsseuAndNextSignUser->getNextSignUser()->user_type_id === $this->userTypeConst::USER_TYPE_HOST_NO) { // ホストの場合 user_type_idが1の場合
                        // ユーザに送付するURL作成
                        $emailUrl = $systemUrl . $this->docConst::DOCUMENT_DETAIL_ENDPOINT . $dealIsseuAndNextSignUser->getSignDoc()->document_id;

                        // メール本文作成
                        $emailContent = $this->getDealMailContent(
                            $dealIsseuAndNextSignUser->getNextSignUser()->counter_party_name,
                            $dealIsseuAndNextSignUser->getNextSignUser()->full_name,
                            $dealIsseuAndNextSignUser->getIssueUser()->full_name,
                            $emailUrl
                        );
                    }
                    break;

                    // 社内書類
                case $this->docConst::DOCUMENT_INTERNAL:
                    // 次の署名書類と送信者取得と起票者を取得
                    $internalIsseuAndNextSignUser = $this->documentSignOrderRepositoryInterface
                                                        ->getInternalSignUserListInfo(
                                                            documentId: $documentId,
                                                            categoryId: $categoryId,
                                                            mUserCompanyId: $mUserCompanyId
                                                        );

                    if (empty($internalIsseuAndNextSignUser->getSignDoc()) || empty($internalIsseuAndNextSignUser->getNextSignUser()) || empty($internalIsseuAndNextSignUser->getIssueUser())) {
                        throw new Exception('common.message.permission');
                    }

                    // file_prot_pw_flgがtrueの場合、メール送信しない旨のエラーを返却し処理を終了する。0 true 1 false
                    if ($internalIsseuAndNextSignUser->getSignDoc()->file_prot_pw_flg === 0) {
                        throw new Exception("common.message.permission");
                        exit;
                    }

                    foreach ($internalIsseuAndNextSignUser->getNextSignUser() as $signUser) {
                        
                        // URL作成
                        $emailUrl = $systemUrl . $this->docConst::DOCUMENT_DETAIL_ENDPOINT . $internalIsseuAndNextSignUser->getSignDoc()->document_id;

                        // 次の署名者のメールアドレス取得
                        $emailAddress = $signUser->email;

                        // メールタイトル作成
                        $emailTitle = $this->getMailTitle($internalIsseuAndNextSignUser->getSignDoc()->title);

                        $emailContent = $this->getInternalMailContent(
                            $signUser->full_name,
                            $internalIsseuAndNextSignUser->getIssueUser()->full_name,
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
                    $archiveIsseuAndNextSignUser = $this->documentSignOrderRepositoryInterface
                                                        ->getArchiveIsseuAndNextSignUserInfo(
                                                            documentId: $documentId,
                                                            categoryId: $categoryId,
                                                            mUserCompanyId: $mUserCompanyId
                                                        );

                    if (empty($archiveIsseuAndNextSignUser->getSignDoc()) || empty($archiveIsseuAndNextSignUser->getNextSignUser()) || empty($archiveIsseuAndNextSignUser->getIssueUser())) {
                        throw new Exception('common.message.permission');
                    }

                    // file_prot_pw_flgがtrueの場合、メール送信しない旨のエラーを返却し処理を終了する。0 true 1 false
                    if ($archiveIsseuAndNextSignUser->getSignDoc()->file_prot_pw_flg === 0) {
                        throw new Exception("common.message.permission");
                        exit;
                    }
                    
                    // 次の署名者のメールアドレス取得
                    $emailAddress = $archiveIsseuAndNextSignUser->getNextSignUser()->email;

                    // メールタイトル作成
                    $emailTitle = $this->getMailTitle($archiveIsseuAndNextSignUser->getSignDoc()->title);
                    
                    // ユーザに送付するURL作成
                    $emailUrl = $systemUrl . $this->docConst::DOCUMENT_DETAIL_ENDPOINT . $archiveIsseuAndNextSignUser->getSignDoc()->document_id;

                    // メール本文作成
                    $emailContent = $this->getArchiveMailContent(
                        $archiveIsseuAndNextSignUser->getNextSignUser()->full_name,
                        $archiveIsseuAndNextSignUser->getIssueUser()->full_name,
                        $emailUrl
                    );

                    break;
            }

            // // キューの設定
            // $paramdata = []; 
            // $paramdata['email']['address']    = $emailAddress;
            // $paramdata['email']['title']      = $emailTitle;
            // $paramdata['email']['content']    = $emailContent;


            // // キューをJSON形式に返却
            // $param =json_encode($paramdata, JSON_UNESCAPED_UNICODE);
            
            // // キューへ登録
            // $ret = $this->queueUtility->createMessage(QueueConstant::QUEUE_NAME_SENDMAIL, $param);
            // if ($ret === -1) {
            //     throw new Exception('common.message.permission');
            // }

            return true;
        } catch (Exception $e) {
            throw $e;
            Log::error($e);
            return false;
        }
    }


    public function getToken($email)
    {
        return hash("sha256", $email);
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


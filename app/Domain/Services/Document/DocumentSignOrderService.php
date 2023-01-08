<?php
declare(strict_types=1);
namespace App\Domain\Services\Document;

use App\Accessers\Queue\QueueUtility;
use Exception;
use App\Domain\Consts\UserTypeConst;
use App\Domain\Consts\DocumentConst;
use App\Domain\Repositories\Interface\Document\DocumentSignOrderRepositoryInterface;


class DocumentSignOrderService
{
    private $emailAddress;
    private $emailTitle;
    private $emailContent;

    /** @var DocumentConst */
    private UserTypeConst $userTypeConst;

    /** @var DocumentConst */
    private DocumentConst $docConst;

    /** @var  DocumentSignOrderRepositoryInterface*/
    private DocumentSignOrderRepositoryInterface $documentSignOrderRepositoryInterface;

    public function __construct(
        UserTypeConst $userTypeConst,
        DocumentConst $docConst,
        DocumentSignOrderRepositoryInterface $documentSignOrderRepositoryInterface
    )
    {
        $this->userTypeConst = $userTypeConst;
        $this->docConst = $docConst;
        $this->documentSignOrderRepositoryInterface = $documentSignOrderRepositoryInterface;
    }
    

    public function signOrder(int $mUserId, int $mUserCompanyId, int $mUserTypeId, int $documentId, int $docTypeId, int $categoryId, string $updateDatetime)
    {
        try {
                    // ログインユーザのワークフローソート取得
            $loginUserWorkFlowSort = $this->documentSignOrderRepositoryInterface->getLoginUserWorkflow(mUserId: $mUserId, mUserCompanyId :$mUserCompanyId);

            // 書類詳細エンドポイント作成
            $documentDetailendPoint = '/document/detail/';

            // システムURL取得
            $systemUrl = url('');

            switch($categoryId) {
                
                // 契約書類
                case $this->docConst::DOCUMENT_CONTRACT:
                    // 次の署名書類と送信者取得と起票者を取得
                    $contractIsseuAndNextSignUser = $this->documentSignOrderRepositoryInterface
                                                        ->getContractIsseuAndNextSignUserInfo(
                                                            documentId: $documentId, categoryId: $categoryId, loginUserWorkFlowSort: $loginUserWorkFlowSort->wf_sort
                                                        );
                                                        
                    // file_prot_pw_flgがtrueの場合、メール送信しない旨のエラーを返却し処理を終了する。0 true 1 fals
                    if ($contractIsseuAndNextSignUser->getSignDoc()->file_prot_pw_flg === 0) {
                        throw new Exception("メールを送信しません");
                        exit;
                    }
                    
                    // 次の署名者のメールアドレス取得
                    $emailAddress = $contractIsseuAndNextSignUser->getNextSignUser()->email;

                    // メールタイトル作成
                    $emailTitle = "[KOT電子契約]「{$contractIsseuAndNextSignUser->getSignDoc()->title}」の署名依頼";

                    if ($contractIsseuAndNextSignUser->getNextSignUser()->user_type_id === $this->userTypeConst::USER_TYPE_GUEST_NO) { // ゲストの場合 user_type_idが1の場合
                        // メールアドレスからハッシュ作成 
                        $token = hash("sha256", $contractIsseuAndNextSignUser->getNextSignUser()->email);

                        // トークン情報を取得
                        $dataContent['company_id']  = $contractIsseuAndNextSignUser->getNextSignUser()->counter_party_id;
                        $dataContent['category_id'] = $contractIsseuAndNextSignUser->getNextSignUser()->category_id;
                        $dataContent['document_id'] = $contractIsseuAndNextSignUser->getSignDoc()->document_id;
                        $dataContent['user_id']     = $contractIsseuAndNextSignUser->getNextSignUser()->user_id;

                        // トークン新規登録
                        $this->documentSignOrderRepositoryInterface->insertToken($token, $dataContent);
                        
                        // ユーザに送付するURL作成
                        $emailUrl = $systemUrl . $documentDetailendPoint . $contractIsseuAndNextSignUser->getSignDoc()->document_id . "/&token=" . $token;

                        // メール本文作成
                        $emailContent = 
                            "{$contractIsseuAndNextSignUser->getNextSignUser()->counter_party_name} {$contractIsseuAndNextSignUser->getNextSignUser()->full_name} 様\n
                            {$contractIsseuAndNextSignUser->getIssueUser()->full_name} 様から契約書類の署名依頼が送信されました。\n
                            以下のURLをクリックして書類の詳細確認、および署名を行ってください。\n
                            {$emailUrl}\n
                            このメールにお心当たりがない場合は、誤ってメールが送信された可能性があります。\n
                            お手数ですが support@huubhr.comまでご連絡をお願い致します。";
                            // 
                    } else if ($contractIsseuAndNextSignUser->getNextSignUser()->user_type_id === $this->userTypeConst::USER_TYPE_HOST_NO) { // ホストの場合 user_type_idが1の場合
                        // ユーザに送付するURL作成
                        $emailUrl = $systemUrl . $documentDetailendPoint . $contractIsseuAndNextSignUser->getSignDoc()->document_id;

                        // メール本文作成
                        $emailContent = 
                            "{$contractIsseuAndNextSignUser->getNextSignUser()->full_name} 様\n
                            {$contractIsseuAndNextSignUser->getIssueUser()->full_name} 様から契約書類の署名依頼が送信されました。\n
                            以下のURLをクリックして書類の詳細確認、および署名を行ってください。\n
                            {$emailUrl}\n
                            このメールにお心当たりがない場合は、誤ってメールが送信された可能性があります。\n
                            お手数ですが support@huubhr.comまでご連絡をお願い致します。";
                    }
                break;
                
                // 取引書類
                case $this->docConst::DOCUMENT_DEAL:
                    // 次の送信者取得と起票者を取得
                    $dealIsseuAndNextSignUser = $this->documentSignOrderRepositoryInterface
                                                        ->getDealIsseuAndNextSignUserInfo(
                                                            $documentId, $categoryId, $loginUserWorkFlowSort->wf_sort
                                                        );
                    // file_prot_pw_flgがtrueの場合、メール送信しない旨のエラーを返却し処理を終了する。0 true 1 fals
                    if ($dealIsseuAndNextSignUser->getSignDoc()->file_prot_pw_flg === 0) {
                        throw new Exception("メールを送信しません");
                        exit;
                    }
                    
                    // 次の署名者のメールアドレス取得
                    $emailAddress = $dealIsseuAndNextSignUser->getNextSignUser()->email;

                    // メールタイトル作成
                    $emailTitle = "[KOT電子契約]「{$dealIsseuAndNextSignUser->getSignDoc()->title}」の署名依頼";
                    
                    if ($dealIsseuAndNextSignUser->getNextSignUser()->user_type_id === $this->userTypeConst::USER_TYPE_GUEST_NO) { // ゲストの場合 user_type_idが1の場合
                        // メールアドレスからハッシュ作成 
                        $token = hash("sha256", $dealIsseuAndNextSignUser->getNextSignUser()->email);

                        // トークン情報を取得
                        $dataContent['company_id']  = $dealIsseuAndNextSignUser->getNextSignUser()->counter_party_id;
                        $dataContent['category_id'] = $dealIsseuAndNextSignUser->getNextSignUser()->category_id;
                        $dataContent['document_id'] = $dealIsseuAndNextSignUser->getSignDoc()->document_id;
                        $dataContent['user_id']     = $dealIsseuAndNextSignUser->getNextSignUser()->user_id;

                        // トークン新規登録
                        $this->documentSignOrderRepositoryInterface->insertToken($token, $dataContent);
                        
                        // ユーザに送付するURL作成
                        $emailUrl = $systemUrl . $documentDetailendPoint . $dealIsseuAndNextSignUser->getSignDoc()->document_id . "/&token=" . $token;

                        // メール本文作成
                        $emailContent = 
                            "{$dealIsseuAndNextSignUser->getNextSignUser()->counter_party_name} {$dealIsseuAndNextSignUser->getNextSignUser()->full_name} 様\n
                            {$dealIsseuAndNextSignUser->getIssueUser()->full_name} 様から取引書類の署名依頼が送信されました。\n
                            以下のURLをクリックして書類の詳細確認、および署名を行ってください。\n
                            {$emailUrl}\n
                            このメールにお心当たりがない場合は、誤ってメールが送信された可能性があります。\n
                            お手数ですが support@huubhr.comまでご連絡をお願い致します。";
                            
                    } else if ($dealIsseuAndNextSignUser->getNextSignUser()->user_type_id === $this->userTypeConst::USER_TYPE_HOST_NO) { // ホストの場合 user_type_idが1の場合
                        // ユーザに送付するURL作成
                        $emailUrl = $systemUrl . $documentDetailendPoint . $dealIsseuAndNextSignUser->getSignDoc()->document_id;

                        // メール本文作成
                        $emailContent = 
                            "{$dealIsseuAndNextSignUser->getNextSignUser()->full_name} 様\n
                            {$dealIsseuAndNextSignUser->getIssueUser()->full_name} 様から取引書類の署名依頼が送信されました。\n
                            以下のURLをクリックして書類の詳細確認、および署名を行ってください。\n
                            {$emailUrl}\n
                            このメールにお心当たりがない場合は、誤ってメールが送信された可能性があります。\n
                            お手数ですが support@huubhr.comまでご連絡をお願い致します。";
                    }
                break;

                // 社内書類
                case $this->docConst::DOCUMENT_INTERNAL:
                    // 次の署名書類と送信者取得と起票者を取得
                    $internalIsseuAndNextSignUser = $this->documentSignOrderRepositoryInterface
                                                        ->getInternalSignUserListInfo(
                                                            documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId
                                                        );

                    // file_prot_pw_flgがtrueの場合、メール送信しない旨のエラーを返却し処理を終了する。0 true 1 fals
                    if ($internalIsseuAndNextSignUser->getSignDoc()->file_prot_pw_flg === 0) {
                        throw new Exception("メールを送信しません");
                        exit;
                    }

                    foreach ($internalIsseuAndNextSignUser->getNextSignUser() as $signUser) {
                        // 次の署名者のメールアドレス取得
                        $emailUrl = $systemUrl . $documentDetailendPoint . $internalIsseuAndNextSignUser->getSignDoc()->document_id;

                        $emailAddress = $signUser->email;
                        // メールタイトル作成
                        $emailTitle = "[KOT電子契約]「{$internalIsseuAndNextSignUser->getSignDoc()->title}」の署名依頼";

                        $emailContent = "
                        {$signUser->full_name} 様\n
                        {$internalIsseuAndNextSignUser->getIssueUser()->full_name} 様から社内書類の署名依頼が送信されました。\n
                        以下のURLをクリックして書類の詳細確認、および署名を行ってください。\n
                        {$emailUrl}\n
                        このメールにお心当たりがない場合は、誤ってメールが送信された可能性があります。\n
                        お手数ですが support@huubhr.comまでご連絡をお願い致します。";
                        
                        // TODO:キュー作成（社内書類）
                        // キューパラメータを作成
                        $paramdata = [];
                        
                        $paramdata['email']   = $emailAddress;
                        $paramdata['title']   = $emailTitle;
                        $paramdata['content'] = $emailContent;

                        // キューをJSON形式に返却
                        $param =json_encode($paramdata, JSON_UNESCAPED_UNICODE);
                        
                        // キューを書き込み
                        // $ret = $queueUtility->createMessage(QueueUtility::QUEUE_NAME_SIGN, $param);
                        // var_dump($emailAddress, $emailTitle, $emailContent); // 確認用
                    }
                exit;
                break;

                // 登録書類
                case $this->docConst::DOCUMENT_ARCHIVE:
                    // 次の署名書類と送信者取得と起票者を取得
                    $archiveIsseuAndNextSignUser = $this->documentSignOrderRepositoryInterface
                                                        ->getArchiveIsseuAndNextSignUserInfo(
                                                            documentId: $documentId, categoryId: $categoryId, mUserCompanyId: $mUserCompanyId
                                                        );
                                                        
                    // file_prot_pw_flgがtrueの場合、メール送信しない旨のエラーを返却し処理を終了する。0 true 1 fals
                    if ($archiveIsseuAndNextSignUser->getSignDoc()->file_prot_pw_flg === 0) {
                        throw new Exception("メールを送信しません");
                        exit;
                    }
                    
                    // 次の署名者のメールアドレス取得
                    $emailAddress = $archiveIsseuAndNextSignUser->getNextSignUser()->email;

                    // メールタイトル作成
                    $emailTitle = "[KOT電子契約]「{$archiveIsseuAndNextSignUser->getSignDoc()->title}」の署名依頼";
                    
                    // ユーザに送付するURL作成
                    $emailUrl = $systemUrl . $documentDetailendPoint . $archiveIsseuAndNextSignUser->getSignDoc()->document_id;

                    // メール本文作成
                    $emailContent = 
                        "{$archiveIsseuAndNextSignUser->getNextSignUser()->full_name} 様\n
                        {$archiveIsseuAndNextSignUser->getIssueUser()->full_name} 様から登録書類の署名依頼が送信されました。\n
                        以下のURLをクリックして書類の詳細確認、および署名を行ってください。\n
                        {$emailUrl}\n
                        このメールにお心当たりがない場合は、誤ってメールが送信された可能性があります。\n
                        お手数ですが support@huubhr.comまでご連絡をお願い致します。";
                break;
            }

            // return var_dump($emailAddress, $emailTitle, $emailContent); // 確認用

            // TODO:キュー作成
            //キューにパラメータを渡す
            // $queueUtility = new QueueUtility();

            // キューパラメータを作成
            $paramdata = [];
                        
            $paramdata['email']   = $emailAddress;
            $paramdata['title']   = $emailTitle;
            $paramdata['content'] = $emailContent;

            // キューをJSON形式に返却
            // return
            $param =json_encode($paramdata, JSON_UNESCAPED_UNICODE);

            // キューを書き込み
            // $ret = $queueUtility->createMessage(QueueUtility::QUEUE_NAME_SIGN, $param);
            // if ($ret === false) {
            //     throw new Exception("メールの送信に失敗しました。");
            //     exit;
            // }

            return true;
        } catch (Exception $e) {
            throw $e;
            return false;
            
        }

    }

}

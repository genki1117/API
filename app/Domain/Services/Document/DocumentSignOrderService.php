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
    private $emailAdress;
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
        // ログインユーザのワークフローソート取得
        $loginUserWorkFlowSort = $this->documentSignOrderRepositoryInterface->getLoginUserWorkflow($mUserId, $mUserCompanyId);

        // 書類詳細エンドポイント作成
        $documentDetailendPoint = '/document/detail/';

        // システムURL取得
        $systemUrl = url('');

        switch($categoryId) {
            
            // 取引書類の場合
            case $this->docConst::DOCUMENT_CONTRACT:
                // 次の送信者取得と起票者を取得
                $contractIsseuAndNextSignUser = $this->documentSignOrderRepositoryInterface
                                                      ->getContractIsseuUserAndNextSignUserInfomation(
                                                        $documentId, $categoryId, $loginUserWorkFlowSort->wf_sort
                                                    );
                                                    
                // file_prot_pw_flgがtrueの場合、メール送信しない旨のエラーを返却し処理を終了する。0 true 1 fals
                if ($contractIsseuAndNextSignUser->getNextSignUser()->file_prot_pw_flg === 0) {
                    throw new Exception("メールを送信しません");
                    exit;
                }
                
                // 次の署名者のメールアドレス取得
                $emailAdress = $contractIsseuAndNextSignUser->getNextSignUser()->email;

                // メールタイトル作成
                $emailTitle = "[KOT電子契約]「{$contractIsseuAndNextSignUser->getNextSignUser()->title}」の署名依頼";
                                
                
                if ($contractIsseuAndNextSignUser->getNextSignUser()->user_type_id === $this->userTypeConst::USER_TYPE_GUEST_NO) { // ゲストの場合 user_type_idが1の場合
                    // メールアドレスからハッシュ作成 
                    $token = hash("sha256", $contractIsseuAndNextSignUser->getNextSignUser()->email);

                    // トークン情報を取得
                    $dataContent['company_id']  = $contractIsseuAndNextSignUser->getNextSignUser()->counter_party_id;
                    $dataContent['category_id'] = $contractIsseuAndNextSignUser->getNextSignUser()->category_id;
                    $dataContent['document_id'] = $contractIsseuAndNextSignUser->getNextSignUser()->document_id;
                    $dataContent['user_id']     = $contractIsseuAndNextSignUser->getNextSignUser()->user_id;

                    // トークン新規登録
                    $this->documentSignOrderRepositoryInterface->insertToken($token, $dataContent);
                    
                    // ユーザに送付するURL作成
                    $emailUrl = $systemUrl . $documentDetailendPoint . "&token=" . $token;

                    $contractIsseuAndNextSignUser->getNextSignUser()->counter_party_name = "testkaisha"; // test

                    // メール本文作成
                    $emailContent = 
                        "{$contractIsseuAndNextSignUser->getNextSignUser()->counter_party_name} {$contractIsseuAndNextSignUser->getNextSignUser()->full_name} 様\n
                        {$contractIsseuAndNextSignUser->getIssueUser()->full_name} 様から契約書類の署名依頼が送信されました。\n
                        以下のURLをクリックして書類の詳細確認、および署名を行ってください。\n
                        {$emailUrl}\n
                        このメールにお心当たりがない場合は、誤ってメールが送信された可能性があります。\n
                        お手数ですが support@huubhr.comまでご連絡をお願い致します。";

                } else  if ($contractIsseuAndNextSignUser->getNextSignUser()->user_type_id === $this->userTypeConst::USER_TYPE_HOST_NO){ // ホストの場合 user_type_idが1の場合
                    // ユーザに送付するURL作成
                    $emailUrl = $systemUrl . $documentDetailendPoint;

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
            

            // 契約書類の場合
            case $this->docConst::DOCUMENT_DEAL:
                // 次の送信者取得と起票者を取得
                $dealIsseuAndNextSignUser = $this->documentSignOrderRepositoryInterface
                                                      ->getDealIsseuUserAndNextSignUserInfomation(
                                                        $documentId, $categoryId, $loginUserWorkFlowSort->wf_sort
                                                    );

                // file_prot_pw_flgがtrueの場合、メール送信しない旨のエラーを返却し処理を終了する。0 true 1 fals
                if ($dealIsseuAndNextSignUser->getNextSignUser()->file_prot_pw_flg === 0) {
                    throw new Exception("メールを送信しません");
                    exit;
                }

                // 次の署名者のメールアドレス取得
                $emailAdress = $dealIsseuAndNextSignUser->getNextSignUser()->email;

                // メールタイトル作成
                $emailTitle = "[KOT電子契約]「{$dealIsseuAndNextSignUser->getNextSignUser()->title}」の署名依頼";


                if ($dealIsseuAndNextSignUser->getNextSignUser()->user_type_id === 0) { // ゲストの場合 user_type_idが1の場合
                    // メールアドレスからハッシュ作成 
                    $token = hash("sha256", $dealIsseuAndNextSignUser->getNextSignUser()->email);

                    // トークン情報を取得
                    $dataContent['company_id']  = $dealIsseuAndNextSignUser->getNextSignUser()->counter_party_id;
                    $dataContent['category_id'] = $dealIsseuAndNextSignUser->getNextSignUser()->category_id;
                    $dataContent['document_id'] = $dealIsseuAndNextSignUser->getNextSignUser()->document_id;
                    $dataContent['user_id']     = $dealIsseuAndNextSignUser->getNextSignUser()->user_id;

                    // トークン新規登録
                    $this->documentSignOrderRepositoryInterface->insertToken($token, $dataContent);
                    
                    // ユーザに送付するURL作成
                    $emailUrl = $systemUrl . $documentDetailendPoint . "&token=" . $token;

                    $dealIsseuAndNextSignUser->getNextSignUser()->counter_party_name = "testkaisha"; // test

                    // メール本文作成
                    $emailContent = 
                        "{$dealIsseuAndNextSignUser->getNextSignUser()->counter_party_name} {$dealIsseuAndNextSignUser->getNextSignUser()->full_name} 様\n
                        {$dealIsseuAndNextSignUser->getIssueUser()->full_name} 様から契約書類の署名依頼が送信されました。\n
                        以下のURLをクリックして書類の詳細確認、および署名を行ってください。\n
                        {$emailUrl}\n
                        このメールにお心当たりがない場合は、誤ってメールが送信された可能性があります。\n
                        お手数ですが support@huubhr.comまでご連絡をお願い致します。";

                } else { // ホストの場合 user_type_idが1の場合
                    // ユーザに送付するURL作成
                    $emailUrl = $systemUrl . $documentDetailendPoint;

                    // メール本文作成
                    $emailContent = 
                        "{$dealIsseuAndNextSignUser->getNextSignUser()->full_name} 様\n
                        {$dealIsseuAndNextSignUser->getIssueUser()->full_name} 様から契約書類の署名依頼が送信されました。\n
                        以下のURLをクリックして書類の詳細確認、および署名を行ってください。\n
                        {$emailUrl}\n
                        このメールにお心当たりがない場合は、誤ってメールが送信された可能性があります。\n
                        お手数ですが support@huubhr.comまでご連絡をお願い致します。";

                }


            break;

            case $this->docConst::DOCUMENT_INTERNAL:
                return 'internal';
                // 社内書類以外の場合はSQLにLIMITを設けて特定の人だけに送信する。
                // 社内書類は全員送信する。
            break;

            case $this->docConst::DOCUMENT_ARCHIVE:
                return 'archive';
            break;

        }

        return $emailUrl;
        // //キューにパラメータを渡す
        // $queueUtility = new QueueUtility();

        // // キューパラメータを作成
        // $paramdata = [];
        
        // $paramdata['company_id'] = $mUser['company_id'];
        // $paramdata['user_id'] = $mUser['user_id'];
        // $paramdata['form']['document_id'] = $form['document_id'];
        // $paramdata['form']['category_id'] = $form['category_id'];
        // $paramdata['form']['update_datetime'] = $form['update_datetime'];
        // $paramdata['form']['user_id'] = $mUser['user_id'];

        // // キューをJSON形式に返却
        // $param =json_encode($paramdata, JSON_UNESCAPED_UNICODE);
        
        // // キューを書き込み
        // $ret = $queueUtility->createMessage(QueueUtility::QUEUE_NAME_SIGN, $param);
    }

}

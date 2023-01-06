<?php
declare(strict_types=1);
namespace App\Domain\Services\Document;

use App\Accessers\Queue\QueueUtility;
use Exception;
use App\Domain\Consts\DocumentConst;
use App\Domain\Repositories\Interface\Document\DocumentSignOrderRepositoryInterface;


class DocumentSignOrderService
{
    /** @var DocumentConst */
    private DocumentConst $docConst;

    /** @var  DocumentSignOrderRepositoryInterface*/
    private DocumentSignOrderRepositoryInterface $documentSignOrderRepositoryInterface;

    public function __construct(
    DocumentConst $docConst,
    DocumentSignOrderRepositoryInterface $documentSignOrderRepositoryInterface
    )
    {
        $this->docConst = $docConst;
        $this->documentSignOrderRepositoryInterface = $documentSignOrderRepositoryInterface;
    }
    

    public function signOrder(int $mUserId, int $mUserCompanyId, int $mUserTypeId, int $documentId, int $docTypeId, int $categoryId, string $updateDatetime)
    {
        // ログインユーザのワークフローソート取得
        return $loginUserWorkFlowSortNumber = $this->documentSignOrderRepositoryInterface->getLoginUserWorkflow($mUserId, $mUserCompanyId);
        switch($categoryId) {
            case $this->docConst::DOCUMENT_CONTRACT:
                // 次の送信者取得
                $nextSignUserInfomation = $this->documentSignOrderRepositoryInterface->getContractNextSignUserInfomation($documentId, $categoryId, $loginUserWorkFlowSortNumber);
                $nextSignUserInfomation->full_name;
                
                // file_prot_pw_flgがtrueの場合、メール送信しない旨のエラーを返却し処理を終了する。
                // 0 true
                // 1 false
                $nextSignUserInfomation->file_prot_flg;
                if ($nextSignUserInfomation->file_prot_flg === 0) {
                    throw new Exception("メールを送信しません");
                    exit;
                }
                $nextSignUserInfomation->counter_party_name = "unkokaisha";
                
                // ゲストの場合
                if ($nextSignUserInfomation->user_type_id === 1) {
                    // システムURL取得
                    $systemUrl = url('');

                    // メールアドレスからハッシュ作成 
                    $token = hash("sha256", $nextSignUserInfomation->email);

                    // オブジェクトに変更
                    // 今は仮
                    $dataContent['company_id'] = $nextSignUserInfomation->counter_party_id;
                    $dataContent['category_id'] = $nextSignUserInfomation->category_id;
                    $dataContent['document_id'] = $nextSignUserInfomation->document_id;
                    $dataContent['user_id'] = $nextSignUserInfomation->user_id;

                    // トークン新規登録
                    $this->documentSignOrderRepositoryInterface->insertToken($token, $dataContent);

                    // 書類詳細エンドポイント作成
                    $documentDetailendPoint = '/document/detail/';
                    
                    // ゲストユーザに送付するURL作成
                    $guestUrl = $systemUrl . $documentDetailendPoint . "&token=" . $token;
                    
                    // 次の署名者のメールアドレス取得
                    $guestEmail = $nextSignUserInfomation->email;
                    
                    // メールタイトル作成
                    $title = "[KOT電子契約]「{$nextSignUserInfomation->title}」の署名依頼";

                    // メール本文作成
                    $Content = 
                        "{$nextSignUserInfomation->counter_party_name} {$nextSignUserInfomation->full_name} 様\n
                        {3} 様から契約書類の署名依頼が送信されました。\n
                        以下のURLをクリックして書類の詳細確認、および署名を行ってください。\n
                        {$guestUrl}\n
                        このメールにお心当たりがない場合は、誤ってメールが送信された可能性があります。\n
                        お手数ですが support@huubhr.comまでご連絡をお願い致します。";

                    //キューにパラメータを渡す

                    $queueUtility = new QueueUtility();

                    // キューパラメータを作成
                    $paramdata = [];
                    
                    $paramdata['company_id'] = $mUser['company_id'];
                    $paramdata['user_id'] = $mUser['user_id'];
                    $paramdata['form']['document_id'] = $form['document_id'];
                    $paramdata['form']['category_id'] = $form['category_id'];
                    $paramdata['form']['update_datetime'] = $form['update_datetime'];
                    $paramdata['form']['user_id'] = $mUser['user_id'];

                    // キューをJSON形式に返却
                    $param =json_encode($paramdata, JSON_UNESCAPED_UNICODE);
                    
                    // キューを書き込み
                    $ret = $queueUtility->createMessage(QueueUtility::QUEUE_NAME_SIGN, $param);



                    return true;
                }
                
                    // $token = hash("sha256", $nextSignUserInfomation->email);
                    // $dataContent['company_id'] = $nextSignUserInfomation->counter_party_id;
                    // $dataContent['category_id'] = $nextSignUserInfomation->category_id;
                    // $dataContent['document_id'] = $nextSignUserInfomation->document_id;
                    // $dataContent['user_id'] = $nextSignUserInfomation->user_id;
                    // return $this->documentSignOrderRepositoryInterface->insertToken($token, $dataContent);

                
                // ※ゲストの場合
                // メールアドレスからハッシュコードを作成し、登録する。
                return $emailHash = hash("sha256", $nextSignUserInfomation->email); // ←これがトークン
                // トークン情報を登録
                // URLを作成する(トークン付き)
                // メール呼び出し
                // 処理終了


                // メール送信キューの呼び出し
                // 処理終了
            break;
            
            case $this->docConst::DOCUMENT_DEAL:
                return 'deal';
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
    }

}

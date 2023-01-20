<?php
use App\Domain\Consts\MailConst;

$support = MailConst::MAIL_SUPPORT_ADDRESS;
$commonSubject = "[KOT電子契約]";

return [

    /*
    |--------------------------------------------------------------------------
    | 電子契約送付メール一覧
    |--------------------------------------------------------------------------
    |
    | 電子契約メールの送信内容を定義する
    |
    */

    // -------- 転送（契約書類） --------
    'forward.contract.title' => $commonSubject.'転送された「:title」の署名依頼',
    'forward.contract.content' => <<<EOF
        :toUser 様

        :fromUser 様から契約書類の署名依頼が転送されました。

        :content

        以下のURLをクリックして書類の詳細確認、および署名を行ってください。

        :url

        このメールにお心当たりがない場合は、誤ってメールが送信された可能性があります。

        お手数ですが {$support}までご連絡をお願い致します。
        EOF,

    // -------- 転送（取引書類） --------
    'forward.deal.title' => $commonSubject.'転送された「:title」の署名依頼',
    'forward.deal.content' => <<<EOF
        :toUser 様

        :fromUser 様から取引書類の署名依頼が転送されました。

        :content

        以下のURLをクリックして書類の詳細確認、および署名を行ってください。

        :url

        このメールにお心当たりがない場合は、誤ってメールが送信された可能性があります。

        お手数ですが {$support}までご連絡をお願い致します。
        EOF,

    // -------- 転送（社内書類） --------
    'forward.internal.title' => $commonSubject.'転送された「:title」の署名依頼',
    'forward.internal.content' => <<<EOF
        :toUser 様

        :fromUser 様から社内書類の署名依頼が転送されました。

        :content

        以下のURLをクリックして書類の詳細確認、および署名を行ってください。

        :url

        このメールにお心当たりがない場合は、誤ってメールが送信された可能性があります。

        お手数ですが {$support}までご連絡をお願い致します。
        EOF,

    // -------- 転送（登録書類） --------
    'forward.archive.title' => $commonSubject.'転送された「:title」の署名依頼',
    'forward.archive.content' => <<<EOF
        :toUser 様

        :fromUser 様から登録書類のタイムスタンプ付与依頼が転送されました。

        :content

        以下のURLをクリックして書類の詳細確認、および署名を行ってください。

        :url

        このメールにお心当たりがない場合は、誤ってメールが送信された可能性があります。

        お手数ですが {$support}までご連絡をお願い致します。
        EOF,

];


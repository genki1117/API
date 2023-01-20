<?php

return [

    /*
    |--------------------------------------------------------------------------
    | 電子契約送付メール一覧
    |--------------------------------------------------------------------------
    |
    | 電子契約メールの送信内容を定義する
    |
    */

    'forward.contract.title' => '[KOT電子契約]転送された「:title」の署名依頼',
    'forward.contract.content' => <<<EOF
        :toUser 様

        :fromUser 様から契約書類の署名依頼が転送されました。

        :content

        以下のURLをクリックして書類の詳細確認、および署名を行ってください。

        :url

        このメールにお心当たりがない場合は、誤ってメールが送信された可能性があります。

        お手数ですが support@huubhr.comまでご連絡をお願い致します。
        EOF,

    'forward.deal.title' => '[KOT電子契約]転送された「:title」の署名依頼',
    'forward.deal.content' => <<<EOF
        :toUser 様

        :fromUser 様から取引書類の署名依頼が転送されました。

        :content

        以下のURLをクリックして書類の詳細確認、および署名を行ってください。

        :url

        このメールにお心当たりがない場合は、誤ってメールが送信された可能性があります。

        お手数ですが support@huubhr.comまでご連絡をお願い致します。
        EOF,

    'forward.internal.title' => '[KOT電子契約]転送された「:title」の署名依頼',
    'forward.internal.content' => <<<EOF
        :toUser 様

        :fromUser 様から社内書類の署名依頼が転送されました。

        :content

        以下のURLをクリックして書類の詳細確認、および署名を行ってください。

        :url

        このメールにお心当たりがない場合は、誤ってメールが送信された可能性があります。

        お手数ですが support@huubhr.comまでご連絡をお願い致します。
        EOF,

    'forward.archive.title' => '[KOT電子契約]転送された「:title」の署名依頼',
    'forward.archive.content' => <<<EOF
        :toUser 様

        :fromUser 様から登録書類のタイムスタンプ付与依頼が転送されました。

        :content

        以下のURLをクリックして書類の詳細確認、および署名を行ってください。

        :url

        このメールにお心当たりがない場合は、誤ってメールが送信された可能性があります。

        お手数ですが support@huubhr.comまでご連絡をお願い致します。
        EOF,

];


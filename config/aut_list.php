<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    '/sample-login' => [
        'reader_role' => [								        // 閲覧者
            'admin_role' => true,								// 管理者権限
            'template_set_role' => true,						// テンプレート作成・編集権限
            'workflow set_role' => true,						// 承認経路作成権限
            'master_set_role' => true,							// マスタ作成・編集権限
            'archive_func_role' => true,						// 書類登録権限
            'ts_role' => true,									// タイムスタンプ付与権限
            'bulk_ts_role' => true,								// 一括タイムスタンプ付与権限
            'bulk_veri_func_role' => true,						// 一括検証利用権限
            'cont_doc_app_role' => true,						// 契約書類承認権限
            'deal_doc_app_role' => true,						// 取引書類承認権限
            'int_doc_app_role' => true,							// 社内書類承認権限
            'arch_doc_app_role' => true,						// 登録書類承認権限
            'use_cert_role' => true,							// 当事者型での署名権限
        ],
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => false,						        // ゲスト
    ],
                                                                

];

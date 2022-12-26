<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Lists
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the Authentication Lists for API.
    |
    */

    //TODO チケット166268にて問い合わせ中
    // システム利用ユーザ権限の「テンプレート登録権限」に該当する項目がユーザ権限マスタに見当たらない
    // (「ts_role」の事として一旦定義しておく)

    '/dashboards/get-data' => [
        'admin_role' => true,								    // 管理者権限
        'template_set_role' => true,						    // テンプレート作成・編集権限
        'workflow_set_role' => true,						    // 承認経路作成権限
        'master_set_role' => true,							    // マスタ作成・編集権限
        'archive_func_role' => true,						    // 書類登録権限
        'ts_role' => true,									    // タイムスタンプ付与権限
        'bulk_ts_role' => true,							        // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => true,						    // 一括検証利用権限
        'cont_doc_app_role' => true,						    // 契約書類承認権限
        'deal_doc_app_role' => true,						    // 取引書類承認権限
        'int_doc_app_role' => true,							    // 社内書類承認権限
        'arch_doc_app_role' => true,						    // 登録書類承認権限
        'use_cert_role' => true,							    // 当事者型での署名権限
        'reader_role' => true,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => false,						        // ゲスト
    ],

    '/documents/get-list' => [
        'admin_role' => true,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => true,						    // 書類登録権限
        'ts_role' => true,									    // タイムスタンプ付与権限
        'bulk_ts_role' => false,							    // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => false,						    // 一括検証利用権限
        'cont_doc_app_role' => true,						    // 契約書類承認権限
        'deal_doc_app_role' => true,						    // 取引書類承認権限
        'int_doc_app_role' => true,							    // 社内書類承認権限
        'arch_doc_app_role' => true,						    // 登録書類承認権限
        'use_cert_role' => true,							    // 当事者型での署名権限
        'reader_role' => true,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => false,						        // ゲスト
    ],

    '/documents/set-bulkdlcsv' => [
        'admin_role' => true,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => true,						    // 書類登録権限
        'ts_role' => true,									    // タイムスタンプ付与権限
        'bulk_ts_role' => false,							    // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => false,						    // 一括検証利用権限
        'cont_doc_app_role' => true,						    // 契約書類承認権限
        'deal_doc_app_role' => true,						    // 取引書類承認権限
        'int_doc_app_role' => true,							    // 社内書類承認権限
        'arch_doc_app_role' => true,						    // 登録書類承認権限
        'use_cert_role' => true,							    // 当事者型での署名権限
        'reader_role' => true,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => false,						        // ゲスト
    ],

    '/documents/set-bulkdlpdf' => [
        'admin_role' => true,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => true,						    // 書類登録権限
        'ts_role' => true,									    // タイムスタンプ付与権限
        'bulk_ts_role' => false,							    // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => false,						    // 一括検証利用権限
        'cont_doc_app_role' => true,						    // 契約書類承認権限
        'deal_doc_app_role' => true,						    // 取引書類承認権限
        'int_doc_app_role' => true,							    // 社内書類承認権限
        'arch_doc_app_role' => true,						    // 登録書類承認権限
        'use_cert_role' => true,							    // 当事者型での署名権限
        'reader_role' => true,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => false,						        // ゲスト
    ],

    '/documents/set-bulksign' => [
        'admin_role' => false,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => false,						    // 書類登録権限
        'ts_role' => true,									    // タイムスタンプ付与権限
        'bulk_ts_role' => false,							    // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => false,						    // 一括検証利用権限
        'cont_doc_app_role' => true,						    // 契約書類承認権限
        'deal_doc_app_role' => true,						    // 取引書類承認権限
        'int_doc_app_role' => true,							    // 社内書類承認権限
        'arch_doc_app_role' => false,						    // 登録書類承認権限
        'use_cert_role' => true,							    // 当事者型での署名権限
        'reader_role' => false,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => false,						        // ゲスト
    ],

    '/documents/save-sign' => [
        'admin_role' => false,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => true,						    // 書類登録権限
        'ts_role' => false,									    // タイムスタンプ付与権限
        'bulk_ts_role' => false,							    // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => false,						    // 一括検証利用権限
        'cont_doc_app_role' => true,						    // 契約書類承認権限
        'deal_doc_app_role' => true,						    // 取引書類承認権限
        'int_doc_app_role' => true,							    // 社内書類承認権限
        'arch_doc_app_role' => false,						    // 登録書類承認権限
        'use_cert_role' => true,							    // 当事者型での署名権限
        'reader_role' => false,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => true,						        // ゲスト
    ],

    '/documents/set-bulkts' => [
        'admin_role' => false,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => false,						    // 書類登録権限
        'ts_role' => false,									    // タイムスタンプ付与権限
        'bulk_ts_role' => false,							    // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => false,						    // 一括検証利用権限
        'cont_doc_app_role' => false,						    // 契約書類承認権限
        'deal_doc_app_role' => false,						    // 取引書類承認権限
        'int_doc_app_role' => false,							// 社内書類承認権限
        'arch_doc_app_role' => true,						    // 登録書類承認権限
        'use_cert_role' => false,							    // 当事者型での署名権限
        'reader_role' => false,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => false,						        // ゲスト
    ],

    '/documents/save-ts' => [
        'admin_role' => false,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => true,						    // 書類登録権限
        'ts_role' => false,									    // タイムスタンプ付与権限
        'bulk_ts_role' => false,							    // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => false,						    // 一括検証利用権限
        'cont_doc_app_role' => false,						    // 契約書類承認権限
        'deal_doc_app_role' => false,						    // 取引書類承認権限
        'int_doc_app_role' => false,							// 社内書類承認権限
        'arch_doc_app_role' => true,						    // 登録書類承認権限
        'use_cert_role' => false,							    // 当事者型での署名権限
        'reader_role' => false,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => false,						        // ゲスト
    ],

    '/documents/get-detail' => [
        'admin_role' => true,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => true,						    // 書類登録権限
        'ts_role' => true,									    // タイムスタンプ付与権限
        'bulk_ts_role' => false,							    // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => false,						    // 一括検証利用権限
        'cont_doc_app_role' => true,						    // 契約書類承認権限
        'deal_doc_app_role' => true,						    // 取引書類承認権限
        'int_doc_app_role' => true,							    // 社内書類承認権限
        'arch_doc_app_role' => true,						    // 登録書類承認権限
        'use_cert_role' => true,							    // 当事者型での署名権限
        'reader_role' => true,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => true,						        // ゲスト
    ],

    '/documents/save-order' => [
        'admin_role' => true,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => true,						    // 書類登録権限
        'ts_role' => false,									    // タイムスタンプ付与権限
        'bulk_ts_role' => false,							    // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => false,						    // 一括検証利用権限
        'cont_doc_app_role' => false,						    // 契約書類承認権限
        'deal_doc_app_role' => false,						    // 取引書類承認権限
        'int_doc_app_role' => false,							// 社内書類承認権限
        'arch_doc_app_role' => false,						    // 登録書類承認権限
        'use_cert_role' => false,							    // 当事者型での署名権限
        'reader_role' => false,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => false,						        // ゲスト
    ],

    '/documents/set-sendbackmail' => [
        'admin_role' => true,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => true,						    // 書類登録権限
        'ts_role' => true,									    // タイムスタンプ付与権限
        'bulk_ts_role' => false,							    // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => false,						    // 一括検証利用権限
        'cont_doc_app_role' => true,						    // 契約書類承認権限
        'deal_doc_app_role' => true,						    // 取引書類承認権限
        'int_doc_app_role' => true,							    // 社内書類承認権限
        'arch_doc_app_role' => true,						    // 登録書類承認権限
        'use_cert_role' => true,							    // 当事者型での署名権限
        'reader_role' => true,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => false,						        // ゲスト
    ],

    '/documents/set-sendforwardmail' => [
        'admin_role' => false,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => true,						    // 書類登録権限
        'ts_role' => false,									    // タイムスタンプ付与権限
        'bulk_ts_role' => false,							    // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => false,						    // 一括検証利用権限
        'cont_doc_app_role' => true,						    // 契約書類承認権限
        'deal_doc_app_role' => true,						    // 取引書類承認権限
        'int_doc_app_role' => true,							    // 社内書類承認権限
        'arch_doc_app_role' => true,						    // 登録書類承認権限
        'use_cert_role' => true,							    // 当事者型での署名権限
        'reader_role' => false,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => true,						        // ゲスト
    ],

    '/documents/set-back' => [
        'admin_role' => false,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => true,						    // 書類登録権限
        'ts_role' => false,									    // タイムスタンプ付与権限
        'bulk_ts_role' => false,							    // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => false,						    // 一括検証利用権限
        'cont_doc_app_role' => true,						    // 契約書類承認権限
        'deal_doc_app_role' => true,						    // 取引書類承認権限
        'int_doc_app_role' => true,							    // 社内書類承認権限
        'arch_doc_app_role' => true,						    // 登録書類承認権限
        'use_cert_role' => true,							    // 当事者型での署名権限
        'reader_role' => false,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => true,						        // ゲスト
    ],

    '/documents/delete' => [
        'admin_role' => true,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => true,						    // 書類登録権限
        'ts_role' => true,									    // タイムスタンプ付与権限
        'bulk_ts_role' => false,							    // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => false,						    // 一括検証利用権限
        'cont_doc_app_role' => false,						    // 契約書類承認権限
        'deal_doc_app_role' => false,						    // 取引書類承認権限
        'int_doc_app_role' => false,							// 社内書類承認権限
        'arch_doc_app_role' => false,						    // 登録書類承認権限
        'use_cert_role' => false,							    // 当事者型での署名権限
        'reader_role' => false,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => false,						        // ゲスト
    ],

    '/documents/save' => [
        'admin_role' => true,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => true,						    // 書類登録権限
        'ts_role' => false,									    // タイムスタンプ付与権限
        'bulk_ts_role' => false,							    // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => false,						    // 一括検証利用権限
        'cont_doc_app_role' => false,						    // 契約書類承認権限
        'deal_doc_app_role' => false,						    // 取引書類承認権限
        'int_doc_app_role' => false,							// 社内書類承認権限
        'arch_doc_app_role' => false,						    // 登録書類承認権限
        'use_cert_role' => false,							    // 当事者型での署名権限
        'reader_role' => false,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => false,						        // ゲスト
    ],

    '/documents/up-word' => [
        'admin_role' => true,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => true,						    // 書類登録権限
        'ts_role' => false,									    // タイムスタンプ付与権限
        'bulk_ts_role' => false,							    // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => false,						    // 一括検証利用権限
        'cont_doc_app_role' => false,						    // 契約書類承認権限
        'deal_doc_app_role' => false,						    // 取引書類承認権限
        'int_doc_app_role' => false,							// 社内書類承認権限
        'arch_doc_app_role' => false,						    // 登録書類承認権限
        'use_cert_role' => false,							    // 当事者型での署名権限
        'reader_role' => false,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => false,						        // ゲスト
    ],

    '/documents/dl-tempcsv' => [
        'admin_role' => true,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => true,						    // 書類登録権限
        'ts_role' => false,									    // タイムスタンプ付与権限
        'bulk_ts_role' => false,							    // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => false,						    // 一括検証利用権限
        'cont_doc_app_role' => false,						    // 契約書類承認権限
        'deal_doc_app_role' => false,						    // 取引書類承認権限
        'int_doc_app_role' => false,							// 社内書類承認権限
        'arch_doc_app_role' => false,						    // 登録書類承認権限
        'use_cert_role' => false,							    // 当事者型での署名権限
        'reader_role' => false,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => false,						        // ゲスト
    ],

    '/documents/set-savebulk' => [
        'admin_role' => true,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => true,						    // 書類登録権限
        'ts_role' => false,									    // タイムスタンプ付与権限
        'bulk_ts_role' => false,							    // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => false,						    // 一括検証利用権限
        'cont_doc_app_role' => false,						    // 契約書類承認権限
        'deal_doc_app_role' => false,						    // 取引書類承認権限
        'int_doc_app_role' => false,							// 社内書類承認権限
        'arch_doc_app_role' => false,						    // 登録書類承認権限
        'use_cert_role' => false,							    // 当事者型での署名権限
        'reader_role' => false,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => false,						        // ゲスト
    ],

    '/validations/set-bulkvaridation' => [
        'admin_role' => false,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => false,						    // 書類登録権限
        'ts_role' => false,									    // タイムスタンプ付与権限
        'bulk_ts_role' => false,							    // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => true,						    // 一括検証利用権限
        'cont_doc_app_role' => false,						    // 契約書類承認権限
        'deal_doc_app_role' => false,						    // 取引書類承認権限
        'int_doc_app_role' => false,							// 社内書類承認権限
        'arch_doc_app_role' => false,						    // 登録書類承認権限
        'use_cert_role' => false,							    // 当事者型での署名権限
        'reader_role' => false,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => false,						        // ゲスト
    ],

    '/validations/get-list' => [
        'admin_role' => false,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => false,						    // 書類登録権限
        'ts_role' => false,									    // タイムスタンプ付与権限
        'bulk_ts_role' => false,							    // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => true,						    // 一括検証利用権限
        'cont_doc_app_role' => false,						    // 契約書類承認権限
        'deal_doc_app_role' => false,						    // 取引書類承認権限
        'int_doc_app_role' => false,							// 社内書類承認権限
        'arch_doc_app_role' => false,						    // 登録書類承認権限
        'use_cert_role' => false,							    // 当事者型での署名権限
        'reader_role' => false,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => false,						        // ゲスト
    ],

    '/validations/get-detail' => [
        'admin_role' => false,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => false,						    // 書類登録権限
        'ts_role' => false,									    // タイムスタンプ付与権限
        'bulk_ts_role' => false,							    // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => true,						    // 一括検証利用権限
        'cont_doc_app_role' => false,						    // 契約書類承認権限
        'deal_doc_app_role' => false,						    // 取引書類承認権限
        'int_doc_app_role' => false,							// 社内書類承認権限
        'arch_doc_app_role' => false,						    // 登録書類承認権限
        'use_cert_role' => false,							    // 当事者型での署名権限
        'reader_role' => false,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => false,						        // ゲスト
    ],

    '/validations/set-dlcsv' => [
        'admin_role' => false,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => false,						    // 書類登録権限
        'ts_role' => false,									    // タイムスタンプ付与権限
        'bulk_ts_role' => false,							    // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => true,						    // 一括検証利用権限
        'cont_doc_app_role' => false,						    // 契約書類承認権限
        'deal_doc_app_role' => false,						    // 取引書類承認権限
        'int_doc_app_role' => false,							// 社内書類承認権限
        'arch_doc_app_role' => false,						    // 登録書類承認権限
        'use_cert_role' => false,							    // 当事者型での署名権限
        'reader_role' => false,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => false,						        // ゲスト
    ],

    '/masters/get-searchdata' => [
        'admin_role' => true,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => true,						    // 書類登録権限
        'ts_role' => false,									    // タイムスタンプ付与権限
        'bulk_ts_role' => true,							        // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => true,						    // 一括検証利用権限
        'cont_doc_app_role' => true,						    // 契約書類承認権限
        'deal_doc_app_role' => true,						    // 取引書類承認権限
        'int_doc_app_role' => true,							    // 社内書類承認権限
        'arch_doc_app_role' => true,						    // 登録書類承認権限
        'use_cert_role' => true,							    // 当事者型での署名権限
        'reader_role' => true,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => false,						        // ゲスト
    ],

    '/files/dl-data' => [
        'admin_role' => true,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => false,						    // 書類登録権限
        'ts_role' => false,									    // タイムスタンプ付与権限
        'bulk_ts_role' => false,							    // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => false,						    // 一括検証利用権限
        'cont_doc_app_role' => false,						    // 契約書類承認権限
        'deal_doc_app_role' => false,						    // 取引書類承認権限
        'int_doc_app_role' => false,							// 社内書類承認権限
        'arch_doc_app_role' => false,						    // 登録書類承認権限
        'use_cert_role' => false,							    // 当事者型での署名権限
        'reader_role' => false,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => false,						        // ゲスト
    ],

    '/files/get-dllist' => [
        'admin_role' => true,								    // 管理者権限
        'template_set_role' => false,						    // テンプレート作成・編集権限
        'workflow_set_role' => false,						    // 承認経路作成権限
        'master_set_role' => false,							    // マスタ作成・編集権限
        'archive_func_role' => false,						    // 書類登録権限
        'ts_role' => false,									    // タイムスタンプ付与権限
        'bulk_ts_role' => false,							    // 一括タイムスタンプ付与権限
        'bulk_veri_func_role' => false,						    // 一括検証利用権限
        'cont_doc_app_role' => false,						    // 契約書類承認権限
        'deal_doc_app_role' => false,						    // 取引書類承認権限
        'int_doc_app_role' => false,							// 社内書類承認権限
        'arch_doc_app_role' => false,						    // 登録書類承認権限
        'use_cert_role' => false,							    // 当事者型での署名権限
        'reader_role' => false,					                // 閲覧者
        'applicant_role' => false,							    // 申込者
        'system_administrator_role' => false,                   // システム管理者
        'guest_user_role' => false,						        // ゲスト
    ],
];

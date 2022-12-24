# Xdebugの利用方法について

## 概要
laravel sailでは初めからXdebugが組み込まれているため、  
そちらを利用して、デバックできるようにする。

## セットアップ
 - .envに下記パラメータが存在しない場合は追加する。
 > SAIL_XDEBUG_MODE=develop,debug,coverage

 - xcodeの設定を行う  
下記記事を参照※後ほど内容をまとめますが、取り急ぎです。   
https://chigusa-web.com/blog/laravel-sail-xdebug/

## 利用方法
#### デバック開始
URLに下記パラメータを付与して、APIリクエストをする
> ?XDEBUG_SESSION_START=session_name

#### デバック終了
下記パラメータを付与して、リクエストを実施するかvscode側で切断する
> ?XDEBUG_SESSION_STOP=session_name

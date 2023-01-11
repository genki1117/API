# 環境構築について
## 初回セットアップ
以下コマンドを順番に実行する。
※dockerはPCにインストール済み
※プロジェクト配下にいること

#### composer installができる場合
##### [ローカル]
 - cp .env.develop .env (envファイルの内容は、バックエンドチャネル>(PHP).env情報)
 - composer install
 - ./vendor/bin/sail up or cd ./vendor/bin sail up
   └コンテナ構築のログが流れる  
   └ログの流れが止まったら、構築完了  
   └docker-compose psコマンドを実行して、STATUSを確認し、RUNNINGになっていればOK  
 - ./vendor/bin/sail php artisan key:generate

#### composer installができない場合
##### [ローカル]
 - cp .env.develop .env
 - docker-compose up  
  └コンテナ構築のログが流れる  
  └ログの流れが止まったら、構築完了  
  └docker-compose psコマンドを実行して、STATUSを確認し、コンテナのステータスがすべてRUNNINGになっていればOK
 - docker-compose exec laravel.test bash  
  └dockerコンテナに移動

##### [dockerコンテナ]
 - composer install
 - php artisan key:generate
dockerコンテナを抜ける
 
上記コマンドすべてを実行完了することで、環境構築完了

## 環境立ち上げ
#### ./vendor/bin/sailが使える場合
- ./vendor/bin/sail up or cd ./vendor/bin sail up

#### ./vendor/bin/sailが使えない場合
- docker-compose up

## DB接続情報

ホスト：127.0.0.1  
ポート：3306  
ユーザー：user  
パスワード：password  
初期時laravelという名のDBが作成されている。

## サンプルコード確認
### サンプルコードを実行するためのテーブル追加

##### [sql文から取り込む場合]
テーブル作成：バックエンドチャネル>SQL>create_table.sql<br>
初期データインサート文：バックエンドチャネル>SQL>init_data.sql

##### [dockerコンテナ]
 - php artisan migrate:fresh --seed  
   DBにサンプル用のテーブルが追加される

### サンプルコード
#### ログインサンプル
Request Url  
  http://localhost/sample-login

### レスポンスパターン

チケット参照(https://redmine.shibuyalabo.com/issues/165934)

# Q&A
### クリーンインストールするには？
下記コマンドを実行
 - docker-compose down
 - docker volume ls  
 - docker volume rm {docker volume lsで確認したvolume name}
   下記volume nameを削除する
   ・{プロジェクトフォルダ名}_sail-minio  
   ・{プロジェクトフォルダ名}_sail-mysql  
   ・{プロジェクトフォルダ名}_sail-redis

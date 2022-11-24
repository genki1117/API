# コード整形について

## 概要
プロジェクトコードはpsr-1, psr-2に準拠させているが、  
整形に時間をかけるのはもったいないため、php-cs-fixerを導入し、  
自動整形できるようにする

### インストール方法
下記コマンドを実行
> composer install

### 実行方法

##### コード整形
> composer run cs-fixer:fix

もしくは

> ./vendor/bin/php-cs-fixer fix

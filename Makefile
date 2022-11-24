# コンテナの起動
up:
		./vendor/bin/sail up

# コンテナをバックグラウンドで起動
d:
		./vendor/bin/sail up -d

# コンテナの停止
down:
		./vendor/bin/sail down

# コンテナが起動しているか確認
ps:
		./vendor/bin/sail ps

# コンテナへの接続
shell:
		./vendor/bin/sail shell

#PHPのバージョン確認
php:
		./vendor/bin/sail php -v

#Nodeのバージョン確認
node:
		./vendor/bin/sail node -v

#Composerのバージョン確認
composer:
		./vendor/bin/sail composer -v

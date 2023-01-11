# 単体テストコードについて

### テスト対象
___
- サービス
- リポジトリ
- リクエスト（バリデーション)

### 観点
___
ServicesとRepositoriesについては、最低限、全パス網羅しているテストを作成すること。<br>
Requestsは全てのバリデーションのチェックができていること。

最終的には、以下の状態になっていること<br>
１：phpunitの実行結果が全てOKになっていること<br>
２：ServicesとRepositoriesソースのカバレッジが１００％になっていること。<br>

### 単体テストの実行方法
___
```
./vendor/bin/sail phpunit<br>
```

↓カバレッジを確認するときは、以下のようにオプションを追加して実行<br>
./vendor/bin/sail phpunit --color=always --coverage-html="storage/logs/coverage"<br>
※storage/logs/coverage<br>に結果が出力されるので、ServicesとRepositoriesのソースが100%になっているかどうかを確認できる。

### privateメソッドのテストについて
___
原則として、コール元（public)メソッドのテストケースにて、全パス網羅できるようにする。
※どうしても上記が困難な場合には、privateメソッド自体のテストケースを作成して、全パスを通す
→ただし、普通にやるとprivateメソッドにはアクセスできないので、ReflectionMethodでの対応が必要

こんなイメージです

~~~php
-----------------------
$class = $this->getObject();
$method = new \ReflectionMethod($class, 'sendMailQue');
$method->setAccessible(true);
$this->assertEquals($method->invokeArgs($class, ['aaa', 'bbb', 'ccc']), -1);
-----------------------
~~~

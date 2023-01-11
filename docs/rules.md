# 実装ルール

### 定数定義について
---
app/Domain/Consts<br>
上記にプログラムで必要になりそうな定義を追加しておりますので、<br>
個別のクラスでconstを切っている方や、数値などで直接判定している方は、<br>
こちらを参照するように修正をお願いします。

### ログインユーザの取得方法について
---

LoggedInUserContextをControllerのコンストラクタで渡して、取得できるようになっております。<br>
※ただし、現在は仮処理（AzureAD認証の実装ができるまで）のため、固定のユーザしか返ってきません。<br>

```
<AuthorizationToken.php>
$user = $this->loginUserRepositoryInterface->getUser(compnayId:"1", email:"host1@email.com");
```

動作確認の際に都合が悪ければ、上記のgetUserの引数を書き換えて、確認いただければと思います。<br>

### DBアクセスクラスのメソッドについて
---

app/Accessers/DBクラスのinsertやupdate関数について。<br>
・insertやupdate処理については、各カラムを配列にまとめて、引数で渡す。<br>
・updateについては、キーは別途引数で渡す。<br>

※単純なinsertやupdate処理は原則この実装で、もしAPI固有のややこしい処理があるのでれば、
それは別関数で個別対応

~~~php
--------------------------
例
【レコード追加】
 (呼ぶ側)
 $data = [
 "company_id" => $companyId,
 "category_id" => $categoryId,
 "app_user_id" => $appUserId,
 "app_status" => 0,
 "create_user" => $opeUserId,
 "create_datetime" => CarbonImmutable::now(),
 "update_user" => $opeUserId,
 "update_datetime" => CarbonImmutable::now(),
 ];
 XXXXX->insert($data);
 
 (呼ばれる側)
 public function insert(array $data): bool
 {
 return $this->builder()->insert($data);
 }
 
【レコード更新】
 (呼ぶ側)
 $data = [
 "company_id" => $companyId,
 "category_id" => $categoryId,
 "app_status" => 2,
 "update_user" => $opeUserId,
 "update_datetime" => CarbonImmutable::now(),
 ];
 XXXXX->update($data);
 
 (呼ばれる側)
 public function update(int $companyId = null, int $categoryId = null, array $data): int
 {
 return $this->builder($this->table)
 ->whereNull("delete_datetime")
 ->when(!empty($companyId), function ($query) use ($companyId) {
 return $query->where('company_id', '=', $companyId);
 })
 ->when(!empty($categoryId), function ($query) use ($categoryId) {
 return $query->where('category_id', '=', $categoryId);
 })
 ->update($data);
 }
--------------------------
~~~

# DTG-API
電子契約プロジェクト<br>
契約書類、取引書類、社内書類、登録書類の4種類の書類をPDF保存。<br>
書類の署名、検証を行いセコムAPIでタイムスタンプを付与する。<br>


<br>

## #164344 csvテンプレートダウンロード
・Controller
>[app/Http/Controllers/Document/DocumentBulkCreateController.php](https://github.com/genki1117/DTG-API/blob/feature/%23164344/app/Http/Controllers/Document/DocumentBulkCreateController.php)
<br>

・Service
>[app/Domain/Services/Document/DocumentDownloadCsvService.php](https://github.com/genki1117/DTG-API/blob/feature/%23164344/app/Domain/Services/Document/DocumentDownloadCsvService.php)<br>

<br>

## 164338 署名依頼メール送信
・Controller
>[app/Http/Controllers/Document/DocumentSignOrderController.php](https://github.com/genki1117/DTG-API/blob/feature/%23164338/app/Http/Controllers/Document/DocumentSignOrderController.php)
<br>

・Service
>[app/Domain/Services/Document/DocumentSignOrderService.php](https://github.com/genki1117/DTG-API/blob/feature/%23164338/app/Domain/Services/Document/DocumentSignOrderService.php)
<br>

・Interface
>[app/Domain/Repositories/Interface/Document/DocumentSignOrderRepositoryInterface.php](https://github.com/genki1117/DTG-API/blob/feature/%23164338/app/Domain/Repositories/Interface/Document/DocumentSignOrderRepositoryInterface.php)
<br>

・Repository
>[app/Domain/Repositories/Document/DocumentSignOrderRepository.php](https://github.com/genki1117/DTG-API/blob/feature/%23164338/app/Domain/Repositories/Document/DocumentSignOrderRepository.php)
<br>

・Accesser
> [app/Accessers/DB/Document/DocumentArchive.php](https://github.com/genki1117/DTG-API/blob/feature/%23164338/app/Accessers/DB/Document/DocumentArchive.php)
<br>

・Entity
>[app/Domain/Entities/Document/DocumentSignOrder.php](https://github.com/genki1117/DTG-API/blob/feature/%23164338/app/Domain/Entities/Document/DocumentSignOrder.php)
<br>

・Test
>・[tests/Unit/Repository/Document/DocumentSignOrderRepositoryTest.php](https://github.com/genki1117/DTG-API/blob/feature/%23164338/tests/Unit/Repository/Document/DocumentSignOrderRepositoryTest.php)<br>
>・[tests/Unit/Request/Document/DocumentSignOrderRequestTest.php](https://github.com/genki1117/DTG-API/blob/feature/%23164338/tests/Unit/Request/Document/DocumentSignOrderRequestTest.php)<br>
>・[tests/Unit/Service/Document/DocumentSignOrderServiceTest.php](https://github.com/genki1117/DTG-API/blob/feature/%23164338/tests/Unit/Service/Document/DocumentSignOrderServiceTest.php)

<br>

## 164335 書類保存
・Controller
>[app/Http/Controllers/Document/DocumentSaveController.php](https://github.com/genki1117/DTG-API/blob/feature/%23164335/app/Http/Controllers/Document/DocumentSaveController.php)
<br>

・Service
>[app/Domain/Services/Document/DocumentSaveService.php](https://github.com/genki1117/DTG-API/blob/feature/%23164335/app/Domain/Services/Document/DocumentSaveService.php)
<br>

・Interface
>[app/Domain/Repositories/Interface/Document/DocumentSaveRepositoryInterface.php](https://github.com/genki1117/DTG-API/blob/feature/%23164335/app/Domain/Repositories/Interface/Document/DocumentSaveRepositoryInterface.php)
<br>

・Repository
>[app/Domain/Repositories/Document/DocumentSaveRepository.php](https://github.com/genki1117/DTG-API/blob/feature/%23164335/app/Domain/Repositories/Document/DocumentSaveRepository.php)
<br>

・Accesser
> [app/Accessers/DB/Document/DocumentArchive.php](https://github.com/genki1117/DTG-API/blob/feature/%23164338/app/Accessers/DB/Document/DocumentArchive.php)
<br>

・Entity
>[app/Domain/Entities/Document/DocumentUpdate.php](https://github.com/genki1117/DTG-API/blob/feature/%23164335/app/Domain/Entities/Document/DocumentUpdate.php)
<br>

・Response
>[app/Http/Responses/Document/DocumentSaveResponse.php](https://github.com/genki1117/DTG-API/blob/feature/%23164335/app/Http/Responses/Document/DocumentSaveResponse.php)
<br>

・Tests
> [tests/Unit/Repository/Document/DocumentSaveRepositoryTest.php](https://github.com/genki1117/DTG-API/blob/feature/%23164335/tests/Unit/Repository/Document/DocumentSaveRepositoryTest.php)<br>
> [tests/Unit/Request/Document/DcoumentSaveRequestTest.php](https://github.com/genki1117/DTG-API/blob/feature/%23164335/tests/Unit/Request/Document/DcoumentSaveRequestTest.php)<br>
> [tests/Unit/Service/Document/DocumentSaveServiceTest.php](https://github.com/genki1117/DTG-API/blob/feature/%23164335/tests/Unit/Service/Document/DocumentSaveServiceTest.php)

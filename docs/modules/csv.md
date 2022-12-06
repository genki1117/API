## 利用方法
### Method
|method|args|type|required|note|
|:---:|:---:|:---:|:---:|:---:|
|read|filePath|string|required||
||option|array|optional|read About Option|
|write|csvPath|string|required||
||contents|array|required||
|changeCharacterCode|type|int|required|read About changeCharacterCode|

#### About Contents
set contents
```php
[
    ['aaa', 'bbb', 'ccc'],
    ['AAA', 'BBB', 'CCC']
]
```
write csv is
```text
"aaa","bbb","ccc",
"AAA","BBB","CCC"

```

#### About Option
Override and use default parameters.
default option
```php
[
    'read_flag'   => 'full',
    'header_flag' => true
]
```
#### About changeCharacterCode
This needs to be called before read or write method.
```php
$this->changeCharacterCode(CsvOperation::CONVERT_SJIS_WIN_TO_UTF8)// sjis-win to utf-8
$this->changeCharacterCode(CsvOperation::CONVERT_UTF8_TO_SJIS_WIN)// utf-8 to sjis-win
```

### Response data
|method|type|note|
|:---:|:---:|:---:|
|read|ReadCsv|See ReadCsv|
|write|bool|true writes successfully, false fails |

#### About ReadCsv
|method|type|note|
|:---:|:---:|:---:|
|getHeader|HeaderCollection||
|getContetns|ContentsCollection||


### Sample
```php
<?php

private $csvFileManager;

public function __construct(CsvFileManager $csvFileManager)
{
    $this->csvFileManager = $csvFileManager;
}

public function readCsvSample()
{
    $sampleCsvData = $this->csvFileManager->read($filePath, $option);
    //get csv header
    $sampleCsvData->getHeader();//return array
    //get csv contents
    $sampleCsvData->getContents();//return array
}

public function writeCsvSample()
{
    //return true writes successfully, false fails
    $this->csvFileManager->write($filePath, $contents);
}

```


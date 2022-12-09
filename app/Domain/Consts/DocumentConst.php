<?php
declare(strict_types=1);
namespace App\Domain\Consts;

class DocumentConst
{
    /** @var int */
    const DOCUMENT_CONTRACT = 1;
    
    /** @var string */
    const DOCUMENT_CONTRACT_NAME = '契約書類';
    
    /** @var int */
    const DOCUMENT_DEAL = 2;
    
    /** @var string */
    const DOCUMENT_DEAL_NAME = '取引書類';
    
    /** @var int */
    const DOCUMENT_INTERNAL = 3;
    
    /** @var string */
    const DOCUMENT_INTERNAL_NAME = '社員書類';
    
    /** @var int */
    const DOCUMENT_ARCHIVE = 4;
    
    /** @var string */
    const DOCUMENT_ARCHIVE_NAME = '登録書類';

    const DOCUMENT_TYPE_LIST = [
        self::DOCUMENT_CONTRACT => self::DOCUMENT_CONTRACT_NAME,
        self::DOCUMENT_DEAL => self::DOCUMENT_DEAL_NAME,
        self::DOCUMENT_ARCHIVE => self::DOCUMENT_INTERNAL_NAME,
        self::DOCUMENT_ARCHIVE => self::DOCUMENT_ARCHIVE_NAME
    ];
}
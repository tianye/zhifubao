<?php
namespace AliSdk\Core;

use AliSdk\Exception\AliSdkException;

class Core
{
    function __construct($http_config = [])
    {
        try {
            if (!isset(Config::$appid)) {
                throw new AliSdkException('未初始化CONFIG');
            }
        } catch (AliSdkException $e) {
            exit($e->getMessage());
        }
    }

    /**
     *  校验$value是否非空
     *
     *  if not set ,return true;
     *  if is null , return true;
     **/
    protected function checkEmpty($value)
    {
        if (!isset($value)) {
            return true;
        }
        if ($value === null) {
            return true;
        }
        if (trim($value) === "") {
            return true;
        }

        return false;
    }

}
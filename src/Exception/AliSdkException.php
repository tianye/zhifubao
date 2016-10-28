<?php
namespace AliSdk\Exception;

class AliSdkException extends \Exception
{
    public function __construct($message = '')
    {
        parent::__construct($message);
    }
}
<?php
namespace AliSdk\Core;

use AliSdk\Exception\AliSdkException;

class Config
{
    static $appid;

    static $http; // HTTP

    static $rsaPrivateKeyFilePath;
    static $rsaPublicKeyFilePath;

    //私钥值
    static $rsaPrivateKey;
    static $rsaPublicKey;

    // 表单提交字符集编码
    static $postCharset = 'UTF-8';
    static $fileCharset = 'UTF-8';

    static $URL = 'https://openapi.alipay.com/gateway.do';

    static $charset   = 'UTF-8';
    static $sign_type = 'RSA';
    static $version   = '1.0';

    static function init($appid = '', $rsaPrivateKeyFilePath = '', $rsaPublicKeyFilePath = '', $rsaPrivateKey = '', $rsaPublicKey = '', $url = 'https://openapi.alipay.com/gateway.do')
    {

        self::$appid = $appid;

        self::$rsaPrivateKeyFilePath = $rsaPrivateKeyFilePath;
        self::$rsaPublicKeyFilePath  = $rsaPublicKeyFilePath;
        self::$rsaPrivateKey         = $rsaPrivateKey;
        self::$rsaPublicKey          = $rsaPublicKey;

        try {
            if (empty(self::$appid)) {
                throw new AliSdkException('必须设置APPID');
            }

            if (empty(self::$rsaPrivateKeyFilePath) && self::$rsaPrivateKeyFilePath == self::$rsaPrivateKey) {
                throw new AliSdkException('必须传入私钥');
            }

            if (empty(self::$rsaPublicKeyFilePath) && self::$rsaPublicKeyFilePath == self::$rsaPublicKey) {
                throw new AliSdkException('必须传入公钥');
            }
        } catch (AliSdkException $e) {
            return false;
        }
    }
}
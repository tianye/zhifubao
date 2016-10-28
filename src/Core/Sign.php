<?php

namespace AliSdk\Core;

use AliSdk\Exception\AliSdkException;

class Sign extends Core
{
    /**
     * 生成签名
     *
     * @param        $params
     * @param string $signType
     *
     * @return string
     */
    public function generateSign($params, $signType = "RSA")
    {
        return $this->sign($this->getSignContent($params), $signType);
    }

    /**
     * 验证签名
     *
     * @param        $data
     * @param        $sign
     * @param string $signType
     *
     * @return bool
     */
    public function verifySign($data, $sign, $signType = 'RSA')
    {
        return $this->verify($data, $sign, $signType);
    }

    /**
     * 签名验证
     *
     * @param        $data
     * @param        $sign
     * @param string $signType
     *
     * @return bool
     */
    private function verify($data, $sign, $signType = 'RSA')
    {
        if ($this->checkEmpty(Config::$rsaPublicKeyFilePath)) {

            $pubKey = Config::$rsaPublicKey;
            $res    = "-----BEGIN PUBLIC KEY-----\n" .
                wordwrap($pubKey, 64, "\n", true) .
                "\n-----END PUBLIC KEY-----";
        } else {

            try {
                if (file_exists(Config::$rsaPrivateKeyFilePath)) {
                    $pubKey = file_get_contents(Config::$rsaPublicKeyFilePath);
                    $res    = openssl_get_publickey($pubKey);
                } else {
                    throw new AliSdkException('请检查公钥路径是否存在');
                }
            } catch (AliSdkException $e) {
                exit($e->getMessage());
            }
        }

        try {
            if (!$res) {
                throw new AliSdkException('支付宝RSA公钥错误。请检查公钥文件格式是否正确');
            }
        } catch (AliSdkException $e) {
            return false;
        }

        //调用openssl内置方法验签，返回bool值

        if ("RSA2" == $signType) {
            $result = (bool) openssl_verify($data, base64_decode($sign), $res, OPENSSL_ALGO_SHA256);
        } else {
            $result = (bool) openssl_verify($data, base64_decode($sign), $res);
        }

        if (!$this->checkEmpty(Config::$rsaPublicKeyFilePath)) {
            //释放资源
            openssl_free_key($res);
        }

        return $result;
    }

    /**
     * 获取预签名参数
     *
     * @param $params
     *
     * @return string
     */
    private function getSignContent($params)
    {
        ksort($params);

        $stringToBeSigned = "";
        $i                = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {

                // 转换成目标字符集
                $v = $this->characet($v, Config::$postCharset);

                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }

        unset ($k, $v);

        return $stringToBeSigned;
    }

    /**
     * 签名
     *
     * @param        $data
     * @param string $signType
     *
     * @return string
     * @throws \AliSdk\Exception\AliSdkException
     */
    private function sign($data, $signType = "RSA")
    {
        if ($this->checkEmpty(Config::$rsaPrivateKeyFilePath)) {
            $priKey = Config::$rsaPrivateKey;
            $res    = "-----BEGIN RSA PRIVATE KEY-----\n" .
                wordwrap($priKey, 64, "\n", true) .
                "\n-----END RSA PRIVATE KEY-----";
        } else {
            try {
                if (file_exists(Config::$rsaPrivateKeyFilePath)) {
                    $priKey = file_get_contents(Config::$rsaPrivateKeyFilePath);
                    $res    = openssl_get_privatekey($priKey);
                } else {
                    throw new AliSdkException('请检查 私钥路径 是否存在');
                }
            } catch (AliSdkException $e) {
                exit($e->getMessage());
            }
        }

        try {
            if (!$res) {
                throw new AliSdkException('您使用的私钥格式错误，请检查RSA私钥配置');
            }
        } catch (AliSdkException $e) {
            exit($e->getMessage());
        }

        if ("RSA2" == $signType) {
            openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
        } else {
            openssl_sign($data, $sign, $res);
        }

        if (!$this->checkEmpty(Config::$rsaPrivateKeyFilePath)) {
            openssl_free_key($res);
        }
        $sign = base64_encode($sign);

        return $sign;
    }

    /**
     * 转换字符集编码
     *
     * @param $data
     * @param $targetCharset
     *
     * @return string
     */
    private function characet($data, $targetCharset)
    {

        if (!empty($data)) {
            $fileType = Config::$fileCharset;
            if (strcasecmp($fileType, $targetCharset) != 0) {

                $data = mb_convert_encoding($data, $targetCharset);
            }
        }

        return $data;
    }

}
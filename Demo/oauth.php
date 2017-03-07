<?php
require '../vendor/autoload.php';

/** 两种传入 公钥私钥的方式都可以 1.为文件方式 2.直接读取内容 */

/** appid 服务窗id , openssl生成的私钥 公钥要在ali 后台设置  , 阿里生成的公钥 要下载下来 生成pem 文件 */
\AliSdk\Core\Config::init('2016010401061932', '../Key/rsa_private_key_pkcs8.pem', '../Key/rsa_public_ali_key.pem');

/** 这个是直接把证书里面的 公钥 私钥 拿出来用 两种初始化方式选择其中一个使用即可*/

//\AliSdk\Core\Config::init('2016010401061932', '', '', 'MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBANnGHhgzd/9AjXKx
//HDbfCdyM12zqOXpWPjQ/enTJckMHUNZGkKmjB1J3gWrKF3UhMH4Hrs1+KnWh87g/
//HxeVlbmq6IDWAE8bcOlr05uL0rO9aR/xsoQpaatb3QDE12XIJiZOPeFrPa7PHxhD
//XohGqfZHB/XuWFBICZpKw88D7chlAgMBAAECgYEAt5xq00zR58ytdf4OI9V5oxK8
//r3/sZlFAsRy0SrNuO0V3yrJEVkK2cbmAzGEH1iHdmOUxZODzO6sIqlfOBzx/tzSM
//qObVWvqEiCuSVyWIqJaZqqsRiAJZ6MAwmTVYOevV92HdzeTYl7n1qmmClVkgQcOm
//gHU01PpyHPvy3L9OugECQQD7SuOaT6UGgWkG9N67Qm6yxOGKEtdQj1Pa3kQPG9DK
//5pHjJ9EYmYY7RwYhyR2CLfBQEKohhkp+DKvEgUE8xjN1AkEA3dp8HnRtXy08ygF0
//K45jXPysF4JhZ3c3/WmC+VSfPLPCkEY196gQ60Cui2C3seO27S4Os6EpjxkHXb/5
//gzNTMQJATQs33u6+PNFeXwiiZS1H/T1JnOiL5SIcZoUwvqUbjanFXqyteepP8kqj
//QaaEio4FGLcTQjYHDsBZxWSPmM93pQJAG967ovPLXZ6IOXRPTL15fA/96oIljGLs
//tLgRjRL1YiHO+mLnmrIRVgxtIPNIgF9z9n3HuQcw+loRk9RvGu1SsQJBAPekENzl
//w+5UP02s8GX5oJhb/JdwSXvlAFQI8BOSTq1mmw3btVrXVVA/3vtaqQpdTIwNN0z7
//yza7h44k5CQTesE=', 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkr
//IvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsra
//prwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUr
//CmZYI/FCEa3/cNMW0QIDAQAB');

/** 此 appid+私钥+公钥 只供测试使用! */

/**
 * !!!!!!!常规使用直接是用 authBase(小授权) / authUserInfo(大授权) 方法即可
 * !!!!!!!支持 PC端 获取授权 + 支付宝中获取授权 | 不支持 手机H5
 */

/** authBase 小授权方法直接获取用户信息 跳转链接 获取 token 连调*/
function authBase($url, $state = 'state')
{
    $OAuth = new \AliSdk\OAuth();

    return $OAuth->authBase($url, $state);
}

/** authUserInfo  大授权 跳转链接 获取 token 获取用户信息 连调 */
function authUserInfo($url, $state = 'state')
{
    $OAuth = new \AliSdk\OAuth();

    return $OAuth->authUserInfo($url, $state);
}

/** 获取跳转链接 */
function getUrl($url, $scope = 'auth_userinfo', $state = 'state')
{
    $OAuth = new \AliSdk\OAuth();

    return $OAuth->url($url, $scope, $state);
}

/** 通过跳转链接的code获取token和用户id */
function getAccessPermission($code)
{
    $OAuth = new \AliSdk\OAuth();

    return $OAuth->getAccessPermission($code);
}

/** 通过token 获取用户信息 (大授权可以使用 小授权使用会提示无效的令牌) */
function getUser($access_token)
{
    $OAuth = new \AliSdk\OAuth();

    return $OAuth->getUser($access_token);
}

//var_dump(authBase('http://www.itse.cc'));

//var_dump(authUserInfo('http://www.itse.cc'));

//var_dump(getUrl('http://www.itse.cc'));
var_dump(getAccessPermission('12ec2c380ef040de8d6430b2cc3cPX04'));
//var_dump(getUser('composeB624ba2eae9a34fa99852555b78851X56'));
exit;
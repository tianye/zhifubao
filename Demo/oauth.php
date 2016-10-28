<?php
require '../vendor/autoload.php';

/** appid 服务窗id , openssl生成的私钥 公钥要在ali 后台设置  , 阿里生成的公钥 要下载下来 生成pem 文件 */
\AliSdk\Core\Config::init('2016010401061932', '../Key/rsa_private_key_pkcs8.pem', '../Key/rsa_public_ali_key.pem');

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
//var_dump(getAccessPermission('9875d4433838433cadebc1e62a7dWX56'));
//var_dump(getUser('composeB624ba2eae9a34fa99852555b78851X56'));
exit;
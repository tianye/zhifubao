<?php
require '../vendor/autoload.php';

use AliSdk\Core\Sign;

/**
 * 签名验证DEMO 可以无视
 */

function codeGetId($code)
{
    $data      = ['grant_type' => 'authorization_code', 'code' => $code, 'method' => 'alipay.system.oauth.token'];
    $sign_info = ['app_id' => '2016010401061932', 'charset' => 'GBK', 'sign_type' => 'RSA', 'timestamp' => date('Y-m-d H:i:s'), 'version' => '1.0'];

    $data_info = array_merge($data, $sign_info);

    $Sign        = new Sign('./rsa_private_key_pkcs8.pem', './rsa_public_ali_key.pem');
    $sign_return = $Sign->generateSign($data_info);

    $data_info['sign'] = $sign_return;

    var_dump($data_info);
}

function idGetMsg($code, $auth_token)
{
    $data      = ['grant_type' => 'authorization_code', 'code' => $code, 'auth_token' => $auth_token, 'method' => 'alipay.user.userinfo.share'];
    $sign_info = ['app_id' => '2016010401061932', 'charset' => 'GBK', 'sign_type' => 'RSA', 'timestamp' => date('Y-m-d H:i:s'), 'version' => '1.0'];

    $data_info = array_merge($data, $sign_info);

    $Sign        = new Sign('./rsa_private_key_pkcs8.pem', './rsa_public_ali_key.pem');
    $sign_return = $Sign->generateSign($data_info);

    $data_info['sign'] = $sign_return;

    var_dump($data_info);
}

function getUrl()
{
    var_dump('https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?app_id=2016010401061932&scope=auth_userinfo&redirect_uri=http%3A%2F%2Fwww.itse.cc');
}

//codeGetId('8f45acd371d0409499a1a93f334eSX04');

//idGetMsg('d670e03c13274fe7ac5d0c99afa3QA56', 'composeB8f8e51318a78422eb4964fc2433f8X56');

function verify($json, $interface_name)
{
//    var_dump($json);
//    var_dump(mb_convert_encoding($json, 'UTF-8'));
//    $json_object = json_decode(mb_convert_encoding($json, 'UTF-8'), true);
//
//
//    var_dump($json_object);
//    // 拼装签名数据
//    $signData                   = null;
//    $signData['signSourceData'] = '{"user_type_value":"1","city":"北京市","is_mobile_auth":"F","user_id":"20881080810534412745523742410456","province":"北京","is_licence_auth":"T","is_certified":"T","is_certify_gradeT","avatar":"https://tfsimg.alipay.com/images/partner/images/partner/T1IVXXXb0bXXXXXXXX","is_student_certified":"F","is_bank_auth":"T","alipay_user_id":"2088121760914564","user_status":"T","is_id_auth":"T"}';
//    $signData['sign']           = 'pxmfprbBd+ldw41e7ilxR/nob6nOIah26LbHNno8f6bZRsTINS4Zh24EvRdTRkeJ+P99D82w5mGEryrmsuyPr7KosOv3dZP+/Ts1irGZkjU1uEQPuz/VPDCVozXejOiW+c+aUWeFMnTP/9E+2LDLPu46/YlGMHUzvJL+KQ3cRAc=';
//
//    var_dump($signData);
    \AliSdk\Core\Config::init('2016010401061932', '../Key/rsa_private_key_pkcs8.pem', '../Key/rsa_public_ali_key.pem');

    $verify = (new Sign())->verifySign(mb_convert_encoding($signData['signSourceData'], 'utf-8'), $signData['sign']);

    print_r($verify);
}

verify('{"user_type_value":"1","city":"北京市","is_mobile_auth":"F","user_id":"20881080810534412745523742410456","province":"北京","is_licence_auth":"T","is_certified":"T","is_certify_gradeT","avatar":"https:\/\/tfsimg.alipay.com\/images\/partner\/images\/partner\/T1IVXXXb0bXXXXXXXX","is_student_certified":"F","is_bank_auth":"T","alipay_user_id":"2088121760914564","user_status":"T","is_id_auth":"T"}', 'alipay_user_userinfo_share_response');

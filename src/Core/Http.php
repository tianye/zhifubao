<?php
namespace AliSdk\Core;

use AliSdk\OAuth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;

class Http
{
    /**
     * @param array  $type
     * @param array  $biz_content
     * @param string $http
     *
     * @return mixed
     * @throws \AliSdk\Exception\AliSdkException
     */
    public static function single($type = ['curl_type' => '', 'interface_type' => ''], $biz_content = [], $http = '')
    {
        $sign_info = [
            'app_id'    => Config::$appid,
            'charset'   => 'utf-8',
            'sign_type' => Config::$sign_type,
            'timestamp' => date('Y-m-d H:i:s'),
            'version'   => Config::$version,
        ];

        if (!($http instanceof Client)) {
            $http = new Client(['headers' => ['content-type' => 'application/x-www-form-urlencoded;charset=' . Config::$postCharset, 'boundary' => self::getMillisecond()]]);
        }

        $biz_content         = array_merge($biz_content, $sign_info);
        $sign                = (new Sign())->generateSign($biz_content);
        $biz_content['sign'] = $sign;

        $rest = self::$type['curl_type'](Config::$URL, $biz_content, $http);

        if (isset($rest['error_response'])) {
            if ((new Sign())->verifySign(json_encode($rest['error_response'], JSON_UNESCAPED_UNICODE), $rest['sign'])) {
                return $rest['error_response'];
            } else {
                exit('签名验证失败');
            }
        } else {
            /** 有的接口返回值签名会验证失败! 不验证签名先 $not_sign */
            $interface_name = str_replace('.', '_', $type['interface_type']) . '_response';
            $not_sign       = [OAuth::ALIPAY_USER_USERINFO_SHARE];
            if (!in_array($type['interface_type'], $not_sign)) {
                if ((new Sign())->verifySign(json_encode($rest[$interface_name], JSON_UNESCAPED_UNICODE), $rest['sign'])) {
                    return $rest[$interface_name];
                } else {
                    exit('签名验证失败');
                }
            } else {
                return $rest[$interface_name];
            }
        }
    }

    public static function get($url, $request, Client $http)
    {
        try {
            $response = $http->get($url, ['form_params' => $request]);
        } catch (ConnectException $e) {
            exit($e->getMessage());
        }

        $response = self::packData($response->getBody()->getContents());

        return $response;
    }

    public static function post($url, $request, Client $http)
    {
        $response = $http->post($url, ['form_params' => $request]);
        $response = self::packData($response->getBody()->getContents());

        return $response;
    }

    protected static function getMillisecond()
    {
        list($s1, $s2) = explode(' ', microtime());

        return (float) sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }

    /**
     * 转换字符集编码
     *
     * @param $data
     * @param $targetCharset
     *
     * @return string
     */
    protected static function characet($data, $targetCharset)
    {

        if (!empty($data)) {
            $fileType = Config::$fileCharset;
            if (strcasecmp($fileType, $targetCharset) != 0) {

                //$data = mb_convert_encoding($data, $targetCharset);
                $data = iconv($fileType, $targetCharset . '//IGNORE', $data);
            }
        }

        return $data;
    }

    /**
     * @param $ReturnData
     *
     * @return mixed
     */
    public static function packData($ReturnData)
    {
        $data = $ReturnData;
        $type = gettype($ReturnData);

        if ($type == 'string') {
            //判断xml
            $xml_parser = xml_parser_create();
            if (xml_parse($xml_parser, $ReturnData, true)) {
                xml_parser_free($xml_parser);
                $type = 'xml';
            }
        }
        switch ($type) {
            //json
            case 'string':
                $data = json_decode($ReturnData, true);
                break;
            //对象
            case 'object':
                $data = json_decode(json_encode($ReturnData), true);
                break;
            //xml
            case 'xml':
                $xml_to_json = json_encode(simplexml_load_string($ReturnData, 'SimpleXMLElement', LIBXML_NOCDATA));
                $data        = json_decode($xml_to_json, true);
                break;
        }

        return $data;
    }
}
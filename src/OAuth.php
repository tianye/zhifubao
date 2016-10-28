<?php
namespace AliSdk;

use AliSdk\Core\Config;
use AliSdk\Core\Core;
use AliSdk\Core\Http;

class OAuth extends Core
{
    const ALIPAY_SYSTEM_OAUTH_TOKEN  = 'alipay.system.oauth.token';
    const ALIPAY_USER_USERINFO_SHARE = 'alipay.user.userinfo.share';
    const API_URL                    = 'https://openauth.alipay.com/oauth2/publicAppAuthorize.htm';

    /**
     * 生成OAuth URL
     *
     * @param string $to
     * @param string $scope auth_userinfo | auth_base
     * @param string $state
     *
     * @return string
     */
    public function url($to = null, $scope = 'auth_userinfo', $state = 'STATE')
    {
        $to !== null || $to = self::current();
        $queryStr = [
            'app_id'       => Config::$appid,
            'scope'        => $scope,
            'redirect_uri' => $to,
            'state'        => $state,
        ];

        return self::API_URL . '?' . http_build_query($queryStr) . '#alipay_redirect';
    }

    /**
     * 直接跳转
     *
     * @param string $to
     * @param string $scope auth_userinfo | auth_base
     * @param string $state
     */
    public function redirect($to = null, $scope = 'auth_userinfo', $state = 'STATE')
    {
        header('Location:' . $this->url($to, $scope, $state));
        exit;
    }

    /**
     *  小授权 获取用户 ID
     *
     * @param string $to    回调跳转网址
     * @param string $state 携带参数
     *
     * @return array|bool
     */
    public function authBase($to = null, $state = 'STATE')
    {
        if (isset($_GET['auth_code'])) {
            $code = $_GET['auth_code'];
        } else {
            $code = '';
        }

        if ($this->checkEmpty($code)) {
            $this->redirect($to, 'auth_base', $state);
        }

        $permission = $this->getAccessPermission($code);

        return $permission;
    }

    /**
     * 大授权 获取用户 信息
     *
     * @param string $to    回调跳转网址
     * @param string $state 携带参数
     *
     * @return array|bool
     */
    public function authUserInfo($to = null, $state = 'STATE')
    {
        if (isset($_GET['auth_code'])) {
            $code = $_GET['auth_code'];
        } else {
            $code = '';
        }

        if (!$this->checkEmpty($code)) {
            $this->redirect($to, 'auth_userinfo', $state);
        }

        $auth_code = $this->getAccessPermission($code);

        $permission = $this->getUser($auth_code['access_token']);

        return $permission;
    }

    /**
     * 获取access_token alipay_user_id refresh_token user_id
     *
     * @param        $code
     * @param string $grant_type
     *
     * @return array|bool
     */
    public function getAccessPermission($code, $grant_type = 'authorization_code')
    {
        $biz_content = [
            'grant_type' => $grant_type,
            'code'       => $code,
            'method'     => self::ALIPAY_SYSTEM_OAUTH_TOKEN,
        ];

        $result = Http::single(['curl_type' => 'post', 'interface_type' => self::ALIPAY_SYSTEM_OAUTH_TOKEN], $biz_content);

        return $result;
    }

    /**
     * 获取用户信息
     *
     * @param $auth_token
     *
     * @return array|bool
     */
    public function getUser($auth_token)
    {
        $biz_content = [
            'auth_token' => $auth_token,
            'method'     => self::ALIPAY_USER_USERINFO_SHARE,
        ];

        $result = Http::single(['curl_type' => 'post', 'interface_type' => self::ALIPAY_USER_USERINFO_SHARE], $biz_content);

        return $result;
    }

    /**
     * 获取当前地址 URL
     *
     * @return string
     */
    public static function current()
    {
        $protocol = (!empty($_SERVER['HTTPS'])
            && $_SERVER['HTTPS'] !== 'off'
            || $_SERVER['SERVER_PORT'] === 443) ? 'https://' : 'http://';
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
        } else {
            $host = $_SERVER['HTTP_HOST'];
        }

        return $protocol . $host . $_SERVER['REQUEST_URI'];
    }
}

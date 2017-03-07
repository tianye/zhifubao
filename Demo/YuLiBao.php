<?php
/**
 * Created by PhpStorm.
 * User: tianye
 * Date: 17/3/7
 * Time: 11:07
 */
require '../vendor/autoload.php';

use \AliSdk\Request\MyBankFinance\YuLiBao;

\AliSdk\Core\Config::init('2016010401061932', '../Key/rsa_private_key_pkcs8.pem', '../Key/rsa_public_ali_key.pem');

/** @var YuLiBao $YuLiBao */
$YuLiBao = new YuLiBao();

/**
 * 网商银行余利宝签约
 *
 * @param \AliSdk\Request\MyBankFinance\YuLiBao $YuLiBao
 */
function capitalPurchase(YuLiBao $YuLiBao)
{
    $request_data = [
        'fund_code'  => '001529',
        'amount'     => '1',
        'currency'   => 'CNY',
        'out_biz_no' => date('YmdHis', time()),
    ];

    /**
     * @var $fund_code
     * @var $amount
     * @var $currency
     * @var $out_biz_no
     */
    extract($request_data);

    $rest = $YuLiBao->capitalPurchase($amount, $out_biz_no, $fund_code, $currency);

    var_dump($rest);
    exit;
}

#capitalPurchase($YuLiBao);

/**
 * 网商银行余利宝赎回
 *
 * @param \AliSdk\Request\MyBankFinance\YuLiBao $YuLiBao
 */
function capitalRansom(YuLiBao $YuLiBao)
{
    $request_data = [
        'fund_code'   => '001529',
        'amount'      => '1',
        'currency'    => 'CNY',
        'out_biz_no'  => date('YmdHis', time()),
        'ransom_mode' => $YuLiBao::RANSOM_MODE_REALTIME,
    ];

    /**
     * @var $fund_code
     * @var $amount
     * @var $currency
     * @var $out_biz_no
     * @var $ransom_mode
     */
    extract($request_data);

    $rest = $YuLiBao->CapitalRansom($amount, $out_biz_no, $ransom_mode, $fund_code, $currency);

    var_dump($rest);
    exit;
}

#capitalRansom($YuLiBao);

/**
 * 起始日期和结束日期最大跨度30天
 * 接口支持分页查询，最多支持4950条记录查询；如果发现查询的总记录数超过4950条，建议缩短查询时间跨度。
 *
 * @param \AliSdk\Request\MyBankFinance\YuLiBao $YuLiBao * 余利宝历史交易查询
 */
function priceQuery(YuLiBao $YuLiBao)
{
    $request_data = [
        'fund_code'  => '001529',
        'start_date' => date('Ymd', time() - (60 * 60 * 24)), //开始时间 24 小时之前
        'end_date'   => date('Ymd'),
        'page'       => '1',
        'page_size'  => '20',
    ];

    /**
     * @var $fund_code
     * @var $start_date
     * @var $end_date
     * @var $page
     * @var $page_size
     */
    extract($request_data);

    $rest = $YuLiBao->PriceQuery($start_date, $end_date, $page, $page_size, $fund_code);

    var_dump($rest);
    exit;
}

#priceQuery($YuLiBao);

/**
 * 余利宝账户和收益查询
 *
 * @param \AliSdk\Request\MyBankFinance\YuLiBao $YuLiBao
 */
function accountQuery(YuLiBao $YuLiBao)
{
    $fund_code = '001529';

    $rest = $YuLiBao->AccountQuery($fund_code);

    var_dump($rest);
    exit;
}

#accountQuery($YuLiBao);
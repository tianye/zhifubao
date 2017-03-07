<?php
namespace AliSdk\Request\MyBankFinance;

use AliSdk\Core\Http;

class YuLiBao
{
    /**
     * code
     *
     * 10000业务处理成功
     * 40001～40006业务处理失败具体失败原因请参考公共错误码。其它请参考API文档。
     * 20000业务出现未知错误或者系统异常业务出现未知错误或者系统异常（请一定要在确定本次调用结果后，再发起重试），若有结果接口的，需调用查询接口发起查询。
     */

    const MYBANK_FINANCE_YULIBAO_CAPITAL_PURCHASE = 'mybank.finance.yulibao.capital.purchase'; //网商银行余利宝签约
    const MYBANK_FINANCE_YULIBAO_CAPITAL_RANSOM   = 'mybank.finance.yulibao.capital.ransom';   //网商银行余利宝赎回
    const MYBANK_FINANCE_YULIBAO_PRICE_QUERY      = 'mybank.finance.yulibao.price.query';      //查询余利宝行情信息 (七日年化收益率、万份收益金额)
    const MYBANK_FINANCE_YULIBAO_ACCOUNT_QUERY    = 'mybank.finance.yulibao.account.query';    //余利宝账户和收益查询

    const RANSOM_MODE_REALTIME    = 'REALTIME';    //赎回模式，REALTIME表示实时，NOTREALTIME 表示非实时赎回（T+1到账）
    const RANSOM_MODE_NOTREALTIME = 'NOTREALTIME'; //赎回模式，REALTIME表示实时，NOTREALTIME 表示非实时赎回（T+1到账）

    /**
     * 网商银行余利宝签约
     *
     * @param integer $amount     金额字段, 单位为分，123456代表1234.56元（壹仟贰佰叁拾肆圆伍角陆分）
     * @param integer $out_biz_no 订单号
     * @param string  $fund_code  基金代码, 目前仅支持001529
     * @param string  $currency   金额类型, CNY 人民币
     *
     * @return array|bool
     */
    public function capitalPurchase($amount, $out_biz_no, $fund_code = '001529', $currency = 'CNY')
    {
        $biz_content = [
            'fund_code'  => $fund_code,
            'amount'     => $amount,
            'currency'   => $currency,
            'out_biz_no' => $out_biz_no,
            'method'     => self::MYBANK_FINANCE_YULIBAO_CAPITAL_PURCHASE,
        ];

        $result = Http::single(['curl_type' => 'post', 'interface_type' => self::MYBANK_FINANCE_YULIBAO_CAPITAL_PURCHASE], $biz_content);

        return $result;
    }

    /**
     * 网商银行余利宝赎回
     *
     * @param integer $amount      金额字段, 单位为分，123456代表1234.56元（壹仟贰佰叁拾肆圆伍角陆分）
     * @param integer $out_biz_no  订单号
     * @param string  $ransom_mode 赎回模式，REALTIME表示实时，NOTREALTIME 表示非实时赎回（T+1到账），仅支持这两种模式。实时赎回日累计金额小于等于500万，大于500万则要使用非实时赎回，选择非实时赎回且日累计金额小于等于500万则会自动转为实时
     * @param string  $fund_code   基金代码, 目前仅支持001529
     * @param string  $currency    金额类型, CNY 人民币
     *
     * @return array|bool
     */
    public function CapitalRansom($amount, $out_biz_no, $ransom_mode = self::RANSOM_MODE_REALTIME, $fund_code = '001529', $currency = 'CNY')
    {
        $biz_content = [
            'fund_code'   => $fund_code,
            'amount'      => $amount,
            'currency'    => $currency,
            'out_biz_no'  => $out_biz_no,
            'ransom_mode' => $ransom_mode,
            'method'      => self::MYBANK_FINANCE_YULIBAO_CAPITAL_RANSOM,
        ];

        $result = Http::single(['curl_type' => 'post', 'interface_type' => self::MYBANK_FINANCE_YULIBAO_CAPITAL_RANSOM], $biz_content);

        return $result;
    }

    /**
     * 余利宝历史交易查询
     * 起始日期和结束日期最大跨度30天
     * 接口支持分页查询，最多支持4950条记录查询；如果发现查询的总记录数超过4950条，建议缩短查询时间跨度。
     *
     * @param integer $start_date 开始日期 年月日
     * @param integer $end_date   结束日期 年月日
     * @param string  $page       页数
     * @param string  $page_size  每页条数
     * @param string  $fund_code  基金代码
     *
     * @return array|bool
     */
    public function PriceQuery($start_date, $end_date, $page = '1', $page_size = '20', $fund_code = '001529')
    {
        $biz_content = [
            'fund_code'  => $fund_code,
            'start_date' => $start_date,
            'end_date'   => $end_date,
            'page'       => $page,
            'page_size'  => $page_size,
            'method'     => self::MYBANK_FINANCE_YULIBAO_PRICE_QUERY,
        ];

        $result = Http::single(['curl_type' => 'post', 'interface_type' => self::MYBANK_FINANCE_YULIBAO_PRICE_QUERY], $biz_content);

        return $result;
    }

    /**
     * 余利宝账户和收益查询
     *
     * @param string $fund_code
     *
     * @return array|bool
     */
    public function AccountQuery($fund_code = '001529')
    {
        $biz_content = [
            'fund_code' => $fund_code,
            'method'    => self::MYBANK_FINANCE_YULIBAO_ACCOUNT_QUERY,
        ];

        $result = Http::single(['curl_type' => 'post', 'interface_type' => self::MYBANK_FINANCE_YULIBAO_ACCOUNT_QUERY], $biz_content);

        return $result;
    }

}
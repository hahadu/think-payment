<?php
/**
 *  +----------------------------------------------------------------------
 *  | Created by  hahadu (a low phper and coolephp)
 *  +----------------------------------------------------------------------
 *  | Copyright (c) 2020. [hahadu] All rights reserved.
 *  +----------------------------------------------------------------------
 *  | SiteUrl: https://github.com/hahadu
 *  +----------------------------------------------------------------------
 *  | Author: hahadu <582167246@qq.com>
 *  +----------------------------------------------------------------------
 *  | Date: 2020/10/10 下午6:02
 *  +----------------------------------------------------------------------
 *  | Description:   ThinkPayment 微信支付
 *  +----------------------------------------------------------------------
 **/

namespace Hahadu\ThinkPayment;
use Hahadu\ThinkPayment\PayOptions as PayConf;
use Hahadu\WechatPay\Kernel\WxPayData\WxPayUnifiedOrder;
use Hahadu\WechatPay\Library\WechatNativePay;
use think\facade\Config;

class WechatPay implements PayInterface
{
    /*****
     * pc端网页支付
     * @param string $subject 订单标题
     * @param string $out_trade_no 商户网站唯一订单号
     * @param string $total_amount 订单金额
     * @return mixed
     */
    public function pc_pay($subject, $out_trade_no, $total_amount)
    {
        dump(Config::get('pay.'));
        dump(payConf::getWxpayOptions());
        // TODO: Implement pc_pay() method.
    }

    /****
     * 手机网页支付
     * @param string $subject 订单标题
     * @param string $out_trade_no 商户网站唯一订单号
     * @param string $total_amount 订单金额
     * @param string $quit_url 用户付款中途退出返回商户网站的地址
     * @return mixed
     */
    public function wap_pay($subject, $out_trade_no, $total_amount, $quit_url)
    {
        // TODO: Implement wap_pay() method.
    }

    /****
     * app端支付
     * @param string $subject 订单标题
     * @param string $out_trade_no 商户网站唯一订单号
     * @param string $total_amount 订单金额
     * @return mixed
     */
    public function app_pay($subject, $out_trade_no, $total_amount)
    {
        // TODO: Implement app_pay() method.
    }

    /****
     * 小程序付款
     * @param string $subject 订单标题
     * @param string $out_trade_no 商户网站唯一订单号
     * @param string $total_amount 订单金额
     * @param string $buyer_id 用户pid
     * @return false|string
     * @throws \Exception
     */
    public function mini_pay($subject, $out_trade_no, $total_amount, $buyer_id)
    {
        // TODO: Implement mini_pay() method.
    }

    /****
     * 生成交易付款码，用户扫码付款
     * @param string $subject 订单标题
     * @param string $out_trade_no 商户网站唯一订单号
     * @param string $total_amount 订单金额
     * @throws \Exception
     */
    public function scan_pay($subject, $out_trade_no, $total_amount)
    {
        $notify = new WechatNativePay(PayConf::getWxpayOptions());
        $input = new WxPayUnifiedOrder();
        $input->SetBody($subject); //订单标题
        $input->SetOut_trade_no($out_trade_no); //商户订单号
        $input->SetTotal_fee($total_amount*100); //订单金额 ，单位分
        $input->SetTrade_type("NATIVE"); //支付方式 NATIVE 用户扫码
        $input->SetProduct_id($out_trade_no); // 二维码中包含的商品ID 扫码支付时此参数必填

        $result = $notify->GetPayUrl($input);
        return $result["code_url"];
    }

    /****
     * 订单查询
     * @param string $out_trade_no 商户订单号
     * @return mixed
     */
    public function query($out_trade_no)
    {
        // TODO: Implement query() method.
    }

    /****
     * 退款接口
     * @param string $out_trade_no
     * @param string $refund_amount
     * @return mixed
     */
    public function refund($out_trade_no, $refund_amount)
    {
        // TODO: Implement refund() method.
    }

    /****
     * 验签接口
     * @param array $param_data
     * @return bool|string|string[]
     */
    public function verify($param_data)
    {
        // TODO: Implement verify() method.
    }
}
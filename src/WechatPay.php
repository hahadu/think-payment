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
use Hahadu\WechatPay\Kernel\WxPayData\WxPayOrderQuery;
use Hahadu\WechatPay\Kernel\WxPayData\WxPayRefund;
use Hahadu\WechatPay\Kernel\WxPayData\WxPayRefundQuery;
use Hahadu\WechatPay\Kernel\WxPayData\WxPayUnifiedOrder;
use Hahadu\WechatPay\Library\WechatNativePay;
use Hahadu\WechatPay\Kernel\WxPayApi;
use Hahadu\WechatPay\Library\WechatJsApiPay;
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
        return $this->scan_pay($subject, $out_trade_no, $total_amount);

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
        $input = new WxPayUnifiedOrder();
        $input->SetBody($subject); //订单标题
        $input->SetOut_trade_no($out_trade_no); //商户订单号
        $input->SetTotal_fee($total_amount*100); //订单金额
        $input->SetTime_start(date("YmdHis")); //订单创建时间
        $input->SetSpbill_create_ip(request()->ip()); //获取用户真实IP地址
        $input->SetTrade_type("MWEB"); //支付方式

        $order = WxPayApi::unifiedOrder(PayConf::getWxpayOptions(),$input);
        if($order['mweb_url']){
            return redirect($order['mweb_url']);
        }
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
        $input = new WxPayUnifiedOrder();
        $input->SetBody($subject); //订单标题
        $input->SetOut_trade_no($out_trade_no); //商户订单号
        $input->SetTotal_fee($total_amount*100); //订单金额
        $input->SetTime_start(date("YmdHis")); //订单创建时间
        $input->SetSpbill_create_ip(request()->ip()); //获取用户真实IP地址
        $input->SetTrade_type("APP"); //支付方式

        $order = WxPayApi::unifiedOrder(PayConf::getWxpayOptions(),$input);
        return $order;

    }

    /****
     * 小程序付款
     * @param string $subject 订单标题
     * @param string $out_trade_no 商户网站唯一订单号
     * @param string $total_amount 订单金额
     * @param string $buyer_id 用户pid
     * @return false|array
     * @throws \Exception
     */
    public function mini_pay($subject, $out_trade_no, $total_amount, $buyer_id)
    {
        $tools = new WechatJsApiPay(PayConf::getWxpayOptions());
        $openId = $tools->GetOpenid();
        //②、统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody($subject); //订单标题
        $input->SetOut_trade_no($out_trade_no); //商户订单号
        $input->SetTotal_fee($total_amount*100); //订单金额
        $input->SetTime_start(date("YmdHis")); //订单创建时间
        $input->SetTrade_type("JSAPI"); //支付方式
        $input->SetOpenid($openId);
        $order = WxPayApi::unifiedOrder(PayConf::getWxpayOptions(), $input);
        $jsApiParameters = $tools->GetJsApiParameters($order);
        //获取共享收货地址js函数参数
        $editAddress = $tools->GetEditAddressParameters();
        return [
            'jsApiParameters' =>$jsApiParameters,
            'editAddress' => $editAddress,
            'order' => $order,
        ];
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
        $input->SetTime_start(date("YmdHis"));
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
        $input = new WxPayOrderQuery();
        $input->SetOut_trade_no($out_trade_no);
        $query = WxPayApi::orderQuery(PayConf::getWxpayOptions(), $input);
        $result = [
            'title' => $query['attach'], // 订单标题
            'total_fee' => $query['total_fee']/100, //订单金额，元
            'fee_type' => $query['fee_type'], //货币类型
            'out_trade_no' =>$query['out_trade_no'], //商户订单号
            'transaction_id' => $query['transaction_id'], //微信交易单号
            'trade_state' => $query['trade_state'], // 订单状态
            'trade_state_desc' => $query['trade_state_desc'], // 订单状态
            'time_end' => $query['time_end'],  //付款时间
            'trade_type' => $query['trade_type'] , //支付方式
        ];
        return $result;

    }

    /****
     * 发起退款接口
     * @param string $out_trade_no
     * @param string $refund_amount
     * @param string $total_amount 订单总金额
     * @return mixed
     */
    public function refund($out_trade_no, $refund_amount,$total_amount='')
    {
        $input = new WxPayRefund();
        $input->SetOut_trade_no($out_trade_no); //商户订单号
        $input->SetTotal_fee($total_amount);  //订单金额
        $input->SetRefund_fee($refund_amount); //退款金额

        $input->SetOut_refund_no("sdkphp".date("YmdHis"));
        $input->SetOp_user_id((PayConf::getWxpayOptions())->GetMerchantId());
        $result = WxPayApi::refund(PayConf::getWxpayOptions(), $input);
        return $result;
    }
    /****
     * 退款查询
     * @param string $out_trade_no 订单支付时传入的商户订单号
     * @param string $out_request_no 请求退款接口时，传入的商户退款单号
     * @return string
     * @throws \Exception
     */
    public function query_refund($out_trade_no,$out_request_no=''){
        $input = new WxPayRefundQuery();
        //下面四个参数必须设置一个
          $input->SetOut_refund_no($out_request_no); // 商户退款单号
        //   $input->SetRefund_id(''); //微信退款单号
        $input->SetOut_trade_no($out_trade_no); // 商户订单号
        //   $input->SetTransaction_id(''); //微信支付平台订单号
        $result = WxPayApi::refundQuery(PayConf::getWxpayOptions(), $input);
        return $result;
    }

    /****
     * 验签接口
     * @param array $param_data
     * @return bool|string|string[]
     */
    public function verify($param_data)
    {

    }
}
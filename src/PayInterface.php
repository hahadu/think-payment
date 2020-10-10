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
 *  | Date: 2020/10/9 下午7:38
 *  +----------------------------------------------------------------------
 *  | Description:   think-payment
 *  +----------------------------------------------------------------------
 **/

namespace Hahadu\ThinkPayment;


interface PayInterface
{
    /*****
     * pc端网页支付
     * @param string $subject 订单标题
     * @param string $out_trade_no 商户网站唯一订单号
     * @param string $total_amount 订单金额
     * @return mixed
     */
    public function pc_pay($subject,$out_trade_no,$total_amount);

    /****
     * 手机网页支付
     * @param string $subject 订单标题
     * @param string $out_trade_no 商户网站唯一订单号
     * @param string $total_amount 订单金额
     * @param string $quit_url 用户付款中途退出返回商户网站的地址
     * @return mixed
     */
    public function wap_pay($subject,$out_trade_no,$total_amount,$quit_url);

    /****
     * app端支付
     * @param string $subject 订单标题
     * @param string $out_trade_no 商户网站唯一订单号
     * @param string $total_amount 订单金额
     * @return mixed
     */
    public function app_pay($subject,$out_trade_no,$total_amount);

    /****
     * 订单查询
     * @param string $out_trade_no 商户订单号
     * @return mixed
     */
    public function query($out_trade_no);

    /****
     * 退款接口
     * @param string $out_trade_no
     * @param string $refund_amount
     * @return mixed
     */
    public function refund($out_trade_no, $refund_amount);

    /****
     * 验签接口
     * @param array $param_data
     * @return bool|string|string[]
     */
    public function verify($param_data);


}
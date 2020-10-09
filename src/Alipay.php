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
 *  | Date: 2020/10/8 下午11:22
 *  +----------------------------------------------------------------------
 *  | Description:   ImAdminThink
 *  +----------------------------------------------------------------------
 **/

namespace Hahadu\ThinkPayment;
use Alipay\EasySDK\Kernel\Factory;
use Alipay\EasySDK\Payment\Huabei\Models\HuabeiConfig;
use Hahadu\ThinkPayment\AlipayLibrary\AlipayTrait;

class Alipay extends PayBaseClass
{
    use AlipayTrait;
    public function __construct(){
        parent::__construct();
    }

    /****
     * pc网页下单
     * @param string $subject 订单标题
     * @param string $out_trade_no 商户网站唯一订单号
     * @param string $total_amount 订单金额
     * @return false|string
     * @throws \Exception
     */
    public function pc_pay($subject,$out_trade_no,$total_amount){
        Factory::setOptions($this->getAlipayOptions());
        try {
            //2. 发起API调用（以支付能力下的统一收单交易创建接口为例）
            $result = Factory::payment()->page()->pay($subject, $out_trade_no, $total_amount, $this->alipay_config['return_url']);
            return $this->response_checker($result);
        } catch (Exception $e) {
            return json_encode($e->getMessage());
        }
    }

    /****
     * 手机网页下单
     * @param string $subject 订单标题
     * @param string $out_trade_no 商户网站唯一订单号
     * @param string $total_amount 订单金额
     * @param string $quit_url 用户付款中途退出返回商户网站的地址
     * @return false|string
     * @throws \Exception
     */
    public function wap_pay($subject,$out_trade_no,$total_amount,$quit_url){
        Factory::setOptions($this->getAlipayOptions());
        try {
            //2. 发起API调用（以支付能力下的统一收单交易创建接口为例）
            $result = Factory::payment()->wap()->pay($subject, $out_trade_no, $total_amount,$quit_url, $this->alipay_config['return_url']);
            return $this->response_checker($result);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /****
     * app下单
     * @param string $subject 订单标题
     * @param string $out_trade_no 商户网站唯一订单号
     * @param string $total_amount 订单金额
     * @return false|string
     * @throws \Exception
     */
    public function app_pay($subject,$out_trade_no,$total_amount){
        Factory::setOptions($this->getAlipayOptions());
        try {
            //2. 发起API调用（以支付能力下的统一收单交易创建接口为例）
            $result = Factory::payment()->app()->pay($subject, $out_trade_no, $total_amount);
            return $this->response_checker($result);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /****
     * @param string $subject
     * @param string $out_trade_no
     * @param string $total_amount
     * @param string $buyer_id 买家支付宝唯一id
     * @return string
     * @throws \Exception
     */
    public function huabei_order($subject,$out_trade_no,$total_amount,$buyer_id ,HuabeiConfig $extendParams){
        Factory::setOptions($this->getAlipayOptions());
        try{
            $result = Factory::payment()->huabei()->create($subject,$out_trade_no,$total_amount, $buyer_id, $extendParams);
            return $this->response_checker($result);
        }catch (Exception $e){
            return $e->getMessage();
        }

    }

    /****
     * 发起退款
     * @param string $out_trade_no 订单支付时传入的商户订单号
     * @param string $refund_amount 需要退款的金额，该金额不能大于订单金额,单位为元，支持两位小数
     * @return false|mixed|string
     * @throws \Exception
     */
    public function alipay_refund($out_trade_no, $refund_amount){
        Factory::setOptions($this->getAlipayOptions());
        try{
            $result = Factory::payment()->common()->refund($out_trade_no, $refund_amount);
            return $this->response_checker($result)->httpBody;
        }catch (Exception $e){
            return $e->getMessage();
        }
    }
}
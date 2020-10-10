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
 *  | Description:   think-payment
 *  +----------------------------------------------------------------------
 **/

namespace Hahadu\ThinkPayment;
use Alipay\EasySDK\Kernel\Factory;
use Alipay\EasySDK\Payment\Huabei\Models\HuabeiConfig;
use Hahadu\ThinkPayment\AlipayLibrary\AlipayTrait;
use Hahadu\ThinkPayment\Response\AlipayCheckResponse as aliCheck;
use Hahadu\ThinkPayment\PayOptions as payConf;
use think\facade\Config;
use function Stringy\create;

class Alipay implements PayInterface
{
    use AlipayTrait;
    private $alipay_config;
    public function __construct(){
        $this->alipay_config = Config::get('pay.aliPay');

    }

    /****
     * pc网页付款
     * @param string $subject 订单标题
     * @param string $out_trade_no 商户网站唯一订单号
     * @param string $total_amount 订单金额
     * @return false|string
     * @throws \Exception
     */
    public function pc_pay($subject,$out_trade_no,$total_amount){
        Factory::setOptions(payConf::getAlipayOptions());
        try {
            //2. 发起API调用（以支付能力下的统一收单交易创建接口为例）
            $result = Factory::payment()->page()->pay($subject, $out_trade_no, $total_amount, $this->alipay_config['return_url']);
            if (aliCheck::success($result)){
                return $result->body;
            } else {
                return $result;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /****
     * 手机网页付款
     * @param string $subject 订单标题
     * @param string $out_trade_no 商户网站唯一订单号
     * @param string $total_amount 订单金额
     * @param string $quit_url 用户付款中途退出返回商户网站的地址
     * @return false|string
     * @throws \Exception
     */
    public function wap_pay($subject,$out_trade_no,$total_amount,$quit_url){
        Factory::setOptions(payConf::getAlipayOptions());
        try {
            //2. 发起API调用（以支付能力下的统一收单交易创建接口为例）
            $result = Factory::payment()->wap()->pay($subject, $out_trade_no, $total_amount,$quit_url, $this->alipay_config['return_url']);
            if (aliCheck::success($result)){
                return $result->body;
            } else {
                return $result;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /****
     * app付款
     * @param string $subject 订单标题
     * @param string $out_trade_no 商户网站唯一订单号
     * @param string $total_amount 订单金额
     * @return false|string
     * @throws \Exception
     */
    public function app_pay($subject,$out_trade_no,$total_amount){
        Factory::setOptions(payConf::getAlipayOptions());
        try {
            //2. 发起API调用（以支付能力下的统一收单交易创建接口为例）
            $result = Factory::payment()->app()->pay($subject, $out_trade_no, $total_amount);
            if (aliCheck::success($result)){
                return $result->body;
            } else {
                return $result;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    /****
     * 小程序付款
     * 获取https://opendocs.alipay.com/mini/introduce/pay
     * @param string $subject 订单标题
     * @param string $out_trade_no 商户网站唯一订单号
     * @param string $total_amount 订单金额
     * @param string $buyer_id 用户pid 支付宝小程序支付场景中该参数必传
     * @return false|string
     * @throws \Exception
     */
    public function mini_pay($subject,$out_trade_no,$total_amount,$buyer_id){
        Factory::setOptions(payConf::getAlipayOptions());
        try{
            $result = Factory::payment()->common()->create($subject,$out_trade_no,$total_amount,$buyer_id);
            if (aliCheck::success($result)){
                return $result->tradeNo; //获取返回的tradeNo
            } else {
                return $result;
            }
        }catch (Exception $e){
            return $e->getMessage();
        }
    }

    /****
     * 生成交易付款码，用户扫码付款
     * @param string $subject 订单标题
     * @param string $out_trade_no 商户网站唯一订单号
     * @param string $total_amount 订单金额
     * @throws \Exception
     */
    public function scan_pay($subject,$out_trade_no,$total_amount){
        Factory::setOptions(payConf::getAlipayOptions());
        try{
            $result = Factory::payment()->faceToFace()->preCreate($subject,$out_trade_no,$total_amount);
        }catch (Exception $e){

        }
    }

    /****
     * 创建花呗分期订单
     * @param string $subject
     * @param string $out_trade_no
     * @param string $total_amount
     * @param string $buyer_id 买家支付宝唯一id
     * @return string
     * @throws \Exception
     */
    public function huabei_order($subject,$out_trade_no,$total_amount,$buyer_id ,HuabeiConfig $extendParams){
        Factory::setOptions(payConf::getAlipayOptions());
        try{
            $result = Factory::payment()->huabei()->create($subject,$out_trade_no,$total_amount, $buyer_id, $extendParams);
            if (aliCheck::success($result)){
                return $result->httpBody;
            } else {
                return $result;
            }
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
    public function refund($out_trade_no, $refund_amount){
        Factory::setOptions(payConf::getAlipayOptions());
        try{
            $result = Factory::payment()->common()->refund($out_trade_no, $refund_amount);
            if (aliCheck::success($result)){
                return $result->httpBody;
            } else {
                return $result;
            }
        }catch (Exception $e){
            return $e->getMessage();
        }
    }

    /****
     * 退款查询
     * @param string $out_trade_no 订单支付时传入的商户订单号
     * @param string $out_request_no 请求退款接口时，传入的退款请求号 ，如果在退款请求时未传入，则该值为创建交易时的外部交易号
     * @return \Alipay\EasySDK\Payment\Common\Models\AlipayTradeFastpayRefundQueryResponse|string
     * @throws \Exception
     */
    public function query_refund($out_trade_no,$out_request_no){
        Factory::setOptions(payConf::getAlipayOptions());
        try{
            $result = Factory::payment()->common()->queryRefund($out_trade_no, $out_request_no);
            if (aliCheck::success($result)){
                if(!empty($result->refundStatus or $result->refundStatus !== 'REFUND_SUCCESS')){
                    return $result;
                }
                return $result->httpBody;
            } else {
                return $result;
            }
        }catch (Exception $e){
            return $e->getMessage();
        }
    }

    public function query($out_trade_no){
        Factory::setOptions(payConf::getAlipayOptions());
        try{
            $result = Factory::payment()->common()->query($out_trade_no);
            if (aliCheck::success($result)){
                return $result->httpBody;
            } else {
                return $result;
            }
        }catch (Exception $e){
            return $e->getMessage();
        }
    }

    /****
     * 支付宝异步验签
     * @param array $param_data
     * @return bool|string|string[]
     * @throws \Exception
     */
    public function verify($param_data){
        $option_data = payConf::getAlipayOptions();
        Factory::setOptions($option_data);
        try{
            $result = Factory::payment()->common()->verifyNotify($param_data);
            return $result;
        }catch (Exception $e){
            return $e->getMessage();
        }
    }


}
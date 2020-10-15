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
use Exception;
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
     * @return mixed 如果成功返回HTML表单
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
            return [
                'message'=>$e->getMessage(),
                'code' =>$e->getCode(),
                'file' =>$e->getFile(),
                'line' =>$e->getLine(),
            ];
        }
    }

    /****
     * 手机网页付款
     * @param string $subject 订单标题
     * @param string $out_trade_no 商户网站唯一订单号
     * @param string $total_amount 订单金额
     * @param string $quit_url 用户付款中途退出返回商户网站的地址
     * @return mixed
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
            return [
                'message'=>$e->getMessage(),
                'code' =>$e->getCode(),
                'file' =>$e->getFile(),
                'line' =>$e->getLine(),
            ];
        }
    }

    /****
     * app付款
     * @param string $subject 订单标题
     * @param string $out_trade_no 商户网站唯一订单号
     * @param string $total_amount 订单金额
     * @return mixed
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
            return [
                'message'=>$e->getMessage(),
                'code' =>$e->getCode(),
                'file' =>$e->getFile(),
                'line' =>$e->getLine(),
            ];
        }
    }
    /****
     * 小程序付款
     * 获取https://opendocs.alipay.com/mini/introduce/pay
     * @param string $subject 订单标题
     * @param string $out_trade_no 商户网站唯一订单号
     * @param string $total_amount 订单金额
     * @param string $buyer_id 用户pid 支付宝小程序支付场景中该参数必传
     * @return mixed
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
            return [
                'message'=>$e->getMessage(),
                'code' =>$e->getCode(),
                'file' =>$e->getFile(),
                'line' =>$e->getLine(),
            ];
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
            return $result->qrCode;
        }catch (Exception $e){
            return [
                'message'=>$e->getMessage(),
                'code' =>$e->getCode(),
                'file' =>$e->getFile(),
                'line' =>$e->getLine(),
            ];
        }
    }

    /****
     * 创建花呗分期订单
     * @param string $subject
     * @param string $out_trade_no
     * @param string $total_amount
     * @param string $buyer_id 买家支付宝唯一id
     * @return mixed
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
            return [
                'message'=>$e->getMessage(),
                'code' =>$e->getCode(),
                'file' =>$e->getFile(),
                'line' =>$e->getLine(),
            ];
        }

    }

    /****
     * 发起退款
     * @param string $out_trade_no 订单支付时传入的商户订单号
     * @param string $refund_amount 需要退款的金额，该金额不能大于订单金额,单位为元，支持两位小数
     * @return mixed
     * @throws \Exception
     */
    public function refund($out_trade_no, $refund_amount,$total_amount=''){
        Factory::setOptions(payConf::getAlipayOptions());
        try{
            $refund = Factory::payment()->common()->refund($out_trade_no, $refund_amount);
            if (aliCheck::success($refund)){
                $result = [
                   // 'code' =>$refund->code, //查询状态码
                   // 'message' => $refund->msg, //查询状态码描述
                    'out_trade_no'=> $refund->outTradeNo, //商户订单号
                    'trade_no'=> $refund->tradeNo,  //支付宝订单号
                    'buyer_logon_id'=> $refund->buyerLogonId, //买家支付宝账号
                    'buyer_user_id'=> $refund->buyerUserId, //买家支付宝ID
                    'refund_fee'=> $refund->refundFee, //退款金额
                    'gmt_refund_pay'=> $refund->gmtRefundPay, //退款时间
                    'fund_change' => $refund->fundChange, //退款资金变化，状态成功Y,失败N
                ];
                return $result;
            } else {
                return $refund;
            }
        }catch (Exception $e){
            return [
                'message'=>$e->getMessage(),
                'code' =>$e->getCode(),
                'file' =>$e->getFile(),
                'line' =>$e->getLine(),
            ];
        }
    }

    /****
     * 退款查询
     * @param string $out_trade_no 订单支付时传入的商户订单号
     * @param string $out_request_no 请求退款接口时，传入的退款请求号 ，如果在退款请求时未传入，则该值为创建交易时的外部交易号
     * @return mixed
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
            return [
                'message'=>$e->getMessage(),
                'code' =>$e->getCode(),
                'file' =>$e->getFile(),
                'line' =>$e->getLine(),
            ];
        }
    }

    /****
     * @param string $out_trade_no
     * @return mixed
     */
    public function query($out_trade_no){
        Factory::setOptions(payConf::getAlipayOptions());
        try{
            $query = Factory::payment()->common()->query($out_trade_no);
            if (aliCheck::success($query)){
                dump($query);
                //响应参数 https://opendocs.alipay.com/apis/api_1/alipay.trade.query?scene=common
                $result = [
                   // 'code' => $query->code, //查询返回状态码 非交易状态码
                   // 'msg' => $query->msg, //code的状态码描述
                    'total_amount' => $query->totalAmount, //交易金额
                //    'buyer_pay_amount' =>$query->buyerPayAmount, //买家实际付款金额 ？
                    'pay_currency' =>$query->payCurrency, //货币种类
                    'buyer_user_id'=>$query->buyerUserId, //支付宝用户唯一ID
                    'out_trade_no' => $query->outTradeNo, //商户订单号
                    'trade_no'  => $query->tradeNo, //支付宝交易订单号
                    'point_amount' =>$query->pointAmount,//支付宝积分支付金额
                    'buyer_logon_id' =>$query->buyerLogonId, //买家支付宝账号
                    'trade_status' =>$query->tradeStatus, //订单状态 交易状态：WAIT_BUYER_PAY（等待买家付款）TRADE_SUCCESS（支付成功）
                    'trade_state_desc' => (function()use($query){
                        switch ($query->tradeStatus){
                            case 'WAIT_BUYER_PAY':
                                return '交易创建，等待买家付款';
                                break;
                            case 'TRADE_CLOSED':
                                return '买家未付款交易超时关闭，或支付完成后全额退款';
                                break;
                            case 'TRADE_FINISHED':
                                return '交易结束，不可退款';
                                break;
                            case 'TRADE_SUCCESS':
                                return '支付成功';
                                break;
                            default:
                                return '未查询到交易状态';
                        }
                    })(),
                    'send_pay_date'=> $query->sendPayDate, //订单付款时间
                ];

                return $result;
            } else {
                return $query;
            }
        }catch (Exception $e){
            return [
                'message'=>$e->getMessage(),
                'code' =>$e->getCode(),
                'file' =>$e->getFile(),
                'line' =>$e->getLine(),
            ];
        }
    }

    /****
     * 支付宝异步验签
     * @param array $param_data
     * @return bool|string|array
     * @throws \Exception
     */
    public function verify($param_data){
        $option_data = payConf::getAlipayOptions();
        Factory::setOptions($option_data);
        try{
            $result = Factory::payment()->common()->verifyNotify($param_data);
            return $result;
        }catch (Exception $e){
            return [
                'message'=>$e->getMessage(),
                'code' =>$e->getCode(),
                'file' =>$e->getFile(),
                'line' =>$e->getLine(),
            ];
        }
    }


}
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
 *  | Date: 2020/10/10 下午1:49
 *  +----------------------------------------------------------------------
 *  | Description:   hahadu/think-payment 配置文件
 *  +----------------------------------------------------------------------
 **/
return[
    //推荐从文件中读取配置字符串而不是直接写入源码中
    'aliPay'    => [
        'app_id' => '20..',
        'notify_url' => 'http://xxxxxx/notify',
        'return_url' => 'http://xxxxxx/return',
        //商户私钥 merchantPrivateKey
        'merchant_private_key' => "MIIEogI...",
        //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
        'alipay_public_key' => "MIIBIjAN....",
        // 加密方式： **RSA2**
        'sign_type' => 'RSA2',
        /*'private_key' => '',*/
        // 使用公钥证书模式，请配置下面两个参数，同时修改ali_public_key为以.crt结尾的支付宝公钥证书路径，如（./cert/alipayCertPublicKey_RSA2.crt）
        'alipay_merchant_cert_path' => '', //应用公钥证书文件路径，例如：/foo/appCertPublicKey_2019051064521003.crt
        'alipay_root_cert' => '', //支付宝根证书文件路径，例如：/foo/alipayRootCert.crt"
        'alipay_cert_path' => '', //支付宝公钥证书文件路径，例如：/foo/alipayCertPublicKey_RSA2.crt
        //接口加密方式（可选）
        'alipay_encrypt_key' => '',
    ],
    'wxPay' => [ //风向潮流
        'appid' => '', // APP APPID
        'app_id' => '', // 公众号 APPID
        'mini_app_id' => '', // 小程序 APPID
        'app_secret'  => '', //公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置）
        'mini_app_secret' => '', //小程序secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置）
        'mch_id' => '', //商户号
        'key' => '', //秘钥
        'sign_type'=> 'HMAC-SHA256', //MD5或者HMAC-SHA256 默认HMAC-SHA256
        'notify_url' => 'http://xxxxxx/notify',
        'cert_client' => '', // optional，退款等情况时用到./cert/apiclient_cert.pem
        'cert_key' => '',// optional，退款等情况时用到 ./cert/apiclient_key.pem
    ],

];


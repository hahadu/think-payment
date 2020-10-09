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
 *  | Date: 2020/10/9 下午5:56
 *  +----------------------------------------------------------------------
 *  | Description:   ImAdminThink
 *  +----------------------------------------------------------------------
 **/

namespace Hahadu\ThinkPayment\AlipayLibrary;


use Alipay\EasySDK\Kernel\Util\ResponseChecker;

trait AlipayTrait
{
    public function response_checker($check_data){
        $responseChecker = new ResponseChecker();
        if ($responseChecker->success($check_data)){
            return $check_data;
        } else {
            $check_data['status_message'] = 'error';
            return $check_data;
        }
    }
}
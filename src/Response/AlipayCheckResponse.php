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
 *  | Date: 2020/10/9 下午10:50
 *  +----------------------------------------------------------------------
 *  | Description:   think-payment
 *  +----------------------------------------------------------------------
 **/

namespace Hahadu\ThinkPayment\Response;


class AlipayCheckResponse
{
    static public function success($response)
    {
        if (!empty($response->code) && $response->code == 10000) {
            return true;
        }
        if (empty($response->code) && empty($response->subCode)) {
            return true;
        }
        return false;
    }

}
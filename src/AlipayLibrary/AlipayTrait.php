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
 *  | Description:   think-payment
 *  +----------------------------------------------------------------------
 **/

namespace Hahadu\ThinkPayment\AlipayLibrary;

trait AlipayTrait
{
    protected function response_checker($check_data){
        if ($this->success($check_data)){
            return $check_data;
        } else {
            $check_data['status_message'] = 'error';
            return $check_data;
        }
    }
    protected function success($response)
    {
        if (!empty($response->code) && $response->code == 10000) {
            return true;
        }
        if (empty($response->code) && empty($response->subCode)) {
            return true;
        }
        return false;
    }

    /**
     * 校验$value是否非空
     *  if not set ,return true;
     *  if is null , return true;
     * @param $value
     * @return bool
     */
    protected function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;

        return false;
    }

}
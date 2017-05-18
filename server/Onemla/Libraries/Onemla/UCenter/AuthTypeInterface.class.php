<?php
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 2015/4/23
 * Time: 12:12
 */
namespace Onemla\UCenter;

class AuthTypeInterface{
    //验证成功
    const STATUS_SUCCESS = 1;
    //取消验证
    const STATUS_CANCEL = 2;
    //验证失败
    const STATUS_FAILURE = 4;
    //验证超时
    const STATUS_EXPIRED = 8;
    //验证终止
    const STATUS_DENIED = 16;
    //帐号不存在
    const STATUS_UNKNOWN = 32;

    //验证方式 email
    const AUTH_TYPE_EMAIL = 1;
    //验证方式 手机号
    const AUTH_TYPE_PHONE = 2;
    //验证方式 昵称
    const AUTH_TYPE_NICKE = 4;

    /**
     * 验证方式集合
     * @var array
     */
    public $authTypes = array(
        self::AUTH_TYPE_EMAIL => 'Email',
        self::AUTH_TYPE_PHONE => 'Phone',
        self::AUTH_TYPE_NICKE => 'Nick Name',
    );
}

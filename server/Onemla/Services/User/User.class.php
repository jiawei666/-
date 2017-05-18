<?php


namespace Services\User;
use Onemla\OnemlaHelper;


/**
 * 用户实体类
 */
class User {

    public function __construct()
    {
        $this -> user_id = 0;
    }

    public function setUserInfo($pUserInfo)
    {
        $this -> user_id   = $pUserInfo['id'];
        $this -> user_name = $pUserInfo['user_name'];
        $this -> phone     = $pUserInfo['phone'];
        $this -> email     = $pUserInfo['email'];
        $this -> user_type = $pUserInfo['user_type'];
        $this -> last_time = $pUserInfo['last_time'];
        if($this -> user_type == UserService::USER_TYPE_MEMBER) {
//            $this->logo = $pUserInfo['logo'];
//            $this->nick_name = $pUserInfo['nick_name'];
        }else{
            $this -> permissions = $pUserInfo['permissions'];
        }
    }

    public function updateSession()
    {
        OnemlaHelper::session(SESSION_UCENTER, $this);
    }
}
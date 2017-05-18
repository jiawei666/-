<?php
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 2015/4/15
 * Time: 20:57
 */
namespace Onemla;
use Onemla\UCenter\UCenterHelper;
use Think\Controller;

class BaseController extends  Controller{
    public function __construct() {
        parent::__construct();

    }

    protected function access_action($action = null, $group = null){
        return array(
            'group' => is_null($group) ? COMPONENTS_INPUT : $group,
            'action' => is_null($action) ? CONTROLLER_NAME . '.' . ACTION_NAME : $action,
        );
    }

    //自动登陆
    protected function checkAutoLogin(){
        if(OnemlaHelper::getUser()->guest){
            $cookieName = UCenterHelper::getShortHashedUserAgent();
            if(cookie($cookieName)){
                $options['responseType'] = 'Cookie';
                if(defined('ADMINISTRATOR')){
                    $options = array_merge($options, $this->access_action('onemla.login.admin', 'Root.1'));
                }
                OnemlaHelper::service('Authentication/Onemla/User/Login', array(array(), $options));
            }
        }
    }
}
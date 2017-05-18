<?php
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 2015/5/11
 * Time: 15:28
 */
namespace Onemla\Behaviors;
use Think\Behavior;

class OnemlaModuleBehavior extends Behavior{
    //行为执行入口
    public function run(&$param){
        $this -> module_check();
    }

    protected function module_check(){
        // 加载模块配置文件
        if(is_file(COMPONENTS_PATH.'/Conf/config'.CONF_EXT))
            C(load_config(COMPONENTS_PATH.'/Conf/config'.CONF_EXT));
        // 加载应用模式对应的配置文件
        if('common' != APP_MODE && is_file(COMPONENTS_PATH.'/Conf/config_'.APP_MODE.CONF_EXT))
            C(load_config(COMPONENTS_PATH.'/Conf/config_'.APP_MODE.CONF_EXT));
        // 当前应用状态对应的配置文件
        if(APP_STATUS && is_file(COMPONENTS_PATH.'/Conf/'.APP_STATUS.CONF_EXT))
            C(load_config(COMPONENTS_PATH.'/Conf/'.APP_STATUS.CONF_EXT));

        // 加载模块别名定义
        if(is_file(COMPONENTS_PATH.'/Conf/alias.php'))
            Think::addMap(include COMPONENTS_PATH.'/Conf/alias.php');

        // 加载模块tags文件定义
        if(is_file(COMPONENTS_PATH.'/Conf/tags.php'))
            Hook::import(include COMPONENTS_PATH.'/Conf/tags.php');

        // 加载模块函数文件
        if(is_file(COMPONENTS_PATH.'/Common/function.php'))
            include COMPONENTS_PATH.'/Common/function.php';
        // 加载模块的扩展配置文件
        load_ext_file(COMPONENTS_PATH);
    }
}
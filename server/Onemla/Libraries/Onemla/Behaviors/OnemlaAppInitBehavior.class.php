<?php
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 2015/4/16
 * Time: 10:37
 */
namespace Onemla\Behaviors;

use Onemla\OnemlaHelper;
use Think\Behavior;
use Think\Route;

class OnemlaAppInitBehavior extends Behavior{
    //行为执行入口
    public function run(&$param){
        $this->start();
    }

    protected function start(){

        //加载配置文件
        $pAppConf = C("APP_CONF");
        if ( ! empty($pAppConf) ) {
            $pAppConfs = explode(",", $pAppConf);
            if ( ! empty($pAppConfs) ) {
                foreach ($pAppConfs as $fileName) {
                    $pFullPath = CONF_PATH.'Common/'.$fileName.CONF_EXT;
                    if(is_file($pFullPath)) {
                        C(include $pFullPath);
                    }
                }
            }
        }


        $this -> app_url();
        $this -> app_init();

        if(C('CORS_USEXDOMAIN')){
            OnemlaHelper::Vendor("Cors/CorsOnemla");
            $cors = new \CorsOnemla\CorsOnemla();
            $cors->handle();
        }
    }

    private function app_init(){
        $varModule      =   C('VAR_MODULE');
        $varPath        =   C('VAR_PATHINFO');
        $depr           =   C('URL_PATHINFO_DEPR');
        if(isset($_GET[$varPath])) { // 判断URL里面是否有兼容模式参数
            $_SERVER['PATH_INFO'] = $_GET[$varPath];
        }elseif(IS_CLI){ // CLI模式下 index.php module/controller/action/params/...
            $_SERVER['PATH_INFO'] = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : '';
        }

        if(!empty($_SERVER['PATH_INFO'])){
            if(!defined('BIND_MODULE') && (!C('URL_ROUTER_ON') || !Route::check())) {
                if ( C('MULTI_MODULE')) { // 获取模块名
                    $paths = explode($depr, trim($_SERVER['PATH_INFO'], '/'), 2);
                    $allowList = C('MODULE_ALLOW_LIST'); // 允许的模块列表
                    $module = preg_replace('/\.' . __EXT__ . '$/i', '', $paths[0]);
                    if (empty($allowList) || (is_array($allowList) && in_array_case($module, $allowList))) {
                        $_GET[REQUEST_MODULE] = $module;
                        $_SERVER['PATH_INFO']   =   isset($paths[1])?$paths[1]:'';
                    }
                }
            }
        }

        unset($_GET[$varPath]);
        $this -> bind();
    }

    private function bind(){
        $component = &$_GET[REQUEST_MODULE];
        $component = filter_var($component, FILTER_SANITIZE_STRING);
        if(!$component){
            $component = REQUEST_MODULE_DEFAULT;
        }

        defined('COMPONENTS_INPUT') or define('COMPONENTS_INPUT', $component);
        defined('COMPONENTS_PATH') or define('COMPONENTS_PATH', ADDON_PATH.COMPONENTS_INPUT);
        define('BIND_MODULE', C('DEFAULT_MODULE'));
        $_GET[ADDON_INPUT] = COMPONENTS_INPUT;

        unset($_GET[REQUEST_MODULE]);
    }

    private  function app_url() {
        $varPath        =   C('VAR_PATHINFO');
        if(isset($_GET[$varPath])) { // 判断URL里面是否有兼容模式参数
            $_SERVER['PATH_INFO'] = $_GET[$varPath];
        }

        if(!defined('__DOMAIN__')){
            $url = $this->get_url();
            $urlMode        =   C('URL_MODEL');
            if($urlMode == URL_COMPAT ){// 兼容模式判断
                define('__DOMAIN__',_PHP_FILE_.'?'.$varPath.'=');
            }elseif($urlMode == URL_REWRITE ) {

                if ($_SERVER['PATH_INFO'] != '') {
                    $url  = substr($url, 0, strpos(urldecode($url), $_SERVER['PATH_INFO']));
                }

                if($url == '/' || $url == '\\')
                    $url    =   '';
                define('__DOMAIN__',$url);
            }else {
                define('__DOMAIN__',_PHP_FILE_);
            }

            // 当前应用地址
            C('TMPL_PARSE_STRING.__DOMAIN__', __DOMAIN__);
        }
    }

    private function get_url(){
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);

        return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
    }
}
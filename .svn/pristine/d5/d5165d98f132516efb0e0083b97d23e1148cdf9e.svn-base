<?php
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 2015/4/15
 * Time: 20:57
 */
namespace Onemla;
use Onemla\Access\OnemlaAccess;
use Onemla\BaseController;

class ViewController extends  BaseController
{

    protected $_theme = '';
    public function __construct()
    {
        $this -> _theme = C('DEFAULT_THEME');
        parent::__construct();
    }

    protected function theme($theme){
        $this -> _theme = $theme;
        return $this;
    }

    protected function theme_display($templateFile = '', $layer = ''){
        $this -> theme(OnemlaHelper::getTheme()) -> display($templateFile, $layer);
    }

    /**
     * 模板显示 重新整理模板路径后调用内置的模板引擎显示方法，
     * @access protected
     * 默认为空 由系统自动定位模板文件
     * @param string $templateFile 指定要调用的模板文件
     * @return void
     */
    protected function display($templateFile = '', $layer = '')
    {
        $templateFile = $templateFile ? $templateFile : C('DEFAULT_ACTION');
        $layer = $layer ? $layer : ADDON_INPUT;

        $array = explode('/', $templateFile);
        $count = count($array);

        if ($count == 1) {
            $templateFile = $layer . '://' . COMPONENTS_INPUT . '@' . $templateFile;
        } elseif ($count == 2) {
            $templateFile = $layer . '://' . $array[0] . '@' . $templateFile;
        } else {
            $templateFile = $array[0] . '@' . $array[1];
            unset($array[0]);
            unset($array[1]);
            foreach ($array as $file) {
                $templateFile .= '/' . $file;
            }
            $templateFile = $layer . '://' . $templateFile;
        }

//        echo T($templateFile,'',$this -> _theme);die;
        parent::display(T($templateFile,'',$this -> _theme));
//        $this->display(T($templateFile,'','blue'));
    }

    //当前组件路径
    protected function component_path($component = '')
    {
        $component = $component ? $component : COMPONENTS_INPUT;
        return ADDON_INPUT . $component . '/';
    }

    public function redirect($url, $params = array(), $delay = 0, $msg = '')
    {
        $url = Url($url, $params);
        redirect($url, $delay, $msg);
    }

    public function httpReturn($flag, $body = null, $msg = '') {
        return $this -> ajaxReturn(array(
            'flag' => $flag,
            'body' => $body,
            'msg'  => $msg,
        ));
    }

    public function checkAdminAccess($pAction)
    {
        if ( ! OnemlaAccess::checkAdminAccess($pAction) ) {
            $this -> error("您没有权限执行该操作");
        }
    }

    /**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param string $message 错误信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    protected function error($message='',$jumpUrl='',$ajax=false) {
        $this->dispatchJump($message,0,$jumpUrl,$ajax);
    }

    /**
     * 操作成功跳转的快捷方法
     * @access protected
     * @param string $message 提示信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    protected function success($message='',$jumpUrl='',$ajax=false) {
        $this->dispatchJump($message,1,$jumpUrl,$ajax);
    }

    /**
     * 默认跳转操作 支持错误导向和正确跳转
     * 调用模板显示 默认为public目录下面的success页面
     * 提示页面为可配置 支持模板标签
     * @param string $message 提示信息
     * @param Boolean $status 状态
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @access private
     * @return void
     */
    protected function dispatchJump($message,$status=1,$jumpUrl='',$ajax=false) {
        if(true === $ajax || IS_AJAX) {// AJAX提交
            $data           =   is_array($ajax)?$ajax:array();
            $data['info']   =   $message;
            $data['status'] =   $status;
            $data['url']    =   $jumpUrl;
            $this->ajaxReturn($data);
        }
        if(is_int($ajax)) $this->assign('waitSecond',$ajax);
        if(!empty($jumpUrl)) $this->assign('jumpUrl',$jumpUrl);
        // 提示标题
        $this->assign('msgTitle',$status? L('_OPERATION_SUCCESS_') : L('_OPERATION_FAIL_'));
        //如果设置了关闭窗口，则提示完毕后自动关闭窗口
        if($this->get('closeWin'))    $this->assign('jumpUrl','javascript:window.close();');
        $this->assign('status',$status);   // 状态
        //保证输出不受静态缓存影响
        C('HTML_CACHE_ON',false);
        if($status) { //发送成功信息
            $this->assign('message',$message);// 提示信息
            // 成功操作后默认停留1秒
            if(!isset($this->waitSecond))    $this->assign('waitSecond','1');
            // 默认操作成功自动返回操作前页面
            if(!isset($this->jumpUrl)) $this->assign("jumpUrl",$_SERVER["HTTP_REFERER"]);

            parent::display(C('TMPL_ACTION_SUCCESS'));
//            $this->display(C('TMPL_ACTION_SUCCESS'));
        }else{
            $this->assign('error',$message);// 提示信息
            //发生错误时候默认停留3秒
            if(!isset($this->waitSecond))    $this->assign('waitSecond','3');
            // 默认发生错误的话自动返回上页
            if(!isset($this->jumpUrl)) $this->assign('jumpUrl',"javascript:history.back(-1);");

            parent::display(C('TMPL_ACTION_ERROR'));
//            $this->display(C('TMPL_ACTION_ERROR'));
            // 中止执行  避免出错后继续执行
            exit ;
        }
    }
}
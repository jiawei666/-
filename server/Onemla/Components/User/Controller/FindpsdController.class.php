<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/7 0007
 * Time: 上午 10:18
 */

namespace Components\User\Controller;

use Components\User\Model\UserModel;
use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Services\SMS\SMSService;
use Services\User\User;
use Services\User\UserService;

class FindpsdController extends ViewController
{

    public function entryphone_num()
    {
        $this->display('entryphone_num');
    }

    public function sendmessage()
    {//发送短信

        $phone_num = OnemlaRequest::getVar('phone');
        $user_model = new UserModel();
        $user_name = $user_model->where(array('phone' => $phone_num))->getField('user_name');
        if ($user_name == '') {
            $this->httpReturn(2, null, '改手机未注册');
        } else {
            for ($i = 0; $i < 4; $i++) {//制作验证码
                $verify .= rand(0, 9);
            }
            $arr = array($verify);
            $res = sendTemplateSMS($phone_num, $arr, 162699);//发送短信验证码
            if ($res['res'] == 1) {//发送成功则返回,并储存session信息
                session($user_name . 'psd_vrf', $verify);//以用户名命名session
                $this->httpReturn(1);
            } else {
                $this->httpReturn(2, null, $res['1']);
            }
        }
    }

    public function check_vrf()
    {//校验验证码
        $info = OnemlaRequest::getVar("");

        $phone_num = $info['phone'];
        $user_model = new UserModel();
        $user_name = $user_model->where(array('phone' => $phone_num))->getField('user_name');//查找用户名

        $verify = $info['verify'];
        if ($verify == session($user_name . 'psd_vrf')) {
            session($user_name . 'psd_vrf_true', 'check_pass');//验证正确,则储存一个session防止跳过验证
            wingo('验证码正确', U('User/Findpsd/editpsd?user_name=' . $user_name));
        } else {
            win_savedata_back('验证码错误,请重新输入');
        }
    }

    public function editpsd()
    {
        $user_name = OnemlaRequest::getVar('user_name');
        if (session('?' . $user_name . 'psd_vrf_true')) {//判断是否通过验证
            $this->assign('user_name', $user_name);
            $this->display('editpsd');
        }
    }

    public function changepsd()
    {
        $info = OnemlaRequest::getVar("");

        $user_name = $info['user_name'];
        if (session('?' . $user_name . 'psd_vrf_true')) {
            $user_service = new UserService();
//            $password = $model->where(array("user_name" => $user_name))->getField('password');
            if ($info['password'] != $info['ag_password']) {
                win_savedata_back('2次输入密码不一致，请重新输入');
                return;
            }
            $result = $user_service->changeUserPwd($user_name, $info['password'], 1);

            if ($result) {//清空session并跳转
                session($user_name . 'psd_vrf_true', null);
                session($user_name . 'psd_vrf', null);
                wingo("修改密码成功！", U("User/Index/login_page"));
            } else {
                win_savedata_back("修改密码失败！");
            }
       }
    }
}

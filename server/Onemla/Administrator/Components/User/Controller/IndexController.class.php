<?php

namespace Components\User\Controller;

use Components\User\Model\UserModel;
use Components\User\Model\SmsVerifyModel;
use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Services\SMS\SMSService;
use Services\User\User;
use Services\User\UserService;

class IndexController extends ViewController {

    public function login_page() {
        $user_name = cookie('member_user_name');
        $pwd = cookie('member_password');

        $this->assign('user_name', $user_name);
        $this->assign('password', $pwd);

        $this->display("index");
    }

    /**
     * 注册
     */
    public function register_page() {
        $this->display("register_page");
    }


    public function login() {
        $user_name = OnemlaRequest::getString('user_name');
        $pwd = OnemlaRequest::getString('password');
//        $verify = OnemlaRequest::getString('verify');
//        $is_verify = $this -> check_verify($verify);
//        if($is_verify){
        $isSave = OnemlaRequest::getString('checkbox') == 'on' ? true : false;
        if ($isSave) {
            cookie('member_user_name', $user_name);
            cookie('member_password', $pwd);
        }
        $userService = new UserService();

        $rst = $userService->verifyAccount($user_name, $pwd);

        if ($rst == UserService::CODE_SUCCESS) {
            $user = OnemlaHelper::getUser();
            if ($user->user_type == 3) {
                $this->redirect(U('Member/Index/index'));
            } else {
                $this->redirect(U('Home/Index/index'));
            }
        } else {
            $this->error($userService->getErrorMessage($rst));
        }
//        }else{
//            $this -> error('验证码错误');
//        }
    }

    public function logout() {
        $userService = new UserService();
        $userService->logout();

        $user_name = cookie('member_user_name');
        $pwd = cookie('member_password');

        $this->assign('user_name', $user_name);
        $this->assign('password', $pwd);

        $this->redirect(U('User/Index/login_page'));
    }


    /**
     * 发送短信验证码
     * */
    public function sendmessage(){
        $phone_num = OnemlaRequest::getVar('phone');
        $model = new UserModel();
        $user =$model->where(array('phone'=>$phone_num))->getField('user_name');
        if($user){
            $arr = array('res'=>0,'msg'=>'该手机已注册');
            $arr = urldecode(json_encode($arr));
            echo $arr;
            exit;
        }
        for($i=0;$i<4;$i++){//制作验证码
            $verify.=rand(0,9);
        }
        $arr = array($verify);
        $res = sendTemplateSMS($phone_num,$arr,162699);//发送短信验证码
        if($res['res']==1){//发送成功则返回,并储存session信息
            session($phone_num.'register_vrf',$verify);//以手机号命名session
            $arr = array('res'=>1);
            $arr = urldecode(json_encode($arr));
            echo $arr;
        }else{
            $arr = array('res'=>0,'msg'=>$res[1]);
            $arr = urldecode(json_encode($arr));
            echo $arr;
        }
    }


    /**
     * 注册
     * */
    public function register() {
        $data['user_name'] = OnemlaRequest::getString('user_name');
        $data['password'] = OnemlaRequest::getString('password');
        $pword_confirm = OnemlaRequest::getString('pword_confirm');
        $data['email'] = OnemlaRequest::getString('email');
        $data['wechat'] = OnemlaRequest::getString('wechat');
        $data['qq'] = OnemlaRequest::getString('qq');
        $data['phone'] = OnemlaRequest::getString('phone');
        $verify = OnemlaRequest::getString('verify');
        if ($verify == session($data['phone'].'register_vrf') && $verify != '') {//验证手机验证码
            $user_service = new UserService();
            if ($data['password'] != $pword_confirm) {
                win_savedata_back('2次输入密码不一致，请重新输入');
                return;
            }
            if ($user_service->isHaveRegister($data['user_name'], UserService::AUTH_TYPE_USER_NAME)) {
                win_savedata_back('该账号已被注册');
                return;
            }
            if ($user_service->isHaveRegister($data['email'], UserService::AUTH_TYPE_EMAIL)) {
                win_savedata_back('该邮箱已被注册');
                return;
            }

//        $sms = new SmsVerifyModel();
//        if(!$sms -> check_verify($data['phone'],$verify,SmsVerifyModel::SMS_TYPE_REGISTER)){
//            $this -> error('短信验证码错误');
//            return;
//        }

            $data['real_name'] = $data['user_name'];
            $data['user_type'] = $user_service::USER_TYPE_MEMBER;
            $result = $user_service->registerUser($data, true);
            if ($result) {
                session($data['phone'].'register_vrf',null);
                wingo('注册成功', U('Member/Index/index'));
            } else {
                win_savedata_back('注册失败');
            }
        } else {
            win_savedata_back('验证码错误,注册失败');
        }
    }

    /**
     *
     * 验证码生成
     */
    public function verify_c() {
        $Verify = new \Think\Verify();
        $Verify->fontSize = 18;
        $Verify->length = 4;
        $Verify->useNoise = false;
        $Verify->codeSet = '0123456789';
        $Verify->imageW = 120;
        $Verify->imageH = 40;
        $Verify->entry();
    }

    /**
     * 验证码检查
     */
    function check_verify($code, $id = "") {
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }

    public function send_verify() {
        $phone = OnemlaRequest::getString('phone');
        $verify = generate_code();

        $sms = new SmsVerifyModel();
        $sms->save_verify($phone, $verify, SmsVerifyModel::SMS_TYPE_REGISTER);

        $sms_service = new SMSService();
        $result = $sms_service->verification($verify, $phone);

        if ($result['returnstatus'] == 'Success')
            $this->httpReturn(1);
        else
            $this->httpReturn(2, null, $result['message']);
    }


}

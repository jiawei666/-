<?php

namespace Components\User\Controller;

use Components\User\Model\SmsVerifyModel;
use Components\User\Model\UserModel;
use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Services\SMS\SMSService;
use Services\User\User;
use Services\User\UserService;
use Services\Mail\MailService;

class IndexController extends ViewController {

    public function login_page(){
        $user_name = cookie('member_user_name');
        $pwd = cookie('member_password');

        $this->assign('user_name', $user_name);
        $this->assign('password', $pwd);

        $this->display("login_page");
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
            $this->redirect(U('Home/Index/index'));
        } else {
            win_savedata_back($userService->getErrorMessage($rst));
        }
    }

    public function logout() {
        $userService = new UserService();
        $userService->logout();

        $user_name = cookie('member_user_name');
        $pwd = cookie('member_password');

        $this->assign('user_name', $user_name);
        $this->assign('password', $pwd);

        $this->redirect(U('Home/Index/index'));
    }

    /*
     * 发送验证码
     * */
    public function send_verify() {

        $phone = OnemlaRequest::getString('phone');

        $model = new UserModel();
        $user =$model->where(array('phone'=>$phone))->getField('user_name');
        if($user){
            $this->httpReturn(2, null,'该手机已注册');
            exit;
        }
        $verify = generate_code();
        $arr = array($verify);
        $res = sendTemplateSMS($phone,$arr,162699);

        if ($res['res'] == '1'){
            session($phone.'register_vrf',$verify);//以手机号命名session
            $this->httpReturn(1);
        }else{
            $this->httpReturn(2, null, $res['1']);
        }

    }

    /**
     * 注册页面
     * */
    public function register_page(){
        $this->display('register_page');
    }

    /**
     * 注册
     * */
    public function register() {
        $data['user_name'] = OnemlaRequest::getString('register-user');
        $data['password'] = OnemlaRequest::getString('register-password');
        $agpassword = OnemlaRequest::getString('register-agpassword');
        $data['email'] = OnemlaRequest::getString('register-email');
        $data['wechat'] = '';
        $data['qq'] = '';
        $data['phone'] = OnemlaRequest::getString('register-tel');
        $verify = OnemlaRequest::getString('register-code');//手机验证码


        if($verify == session($data['phone'].'register_vrf') && $verify != ''){
            $user_service = new UserService();
            if ($data['password'] != $agpassword) {
                win_savedata_back('2次输入密码不一致，请重新输入');
                return;
            }

            if ($user_service->isHaveRegister($data['user_name'], UserService::AUTH_TYPE_USER_NAME)) {
                win_savedata_back('该账号已被注册');
                return;
            }

            if ($user_service->isHaveRegister($data['phone'], UserService::AUTH_TYPE_PHONE)) {
                win_savedata_back('该手机号已被注册');
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
            $result = $user_service->registerUser($data, true);
            if ($result) {
                session($data['phone'].'register_vrf',null);
                wingo('注册成功', U('Home/Index/index'));
            } else {
                winback('注册失败');
            }

        }else{
            win_savedata_back('验证码错误');
        }

    }

//    /**
//     *
//     * 验证码生成
//     */
//    public function verify_c() {
//        $Verify = new \Think\Verify();
//        $Verify->fontSize = 18;
//        $Verify->length = 4;
//        $Verify->useNoise = false;
//        $Verify->codeSet = '0123456789';
//        $Verify->imageW = 120;
//        $Verify->imageH = 40;
//        $Verify->entry();
//    }
//
//    /**
//     * 验证码检查
//     */
//    function check_verify($code, $id = "") {
//        $verify = new \Think\Verify();
//        return $verify->check($code, $id);
//    }





    /*
     * 发送邮箱找回密码
     * */
    public function findpsd(){
        $email = '957089263@qq.com';
        $mailModel = new MailService();
        $res = $mailModel->sendFindPWDMail($email);

    }

}

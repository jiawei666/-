<?php

namespace Components\Admin\Controller;
use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Services\User\UserService;
use Components\User\Model\UserModel;

class IndexController extends ViewController {
    public function __construct()
    {//防止会员通过更改url跳转到管理员界面
        parent::__construct();
        if(OnemlaHelper::getUser()->user_type==3){
            $this->redirect('Member/Index/index');
            exit;
        }
    }

    public function index() {

        $user_name = cookie('user_name');
        $pwd = cookie('password');

        $this -> assign('user_name',$user_name);
        $this -> assign('password',$pwd);
        $this -> display("index");
    }

    public function login(){

        $user_name = OnemlaRequest::getString('user_name');
        $pwd = OnemlaRequest::getString('password');
        $isSave = OnemlaRequest::getString('checkbox') == 'on' ? true : false;
        if($isSave){
            cookie('user_name',$user_name);
            cookie('password',$pwd);
        }
        $userService = new UserService();

        $rst = $userService -> verifyAccount($user_name,$pwd,UserService::USER_TYPE_SUPER_ADMIN);

        if($rst == UserService::CODE_SUCCESS){
            $this -> redirect(U('Home/Index/index'));
        }else{
            $this -> error($userService -> getErrorMessage($rst));
        }
    }

    public function logout(){
        $userService = new UserService();
        $userService-> logout();

        $user_name = cookie('user_name');
        $pwd = cookie('password');

        $this -> assign('user_name',$user_name);
        $this -> assign('password',$pwd);
        $this -> display("index");
    }

    /******************************************************************************************************************/
    public function register_page(){
        $this->display('register_page');
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

    /*
     * 新增管理员
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
        if($verify == session($data['phone'].'register_vrf') && $verify!=''){
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
            $data['user_type'] = $user_service::USER_TYPE_ADMIN;
            $result = $user_service->registerUser($data, true);
            if ($result) {
                session($data['phone'].'register_vrf',null);
                wingo('注册成功', U('Home/Index/index'));
            } else {
                win_savedata_back('注册失败');
            }
        }else{
            win_savedata_back('验证码错误,注册失败');
        }

    }
    public function addAdmin(){
        $this->display();
    }
}
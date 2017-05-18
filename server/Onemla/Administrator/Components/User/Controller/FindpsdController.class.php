<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/22 0022
 * Time: 下午 2:11
 */
namespace Components\User\Controller;

use Components\User\Model\UserModel;
use Components\User\Model\SmsVerifyModel;
use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Services\SMS\SMSService;
use Services\User\User;
use Services\User\UserService;

class FindpsdController extends ViewController{

    public  function way(){
        $this->display('way');
    }

    public function entryphone_num(){
        $this->display('entryphone_num');
    }

    public function sendmessage(){//发送短信
        $phone_num = OnemlaRequest::getVar('phone');
        $user_model = new UserModel();
        $user_name = $user_model->where(array('phone'=>$phone_num))->getField('user_name');
        if($user_name == ''){
            $arr = array('res'=>0,'msg'=>'该手机号码未注册');
            $arr = urldecode(json_encode($arr));
            echo $arr;
        }else{
            for($i=0;$i<4;$i++){//制作验证码
                $verify.=rand(0,9);
            }
            $arr = array($verify);
            $res = sendTemplateSMS($phone_num,$arr,162699);//发送短信验证码
            if($res['res']==1){//发送成功则返回,并储存session信息
                session($user_name.'psd_vrf',$verify);//以用户名命名session
                $arr = array('res'=>1,'phone'=>$phone_num,'user_name'=>$user_name);
                $arr = urldecode(json_encode($arr));
                echo $arr;
            }else{
                $arr = array('res'=>0,'msg'=>$res[1]);
                $arr = urldecode(json_encode($arr));
                echo $arr;
            }
        }
    }

//    public function entryverify(){
//        $user_name = OnemlaRequest::getVar('user_name');
//        $this -> assign('user_name',$user_name);
//        $this -> display('entryverify');
//    }

    public function check_vrf(){//校验验证码
        $info = OnemlaRequest::getVar("");

        $phone_num = $info['phone'];
        $user_model = new UserModel();
        $user_name = $user_model->where(array('phone'=>$phone_num))->getField('user_name');//查找用户名

        $verify = $info['verify'];
        if ($verify == session($user_name.'psd_vrf')) {
            session($user_name.'psd_vrf_true','check_pass');//验证正确,则储存一个session防止跳过验证
            wingo( '验证码正确',U('User/Findpsd/editpsd?user_name='.$user_name));
        } else {
            win_savedata_back('验证码错误,请重新输入');
        }
    }

    public function entryEmail(){//发送邮箱界面
        $this->display('entryEmail');
    }

    public function sendEmail(){//发送邮件
        $email = OnemlaRequest::getVar('email');
        $usermodel = M('user');
        $where['email']=array('eq',$email);
        $user_name = $usermodel->where($where)->getField('user_name');

        if($user_name == ''){
            $this->httpReturn(2,'','该邮箱没有被注册');
        }else {
            $title = '验证码';
            for($i=0;$i<4;$i++){
                $verify.=rand(0,9);
            }
            $verifyHtml = '这是您的验证码:<h1 style="color: #00acd6">'.$verify.'</h1>';
            if(sendEmail($email,$title,$verifyHtml)=='发送成功'){
                session($user_name.'psd_email_vrf',$verify);//将验证码存到session里
                $this->httpReturn(1,'','发送邮件成功,请注意到邮箱查收');
            }else{
                $this->httpReturn(2,'','发送邮件失败');
            }
        }
    }

    public function check_email_vrf(){//校验验证码
        $info = OnemlaRequest::getVar("");

        $email = $info['email'];
        $user_model = new UserModel();
        $user_name = $user_model->where(array('email'=>$email))->getField('user_name');//查找用户名

        $verify = $info['verify'];
        if ($verify == session($user_name.'psd_email_vrf')) {
            session($user_name.'psd_vrf_true','check_pass');//验证正确,则储存一个session防止跳过验证
            wingo( '验证码正确',U('User/Findpsd/editpsd?user_name='.$user_name));
        }else{
            win_savedata_back('验证码错误,请重新输入');
        }
    }

    public function editpsd(){
        $user_name = OnemlaRequest::getVar('user_name');
        if (session('?'.$user_name.'psd_vrf_true')) {//判断是否通过验证
            $this -> assign('user_name',$user_name);
            $this -> display('editpsd');
        }
    }

    public function changepsd(){
        $info = OnemlaRequest::getVar("");
        $user_name = $info['user_name'];
        if (session('?'.$user_name.'psd_vrf_true')){
            $user_service = new UserService();
//            $password = $model->where(array("user_name" => $user_name))->getField('password');
            if ($info['password'] != $info['ag_password']) {
                $this->error('2次输入密码不一致，请重新输入');
                return;
            }
            $result = $user_service->changeUserPwd($user_name, $info['password'], 1);

            if ($result) {//清空session并跳转
                session($user_name.'psd_vrf_true',null);
                session($user_name.'psd_vrf',null);
                $this->success("修改密码成功！", U("User/Index/logout"));
            } else {
                $this->error("修改密码失败！");
            }
        }
    }
}
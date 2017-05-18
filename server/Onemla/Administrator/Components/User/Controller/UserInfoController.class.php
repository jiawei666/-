<?php

namespace Components\User\Controller;

use Components\User\Model\SmsVerifyModel;
use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Services\SMS\SMSService;
use Onemla\SessionViewController;
use Components\Replace\Model\UserModel;
use Components\User\Model\UserInfoModel;
use Components\Mycar\Model\CarModelModel;
use Services\User\UserService;
use Components\User\Model\MessageModel;
use Org\Page\Page;
use Components\User\Model\EmailVerifyModel;

class UserInfoController extends SessionViewController {

    /**
     * 我的资料
     */
    public function userInfo() {
        $UserModel = new UserModel();
        $CarModelModel = new CarModelModel();
        $user_id = OnemlaHelper::getUserId();
        $field = "u.*,ui.money,ui.nick_name,ui.age,ui.logo,c.car_model_id,c.car_belonging,c.car_letter,c.car_num,c.purchase_date";
        $info = $UserModel->alias("u")->where(array("u.id" => $user_id))
                        ->join("tb_user_info as ui on ui.user_id = u.id")
                        ->join("tb_car as c on c.user_id = u.id")
                        ->field($field)->order("c.id desc")->find(); //账户余额
        $info['model'] = $CarModelModel->where(array("id" => $info['car_model_id']))->field("model")->find();
        $logo = substr($info['logo'], 0, 4);
        $this->assign('logo', $logo);
        $this->assign('info', $info);
        $this->display("user_info");
    }

    /**
     * 修改我的资料
     */
    public function userInfoEdit() {
        $UserModel = new UserModel();
        $CarModelModel = new CarModelModel();
        $user_id = OnemlaHelper::getUserId();
        $field = "u.*,ui.money,ui.nick_name,ui.age,ui.logo,c.car_model_id,c.car_belonging,c.car_letter,c.car_num,c.purchase_date";
        $info = $UserModel->alias("u")->where(array("u.id" => $user_id))
                        ->join("tb_user_info as ui on ui.user_id = u.id")
                        ->join("tb_car as c on c.user_id = u.id")
                        ->field($field)->order("c.id desc")->find(); //账户余额
        $info['model'] = $CarModelModel->where(array("id" => $info['car_model_id']))->field("model")->find();
        $logo = substr($info['logo'], 0, 4);
        $this->assign('logo', $logo);
        $this->assign('info', $info);
        $this->display("user_info_edit");
    }

    /**
     * 登陆密码修改
     */
    public function editPassword() {
        $UserModel = new UserModel();
        $UserService = new UserService();
        $info = OnemlaRequest::getVar("");
        //验证原密码
        $pHash = $UserService->hashPassword($info['pword_new']); //密码的hash值
        $UserInfo = $UserModel->where(array("id" => OnemlaHelper::getUserId()))->find();
        $result = $UserService->verifyPassword($info['pword'], $UserInfo['password']); //验证原密码是否一致
        if ($result) {
            $success = $UserModel->where(array("id" => OnemlaHelper::getUserId()))->save(array("password" => $pHash));
            if ($success) {
                wingo("密码修改成功", U('User/Index/logout'));
            } else {
               win_savedata_back("密码修改失败");
            }
        } else {
            win_savedata_back("输入的旧密码与原密码不一致");
        }
    }

    /**
     * 设置支付密码
     */
    public function setPayPassword() {
        $UserModel = new UserModel();
        $UserService = new UserService();
        $info = OnemlaRequest::getVar("");
        $pHash = $UserService->hashPassword($info['pay_pword']); //密码加密
        $UserInfo = $UserModel->where(array("id" => OnemlaHelper::getUserId()))->find();
        if ($UserInfo['pay_password'] == '0') {
            $success = $UserModel->where(array("id" => OnemlaHelper::getUserId()))->save(array("pay_password" => $pHash));
            if ($success) {
                $this->success("成功设置支付密码");
            } else {
                $this->error("设置支付密码失败");
            }
        } else {
            $this->error("支付密码已经设置！");
        }
    }

    /**
     * 修改支付密码
     */
    public function editPayPassword() {
        $UserModel = new UserModel();
        $UserService = new UserService();
        $info = OnemlaRequest::getVar("");
        //验证原密码
        $pHash = $UserService->hashPassword($info['pay_pword_edit']); //密码的hash值
        $UserInfo = $UserModel->where(array("id" => OnemlaHelper::getUserId()))->find();
        $result = $UserService->verifyPassword($info['pay_pword_old'], $UserInfo['pay_password']); //验证原密码是否一致
        if ($result) {
            $success = $UserModel->where(array("id" => OnemlaHelper::getUserId()))->save(array("pay_password" => $pHash));
            if ($success) {
                $this->success("支付密码修改成功");
            } else {
                $this->error("支付密码修改失败");
            }
        } else {
            $this->error("输入的旧密码与原密码不一致");
        }
    }

    /**
     * 修改用户信息
     */
    public function editUserInfo() {
        $model = new UserInfoModel();
        $info = OnemlaRequest::getVar("");
        $file = upload_img(array(),$rootPath = "App/Res/Uploads");
        $logo = $model->where(array("user_id" => OnemlaHelper::getUserId()))->field("logo")->find();
        if ($file['info']['pic_url']['savename'] != '') {
            $files = delAppFile($logo['logo']);
            unlink($files);
            $logo = 'Res/Uploads/'.$file['info']['pic_url']['savename'];
        } else {
            $logo = $logo['logo'];
        }
        $data = array(
            'nick_name' => $info['nick_name'],
            'age' => $info['age'],
            'logo' => $logo,
        );
        $result = $model->where(array("user_id" => OnemlaHelper::getUserId()))->save($data);
        if ($result) {
            $this->success("修改成功！");
        } else {
            $this->error("修改失败！", U("User/UserInfo/userInfoEdit"));
        }
    }

    /**
     * 邮件发送
     */
    public function sendMail() {
        $info = OnemlaRequest::getVar("");
        $model = new \Services\Mail\MailService();
        $EmailModel = new EmailVerifyModel();
        $verify = generate_code();//验证码
        $EmailModel->save_verify($info['email'], $verify, 1);
        $content .= "验证需要修改的邮箱有效性，请点击一下链接进行验证：";
        $content .= "<br/>";
        $content .= "如点击无效，请复制到浏览器打开！";
        $content .= "<br/>";
        $content .= "http://".$_SERVER['HTTP_HOST'].U("User/UserInfo/editMail",array("email"=>$info['email'],"verify"=>$verify));
        $result = $model->sendMail($info['email'], "邮箱验证", "$content");
        $msg = array("result" => $result, "msg" => "201", 'id' => $id);
        echo json_encode($msg);
    }
    
    /**
     * 修改邮箱
     */
    public function editMail() {
        $model = new UserModel();
        $EmailVerifyModel = new EmailVerifyModel();
        $info = OnemlaRequest::getVar("");
        $flag = $EmailVerifyModel->check_verify($info['email'], $info['verify'], 1);
        if(!$flag){
            $this -> error('验证码错误', U("User/UserInfo/userInfoEdit"));
        }
        $data = array(
            'email' => $info['email'],
        );
        $result = $model->where(array("id" => OnemlaHelper::getUserId()))->save($data);
        if ($result) {
            $this->success("修改成功！", U("User/UserInfo/userInfoEdit"));
        } else {
            $this->error("修改失败！", U("User/UserInfo/userInfoEdit"));
        }
    }

    /**
     * 消息中心--未读
     */
    public function messageUnread() {
        $model = new MessageModel();
        $Page = new Page($model->get_count(0));
        $limit = '' . $Page->firstRow . ',' . $Page->listRows;
        $list = $model->where(array("user_id"=>  OnemlaHelper::getUserId(),"state" => 0, 'status' => 0))->limit($limit)->order("id desc")->select(); //未读消息
        $this->assign('list', $list);
        $this->assign('page_show', $Page->AdminShow());
        $this->assign('count_unread', $model->get_count(0)); //未读
        $this->assign('count_readed', $model->get_count(1)); //已读
        $this->display("message");
    }

    /**
     * 消息中心--已读
     */
    public function messageReaded() {
        $model = new MessageModel();
        $Page = new Page($model->get_count(1));
        $limit = '' . $Page->firstRow . ',' . $Page->listRows;
        $list = $model->where(array("user_id"=>  OnemlaHelper::getUserId(),"state" => 1, 'status' => 0))->limit($limit)->order("id desc")->select(); //已读消息
        $this->assign('list', $list);
        $this->assign('page_show', $Page->AdminShow());
        $this->assign('count_unread', $model->get_count(0)); //未读
        $this->assign('count_readed', $model->get_count(1)); //已读
        $this->display("message_readed");
    }

    /**
     * 标记为已读
     */
    public function updateReaded() {
        $model = new MessageModel();
        $id = OnemlaRequest::getVar("id");
        $result = $model->where(array("id" => $id))->save(array("state" => 1));
        if ($result) {
            $msg = array("result" => "success");
        } else {
            $msg = array("result" => "error");
        }
        echo json_encode($msg);
    }

    /**
     * 删除
     */
    public function delMessage() {
        $model = new MessageModel();
        $id = OnemlaRequest::getVar("id");
        $result = $model->where(array("id" => $id))->save(array("status" => 1));
        if ($result) {
            $msg = array("result" => "success");
        } else {
            $msg = array("result" => "error");
        }
        echo json_encode($msg);
    }

}

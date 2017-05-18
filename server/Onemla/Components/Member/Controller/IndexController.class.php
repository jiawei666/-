<?php

namespace Components\Member\Controller;

use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Components\User\Model\UserModel;
use Components\User\Model\UserInfoModel;
use Onemla\SessionViewController;
use Services\User\UserService;

class IndexController extends SessionViewController {

    /**
     * 用户列表
     */
    public function index() {
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_MEMBER);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_MEMBER_ONE);

        $model = new UserModel();
        $user_id = OnemlaHelper::getUserId();
        $info = $model->where(array("id" => $user_id))->find();
        $this->assign('info', $info);
        $this->display();
    }

    /**
     * 编辑会员信息
     */
    public function edit() {
        $model = new UserModel();
        $msg = $model->editMember();
        if ($msg) {
            $this->success("修改成功！", U("Member/Index/index"));
        } else {
            $this->success("修改失败！");
        }
    }

    /**
     * 密码设置
     */
    public function setPassword() {
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_MEMBER);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_MEMBER_TWO);

        $this->display("set_password");
    }

    /**
     * 修改密码
     */
    public function editPassword() {
        $model = new UserModel();
        $user_service = new UserService();
        $info = OnemlaRequest::getVar("");
        $uInfo = $model->where(array("id" => OnemlaHelper::getUserId()))->find();
        if ($info['password'] != $info['ag_password']) {
            $this->error('2次输入密码不一致，请重新输入');
            return;
        }
        $verifyPass = $user_service->verifyPassword($info['old_password'], $uInfo['password']);
        if (!$verifyPass) {
            $this->error('输入密码与原密码不一致');
            return;
        }

        $result = $user_service->changeUserPwd($uInfo['user_name'], $info['password'], 1);
        if ($result) {
            $this->success("修改密码成功！", U("User/Index/logout"));
        } else {
            $this->error("修改密码失败！");
        }
    }

    /**
     *认证信息--个人
     */
    public function certificationPersonal() {
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_MEMBER);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_MEMBER_THEWW);
        $model = new UserInfoModel();
        $info = $model->where(array("user_id" => OnemlaHelper::getUserId(),'type'=>1))->find();
        //获取城市列表
        $pCitySelectJs = getPCitySelectJs('province', 'city', $pid = $info['province_id'], $cid = $info['city_id']);
        $pCountryCfg = getCfgProvinceByCountry();
        $region = '';

        foreach ($pCountryCfg as $value) {
            $region = $region . '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
        }
        $this->assign("info",$info);
        $this->assign("region", $region);
        $this->assign('pCitySelectJs', $pCitySelectJs);

        $this->display("certification_personal");
    }

    /**
     * 修改--个人信息认证
     */
    public function editCertification() {
        $model = new UserInfoModel();
        $info = OnemlaRequest::getVar("");
        $rInfo = $model->where(array("user_id" => OnemlaHelper::getUserId()))->find();
        $file = upload_img(array(), $rootPath = "Res/Uploads");
        $card_image = $file['info']['card_image']['savename'];

        $card_image_url = $card_image == '' ? '' : delAdminFile($rInfo['card_image'], $pathUrl = "Res/Uploads/"); //删除本地图

        if ($info['type'] == 1) {//个人
            $data = array(
                'type' => $info['type'],
                'province_id' => $info['province_id'],
                'city_id' => $info['city'],
                'address' => $info['address'],
                'real_name' => $info['real_name'], //真实姓名
                'document_type' => $info['document_type'], //证件类型
                'card_id' => $info['card_id'], //证件号码
                'card_image' => $card_image, //证件照片
                'update_time' => date("Y-m-d H:i:s"),
            );
        } else {//公司
            $data = array(
                'type' => $info['type'],
                'province_id' => $info['province_id'],
                'city_id' => $info['city'],
                'address' => $info['address'],
                'real_name' => $info['real_name'], //公司名称
                'website' => $info['website'], //公司网站
                'card_id' => $info['card_id'], //营业执照号
                'card_image' => $card_image, //营业执照
                'update_time' => date("Y-m-d H:i:s"),
            );
        }

        $result = $model->where(array("user_id" => OnemlaHelper::getUserId()))->save($data);
        if ($result) {
            unlink($card_image_url);
            if ($info['type'] == 1) {
                $this->success("提交修改信息成功！", U("Member/Index/certificationPersonal"));
            } else {
                $this->success("提交修改信息成功！", U("Member/Index/certificationCompany"));
            }
        } else {
            $this->error("修改密码失败！");
        }
    }

    /**
     * 认证信息--公司
     */
    public function certificationCompany() {
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_MEMBER);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_MEMBER_THEWW);
        
        $model = new UserInfoModel();
        $info = $model->where(array("user_id" => OnemlaHelper::getUserId(),'type'=>2))->find();
        //获取城市列表
        $pCitySelectJs = getPCitySelectJs('province', 'city', $pid = $info['province_id'], $cid = $info['city_id']);
        $pCountryCfg = getCfgProvinceByCountry();
        $region = '';

        foreach ($pCountryCfg as $value) {
            $region = $region . '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
        }
        $this->assign("info",$info);
        $this->assign("region", $region);
        $this->assign('pCitySelectJs', $pCitySelectJs);
        
        $this->display("certification_company");
    }

}

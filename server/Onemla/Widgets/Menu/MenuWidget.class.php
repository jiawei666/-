<?php

/**
 * Created by PhpStorm.
 */

namespace Widgets\Menu;

use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Components\User\Model\UserModel;

class MenuWidget extends ViewController {

    public function Index() {
        $user = OnemlaHelper::getUser();
        $this->assign('user_name',$user->user_name);
        $this->display('Menu/Menu', 'Widgets');
    }

    public function Left_Menu() {
        $id = OnemlaHelper::getUserId();
        $model = new UserModel();
//        $AdminModel = new AdminModel();
//        $uInfo = $AdminModel->where(array("id"=>$id))->find();
        $info = $model->where(array("id"=>$id))->find();
//        $this->assign('permissions', parseStr2($info['permissions']));
        $this -> assign('actived',OnemlaHelper::getActived());
        $this -> assign('menu_actived',OnemlaHelper::getMenuActived());
        $this->assign('user_type',$info['user_type']);
        $this->display('Menu/Left_Menu', 'Widgets');
    }

    public function wechatbanner(){
        $bsnId = OnemlaRequest::getVar('bsn_id');
        $bsnModel = M('live_bsn');
        $bsnInfo = $bsnModel->where(array('id'=>$bsnId))->find();
        $this->assign('bsnInfo',$bsnInfo);
        $userInfoModel = M('user_info');
        $this->userInfo = $userInfoModel->where(array('user_id'=>$bsnInfo['user_id']))->field('type,live_name,public_logo,qr_code')->find();
        $this->session_open_id = session('open_id');
        $followModel = M('live_bsn_follow');
        $this->followInfo = $followModel->where(array('open_id'=>session('open_id'),'bsn_id'=>$bsnId))->find();
        $this->display('Menu/wechatbanner', 'Widgets');
    }

}

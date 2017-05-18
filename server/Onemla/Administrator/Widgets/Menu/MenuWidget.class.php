<?php
/**
 * Created by PhpStorm.
 */
namespace Widgets\Menu;
use Onemla\OnemlaHelper;
use Onemla\ViewController;
use Components\User\Model\UserModel;
class MenuWidget extends ViewController{
    public function Index() {
        $user = OnemlaHelper::getUser();
        $this->assign('user_name',$user->user_name);
        $this->display('Menu/Menu', 'Widgets');
    }

    public function Left_Menu() {
        $id = OnemlaHelper::getUserId();
        $model = new UserModel();
        $live_bsnModel = M('live_bsn');
//        $AdminModel = new AdminModel();
//        $uInfo = $AdminModel->where(array("id"=>$id))->find();
        $info = $model->where(array("id"=>$id))->find();
        $is_live = $live_bsnModel->where(array('user_id'=>$id))->find();
//        $this->assign('permissions', parseStr2($info['permissions']));
        $this -> assign('actived',OnemlaHelper::getActived());
        $this -> assign('menu_actived',OnemlaHelper::getMenuActived());
        $this->assign('user_type',$info['user_type']);
        $this->assign('is_live',$is_live);
        $this->display('Menu/Left_Menu', 'Widgets');
    }
}
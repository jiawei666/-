<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/6 0006
 * Time: 下午 2:11
 */

namespace Components\User\Controller;


use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ServeViewController;

class PermissionsController extends ServeViewController
{
    public function index(){
        $type = OnemlaRequest::getInt('type');
        $permissions_group = C('permissions_group');
        $perGroupModel = OnemlaHelper::getModel('PermissionsGroup');
        $permissions_group_list = $perGroupModel->getList();
//        dump($permissions_group_list);exit;
        $this->assign('type',$type);
        $this->assign('permissions_group',$permissions_group);
        $this->assign('permissions_group_list',$permissions_group_list);
        $this->display('permissions');
    }

    public function modify(){

        $data = $_POST;
        $str = '';
        foreach($data['permissions'] as $key=>$list){
            $str .= implode(',',$list).',';
        }
        $data['permissions'] = substr($str,0,-1);

        $perGroupModel = OnemlaHelper::getModel('PermissionsGroup');
        $result = $perGroupModel->modify($data);
        if($result)
            $this->success('操作成功',U('User/Permissions/index'));
        else
            $this->error('操作失败');
    }

    public function changeState(){
        $id = OnemlaRequest::getInt('id');
        $state = OnemlaRequest::getInt('state');
        $perGroupModel = OnemlaHelper::getModel('PermissionsGroup');
        $result = $perGroupModel->changeState($id,$state);
        $this->httpReturn($result ? 1 : 2);
    }

}
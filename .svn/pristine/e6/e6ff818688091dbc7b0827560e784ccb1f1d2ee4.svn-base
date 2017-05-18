<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/6 0006
 * Time: 下午 2:22
 */

namespace Components\User\Model;


use Onemla\OnemlaModel;

class PermissionsGroupModel extends OnemlaModel
{
    const PERMISSIONS_STATE_Y = 1;//权限组状态可用
    const PERMISSIONS_STATE_N = 0;//权限组状态禁用
    public function getList(){
        return $this->select();
    }

    public function modify($data){
        if(!empty($data['id'])){
            $data['update_time'] = time();
            $result = $this->data($data)->save();
        }else{
            $data['update_time'] = time();
            $data['create_time'] = time();
            $data['state'] = self::PERMISSIONS_STATE_Y;
            $result = $this->data($data)->add();
        }
        return $result;
    }

    public function changeState($id,$state){
        $where = array(
            'id' => $id,
        );
        if(empty($state))
            $data['state'] = self::PERMISSIONS_STATE_N;
        else
            $data['state'] = self::PERMISSIONS_STATE_Y;
        $data['update_time'] = time();
        return $this->where($where)->data($data)->save();
    }
}
<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 2016/9/26
 * Time: 15:45
 */

namespace Components\Repair\Model;

use Onemla\OnemlaHelper;
use Onemla\OnemlaModel;
use Onemla\OnemlaRequest;
use Org\Page\Page;
use Components\User\Model\UserModel;

class RepairRecordModel extends OnemlaModel {

    /**
     * 获取聊天记录
     */
    public function get_list(){
        $where['repair_order_id'] = OnemlaRequest::getVar("id");
        $list = $this->alias("a")->where($where)
            ->join("tb_user as u on u.id = a.user_id")->field("u.user_name,a.*")->order('ctime asc')->select();
        return $list;
    }

    /**
     * 根据ID获取聊天记录
     */
    public function get_list_by_id($id){
        $where['repair_order_id'] = $id;
        $list = $this->where($where)->field("type")->order('id desc')->find();
        return $list;
    }

    /**
     * 新增记录
     */
    public function add_list($type){
        $info = OnemlaRequest::getVar("");
        $data['repair_order_id'] = $info['repair_order_id'];
        $data['type'] = $type;
        $data['content'] = $info['content'];
        $data['user_id'] = OnemlaHelper::getUserId();
        $data['ctime'] = date('y-m-d h:i:s',time());
        $result = $this->add($data);
        $repairModel = M('repair_order');
        $status = $type==1 ? 1 : 2;
        $repairModel->where(array('id'=>$info['repair_order_id']))->save(array('status'=>$status));
        return $result;
    }

    public function delRecord($repairId){
        $this->where(array('repair_order_id'=>$repairId))->delete();
    }

}

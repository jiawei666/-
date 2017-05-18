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
        $list = $this->alias("a")->where($where)->join("tb_user as u on u.id = a.user_id")->field("u.user_name,a.*")->order('ctime asc')->select();
        return $list;
    }

    /**
     * 获取聊天记录
     */
    public function add_list(){
        $info = OnemlaRequest::getVar("");
        $data['repair_order_id'] = $info['repair_order_id'];
        $data['type'] = 1;
        $data['content'] = $info['content'];
        $data['user_id'] = OnemlaHelper::getUserId();
        $data['ctime'] = date('y-m-d h:i:s',time());
        print_r($data);exit;
        $result = $this->add($data);
        return $result;
    }

}

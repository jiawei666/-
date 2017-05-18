<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 2016/9/26
 * Time: 15:45
 */

namespace Components\Home\Model;

use Onemla\OnemlaHelper;
use Onemla\OnemlaModel;
use Onemla\OnemlaRequest;
use Org\Page\Page;
use Components\User\Model\UserModel;

class Live_chatModel extends OnemlaModel {

    /**
     * 获取聊天记录
     */
    public function get_list(){
        $from_user_id = OnemlaRequest::getVar("from_user_id");
        if($from_user_id != ''){
            $list = $this->alias("a")->where("(`from_user_id` = '".OnemlaHelper::getUserId()."' AND `to_user_id` = '".$from_user_id."') OR (`from_user_id` = '".$from_user_id."' AND `to_user_id` = '".OnemlaHelper::getUserId()."')")
                ->join("tb_user as u on u.id = a.from_user_id")->field("u.user_name,a.*")->order('create_time asc')->select();
        }
        return $list;
    }

    /**
     * 新增记录
     */
    public function add_list($type){
        $info = OnemlaRequest::getVar("");
        $data['type'] = $type;
        $data['content'] = $info['content'];
        $data['to_user_id'] = $info['to_user_id'];
        $data['from_user_id'] = OnemlaHelper::getUserId();
        $data['create_time'] = date('y-m-d h:i:s',time());
        $result = $this->add($data);
        return $result;
    }

    /**
     * 获取聊天记录
     */
    public function get_member_list(){
        $from_user_id = OnemlaRequest::getVar('from_user_id');
        if($from_user_id  == '') $from_user_id = 1;
        $list['from_user_name'] = $this->alias("a")
            ->join('tb_user as u on u.id = a.from_user_id')->field('u.user_name,u.id')
            ->where(array('to_user_id'=>OnemlaHelper::getUserId()))->select();
        foreach($list['from_user_name'] as $key=>$value){
            $count = count($list['from_user_name']);
            for($i=$key+1;$i<=$count;$i++){
                if($list['from_user_name'][$key] == $list['from_user_name'][$i]){
                    unset($list['from_user_name'][$i]);
                }
            }
            $list['from_user_name'] = array_merge($list['from_user_name']);
        }
        $list['from_user_id'] = $from_user_id;


        $list['content'] = $this->alias("a")->where("(`from_user_id` = '".OnemlaHelper::getUserId()."' AND `to_user_id` = '".$from_user_id."') OR (`from_user_id` = '".$from_user_id."' AND `to_user_id` = '".OnemlaHelper::getUserId()."')")
            ->join("tb_user as u on u.id = a.from_user_id")->field("u.user_name,a.*")->order('create_time asc')->select();
        return $list;
    }

    /**
     * 新增记录
     */
    public function add_member_list($type){
        $info = OnemlaRequest::getVar("");
        $data['type'] = $type;
        $data['content'] = $info['content'];
        $data['to_user_id'] = $info['to_user_id'];
        $data['from_user_id'] = OnemlaHelper::getUserId();
        $data['create_time'] = date('y-m-d h:i:s',time());
        $result = $this->add($data);
        return $result;
    }

}
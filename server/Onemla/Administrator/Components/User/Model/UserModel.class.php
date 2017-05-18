<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 2016/9/26
 * Time: 15:45
 */

namespace Components\User\Model;

use Onemla\OnemlaHelper;
use Onemla\OnemlaModel;
use Onemla\OnemlaRequest;
use Services\User\UserService;
use Org\Page\Page;

class UserModel extends OnemlaModel {

    /**
     * 用户信息
     */
    public function getList() {
        $user_name = OnemlaRequest::getVar("user_name"); //用户名称
        $phone = OnemlaRequest::getVar("phone"); //用户名称
        $date_start = OnemlaRequest::getVar("date_start");
        $date_end = OnemlaRequest::getVar("date_end");

        $where['id'] = array(array("NEQ", 3),array("NEQ", OnemlaHelper::getUserId())); //不显示超级管理员跟管理员本身
        $where['user_name'] = array("like", "%" . $user_name . "%");
        $where['phone'] = array("like", "%" . $phone . "%");
        if ($date_start != "" && $date_end != "") {
            $start = $date_start == '' ? '' : $date_start;
            $end = $date_end == '' ? '' : ($date_end . '23:59:59');
            $where['create_time'] = array(array('gt', $start), array('lt', $end));
        }
        $count = $this->where($where)->count();
        $Page = new Page($count, $listRows = 10, array('user_name' => $user_name));
        $limit = '' . $Page->firstRow . ',' . $Page->listRows;

        $list = $this
                ->where($where)
                ->limit($limit)
                ->field("id,user_name,email,wechat,qq,phone,state,create_time")
                ->order('create_time desc')
                ->select();
        $msg = array(
            'list' => $list,
            'count' => $count,
            'page_show' => $Page->AdminShow(),
            'user_name' => $user_name,
            'phone' => $phone,
            'date_start' => $date_start,
            'date_end' => $date_end,
        );
        return $msg;
    }

    /**
     * 编辑会员信息
     */
    public function editMember() {
        $info = OnemlaRequest::getVar("");


        $emailRes = $this->where(array('email'=>$info['email']))->getField('email');
        $presentEmail = $this->where(array('id'=>OnemlaHelper::getUserId()))->getField('email');
        if($emailRes!='' && $info['email']!=$presentEmail){
            $resArr = array('code'=>0,'msg'=>'修改失败,该邮箱已注册!');
            return $resArr;
            exit;
        }

        $res = $this->where(array('id'=>OnemlaHelper::getUserId()))->getField('phone');
        if($res != $info['phone'] && $info['phone']!=''){

            if($info['verify']==''){
                $resArr = array('code'=>0,'msg'=>'请输入验证码');
                return $resArr;
                exit;
            }

            if($info['verify'] == session($info['phone'].'edit_info_vrf')){
                $data = array(
                    'id' => OnemlaHelper::getUserId(),
                    'phone' => $info['phone'],
                    'email' => $info['email'],
                    'wechat' => $info['wechat'],
                    'qq' => $info['qq'],
                    'update_time' => date("Y-m-d H:i:s"),
                );
                $result = $this->save($data);
                if($result){
                    session($info['phone'].'edit_info_vrf',null);
                    $resArr = array('code'=>1,'msg'=>'修改成功！');
                    return $resArr;
                }else{
                    $resArr = array('code'=>0,'msg'=>'修改失败！');
                    return $resArr;
                }
            }else{
                $resArr = array('code'=>0,'msg'=>'验证码错误！');
                return $resArr;
            }
        }else{
            $data = array(
                'id' => OnemlaHelper::getUserId(),
                'email' => $info['email'],
                'wechat' => $info['wechat'],
                'qq' => $info['qq'],
                'update_time' => date("Y-m-d H:i:s"),
            );
            $result = $this->save($data);
            if($result){
                $resArr = array('code'=>1,'msg'=>'修改成功！');
                return $resArr;
            }else{
                $resArr = array('code'=>0,'msg'=>'修改失败！');
                return $resArr;
            }
            exit;
        }

    }
}

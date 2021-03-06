<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 2016/9/26
 * Time: 15:45
 */

namespace Components\Activity\Model;

use Onemla\OnemlaHelper;
use Onemla\OnemlaModel;
use Onemla\OnemlaRequest;
use Org\Page\Page;
use Components\User\Model\UserModel;

class ActivityModel extends OnemlaModel {

    /**
     * 活动列表
     */
    public function getList() {
        $user_name = OnemlaRequest::getVar("user_name"); //用户名称
        $title = OnemlaRequest::getVar("title"); //用户名称
        
        $model = new UserModel();
        $uWhere['user_name'] = array("like", "%" . $user_name . "%");
        $ulist = $model->where($uWhere)->select();
        foreach ($ulist as $v){
            $user_id .= $v['id'].','; 
        }
        $where['user_id'] = array("in",  $user_id );
        $where['title'] = array("like", "%" . $title . "%");
        
        $count = $this->where($where)->count();
        $Page = new Page($count, $listRows = 10, array('user_name'=>$user_name,'title'=>$title));
        $limit = '' . $Page->firstRow . ',' . $Page->listRows;
        
        $list = $this->alias("a")
                ->where($where)
                ->join("tb_user as u on u.id = a.user_id")
                ->field("u.user_name,a.*")
                ->limit($limit)
                ->select();
        $msg = array(
            'list' => $list,
            'count' => $count,
            'page_show' => $Page->AdminShow(),
            'user_name' => $user_name,
            'title' => $title,
        );
        return $msg;
    }
    
    /**
     * 
     * @return string
     * 会员活动列表
     */
    public function getMemberList() {
        $title = OnemlaRequest::getVar("title"); //用户名称
        $channel = OnemlaRequest::getVar("channel"); //频道
        
        $where['user_id'] = OnemlaHelper::getUserId();
        $where['title'] = array("like", "%" . $title . "%");
        if($channel){
            $where['channel'] = $channel;
        }
        
        
        $count = $this->where($where)->count();
        $Page = new Page($count, $listRows = 10, array('title'=>$title,'channel'=>$channel));
        $limit = '' . $Page->firstRow . ',' . $Page->listRows;
        
        $list = $this->alias("a")
                ->where($where)
                ->join("tb_user as u on u.id = a.user_id")
                ->field("u.user_name,a.*")
                ->limit($limit)
                ->select();
        $msg = array(
            'list' => $list,
            'count' => $count,
            'page_show' => $Page->AdminShow(),
            'user_name' => $user_name,
            'title' => $title,
            'channel' => $channel,
        );
        return $msg;
    }

    public function edit() {
        $info = OnemlaRequest::getVar("");
        $file = upload_img(array(), $rootPath = "Res/Uploads/activity");
        $bg_image = $file['info']['bg_image']['savename'];
        $logo = $file['info']['logo']['savename'];
        //新增
        if (empty($info['id'])) {
            $data = array(
                'user_id' => OnemlaHelper::getUserId(),
                'serial_number' => $info['serial_number'],
                'title' => $info['title'], 
                'status' => 1,//1.待审核  2.已审核  3.不通过
                'time' => $info['time'],
                'introduction' => $info['introduction'],
                'channel' => $info['channel'], 
                'bg_image' => $bg_image,
                'logo' => $logo,
                'update_time' => date("Y-m-d H:i:s"),
                'create_time' => date("Y-m-d H:i:s"),
            );
            $id = $this->add($data);
            if ($id) {
                $msg = array("code" => "add_success", "msg" => "新增成功");
                return $msg;
            } else {
                $msg = array("code" => "add_error", "msg" => "新增失败");
                return $msg;
            }
        }
        //修改
        else {
            $rInfo = $this->where(array("id" => $info['id']))->find();
            $bg_image_url = $bg_image == '' ? '' : delAdminFile($rInfo['bg_image'], $pathUrl = "Res/Uploads/activity/"); //删除本地图
            $logo_url = $logo == '' ? '' : delAdminFile($rInfo['logo'], $pathUrl = "Res/Uploads/activity/"); //删除本地图

            $data = array(
                'user_id' => OnemlaHelper::getUserId(),
                'serial_number' => $info['serial_number'],
                'title' => $info['title'], 
                'status' => 1,//1.待审核  2.已审核  3.不通过
                'time' => $info['time'],
                'introduction' => $info['introduction'],
                'channel' => $info['channel'], 
                'bg_image' => $bg_image == '' ? $rInfo['bg_image'] : $bg_image, 
                'logo' => $logo == '' ? $rInfo['logo'] : $logo,
                'update_time' => date("Y-m-d H:i:s"),
                'create_time' => date("Y-m-d H:i:s"),
            );
            $id = $this->where(array("id" => $info['id']))->save($data);
            if ($id == "1") {
                unlink($bg_image_url);
                unlink($logo_url);
                $msg = array("code" => "edit_success", "msg" => "修改成功");
                return $msg;
            } else {
                $msg = array("code" => "edit_error", "msg" => "修改失败");
                return $msg;
            }
        }
    }

}

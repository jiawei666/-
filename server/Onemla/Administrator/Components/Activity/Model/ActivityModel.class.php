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
        $status = OnemlaRequest::getVar('status');//活动状态
        $title = OnemlaRequest::getVar("title"); //用户名称
        
        $model = new UserModel();
        $uWhere['user_name'] = array("like", "%" . $user_name . "%");
        $ulist = $model->where($uWhere)->select();
        foreach ($ulist as $v){
            $user_id .= $v['id'].','; 
        }
        $where['user_id'] = array("in",  $user_id );
        $where['title'] = array("like", "%" . $title . "%");
        $where['status'] = array("like", "%" . $status . "%");
        
        $count = $this->where($where)->count();
        $Page = new Page($count, $listRows = 10, array('user_name'=>$user_name,'title'=>$title));
        $limit = '' . $Page->firstRow . ',' . $Page->listRows;

        $list = $this->alias("a")
                ->where($where)
                ->join("tb_user as u on u.id = a.user_id")
                ->join("tb_channel as c on c.id = a.channel_id")
                ->field("u.user_name,a.*,c.channel_name")
                ->limit($limit)
                ->order('create_time desc')
                ->select();
        $msg = array(
            'list' => $list,
            'count' => $count,
            'page_show' => $Page->AdminShow(),
            'status'=> $status,
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
        $status = OnemlaRequest::getVar('status');//活动状态
        $channel_id = OnemlaRequest::getVar("channel_id"); //频道
        
        $where['user_id'] = OnemlaHelper::getUserId();
        $where['title'] = array("like", "%" . $title . "%");
        $where['status'] = array("like", "%" . $status . "%");
        if($channel_id){
            $where['channel_id'] = $channel_id;
        }
        
        
        $count = $this->where($where)->count();
        $Page = new Page($count, $listRows = 10, array('title'=>$title,'channel_id'=>$channel_id));
        $limit = '' . $Page->firstRow . ',' . $Page->listRows;
        
        $list = $this->alias("a")
                ->where($where)
                ->join("tb_user as u on u.id = a.user_id")
                ->join("tb_channel as c on c.id = a.channel_id")
                ->field("u.user_name,a.*,c.channel_name")
                ->order('create_time desc')
                ->limit($limit)
                ->select();
        $msg = array(
            'list' => $list,
            'count' => $count,
            'page_show' => $Page->AdminShow(),
            'title' => $title,
            'status'=> $status,
            'channel_id' => $channel_id,
        );
        return $msg;
    }

    public function edit() {
        $info = OnemlaRequest::getVar("");
        $exts_limit=array(
            'exts' =>  array('jpg','gif', 'png', 'jpeg'),
            'maxSize' => 2097152,
        );
        $file = upload_img($exts_limit, $rootPath = "Res/Uploads/activity");


        //上传错误则直接return错误信息
        if($file['error'] != '') {
            if($file['error'] == '没有文件被上传！') {
                //这个if语句将跳过
            } else {
                return  $msg = array("code" => "upload_error", "msg" => $file['error']);
                exit;
            }
        }
        $bg_image = $file['info']['bg_image']['savename'];
        $logo = $file['info']['logo']['savename'];



         //自动生成HD190316001编号
        $time = date(Ymd) ;
        $time=substr($time,2);
        $last_num = $this->getfield('max(serial_number)');
        if( $last_num == ''){
            $serial_num = $last_num = 'HD'.$time.'001';
        }else{
            $daytime = substr($last_num,2,6);
           if($time == $daytime){
               $number=substr($last_num,2)+1;
               $serial_num = 'HD'.$number ;
           } else{
               $serial_num = 'HD'.$time.'001';
           }
        }
        //新增


        if (empty($info['id'])) {

            $data = array(
                'user_id' => OnemlaHelper::getUserId(),
                'serial_number' => $serial_num,
                'title' => $info['title'], 
                'status' => 1,//1.待审核  2.已审核  3.不通过
                'time' => $info['time'],
                'introduction' => $info['introduction'],
                'channel_id' => $info['channel_id'],
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
                'title' => $info['title'], 
                'status' => 1,//1.待审核  2.已审核  3.不通过
                'time' => $info['time'],
                'introduction' => $info['introduction'],
                'channel_id' => $info['channel_id'], 
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

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

class RepairReplyModel extends OnemlaModel {

    /**
     * 活动列表
     */
    public function getList() {
        $repair_number = OnemlaRequest::getVar("repair_number"); //用户名称
        $type = OnemlaRequest::getVar("type"); //分类
        
        $where['repair_number'] = array("like", "%" . $repair_number . "%");
        if ($type) {
            $where['type'] = $type;
        }


        $count = $this->where($where)->count();
        $Page = new Page($count, $listRows = 10, array('repair_number' => $repair_number, 'type' => $type));
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
            'repair_number' => $repair_number,
            'type' => $type,
        );
        return $msg;
    }

    public function edit() {
        $info = OnemlaRequest::getVar("");
        $file = upload_img(array(), $rootPath = "Res/Uploads/repair");
        $image = $file['info']['image']['savename'];
//        print_r($file);exit;
        //新增
        if (empty($info['id'])) {
            $data = array(
                'user_id' => 2,
                'repair_number' => $info['repair_number'],
                'type' => $info['type'],
                'introduction' => $info['introduction'],
                'image' => $image,
                'contact' => $info['contact'],
                'phone' => $info['phone'],
                'email' => $info['email'],
                'status' => 1, //1.待审核  2.已审核  3.不通过
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
            $image_url = $image == '' ? '' : delAdminFile($rInfo['image'], $pathUrl = "Res/Uploads/repair/"); //删除本地图

            $data = array(
                'user_id' => 2,
                'repair_number' => $info['repair_number'],
                'type' => $info['type'],
                'introduction' => $info['introduction'],
                'image' => $image == '' ? $rInfo['image'] : $image,
                'contact' => $info['contact'],
                'phone' => $info['phone'],
                'email' => $info['email'],
//                'status' => 1,//1.待审核  2.已审核  3.不通过
                'update_time' => date("Y-m-d H:i:s"),
                'create_time' => date("Y-m-d H:i:s"),
            );
            $id = $this->where(array("id" => $info['id']))->save($data);
            if ($id == "1") {
                unlink($image_url);
                $msg = array("code" => "edit_success", "msg" => "修改成功");
                return $msg;
            } else {
                $msg = array("code" => "edit_error", "msg" => "修改失败");
                return $msg;
            }
        }
    }

}

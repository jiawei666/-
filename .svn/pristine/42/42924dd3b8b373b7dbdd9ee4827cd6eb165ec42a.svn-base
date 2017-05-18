<?php

namespace Components\MemberActivity\Controller;

use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Components\User\Model\UserModel;
use Components\User\Model\UserInfoModel;
use Onemla\SessionViewController;
use Components\Activity\Model\ActivityModel;

class IndexController extends SessionViewController {

    /**
     * 活动列表
     */
    public function index() {
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_MEMBER_ACTIVITY);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_MEMBER_ACTIVITY_ONE);

        $model = new ActivityModel();
        $info = $model->getMemberList();
        $this->assign('list', $info['list']);
        $this->assign('page_show', $info['page_show']);
        $this->assign('user_name', $info['user_name']);
        $this->assign('title', $info['title']);
        $this->assign('channel',$info['channel']);
        $this->display();
    }

    /**
     * 新增，修改基本信息
     */
    public function editPage() {
        $model = new ActivityModel();
        $id = OnemlaRequest::getVar("id");
        $info = $model->where(array("id" => $id))->find();
        $this->assign('info', $info);
        $this->display("edit");
    }

    /**
     * 详情
     */
    public function detail() {
        $model = new ActivityModel();
        $id = OnemlaRequest::getVar("id");
        $info = $model->where(array("id" => $id))->find();
        $this->assign('info', $info);
        $this->display("detail");
    }

    /**
     * 新增，修改基本信息
     */
    public function edit() {
        $model = new ActivityModel();
        $msg = $model->edit();
        if ($msg['code'] == "add_success") {
            $this->success($msg['msg'], U("MemberActivity/Index/index"));
        }
        if ($msg['code'] == "add_error") {
            $this->success($msg['msg']);
        }
        if ($msg['code'] == "edit_success") {
            $this->success($msg['msg'], U("MemberActivity/Index/index"));
        }
        if ($msg['code'] == "edit_error") {
            $this->success($msg['msg']);
        }
    }

    /**
     * 删除
     */
    public function delete() {
        $model = new ActivityModel();
        $id = OnemlaRequest::getVar("id");
        //删除本地图片
        $rInfo = $model->where(array("id" => $id))->find();
        $bg_image_url = $bg_image == '' ? '' : delAdminFile($rInfo['bg_image'], $pathUrl = "Res/Uploads/activity/"); //删除本地图
        $logo_url = $logo == '' ? '' : delAdminFile($rInfo['logo'], $pathUrl = "Res/Uploads/activity/"); //删除本地图
        unlink($bg_image_url);
        unlink($logo_url);
        
        $result = $model->where(array("id" => $id))->delete();
        if ($result) {
            $this->httpReturn(1, $id, "删除成功");
        } else {
            $this->httpReturn(2, $id, "删除失败");
        }
    }

}

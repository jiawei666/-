<?php

namespace Components\Activity\Controller;

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
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_ACTIVITY);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_ACTIVITY_ONE);

        $model = new ActivityModel();
        $info = $model->getList();
        $this->assign('list', $info['list']);
        $this->assign('page_show', $info['page_show']);
        $this->assign('user_name', $info['user_name']);
        $this->assign('title', $info['title']);
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
            $this->success($msg['msg'], U("Activity/Index/index"));
        }
        if ($msg['code'] == "add_error") {
            $this->success($msg['msg']);
        }
        if ($msg['code'] == "edit_success") {
            $this->success($msg['msg'], U("Activity/Index/index"));
        }
        if ($msg['code'] == "edit_error") {
            $this->success($msg['msg']);
        }
    }

    /**
     * 审核
     */
    public function audit() {
        $id = OnemlaRequest::getVar("id");
        $status = OnemlaRequest::getVar("inputValue"); //1.正常  2.锁定
        $model = new ActivityModel();
        $data = array(
            'status' => $status,
        );
        $msg = $model->where(array("id" => $id))->save($data);
        if ($msg == "1") {
            $this->httpReturn(1, $msg, 'success');
        } else {
            $this->httpReturn(2, $msg, 'error');
        }
    }

    /**
     * 删除
     */
    public function delete() {
        $model = new ActivityModel();
        $id = OnemlaRequest::getVar("id");
        $result = $model->where(array("id" => $id))->delete();
        if ($result) {
            $this->httpReturn(1, $id, "删除成功");
        } else {
            $this->httpReturn(2, $id, "删除失败");
        }
    }

}

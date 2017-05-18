<?php

namespace Components\MemberRepair\Controller;

use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Components\User\Model\UserModel;
use Components\User\Model\UserInfoModel;
use Onemla\SessionViewController;
use Components\Repair\Model\RepairOrderModel;
use Components\Repair\Model\RepairReplyModel;

class IndexController extends SessionViewController {

    /**
     * 活动列表
     */
    public function index() {
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_MEMBER_REPAIR);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_MEMBER_REPAIR_ONE);

        $model = new RepairOrderModel();
        $info = $model->getMemberList();
        $this->assign('list', $info['list']);
        $this->assign('page_show', $info['page_show']);
        $this->assign('user_name', $info['user_name']);
        $this->assign('type', $info['type']);
        $this->assign('repair_number', $info['repair_number']);
        $this->display();
    }

    /**
     * 新增，修改基本信息
     */
    public function editPage() {
        $model = new RepairOrderModel();
        $id = OnemlaRequest::getVar("id");
        $info = $model->where(array("id" => $id))->find();
        $this->assign('info', $info);
        $this->display("edit");
    }

    /**
     * 详情
     */
    public function detail() {
        $model = new RepairOrderModel();
        $id = OnemlaRequest::getVar("id");
        $info = $model->where(array("id" => $id))->find();
        $this->assign('info', $info);
        $this->display("detail");
    }

    /**
     * 新增，修改基本信息
     */
    public function edit() {
        $model = new RepairOrderModel();
        $msg = $model->edit();
        if ($msg['code'] == "add_success") {
            $this->success($msg['msg'], U("MemberRepair/Index/index"));
        }
        if ($msg['code'] == "add_error") {
            $this->success($msg['msg']);
        }
        if ($msg['code'] == "edit_success") {
            $this->success($msg['msg'], U("MemberRepair/Index/index"));
        }
        if ($msg['code'] == "edit_error") {
            $this->success($msg['msg']);
        }
    }

    /**
     * 审核
     */
    public function reply() {
        $id = OnemlaRequest::getVar("id");
        $inputValue = OnemlaRequest::getVar("inputValue"); //1.正常  2.锁定
        $model = new RepairOrderModel();
        $RepairReplyModel = new RepairReplyModel();
        $data = array(
            'repair_id' => $id,
            'reply' => $inputValue,
            'update_time' => date("Y-m-d H:i:s"),
            'create_time' => date("Y-m-d H:i:s"),
        );
        $result = $RepairReplyModel->add($data);
        if ($result) {
            $_data = array(
                'status' => 2,
            );
            $msg = $model->where(array("id" => $id))->save($_data);
            $this->httpReturn(1, $msg, 'success');
        } else {
            $this->httpReturn(2, $msg, 'error');
        }
    }

    /**
     * 删除
     */
    public function delete() {
        $model = new RepairOrderModel();
        $RepairReplyModel = new RepairReplyModel();
        $id = OnemlaRequest::getVar("id");
        $result = $model->where(array("id" => $id))->delete();
        if ($result) {
            $RepairReplyModel->where(array("repair_id" => $id))->delete();
            $this->httpReturn(1, $id, "删除成功");
        } else {
            $this->httpReturn(2, $id, "删除失败");
        }
    }

}

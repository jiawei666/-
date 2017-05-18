<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/20 0020
 * Time: 下午 1:17
 */
namespace Components\CaAndSo\Controller;

use Components\CaAndSo\Model\SolutionModel;
use Components\Repair\Model\ChannelModel;
use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Components\User\Model\UserModel;
use Components\User\Model\UserInfoModel;
use Onemla\SessionViewController;
use Components\Activity\Model\ActivityModel;

class SolutionController extends SessionViewController {

    public function __construct()
    {//防止会员通过更改url跳转到管理员界面
        parent::__construct();
        if(OnemlaHelper::getUser()->user_type==3){
            $this->redirect('Member/Index/index');
            exit;
        }
    }

    /**
     * 解决方案列表
     */
    public function index() {
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_CAANDSO) ;
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_CAANDSO_TWO);

        $model = new SolutionModel();
        $info = $model->getList();

        foreach($info['list'] as $key => $value){//去掉html标签
            $content = $info['list'][$key]['content'] ;
            $info['list'][$key]['content'] = strip_tags($content);
        }

        foreach($info['list'] as $key => $value){//截取-解决方案-字符串
            $content = $info['list'][$key]['content'] ;
            $info['list'][$key]['content'] = msubstr($content,0,30,"utf-8",true);
        }


        $this->assign('list', $info['list']);
        $this->assign('page_show', $info['page_show']);
        $this->assign('content', $info['content']);
        $this->assign('show',$info['show']);
        $this->assign('title',$info['title']);
        $this->display();
    }

    /**
     * 新增，修改解决方案信息
     */
    public function editPage() {
        $model = new SolutionModel();
        $id = OnemlaRequest::getVar("id");
        $info = $model->where(array("id" => $id))->find();
        $this->assign('info', $info);
        $this->display("edit");
    }

    /**
     * 新增，修改解决方案信息
     */
    public function edit() {
        $model = new SolutionModel();
        $msg = $model->edit();
        if ($msg['code'] == "add_success") {
            wingo($msg['msg'], U("CaAndSo/Solution/index"));
        }
        if ($msg['code'] == "add_error") {
            win_savedata_back($msg['msg']);
        }
        if ($msg['code'] == "edit_success") {
            wingo($msg['msg'], U("CaAndSo/Solution/index"));
        }
        if ($msg['code'] == "edit_error") {
            win_savedata_back($msg['msg']);
        }
    }


    /**
     * 删除
     */
    public function delete() {
        $model = new SolutionModel();
        $id = OnemlaRequest::getVar("id");

        $result = $model->where(array("id" => $id))->delete();
        if ($result) {
            $this->httpReturn(1, $id, "删除成功");
        } else {
            $this->httpReturn(2, $id, "删除失败");
        }
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/20 0020
 * Time: 下午 1:02
 */

namespace Components\CaAndSo\Model;

use Onemla\OnemlaModel;

use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Org\Page\Page;
use Components\User\Model\UserModel;

class SolutionModel extends OnemlaModel {

    /**
     * 解决方案列表
     */
    public function getList() {
        $content = OnemlaRequest::getVar("content"); //案例名称
        /*
         * 查询条件
         * */
        $where['content'] = array("like", "%" . $content . "%");
        /*
         * 数据分页
         * */
        $count = $this->where($where)->count();
        $Page = new Page($count, $listRows = 10, array('title'=>$title,'content'=>$content));
        $limit = '' . $Page->firstRow . ',' . $Page->listRows;

        $list = $this
            ->where($where)
            ->limit($limit)
            ->order('create_time desc')
            ->select();
        $msg = array(
            'list' => $list,
            'page_show' => $Page->AdminShow(),
            'title' => $title,
            'content' => $content,
        );
        return $msg;
    }



    public function edit() {
        $info = OnemlaRequest::getVar("");

        //新增
        if (empty($info['id'])) {
            $data = array(
                'content' => html_entity_decode($info['content']),
                'create_time' => date("Y-m-d H:i:s"),
            );
            $addres = $this->add($data);
            if ($addres) {
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

            $data = array(
                'content' => $info['content'],
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

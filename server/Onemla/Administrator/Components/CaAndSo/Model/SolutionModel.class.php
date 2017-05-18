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
        $title = OnemlaRequest::getVar("title"); //标题
        $content = OnemlaRequest::getVar("content"); //解决方案
        $show = OnemlaRequest::getVar("show");//是否在前台显示
        /*
         * 查询条件
         * */
        $where['title'] = array('like',"%".$title."%");
        $where['content'] = array("like", "%" . $content . "%");
        $where['show'] = array("like", "%" . $show . "%");
        /*
         * 数据分页
         * */
        $count = $this->where($where)->count();
        $Page = new Page($count, $listRows = 5, array('show'=>$show,'content'=>$content,'title'=>$title));
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
            'show' => $show,
            'title' => $title,
        );
        return $msg;
    }



    public function edit() {
        $info = OnemlaRequest::getVar("");

        if (empty($info['id'])) {//新增
            $data = array(
                'title' => $info['title'],
                'content' => html_entity_decode($info['content']),
                'create_time' => date("Y-m-d H:i:s"),
                'show' => $info['show'],
            );
            $addres = $this->add($data);
            if ($addres) {
                $msg = array("code" => "add_success", "msg" => "新增成功");
                return $msg;
            } else {
                $msg = array("code" => "add_error", "msg" => "新增失败");
                return $msg;
            }

        } else {//修改

            $data = array(
                'title' => $info['title'],
                'show' =>$info['show'],
                'content' => html_entity_decode($info['content']),
            );
            $id = $this->where(array("id" => $info['id']))->save($data);
            if ($id == "1") {
                $msg = array("code" => "edit_success", "msg" => "修改成功");
                return $msg;
            } else {
                $msg = array("code" => "edit_error", "msg" => "修改失败,未做任何修改");
                return $msg;
            }
        }
    }

}

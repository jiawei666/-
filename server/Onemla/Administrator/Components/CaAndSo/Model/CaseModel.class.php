<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/16 0016
 * Time: 下午 7:03
 */

namespace Components\CaAndSo\Model;


use Onemla\OnemlaModel;

use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Org\Page\Page;
use Components\User\Model\UserModel;

class CaseModel extends OnemlaModel {

    /**
     * 案例列表
     */
    public function getList() {
        $title = OnemlaRequest::getVar("title"); //案例标题
        $type_id = OnemlaRequest::getVar("type_id"); //案例名称
        $date_start = OnemlaRequest::getVar("date_start");//开始时间
        $date_end = OnemlaRequest::getVar("date_end");//结束时间
        /*
         * 查询条件
         * */
        $where['title'] = array("like", "%" . $title . "%" );
        $where['type_id'] = array("like", "%" . $type_id . "%");
        /*
         * 数据分页
         * */
        $count = $this->where($where)->count();
        $Page = new Page($count, $listRows = 5, array('title'=>$title,'type_id'=>$type_id));
        $limit = '' . $Page->firstRow . ',' . $Page->listRows;

        $list = $this->alias('a')
            ->where($where)
            ->join('tb_case_type as t on a.type_id=t.id')
            ->field('a.*,t.type')
            ->limit($limit)
            ->order('create_time desc')
            ->select();
        $msg = array(
            'list' => $list,
            'date_start'=>$date_start,
            'date_end'=>$date_end,
            'page_show' => $Page->AdminShow(),
            'title' => $title,
            'type_id' => $type_id,
        );
        return $msg;
    }



    public function edit() {
        $info = OnemlaRequest::getVar("");
        $file = upload_img(array(), $rootPath = "Res/Uploads/case");
        $image = $file['info']['image']['savename'];

        //新增
        if (empty($info['id'])) {
            $data = array(
                'alink' => $info['alink'],
                'title' => $info['title'],
//                'content' => html_entity_decode($info['content']),
                'image' => $image,
                'type_id' => $info['type_id'],
                'update_time' => date("Y-m-d H:i:s"),
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

            $image_url = $image == '' ? '' : delAdminFile($rInfo['image'], $pathUrl = "Res/Uploads/case/"); //删除本地图
            $data = array(
//                'content' => $info['content'],
                'alink' => $info['alink'],
                'title' => $info['title'],
                'image' => $image == '' ? $rInfo['image'] : $image,
                'type_id' => $info['type_id'],
                'update_time' => date("Y-m-d H:i:s"),

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

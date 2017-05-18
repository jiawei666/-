<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/16 0016
 * Time: 下午 7:03
 */

namespace Components\Caseinfo\Model;


use Onemla\OnemlaModel;
use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Org\Page\Page;

class CaseModel extends OnemlaModel {

    /**
     * 案例列表
     */
    public function getList() {
        $type_id = OnemlaRequest::getVar('type_id');
        $search = OnemlaRequest::getVar("search"); //搜索值
        $likeSearch = '%'.$search.'%';
        $type_id = '%'.$type_id.'%';
        /*
         * 查询条件
         * */
//        $where['title'] = array("like", "%" . $search . "%" );
//        $where['content'] = array("like", "%" . $search . "%");
        /*
         * 数据分页
         * */
        $count = $this->where("`title` LIKE '%s' and `type_id` LIKE '%s'",array($likeSearch,$type_id))->count();
        $Page = new Page($count, $listRows = 8,array('search' =>$search,'type_id'=>$type_id));//查询分页,数据通过get传输
        $limit = $Page->firstRow . ',' . $Page->listRows;
        $list = $this
            ->where("`title` LIKE '%s' and `type_id` LIKE '%s'",array($likeSearch,$type_id))
            ->limit($limit)
            ->order('create_time desc')
            ->select();
        $msg = array(
            'list' => $list,
            'page_show' => $Page->AdminShow(),
            'search' => $search,
        );
        return $msg;
    }
}

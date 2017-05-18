<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12 0012
 * Time: 上午 10:08
 */

namespace Components\MemberLive_bsn\Controller;

use Components\MemberLive_bsn\Model\Live_roomModel;
use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Org\Page\Page;
use Onemla\SessionViewController;
class TagsController extends SessionViewController
{

    /*
     * 话题列表
     * */
    public function index(){
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_MEMBER_LIVE_BSN);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_MEMBER_LIVE_BSN_THREE);
        $model = M('live_tags');

        $count = $model->where(array('bsn_id'=>getLive_bsn_id()))->count();
        $Page = new Page($count, $listRows = 10);
        $limit = '' . $Page->firstRow . ',' . $Page->listRows;

        $list = $model->where(array('bsn_id'=>getLive_bsn_id()))->limit($limit)->order('create_time desc')->select();
        $this->assign('list',$list);
        $this->assign('page_show',$Page->AdminShow());
        $this->display();
    }

    /*
     * 新增
     * */
    public function add(){
        $tags = OnemlaRequest::getVar('tags');
        if($tags==''){
            win_savedata_back('话题不能为空');
            exit;
        }
        $model = M('live_tags');
        if($find = $model->where(array('tags'=>$tags,'bsn_id'=>getLive_bsn_id()))->find()){
            win_savedata_back('话题已存在');
            exit;
        }
        $res = $model->add(array('tags'=>$tags,'bsn_id'=>getLive_bsn_id(),'create_time'=>date('Y-m-d H:i:s')));
        if($res){
            wingo('新增成功',U('MemberLive_bsn/Tags/index'));
        }else{
            win_savedata_back('新增失败');
        }
    }

    /*
     * 删除
     * */
    public function delete(){
        $id = OnemlaRequest::getVar('id');
        $model = new Live_roomModel();
        $res = $model->delTags($id);
        if($res){
            $this->httpReturn(1);
        }else{
            $this->httpReturn(2);
        }
    }
}
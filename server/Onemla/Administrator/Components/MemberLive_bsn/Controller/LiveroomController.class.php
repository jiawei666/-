<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12 0012
 * Time: 上午 11:19
 */

namespace Components\MemberLive_bsn\Controller;
use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Onemla\SessionViewController;
use Components\MemberLive_bsn\Model\Live_roomModel;

class LiveroomController extends SessionViewController
{

    public function test(){

    }

    public function index(){
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_MEMBER_LIVE_BSN);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_MEMBER_LIVE_BSN_FOUR);
        $model = new Live_roomModel();
        $info = $model->getMemberList();
        $this->assign('list',$info['list']);
        $this->assign('title',$info['title']);
        $this->assign('channel_id',$info['channel_id']);
        $this->assign('tags_id',$info['tags_id']);
        $this->assign('character_id',$info['character_id']);
        $this->assign('audit_status',$info['audit_status']);
        $this->assign('page_show',$info['page_show']);

        $channelModel = M('live_channel');
        $channel = $channelModel->where(array('bsn_id'=>getLive_bsn_id()))->select();
        $tagsModel = M('live_tags');
        $tags = $tagsModel->where(array('bsn_id'=>getLive_bsn_id()))->select();
        $characterModel = M('live_character');
        $character = $characterModel->select();

        $this->assign('channel',$channel);
        $this->assign('character',$character);
        $this->assign('tags',$tags);
        $this->display();
    }

    /*
     * 新增/修改页面
     * **/
    public function editpage(){
        $relationModel = M('live_rt_relation');
        $id = OnemlaRequest::getVar('id');
        $this->assign('id',$id);
        $model = M('live_room');
        $info = $model->where(array('room_id'=>$id))->find();
        $relationArr = $relationModel->where(array('liveroom_id'=>$id))->select();
        foreach($relationArr as $key=>$val){
            $info['tags'.$key.'_id'] = $val['livetags_id'];
        }

        //获取城市列表
        $pCitySelectJs = getPCitySelectJs('province', 'city', $pid = $info['province_id'], $cid = $info['city_id']);
        $pCountryCfg = getCfgProvinceByCountry();
        $region = '';
        foreach ($pCountryCfg as $value) {
            $region = $region . '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
        }
        $this->assign("region", $region);
        $this->assign('pCitySelectJs', $pCitySelectJs);

        $channelModel = M('live_channel');
        $channel = $channelModel->where(array('bsn_id'=>getLive_bsn_id()))->select();
        $this->assign('channel',$channel);

        $tagsModel = M('live_tags');
        $tags = $tagsModel->where(array('bsn_id'=>getLive_bsn_id()))->select();
        $this->assign('tags',$tags);

        $characterModel = M('live_character');
        $character = $characterModel->select();
        $this->assign('character',$character);


        $this->assign('info',$info);
        $this->display('edit');
    }

    /*
    * 话题js
    * */
//    public function selectTags(){
//        $tags1 = OnemlaRequest::getVar('tags1');
//        $tags2 = OnemlaRequest::getVar('tags2');
//        $tags3 = OnemlaRequest::getVar('tags3');
//        $select = array('','---请选择---');
//        $tagsModel = M('live_tags');
//        $tagsArr1 = $tagsModel->where('`bsn_id`='.getLive_bsn_id().' and `tags` != "'.$tags2.'" and `tags` != "'.$tags3.'"')->select();
//        $select1 = '<option value="'.$select[0].'">' .$select[1]. '</option>';
//        foreach($tagsArr1 as $value){
//            $select1 .= '<option value="' . $value['id'] . '">' . $value['tags'] . '</option>';
//        }
//        $tagsArr2 = $tagsModel->where('`bsn_id`='.getLive_bsn_id().' and `tags` != "'.$tags1.'" and `tags` != "'.$tags3.'"')->select();
//        $select2 = '<option value="'.$select[0].'">' .$select[1]. '</option>';
//        foreach($tagsArr2 as $value){
//            $select2 .= '<option value="' . $value['id'] . '">' . $value['tags'] . '</option>';
//        }
//        $tagsArr3 = $tagsModel->where('`bsn_id`='.getLive_bsn_id().' and `tags` != "'.$tags1.'" and `tags` != "'.$tags2.'"')->select();
//        $select3 = '<option value="'.$select[0].'">' .$select[1]. '</option>';
//        foreach($tagsArr3 as $value){
//            $select3 .= '<option value="' . $value['id'] . '">' . $value['tags'] . '</option>';
//        }
//        $arr = array('a'=>$select1,'b'=>$select2,'c'=>$select3);
//        $this->ajaxReturn($arr,'json');
//    }

    /*
     * 修改
     * */
    public function edit(){
        $model = new Live_roomModel();
        $msg = $model->edit();
        if ($msg['code'] == 1) {
            wingo($msg['msg'],U('MemberLive_bsn/Liveroom/index'));
        }
        if ($msg['code'] == 0) {
            win_savedata_back($msg['msg']);
        }
    }

    /*
     * 详情
     * */
    public function detail(){
        $relationModel = M('live_rt_relation');
        $id = OnemlaRequest::getVar('id');
        $bsn_id = OnemlaRequest::getVar('bsn_id')==''? getLive_bsn_id():OnemlaRequest::getVar('bsn_id');

        $model = M('live_room');
        $info = $model->alias('a')
            ->join('tb_live_bsn as b on b.id=a.r_bsn_id')
            ->join('tb_user_info as u on u.user_id=b.user_id')
            ->field('a.*,u.live_name')
            ->where(array('room_id'=>$id))->find();

        $relationArr = $relationModel->where(array('liveroom_id'=>$id))->select();
        foreach($relationArr as $key=>$val){
            $info['tags'.$key.'_id'] = $val['livetags_id'];
        }

        //获取城市列表
        $pCitySelectJs = getPCitySelectJs('province', 'city', $pid = $info['province_id'], $cid = $info['city_id']);
        $pCountryCfg = getCfgProvinceByCountry();
        $region = '';
        foreach ($pCountryCfg as $value) {
            $region = $region . '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
        }
        $this->assign("region", $region);
        $this->assign('pCitySelectJs', $pCitySelectJs);

        $channelModel = M('live_channel');
        $channel = $channelModel->where(array('bsn_id'=>$bsn_id))->select();
        $tagsModel = M('live_tags');
        $tags = $tagsModel->where(array('bsn_id'=>$bsn_id))->select();
        $characterModel = M('live_character');
        $character = $characterModel->select();
        $this->assign('channel',$channel);
        $this->assign('character',$character);
        $this->assign('tags',$tags);
        $this->assign('info',$info);
        $this->display('detail');
    }

    /*
     * 删除
     * */
    public function delete(){
        $id = OnemlaRequest::getVar('id');
        $model = new Live_roomModel();
        $res = $model->delRoom($id);
        if($res=='1'){
            $this->httpReturn(1);
        }else{
            $this->httpReturn(2);
        }
    }
}
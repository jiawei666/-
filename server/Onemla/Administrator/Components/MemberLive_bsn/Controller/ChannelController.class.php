<?php
namespace Components\MemberLive_bsn\Controller;

use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Org\Page\Page;
use Onemla\SessionViewController;
use Components\MemberLive_bsn\Model\Live_roomModel;
class ChannelController extends SessionViewController
{
    /*
     * 频道列表
     * */
    public function index(){
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_MEMBER_LIVE_BSN);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_MEMBER_LIVE_BSN_TWO);


        $model = M('live_channel');

        $count = $model->where(array('bsn_id'=>getLive_bsn_id()))->count();
        $Page = new Page($count, $listRows = 10);
        $limit = '' . $Page->firstRow . ',' . $Page->listRows;

        $list = $model->where(array('bsn_id'=>getLive_bsn_id()))->order('create_time desc')->limit($limit)->select();

        foreach($list as $key => $value){//截取-频道说明-字符串
            $introduce = strip_tags(html_entity_decode($list[$key]['introduce']));
            $list[$key]['introduce'] = msubstr($introduce,0,40,"utf-8",true);
        }


        $this->assign('list',$list);
        $this->assign('page_show',$Page->AdminShow());
        $this->display();
    }

    /*
     * 修改页面
     * */
    public function editpage(){
        $id = OnemlaRequest::getVar('id');
        $model = M('live_channel');
        $info = $model->where(array('id'=>$id))->find();
        $this->assign('info',$info);
        $this->display('edit');
    }

    /*
     * 新增\修改频道信息
     * */
    public function edit(){
        $info = OnemlaRequest::getVar('');
        $model = M('live_channel');
        $rInfo = $model->where(array('id'=>$info['id']))->find();
        $upload_limit = array(
            'exts' =>  array('jpg', 'gif', 'png', 'jpeg'),
            'maxSize' => 2097152,);
//        $image = upload_img($upload_limit,'Res/Uploads/live_channel');
        $channelImage = uploadOne($_FILES['image'],'Res/Uploads/live_channel/');
        if ($channelImage['flag'] == 'error' && $rInfo['image']==''){
            win_savedata_back($channelImage['msg']);exit;
        }
        $itd_image = uploadOne($_FILES['itd_image'],'Res/Uploads/live_channel/');
        if ($itd_image['flag'] == 'error'){
            if($itd_image['msg']!='没有文件被上传！'){
                win_savedata_back($itd_image['msg']);exit;
            }
        }
        $findChannel = $model->where(array('channel'=>$info['channel'],'bsn_id'=>getLive_bsn_id()))->getField('channel');
        if(!$info['id']){//新增
            if($findChannel){
                win_savedata_back('频道已存在');
                exit;
            }
            $data=array(
                'bsn_id'=>getLive_bsn_id(),
                'channel'=>$info['channel'],
                'image' => $channelImage['msg']['file'],
                'itd_image' => $itd_image['msg']['file'],
                'introduce'=>$info['introduce'],
                'create_time'=>date('Y-m-d H:i:s'),
            );
            $res = $model->add($data);
            if($res){
                wingo('新增成功',U('MemberLive_bsn/Channel/index'));
            }else{
                win_savedata_back('新增失败');
            }
        }else{//修改
            if($findChannel && $findChannel != $rInfo['channel']){
                win_savedata_back('频道已存在');
                exit;
            }
            $imageUrl = empty($channelImage['msg']['file'])? '' : delAdminFile($rInfo['image'],'Res/Uploads/live_channel/');
            $itd_imageUrl = empty($itd_image['msg']['file'])? '' : delAdminFile($rInfo['itd_image'],'Res/Uploads/live_channel/');
            $data=array(
                'channel'=>$info['channel'],
                'image'=>empty($channelImage['msg']['file'])? $rInfo['image'] : $channelImage['msg']['file'],
                'itd_image'=>empty($itd_image['msg']['file']) ? $rInfo['itd_image'] : $itd_image['msg']['file'],
                'introduce'=>$info['introduce'],
            );
            $rdata =array(
                'channel'=>$rInfo['channel'],
                'image'=>$rInfo['image'],
                'itd_image'=>$rInfo['itd_image'],
                'introduce'=>$rInfo['introduce'],
            );
            if($data == $rdata){
                win_savedata_back('修改失败,未做任何改动');
                exit;
            }
            $res = $model->where(array('id'=>$info['id']))->save($data);
            if($res){
                unlink($imageUrl);
                unlink($itd_imageUrl);
                wingo('修改成功',U('MemberLive_bsn/Channel/index'));
            }else{
                win_savedata_back('修改失败');
            }
        }
    }

    /*
     * 删除
     * */
    public function delete(){
        $id = OnemlaRequest::getVar('id');
        $model = new Live_roomModel();
        $res = $model->delChannel($id);
        if($res == 'roomFalse'){
            $this->httpReturn(3);
        }elseif($res){
            $this->httpReturn(1);
        }else{
            $this->httpReturn(2);
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/25 0025
 * Time: 上午 11:15
 */

namespace Components\Live_room\Controller;
use Components\MemberLive_bsn\Model\Live_roomModel;
use Onemla\SessionViewController;
use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Org\Page\Page;


class BannerController extends SessionViewController
{
    public function __construct()
    {//防止会员通过更改url跳转到管理员界面
        parent::__construct();
        if(OnemlaHelper::getUser()->user_type==3){
            $this->redirect('Member/Index/index');
            exit;
        }
    }
    /*
     * banner列表
     * */
    public function index(){
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_LIVE_ROOM);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_LIVE_ROOM_THREE);

        $search = OnemlaRequest::getVar('');
        $where['show'] = array('like','%'.$search['show'].'%');
        $where['title'] = array('like','%'.$search['title'].'%');

        $bannerModel = M('live_banner');
        $count = $bannerModel->where($where)->count();
        $Page = new Page($count, $listRows = 10, array('title'=>$search['title'], 'show'=>$search['show'],));
        $limit = '' . $Page->firstRow . ',' . $Page->listRows;
        $this->bannerList = $bannerModel->where($where)->limit($limit)->order('first asc,create_time desc')->select();
        $this->now = date('Y-m-d H:i:s');
        $this->page_show = $Page->AdminShow();
        $this->search = $search;
        $this->display();
    }

    /*
     * 修改页面
     * */
    public function editPage(){
        $id = OnemlaRequest::getVar('id');
        $bannerModel = M('live_banner');
        $this->info = $bannerModel->where(array('id'=>$id))->find();
        $this->display('edit');
    }

    /*
     * 修改
     * */
    public function edit(){
        $bannerModel = M('live_banner');
        $info = OnemlaRequest::getVar('');
        is_numeric( $info['first'] ) or win_savedata_back('优先级必须为数字');
        $exts_limit=array(
            'exts' =>  array('jpg','gif', 'png', 'jpeg'),
            'maxSize' => 2097152,
        );
        $file = upload_img($exts_limit, $rootPath = "Res/Uploads/live_banner");
        $image = $file['info']['image']['savename'];

        $findFirst = $bannerModel->where(array('first'=>$info['first']))->getField('first');
        //新增
        if (empty($info['id'])){

            if($findFirst){
                win_savedata_back('优先级重复');exit;
            }

            if($file['error'] != '') {//上传错误则直接return错误信息
                win_savedata_back($file['error']);
                exit;
            }
            $data = array(
                'title' =>$info['title'],
                'image' => $image,
                'alink' => $info['alink'],
                'start_time' => $info['start_time'],
                'end_time' => $info['end_time'],
                'first' => $info['first'],
                'show' => $info['show'],
                'create_time' => date("Y-m-d H:i:s"),
            );
            $id = $bannerModel->add($data);
            if($id){
                wingo('新增成功',U('Live_room/Banner/index'));
            }else{
                win_savedata_back('新增失败');
            }
        }
        //修改
        else {
            if($file['error'] != '' && $file['error'] != '没有文件被上传！') {//上传错误则直接return错误信息
                win_savedata_back($file['error']);
                exit;
            }
            $rInfo = $bannerModel->where(array("id" => $info['id']))->find();

            if($info['first']!=$rInfo['first'] && $info['first']==$findFirst){
                 win_savedata_back('优先级重复');exit;
            }

            $image_url = $image == '' ? '' : delAdminFile($rInfo['image'], $pathUrl = "Res/Uploads/live_banner/"); //删除本地图
            $data = array(
                'title' =>$info['title'],
                'image' => $image==""?$rInfo['image']:$image,
                'start_time' => $info['start_time'],
                'end_time' => $info['end_time'],
                'alink' => $info['alink'],
                'show' => $info['show'],
                'first' => $info['first'],
            );
            $id = $bannerModel->where(array("id" => $info['id']))->save($data);
            if($id){
                unlink($image_url);
                wingo('修改成功',U('Live_room/Banner/index'));
            }else{
                win_savedata_back('修改失败,没有进行任何修改!');
            }
        }
    }

    /*
     *删除
     **/
    public function delete(){
        $id = OnemlaRequest::getVar('id');
        $model = new Live_roomModel();
        $res = $model->delBanner($id);
        if($res){
            $this->httpReturn(1);
        }else{
            $this->httpReturn(2);
        }
    }
}
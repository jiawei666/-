<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/21 0021
 * Time: 上午 9:49
 */

namespace Components\Live_room\Controller;

use Components\MemberLive_bsn\Model\Live_roomModel;
use Onemla\SessionViewController;
use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Org\Page\Page;
use Components\User\Model\UserInfoModel;


class CharacterController extends SessionViewController{

    public function __construct()
    {//防止会员通过更改url跳转到管理员界面
        parent::__construct();
        if(OnemlaHelper::getUser()->user_type==3){
            $this->redirect('Member/Index/index');
            exit;
        }
    }

    /*
   * 性质分类管理
   * */

    public function index() {
        OnemlaHelper::setMenuActived(OnemlaHelper::ACTIVED_LIVE_ROOM);
        OnemlaHelper::setActived(OnemlaHelper::ACTIVED_LIVE_ROOM_TWO);

        $model = M('live_character');

        $character = OnemlaRequest::getVar('character');
        if($character != ''){
            $find = $model->where(array('character'=>$character))->find();

            if(!$find){
                $res = $model->add(array('character'=>$character,'create_time'=>date('Y-m-d H:i:s')));
                if($res) wingo('新增成功');
                else win_savedata_back('新增失败');
            } else {
                win_savedata_back('性质已经存在');
            }
        }

        $count = $model->count();
        $Page = new Page($count, $listRows = 10);
        $limit = '' . $Page->firstRow . ',' . $Page->listRows;

        $info = $model->order('create_time desc')->limit($limit)->select();
        $this->assign('info',$info);
        $this->assign('page_show',$Page->Adminshow());
        $this->display();
    }

    public function editPage(){
        $id = OnemlaRequest::getVar('id');
        $this->info = M('live_character')->where(array('id'=>$id))->find();
        $this->display('edit');
    }

    /*
     * 修改
     * **/
    public function edit(){
        $id =  OnemlaRequest::getVar('id');
        $character = OnemlaRequest::getVar('character');

        $model = M('live_character');
        $info = $model->where(array('character'=>$character))->find();
        if($info['character']==$character){
            win_savedata_back('性质已存在或未修改');exit;
        }
        $res = $model->where(array('id'=>$id))->save(array('character'=>$character));
        if($res){
            wingo('修改成功','index.php?&m=Live_room&c=Character&a=index');
        }else{
            win_savedata_back('修改失败,请重试');
        }
    }

    /*
     * 删除
     * */
    public function delete(){
        $id = OnemlaRequest::getVar('id');
        $model =new Live_roomModel();
        $res = $model->delCharacter($id);
        if($res=='roomFalse'){
            $this->httpReturn(3);
        }elseif($res){
            $this->httpReturn(1);
        }else{
            $this->httpReturn(2);
        }
    }



}
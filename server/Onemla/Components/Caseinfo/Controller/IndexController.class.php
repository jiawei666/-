<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/29 0029
 * Time: 下午 4:23
 */

namespace Components\Caseinfo\Controller;

use Components\Caseinfo\Model\CaseModel;
use Components\Repair\Model\ChannelModel;
use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;
use Onemla\ViewController;
use Components\User\Model\UserModel;
use Components\User\Model\UserInfoModel;
use Onemla\SessionViewController;
use Components\Activity\Model\ActivityModel;

class IndexController extends ViewController
{

    /**
     * 案例列表
     */
    public function index()
    {
        $model = new CaseModel();
        $info = $model->getList();


        foreach($info['list'] as $key=>$val){
            $info['list'][$key]['update_time'] = substr($info['list'][$key]['update_time'],0,10);
        }
        $this->assign('list', $info['list']);
        $this->assign('page_show', $info['page_show']);
        $this->assign('search', $info['search']);
        $this->display('case');
    }

}

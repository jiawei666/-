<?php
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 2015/5/18
 * Time: 20:44
 */
namespace Components\File\Model;

use Onemla\OnemlaHelper;
use Onemla\OnemlaModel;
use Onemla\OnemlaRequest;

class FileModel extends OnemlaModel{
    protected $tableName = 'attachment';
    public function save2mysql($info){
        $files = array();

        foreach($info['info'] as $file){
            $files['user_id'] = OnemlaHelper::getUserId();
            $files['shopId'] = OnemlaHelper::getShopId();
            $files['atta_key'] = $file['key'];
            $files['savepath'] = $file['savepath'];
            $files['name'] = $file['name'];
            $files['savename'] = $file['savename'];
            $files['size'] = $file['size'];
            $files['type'] = $file['type'];
            $files['ext'] = $file['ext'];
            $files['referer'] = OnemlaRequest::getUrl('HTTP_REFERER', '', 'server');
            $files['create_time'] = OnemlaRequest::requestTime();
        }

        if($this->add($files)){
            OnemlaHelper::systemService('Money/use_memory', array(OnemlaHelper::getUserId(), $files['size']/1024));
        }
    }
}
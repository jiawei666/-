<?php


namespace Services\File;

use Onemla\OnemlaHelper;
use Onemla\OnemlaModel;
use Onemla\OnemlaRequest;
use Onemla\OnemlaService;
use Onemla\UCenter\UCenterHelper;
use Think\Model;

class FileService extends OnemlaService {


    /*
     * 初始化上传信息
     * @param $savePath : string 保存的目录名,以模块名命名；如用户类，则'User'
     * @param $saveName : string 保存的文件名，留空则自动命名
     * @param $exts : string 允许上传的格式；如：'jpg,png,gif'
     * @param $maxSize : int 允许上传文件的最大字节，默认C('upload.maxSize')
     * @return Object 上传格式信息
     * */
    public function initUpload($savePath='',$saveName='',$exts='',$maxSize=''){
        $path = C('TMPL_PARSE_STRING.__UPLOAD__');
        if(!is_dir($path)) mkdir($path, 0777);
//        $savePath = OnemlaRequest::getString('savePath');
//        $saveName = OnemlaRequest::getString('saveName');
//        $exts = OnemlaRequest::getString('exts');
//        $maxSize = OnemlaRequest::getString('maxSize');
        if($savePath){
            $data['savePath'] = '/'.$savePath.'/'.OnemlaHelper::getUserId().'/';
        }else{
            $data['savePath'] = C('upload.savePath', null, '/temp/').'/'.OnemlaHelper::getUserId().'/';
        }
        if($saveName){
            $data['saveName'] = $saveName;
            $data['replace'] = true;
        }
        if($exts){
            $exts = explode(',', $exts);
            $master = C('upload.exts', null, array());
            $exts = array_intersect($exts, $master);
            $data['exts'] = $exts;
        }
        if($maxSize)
            $data['maxSize'] = $maxSize;
        else
            $data['maxSize'] = C('upload.maxSize');

        $data['subName'] = '';  //取消日期文件夹

        return $data;
    }

}
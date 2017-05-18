<?php

namespace Components\File\Controller;

use Onemla\OnemlaHelper;
use Onemla\OnemlaRequest;

class FileController extends \Think\Controller{

	private $error = null;
	
	 public function _initialize(){
        //此处为解决Uploadify在火狐下出现http 302错误 重新设置SESSION
        session_write_close();
        if (isset($_GET['session_id'])) {
            session_id($_GET['session_id']);
            session_start();
        }
        $this->user = session('admin_user');
// 		$this->checkLogin();
    }

    private function checkLogin() {
		if (!empty($this->user)) {
			//$this->checkAuths(session('login_uid'));
		} else {
			if(CONTROLLER_NAME=="Index"&&ACTION_NAME=="index")
				redirect(U("Public/index"));
			else
				$this->error("请重新登录系统",U("Public/index"));
		}
	}

	public function uploadPicture(){
	     $info = upload(array('savePath'=>C('upload.savePath').I('path').'/'));
	     $this->ajaxReturn($info);
	}

    protected function initUpload(){
        $path = C('TMPL_PARSE_STRING.__UPLOAD__');
        if(!is_dir($path)) mkdir($path, 0777);

        $savePath = OnemlaRequest::getString('savePath');
        $saveName = OnemlaRequest::getString('saveName');
        $exts = OnemlaRequest::getString('exts');
        $maxSize = OnemlaRequest::getString('maxSize');

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

        return $data;
    }
	
	public function upload(){
		$result = array('error' => 1, 'message' => '');

		$info = upload($this->initUpload());

		if($info['info']) {
			$fileName = array_keys($info['info']);
			$fileName = $fileName[0];
			$result['error'] = 0;
			$result['filename'] = $info['info'][$fileName]['savepath'].$info['info'][$fileName]['savename'];
			$result['url']      = $info['info'][$fileName]['url'];
            $result['realname'] = $info['info'][$fileName]['name'];
            $result['maxSize'] = $info['info'][$fileName]['maxSize'];
			//$result['file'] = $info['info'][$fileName]['file'];
		} else {
			$result['error'] = 1;
			$result['message'] = $info['error'];
		}
		exit(json_encode($result));
	}

    public function Resupload(){
        $result = array('error' => 1, 'message' => '');

        $isFull = OnemlaHelper::systemService('Money/checkMemory');
        if($isFull){
            $result['message'] = '上传空间已满，请联系传芯平台客服';
            exit(json_encode($result));
        }

        $info = upload($this->initUpload());
        OnemlaHelper::getModel('File') -> save2mysql($info);

        if($info['info']) {
            $fileName = array_keys($info['info']);
            $fileName = $fileName[0];
            $result['error'] = 0;
            $result['filename'] = $info['info'][$fileName]['savepath'].$info['info'][$fileName]['savename'];
            $result['url']      = $info['info'][$fileName]['url'];
            $result['realname'] = $info['info'][$fileName]['name'];
            //$result['file'] = $info['info'][$fileName]['file'];
        } else {
            $result['error'] = 1;
            $result['message'] = $info['error'];
        }
        exit(json_encode($result));
    }
    
    public function editorUpload(){
    	$result = array('error' => 1, 'message' => '');
    
    	$info = upload($this->initUpload());
    	OnemlaHelper::getModel('File') -> save2mysql($info);
    
    	if(!$info['info']) $this->error = $info['error'];
		return $info['info'];
    }
    
	public function ajaxuploadold() {
		$result = array('error' => 1, 'message' => '');
		$path = C('upload.rootPath').C('upload.savePath');
		if(!is_dir($path)) mkdir($path, 0777);
		$info = upload();
		if($info['info']) {
			$fileName = array_keys($info['info']);
			$fileName = $fileName[0];
			$result['error'] = 0;
			$result['filename'] = $info['info'][$fileName]['savepath'].$info['info'][$fileName]['savename'];
			$result['url'] = $info['info'][$fileName]['url'];
			//$result['file'] = $info['info'][$fileName]['file'];
		}else{
			$result['error'] = 1;
			$result['message'] = $info['error'];
		}
		exit(json_encode($result));
	}
	
	public function ue_upimg() {
		$info = $this->editorUpload ();
		$img = $info ['imgFile'] ['url'];
		
		$return = array ();
//		$return ['id'] = $info ['imgFile'] ['id'];
		$return ['url'] = $img;
		$title = htmlspecialchars ( $_POST ['pictitle'], ENT_QUOTES );
		$return ['title'] = $title;
		$return ['original'] = $info ['imgFile'] ['name'];
		$return ['state'] = ($img) ? 'SUCCESS' : $this->error;
		/* 返回JSON数据 */
		$this->ajaxReturn ( $return );
	}
	// ueditor编辑器在线管理处理
	// 扫描目录下（包括子文件夹）的图片并返回
	public function ue_mgimg() {
		$setting = C ( 'editor' );
		$imgRootPath = $setting ['rootPath'];
		$paths = array (
				'' 
		);
		$files = array ();
		$files = $this->getfiles ( $imgRootPath );
		if (! count ( $files ))
			return;
		rsort ( $files, SORT_STRING );
		$str = implode ( '|', $files );
		echo $str;
	}
  
	/**
	 * 遍历获取目录下的指定类型的文件
	 *
	 * @param
	 *        	$path
	 * @param array $files
	 * @return array
	 */
	function getfiles($path, &$files = array()) {
		if (! is_dir ( $path ))
			return null;
		$handle = opendir ( $path );
		while ( false !== ($file = readdir ( $handle )) ) {
			if ($file != '.' && $file != '..') {
				$path2 = $path . '/' . $file;
				if (is_dir ( $path2 )) {
					$this->getfiles ( $path2, $files );
				} else {
					if (preg_match ( "/\.(gif|jpeg|jpg|png|bmp)$/i", $file )) {
						// $files[] = '/dev/'.$path2;
						$files [] = __ROOT__ . '/' . ltrim ( ltrim ( $path2, '.' ), '/' );
					}
				}
			}
		}
		return $files;
	}

    public function ajaxupload() {
        $result = array('error' => 1, 'message' => '');
        $path = C('upload.rootPath').C('upload.savePath');
        if(!is_dir($path)) mkdir($path, 0777);
        $info = upload();
        if($info['info']) {
            $fileName = array_keys($info['info']);
            $fileName = $fileName[0];
            $result['error'] = 0;
            $result['filename'] = $info['info'][$fileName]['savepath'].$info['info'][$fileName]['savename'];
            $result['url'] = $info['info'][$fileName]['url'];
            //$result['file'] = $info['info'][$fileName]['file'];
        } else {
            $result['error'] = 1;
            $result['message'] = $info['error'];
        }
        exit(json_encode($result));
    }

} 
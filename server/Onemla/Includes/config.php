<?php
return array(

    /* 组件配置 */
    'VAR_ADDON'        => 'Components',
    'DEFAULT_MODULE'   => 'Temp',  // 默认模块
    'VIEW_PATH'        => APP_PATH. 'Templates/Skin1/',
    'TOKEN_SECRET'     => '~Tem@#plat*(E',//TOKEN加密串
    'TMPL_ACTION_ERROR'     =>  '../Libraries/Onemla/Tpl/dispatch_jump.tpl', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   =>  '../Libraries/Onemla/Tpl/dispatch_jump.tpl', // 默认成功跳转对应的模板文件
    // 设置默认的模板主题
//    'DEFAULT_THEME'    =>    'default',

    'LANG_AUTO_DETECT' => FALSE,   //关闭语言的自动检测，如果你是多语言可以开启
    'LANG_SWITCH_ON'   => TRUE,    //开启语言包功能，这个必须开启
    'DEFAULT_LANG'     => 'zh-cn', //zh-cn文件夹名字 /lang/zh-cn/common.php

    /* 配置模版替换规则 */
    'TMPL_PARSE_STRING'   => array(
        '__IMAGES__'      => __ROOT__ . '/Res/images/', // images路径
        '__CSS__'         => __ROOT__ . '/Res/css/', // css路径
        '__UPLOAD__'      => __ROOT__ . '/Res/Uploads', //增加新的上传路径替换规则
        '__JAVASCRIPTS__' => __ROOT__ . '/Res/js/', // 增加JAVASCRIPTS路径替换规则
        '__ACTIVITY__' => __ROOT__.'/Res/Uploads/activity/',
        '__REPAIR__' => __ROOT__.'/Res/Uploads/repair/',
		'__CASE__' => __ROOT__.'/Res/Uploads/case/',
		'__CERTIFICATION__' => __ROOT__.'/Res/Uploads/certification/',
		'__WECHAT__'         => __ROOT__ . '/Res/wechat/', // wechat模块路径
    ),


    'AUTOLOAD_NAMESPACE' => array(
        'Onemla'     => APP_PATH. 'Libraries/Onemla',
        'Components' => './Components/',
        'Widgets'    => './Widgets/',
    ),

	'upload' => array(
				'maxSize' => 3145728,
				'exts' => array('jpg', 'gif', 'png', 'jpeg',
                    'doc', 'docx', 'rar', 'zip', 'txt', 'xls', 'xlsx', 'pdf', 'ppt',
                ),
				'rootPath' => './Res/Uploads',
				'savePath' => '/',
                'userPath' => '/User/',
	),

    'LOAD_EXT_FILE' => 'jsbuilder,function_debug',

    'APP_CONF' => "appConf,appCfg",

    'BAIDU_MAP' => array(
        'ak' => 'po4ONNc7uh2vg9PKwMIAO8dy'
    ),

	//直播家公众号
	'WCAPPID' => 'wxe1ac2297a10da8e7',
	'WCAPPSECRET' => '598d43cff786977d91457e242707a1ec',

////    微信公众号
//    'WECHAT_AUTH' => array(
//
//        'APP_ID'             => 'wxb66cdbe3b204f4f6',
//        'APP_SECRET'         => '7b3216511b52572ce2b3237204269662',
//        'MCHID'              => '1248879401',
//        'KEY'                => 'homepiu0000000000000000000000pay',
//        'TOKEN_REQUEST_TIME' => 1800,
//
//        //异步通知url，商户根据实际开发过程设定
//        'NOTIFY_URL' => "http://homepiu.haidilun.com/server/Onemla/index.php",
//
//        //本例程通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒\
//        'CURL_TIMEOUT' => 30,
//
//        'home_url' => "http://homepiu.haidilun.com/www",
//        'contact_phone' => "13750042753",
//    ),

//   QQ发送邮件
	'MAIL_HOST' =>'smtp.ym.163.com',//smtp服务器的名称
	'SMTP_PORT'   => 465, //SMTP服务器端口
	'MAIL_SMTPAUTH' =>true, //启用smtp认证
	'MAIL_SMTPSECURE'=>'ssl',//设置使用ssl加密方式登录鉴权
	'MAIL_USERNAME' =>'support@no88.com',//你的邮箱名
	'MAIL_FROM' =>'support@no88.com',//发件人地址
	'MAIL_FROMNAME'=>'直播家找回密码',//发件人姓名
	'MAIL_PASSWORD' =>'123456',//邮箱密码
	'MAIL_CHARSET' =>'utf-8',//设置邮件编码
	'MAIL_ISHTML' =>true, // 是否HTML格式邮件


	'SHOW_PAGE_TRACE' =>true, // 显示页面Trace信息


);
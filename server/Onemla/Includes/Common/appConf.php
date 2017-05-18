<?php
return array(

    /* 数据库配置 */
    'DB_TYPE'    => 'mysqli',          //数据库类型
    'DB_HOST'    => 'localhost',    //服务器地址
    'DB_NAME'    => 'live',     //数据库名
    'DB_USER'    => 'root',     //用户名
    'DB_PWD'     => 'root',     //密码
    'DB_PORT'    => 3306,              //端口
    'DB_CHARSET' => 'utf8',            //字符集
    'DB_PREFIX'  => 'tb_',

    'URL_CASE_INSENSITIVE' => false,  //URL区分大小写
    'TMPL_STRIP_SPACE'     => false,  //是否去除模板文件里面的html空格与换行
    'ACTIVE_USER'          => 1,
    'URL_MODEL'            => 0,      //URL模式
    'VERSION'              => '2.0',

    'CACHE_CNF' => array(
//        'DATA_CACHE_TYPE' => 'redis',
//        'memcache'        => array(
//            'host'        => '127.0.0.1',
//            'port'        => '6379',
//            'username'    => '',
//            'password'    => '',
//            'prefix'      => 'O.ichasin.session',
//            'expire'      => 600
//        ),

//        'DATA_CACHE_TYPE'            => 'memcache',
//        'memcache'                   => array(
//            'host'                   => 'ac67e699a8d54079.m.cnhzaliqshpub001.ocs.aliyuncs.com',
//            'port'                   => '11211',
//            'username'               => 'ac67e699a8d54079',
//            'password'               => 'Adminwendy123',
//            'prefix'                 => 'O.ichasin.session',
//            'expire'                 => 600
//        ),

    ),

    //聊天
//    'nodeServer' => array(
//        'serverIp' => '127.0.0.1',
//        'serverPort' => 5001,
//    ),
    //聊天测试环境地址
//    'nodeServer' => array(
//        'serverIp' => '121.40.50.83',
//        'serverPort' => 8101,
//    ),


    'CORS_USEXDOMAIN' => 1,//跨域

//    ),
);
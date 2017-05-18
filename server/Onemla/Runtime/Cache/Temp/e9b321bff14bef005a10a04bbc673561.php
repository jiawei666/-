<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta http-equiv="Cache-Control" content="no-siteapp" />
        <meta name="format-detection" content="telephone=no" />
        <title>直播家后台</title>
        <script type="text/javascript" src="/live/server/Onemla/administrator/Res/js/jquery.min.js" ></script>
        <script type="text/javascript" src="/live/server/Onemla/administrator/Res/js/bootstrap.min.js" ></script> 
        <script type="text/javascript" src="/live/server/Onemla/administrator/Res/js/js.cookie.js" ></script>
        <script type="text/javascript" src="/live/server/Onemla/administrator/Res/js/sub.js" ></script>
        <link rel="stylesheet" href="/live/server/Onemla/administrator/Res/css/bootstrap.min.css" />
        <link rel="stylesheet" href="/live/server/Onemla/administrator/Res/css/css.css" />
        <link rel="stylesheet" href="/live/server/Onemla/administrator/Res/css/style.css" />
        <script type="text/javascript" src="/live/server/Onemla/administrator/Res/js/sweetalert.min.js" ></script>
        <link rel="stylesheet" href="/live/server/Onemla/administrator/Res/css/sweetalert.css" />

        <?php echo \Onemla\OnemlaHelper::getDocument()->fetchHead();?>
    
    <link rel="stylesheet" href="/live/server/Onemla/administrator/Res/css/login.css" />

</head>

<body>
<div class="submit_load" style="">
    <div>
        <img src="/live/server/Onemla/administrator/Res/images/load.png"  />
        <span >正在提交，请等待</span>
    </div>
</div>



    <div class="loginBox">
        <form action="<?php echo U('User/Index/login');?>" method="post">
            <h3 class="title">直播家后台管理系统</h3>
            <div class="form_list">
                <dl class="flex">
                    <dd class="icon">
                        <span class="user_icon"></span>
                    </dd>
                    <dd class="inputTxt">
                        <input type="text" name="user_name" placeholder="请输入账号" class="input_01" required="" />
                    </dd>
                </dl>
                <dl class="flex">
                    <dd class="icon">
                        <span class="pword_icon"></span>
                    </dd>
                    <dd class="inputTxt">
                        <input type="password" name="password" placeholder="请输入密码" class="input_01" required="" />
                    </dd>
                </dl>
                <dl>
                    <dd class="bt">
                        <button type="submit" class="btn_01">登录</button>
                    </dd>
                </dl>
                <p class="text forget_text flex">
                    <span>
                        你还没有账号？<a href="<?php echo U('User/Index/register_page');?>">立即注册</a>
                    </span>
                    <span>
                        <a href="<?php echo U('User/Findpsd/way');?>">忘记密码?</a>
                    </span>
                </p>
                <!--<p class="text">-->
                    <!--技术支持：珠海市朗捷软件科技有限公司-->
                <!--</p>-->
            </div>
        </form>
    </div>




</body>
</html>
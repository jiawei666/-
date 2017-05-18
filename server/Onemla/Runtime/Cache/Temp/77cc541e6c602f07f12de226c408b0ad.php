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
    
    <script src="/live/server/Onemla/administrator/Res/js/edit_info_sendSMS.js"></script>
    <script src="/live/server/Onemla/administrator/Res/js/regular.js"></script>

</head>

<body>
<div class="submit_load" style="">
    <div>
        <img src="/live/server/Onemla/administrator/Res/images/load.png"  />
        <span >正在提交，请等待</span>
    </div>
</div>


    <div id="main">
        <?php echo \Onemla\OnemlaHelper::W('Menu/Index');?>
        <div id="box" class="flex">
            <?php echo \Onemla\OnemlaHelper::W('Menu/Left_Menu');?>
            <div id="content">
                <p class="location flex">
                    <span class="icon menu_icon"></span>
                    会员信息
                    <i class="gt">&gt;</i>
                    <span class="skin_color">会员信息</span>
                </p>
                <div class="essential skin_bg">
                    基本信息
                </div>
                <form method="post" action="<?php echo U('Member/Index/edit');?>">
                <ul class="essential-box">
                    <li class="essential-title">
                        <span>账号：</span>
                        <input type="text" value="<?php echo ($info['user_name']); ?>" disabled="" />
                    </li>
                    <li class="essential-state">
                        <span>手机号：</span>
                        <input type="text" name="phone" value="<?php echo ($info[phone]); ?>" oninput="checkphone($('#btn'))" disabled=""/>
                        <input type="button" name="button" style="width: 50px;margin-left: 15px;background-color: #f8b551;color: white" id="edit" value="修改"/>
                    </li>
                    <li class="essential-state"  id="entry_verify" style="display: none">
                        <input type="text" name="verify" id="verify" style="width: 150px;margin-left: 153px" placeholder="请输入验证码" />
                        <input type="button" id="btn" name="button"  value="免费获取验证码"  style="width: 130px;color: white;background-color: #9e9e9a"/>
                    </li>
                    <li class="essential-state">
                        <span>邮箱：</span>
                        <input type="text" name="email" value="<?php echo ($info[email]); ?>" required=""  />
                    </li>
                    <li class="essential-time">
                        <span>微信号：</span>
                        <input type="text" name="wechat" value="<?php echo ($info[wechat]); ?>" required=""  />
                    </li>
                    <li class="essential-brief">
                        <span>QQ：</span>
                        <input type="text" name="qq" value="<?php echo ($info[qq]); ?>" required=""  />
                    </li>
                    <li class="bt">
                    </li>
                </ul>
                <input type="submit" id="info-submit" name="" onclick="return registercheck()" value="提交" />
                </form>
            </div>
            <!-- box end -->
        </div>
        <!-- main end -->
        <script>
            $('form').submit(function(){
                $('.submit_load').show();
                $('input[type="submit"]').attr('disabled','')
            });

            $('#edit').click(function(){
                if($('#edit').val()=='修改'){
                    $('input[name="phone"]').val('');
                    $('#edit').val('取消')
                }else {
                    $('#edit').val('修改')
                }

                $('#entry_verify').toggle();

                if($('input[name="phone"]').attr('disabled') == 'disabled'){
                    $('input[name="phone"]').removeAttr('disabled');
                }else {
                    var ee=<?php echo ($info["phone"]); ?>;
                    $('input[name="phone"]').attr('disabled','disabled');
                    $('input[name="phone"]').val(ee);
                    $('#btn').css('background-color','#9e9e9a');
                    $('#btn').css('border','1px solid #e0e0e0');
                    $('#btn').attr('disabled','disabled');
                }

            });
        </script>
    </div>




</body>
</html>
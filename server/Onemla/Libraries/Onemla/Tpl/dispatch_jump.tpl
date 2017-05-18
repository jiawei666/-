<?php
if(C('LAYOUT_ON')) {
echo '{__NOLAYOUT__}';
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta http-equiv="Cache-Control" content="no-siteapp" />
        <meta name="format-detection" content="telephone=no" />
        <title>直播家后台</title>
        <style type="text/css">
            *{
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            html,body{
                height: 100%;
            }
            body{
                font-size: 12px;
                font-family: arial,"微软雅黑";
                color: #333333;
                background-color: #e9edf3;
            }
            .hint{
                width: 100%;
                height: 100%;
                position: relative;
            }
            .hint_con{
                width: 540px;
                height: 380px;
                background-color: #FFFFFF;
                border-radius: 10px;
                padding-top: 75px;
                text-align: center;
                position: absolute;
                margin: auto;
                left: 0;
                right: 0;
                top: 0;
                bottom: 0;
            }
            .hint_con p.icon{
                margin-bottom: 20px;
            }
            .hint_con p.icon span{
                display: inline-block;
                width: 76px;
                height: 76px;
                background-repeat: no-repeat;
                background-size: cover;
            }
            .hint_con p.icon span.complete_icon{
                background-image: url(__IMAGES__/hint_icon_complete_01.png);
            }
            .hint_con p.icon span.error_icon{
                background-image: url(__IMAGES__/hint_icon_error_01.png);
            }
            .hint_con p.title{
                font-size: 26px;
                color: #333333;
                margin-bottom: 40px;
            }
            .hint_con p.text{
                font-size: 16px;
                color: #808080;
            }
            .hint_con p.text a{
                color: #4D8DDA;
                text-decoration: none;
            }
        </style>
    </head>
    <body>

        <div class="hint">
            <div class="hint_con">


                <?php if(isset($message)) {?>
                        <p class="icon">
                        <span class="complete_icon"></span>
                        </p>
                        <p class="title">
                            <?php echo($message); ?>
                        </p>
                    <?php }else{?>
                        <p class="icon">
                        <span class="error_icon"></span>
                        </p>
                        <p class="title">
                            <?php echo($error); ?>
                        </p>
                    <?php }?>

                <p class="text">
                    页面<span id="wait" class="sec"><?php echo($waitSecond); ?></span>s后将自动<a id="href" href="<?php echo($jumpUrl); ?>"> 跳转</a>，请稍等...
                </p>
            </div>
        </div>
        <script type="text/javascript">
            (function () {
                var wait = document.getElementById('wait'), href = document.getElementById('href').href;
                var interval = setInterval(function () {
                    var time = --wait.innerHTML;
                    if (time <= 0) {
                        location.href = href;
                        clearInterval(interval);
                    }
                    ;
                }, 1000);
            })();
        </script>
    </body>
</html>


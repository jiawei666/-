<?php if (!defined('THINK_PATH')) exit();?><!--二维码-->
<div class="public-container">
    <div class="public-code">
        <img <?php echo ($userInfo["type"] ==1 ? 'src="/live/server/Onemla/Administrator/Res/Uploads/certification/personal/':'src="/live/server/Onemla/Administrator/Res/Uploads/certification/company/'); echo ($userInfo[qr_code]); ?>"/>
    </div>
</div>
<!--banner-->
<div class="banner">
    <div class="banner-cotainer">
        <div class="banner-sign">
            <img <?php echo ($userInfo["type"] ==1 ? 'src="/live/server/Onemla/Administrator/Res/Uploads/certification/personal/':'src="/live/server/Onemla/Administrator/Res/Uploads/certification/company/'); echo ($userInfo[public_logo]); ?>"/>
        </div>
        <h3 class="banner-title">
            <?php echo ($userInfo[live_name]); ?>
        </h3>
        <?php if($session_open_id !=''): ?><p class="banner-follow">
                <em><?php echo ($bsnInfo[follow_count]); ?>关注</em>
                <span id="follow" <?php echo ($followInfo[id] !=''? 'style="display:none;"':''); ?>>
                <input type="hidden" value="<?php echo ($bsnInfo[id]); ?>">
                <img  src="/live/server/Onemla/Res/wechat/bsn/images/icon/follow-banner-icon.png"/>
                加关注
                </span>
                <span  <?php echo ($followInfo[id] == '' ? 'style="display:none;"':'style="background-color: #3b3b3b;"'); ?> >
                已关注
                </span>
            </p><?php endif; ?>
    </div>

        <div class="public-number">
            <p>
                关注公众号
            </p>
        </div>

    <script>
        $(".public-number").on("click",function(){
            $(".public-container").toggle();
        });
        $('.public-container').on('click',function(){
            $('.public-container').hide();
        })
    </script>

</div>
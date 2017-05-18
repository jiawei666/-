<?php if (!defined('THINK_PATH')) exit();?><!--底部导航-->
<footer>
    <a class="footer-nav-home active" href="<?php echo U('WechatIndex/Index/index');?>">
        <div class="footer-nav-icon">
            <img src="/live/server/Onemla/Res/wechat/images/icon/home-active.png"/>
        </div>
        <p>首页</p>
    </a>
    <a class="footer-nav-nature" href="<?php echo U('WechatIndex/Index/character');?>">
        <div class="footer-nav-icon">
            <img src="/live/server/Onemla/Res/wechat/images/icon/nature.png"/>
        </div>
        <p>性质分类</p>
    </a>
    <a class="footer-nav-region" href="<?php echo U('WechatIndex/Index/region');?>">
        <div class="footer-nav-icon">
            <img src="/live/server/Onemla/Res/wechat/images/icon/region.png"/>
        </div>
        <p>地区分类</p>
    </a>
    <a class="footer-nav-liveRoom" href="<?php echo U('WechatIndex/Index/live_bsn');?>">
        <div class="footer-nav-icon">
            <img src="/live/server/Onemla/Res/wechat/images/icon/Live-room.png"/>
        </div>
        <p>直播商</p>
    </a>
</footer>
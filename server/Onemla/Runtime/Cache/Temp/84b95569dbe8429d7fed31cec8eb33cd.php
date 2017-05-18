<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <title>直播家公众号</title>
    <link rel="stylesheet" href="/live/server/Onemla/Res/wechat/bsn/css/vendor/swiper.min.css" />
    <link rel="stylesheet" href="/live/server/Onemla/Res/wechat/bsn/css/css.css" />
    <link rel="stylesheet" href="/live/server/Onemla/Res/wechat/bsn/css/follow-home.css" />
    <script type="text/javascript" src="/live/server/Onemla/Res/wechat/js/vendor/jquery-2.1.4.min.js" ></script>
    <script type="text/javascript" src="/live/server/Onemla/Res/wechat/js/vendor/swiper.min.js" ></script>
    <script type="text/javascript" src="/live/server/Onemla/Res/wechat/bsn/js/js.js" ></script>
    <script src="/live/server/Onemla/Res/wechat/bsn/js/live_follow.js"></script>
    <script src="/live/server/Onemla/Res/wechat/js/view_count.js"></script>
</head>
<body>

	<?php echo \Onemla\OnemlaHelper::W('Menu/wechatbanner');?>


	<!--分类栏-->
	<div class="home-nav">
		<a href="<?php echo U('WechatIndex/LiveBsn/tags');?>&bsn_id=<?php echo ($bsnInfo[id]); ?>">
			<span>话题</span>
		</a>
		<a class="active" href="<?php echo U('WechatIndex/LiveBsn/channel');?>&bsn_id=<?php echo ($bsnInfo[id]); ?>">
			<span>频道</span>
		</a>
		<a href="<?php echo U('WechatIndex/LiveBsn/index');?>&bsn_id=<?php echo ($bsnInfo[id]); ?>">
			<span>介绍</span>
		</a>
	</div>
	<!--频道列表-->
	<ul class="channel-list">
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="channel-list-container">
				<a href="<?php echo U('WechatIndex/LiveBsn/channel_brief');?>&bsn_id=<?php echo ($bsnInfo[id]); ?>&channel_id=<?php echo ($vo["id"]); ?>" >
					<img class="channel-list-img" src="/live/server/Onemla/Administrator/Res/Uploads/live_channel/<?php echo ($vo['image']); ?>"/>
					<p class="channel-list-title">
						<?php echo ($vo["channel"]); ?>
					</p>
				</a>
			</li><?php endforeach; endif; else: echo "" ;endif; ?>
	</ul>

</body>

</html>
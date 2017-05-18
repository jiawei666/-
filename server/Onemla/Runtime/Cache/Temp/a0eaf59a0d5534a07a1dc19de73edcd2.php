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
	<div class="channel-details-nav">
		<a class="active" href="<?php echo U('WechatIndex/LiveBsn/channel_brief');?>&channel_id=<?php echo ($channel_id); ?>&bsn_id=<?php echo ($bsn_id); ?>">
			<span>简介</span>
		</a>
		<a href="<?php echo U('WechatIndex/LiveBsn/channel_activity');?>&channel_id=<?php echo ($channel_id); ?>&bsn_id=<?php echo ($bsn_id); ?>">
			<span>活动</span>
		</a>
	</div>
	<!--介绍-->
	<div class="introduce-home-container">
		<h4 class="introduce-home-title"><?php echo ($channelInfo[title]); ?></h4>
		<p class="introduce-home-text">
			<?php echo ($channelInfo[introduce]); ?>
		</p>
		<?php if($channelInfo['itd_image']!=''): ?><img class="introduce-home-img" src="/live/server/Onemla/Administrator/Res/Uploads/live_channel/<?php echo ($channelInfo['itd_image']); ?>"/><?php endif; ?>
	</div>

</body>

</html>
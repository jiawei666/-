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


	<script src="/live/server/Onemla/Res/wechat/js/more_channel.js"></script>
	<!--分类栏-->
	<div class="channel-details-nav">
		<a  href="<?php echo U('WechatIndex/LiveBsn/channel_brief');?>&channel_id=<?php echo ($channel_id); ?>&bsn_id=<?php echo ($bsn_id); ?>">
			<span>简介</span>
		</a>
		<a class="active" href="<?php echo U('WechatIndex/LiveBsn/channel_activity');?>&channel_id=<?php echo ($channel_id); ?>&bsn_id=<?php echo ($bsn_id); ?>">
			<span>活动</span>
		</a>
	</div>
	<!--活动列表-->
	<div class="channel-details-container" name="roomList">
		<?php if(is_array($roomList)): $i = 0; $__LIST__ = $roomList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="home-list">
				<a href="<?php echo ($vo["alink"]); ?>" name="view_count">
					<input type="hidden" value="<?php echo ($vo[room_id]); ?>">
					<img class="home-list-img" src="/live/server/Onemla/Administrator/Res/Uploads/live_room/<?php echo ($vo["image"]); ?>"/>
					<div class="home-list-container">
						<h4 class="home-list-title">
							<?php echo ($vo["title"]); ?>
						</h4>
						<p class="home-list-time">
							<?php echo ($vo["start_time"]); ?>
						</p>
						<p class="home-list-number">
							<?php echo ($vo["view_count"]); ?>
						</p>
						<?php if($vo[status]==2): ?><p class="home-list-href underway" href="javascript:void(0)">
								直播中
							</p>
							<?php elseif($vo[status]==1): ?>
							<p class="home-list-href trailer" href="javascript:void(0)">
								预告
							</p>
							<?php else: ?>
							<p class="home-list-href playback" href="javascript:void(0)">
								回放
							</p><?php endif; ?>
					</div>
				</a>
			</div><?php endforeach; endif; else: echo "" ;endif; ?>
	</div>
	<input type="hidden" name="bsn_id" value="<?php echo ($bsn_id); ?>">
	<input type="hidden" name="channel_id" value="<?php echo ($channel_id); ?>">
	<!--<button value="2" name="more" style="position: relative;bottom: 1rem;left: 3.2rem;color: white;background-color: #c81914;border: 1px solid #adadad;">加载更多</button>-->
	<?php if($isempty==1): ?><div class="loading_done" name="more" >
			<p>暂时没有数据</p>
		</div>
		<?php else: ?>
		<div class="loading" name="more" >
			<input type="hidden" value="2" name="number">
			<p>点击加载更多</p>
		</div><?php endif; ?>

</body>

</html>
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


	<script src="/live/server/Onemla/Res/wechat/js/more_tags.js"></script>
	<!--分类栏-->
	<div class="home-nav">
		<a class="active" href="<?php echo U('WechatIndex/LiveBsn/tags');?>&bsn_id=<?php echo ($bsnInfo[id]); ?>">
			<span>话题</span>
		</a>
		<a href="<?php echo U('WechatIndex/LiveBsn/channel');?>&bsn_id=<?php echo ($bsnInfo[id]); ?>">
			<span>频道</span>
		</a>
		<a href="<?php echo U('WechatIndex/LiveBsn/index');?>&bsn_id=<?php echo ($bsnInfo[id]); ?>">
			<span>介绍</span>
		</a>
	</div>
	<!--子分类栏-->
	<div class="home-subnav">
		<a <?php echo ($tags_id=='' ? 'class="active"':''); ?> href="<?php echo U('WechatIndex/LiveBsn/tags');?>&bsn_id=<?php echo ($bsnInfo[id]); ?>">
				<span>
					全部
				</span>
		</a>
		<?php if(is_array($tagsList)): $i = 0; $__LIST__ = $tagsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a  name="<?php echo ($vo["id"]); ?>" <?php echo ($tags_id==$vo[id] ? 'class="active"':''); ?> href="<?php echo U('WechatIndex/LiveBsn/tags');?>&tags_id=<?php echo ($vo[id]); ?>&bsn_id=<?php echo ($bsnInfo[id]); ?>" >
				<span>
					<?php echo ($vo["tags"]); ?>
				</span>
			</a><?php endforeach; endif; else: echo "" ;endif; ?>
	</div>
	<!--话题列表-->
	<div class="home-list-conmtainer" name="roomList">
		<?php if(is_array($room)): $i = 0; $__LIST__ = $room;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$rm_vo): $mod = ($i % 2 );++$i;?><div class="home-list">
				<a href="<?php echo ($rm_vo["alink"]); ?>" name="view_count">
					<input type="hidden" value="<?php echo ($rm_vo[room_id]); ?>">
					<img class="home-list-img" src="/live/server/Onemla/Administrator/Res/Uploads/live_room/<?php echo ($rm_vo['image']); ?>"/>
					<div class="home-list-container">
						<h4 class="home-list-title">
							<?php echo ($rm_vo["title"]); ?>
						</h4>
						<p class="home-list-time">
							开始时间：<?php echo ($rm_vo["start_time"]); ?>
						</p>
						<p class="home-list-number">
							<?php echo ($rm_vo["view_count"]); ?>
						</p>
						<?php if($rm_vo[status]==2): ?><p class="home-list-href underway" href="javascript:void(0)">
								直播中
							</p>
							<?php elseif($rm_vo[status]==1): ?>
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
	<input type="hidden" name="bsn_id" value="<?php echo ($bsnInfo[id]); ?>">
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
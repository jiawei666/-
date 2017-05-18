<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <title>直播家公众号</title>
    <script type="text/javascript" src="/live/server/Onemla/Res/wechat/js/vendor/jquery-2.1.4.min.js" ></script>
    <script type="text/javascript" src="/live/server/Onemla/Res/wechat/js/vendor/swiper.min.js" ></script>
    <script type="text/javascript" src="/live/server/Onemla/Res/wechat/js/js.js" ></script>
    <script src="/live/server/Onemla/Res/wechat/js/view_count.js"></script>
    <?php echo \Onemla\OnemlaHelper::getDocument()->fetchHead();?>
    
	<link rel="stylesheet" href="/live/server/Onemla/Res/wechat/css/vendor/swiper.min.css" />
	<link rel="stylesheet" href="/live/server/Onemla/Res/wechat/css/css.css" />
	<link rel="stylesheet" href="/live/server/Onemla/Res/wechat/css/index.css" />
	<script src="/live/server/Onemla/Res/wechat/js/more_index.js"></script>

</head>
<body onload="regeocoder()">
    

	<!--banner-->
		<div class="swiper-container banner">
			<div class="swiper-wrapper" id="swiper-wrapper">
				<?php if(is_array($banner)): $i = 0; $__LIST__ = $banner;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$bn_vo): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
						<?php if($bn_vo[alink]==''): ?><a href="#" name="view_count">
								<input type="hidden" value="<?php echo ($bn_vo[room_id]); ?>">
								<img src="/live/server/Onemla/Administrator/Res/Uploads/live_banner/<?php echo ($bn_vo["image"]); ?>" alt="">
							</a>
							<?php else: ?>
							<a href="<?php echo ($bn_vo[alink]); ?>" name="view_count">
								<input type="hidden" value="<?php echo ($bn_vo[room_id]); ?>">
								<img src="/live/server/Onemla/Administrator/Res/Uploads/live_banner/<?php echo ($bn_vo["image"]); ?>" alt="">
							</a><?php endif; ?>
						<div class="banner-href">
							<p class="banner-href-brief"><?php echo ($bn_vo["title"]); ?></p>
							<!--<?php if($bn_vo[status]==1): ?>-->
								<!--<a style="background:#fc7a2b" href="javascript:void(0)">-->
									<!--直播中-->
								<!--</a>-->
								<!--<?php elseif($rm_vo[status]==2): ?>-->
								<!--<a style="background:#00a0e9" href="javascript:void(0)">-->
									<!--预告-->
								<!--</a>-->
								<!--<?php else: ?>-->
								<!--<a style="background:#aaaaaa" href="javascript:void(0)">-->
									<!--回放-->
								<!--</a>-->
							<!--<?php endif; ?>-->
						</div>
					</div><?php endforeach; endif; else: echo "" ;endif; ?>
			</div>
			<div class="swiper-pagination"></div>
		</div>
	<!--直播商推荐-->
	<div class="index-recommend swiper-container">
		<div class="index-recommend-container swiper-wrapper">
			<div class="swiper-slide">
				<a href="<?php echo U('WechatIndex/Index/live_bsn');?>">
					<img src="/live/server/Onemla/Res/wechat/images/recommend-1.jpg"/>
					<p class="index-recommend-text">全部直播商</p>
				</a>
			</div>
			<?php if(is_array($userInfo)): $i = 0; $__LIST__ = $userInfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ui_vo): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
					<a href="<?php echo U('WechatIndex/LiveBsn/index');?>&bsn_id=<?php echo ($ui_vo["bsn_id"]); ?>">
						<img <?php echo ($ui_vo["type"] ==1 ? 'src="/live/server/Onemla/Administrator/Res/Uploads/certification/personal/':'src="/live/server/Onemla/Administrator/Res/Uploads/certification/company/'); echo ($ui_vo["public_logo"]); ?>"/>
						<p class="index-recommend-text">
							<?php if(judgeUtf8($ui_vo[live_name])==1): if(strlen($ui_vo[live_name]) > 15): echo msubstr($ui_vo["live_name"],0,4);?>
									<?php else: ?>
									<?php echo ($ui_vo[live_name]); endif; ?>
								<?php elseif(strlen($ui_vo[live_name]) > 9): ?>
								<?php echo msubstr($ui_vo["live_name"],0,6);?>
								<?php else: ?>
								<?php echo ($ui_vo[live_name]); endif; ?>
						</p>
					</a>
				</div><?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
	</div>
	<!--直播列表-->
	<div class="index-list-container" name="roomList">
		<?php if(is_array($room)): $i = 0; $__LIST__ = $room;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$rm_vo): $mod = ($i % 2 );++$i;?><div class="index-list" >
				<a href="<?php echo ($rm_vo["alink"]); ?>" name="view_count">
					<input type="hidden" value="<?php echo ($rm_vo[room_id]); ?>">
					<img src="/live/server/Onemla/Administrator/Res/Uploads/live_room/<?php echo ($rm_vo["bg_image"]); ?>"/>
				</a>
				<p class="index-list-time">
					<?php echo ($rm_vo["start_time"]); ?>
				</p>
				<div class="index-list-href">
					<p class="index-list-brief"><?php echo ($rm_vo["title"]); ?></p>
					<?php if($rm_vo[status]==2): ?><a class="underway" href="javascript:void(0)">
							直播中
						</a>
						<?php elseif($rm_vo[status]==1): ?>
						<a class="trailer" href="javascript:void(0)">
							预告
						</a>
						<?php else: ?>
						<a class="playback" href="javascript:void(0)">
							回放
						</a><?php endif; ?>
					<p class="index-list-eye">
						<?php echo ($rm_vo["view_count"]); ?>
					</p>
				</div>
			</div><?php endforeach; endif; else: echo "" ;endif; ?>
	</div>
	<!--<button value="2" name="more" style="position: relative;bottom: 0.8rem;;left: 3.2rem;color: white;background-color: #c81914;border: 1px solid #adadad;">加载更多</button>-->
	<div class="loading" name="more" >
		<input type="hidden" value="2" name="number">
		<p>点击加载更多</p>
	</div>

    
	<?php echo \Onemla\OnemlaHelper::W('Footer/wechatIndex');?>

	<script>
		window.onload = function(){
			//轮播
			var mySwiper = new Swiper('.swiper-container.banner',{
				pagination : '.swiper-pagination',
				paginationClickable :true,
				loop:true,
				autoplay:2000
			});
			var swiper = new Swiper('.index-recommend.swiper-container', {
				slidesPerView: 5,
				spaceBetween: 50,
				freeMode: true,
				breakpoints: {
					420: {
						slidesPerView: 4,
						spaceBetween: 40
					},
					320: {
						slidesPerView: 3,
						spaceBetween: 30
					}
				}
			});
		}
	</script>

</body>
</html>
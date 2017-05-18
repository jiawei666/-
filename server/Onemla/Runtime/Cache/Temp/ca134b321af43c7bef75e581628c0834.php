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
	<style>
		.live-room .search-submit{
			background: url(/live/server/Onemla/Res/wechat/images/icon/search.png) no-repeat;
			background-size: 100% 100%;
		}
		.live-room-add{
			background: url(/live/server/Onemla/Res/wechat/images/icon/live-room-choice-1.png) no-repeat;
			background-size: 100% 100%;
		}
		.live-room-remove{
			background: url(/live/server/Onemla/Res/wechat/images/icon/live-room-choice-2.png) no-repeat;
			background-size: 100% 100%;
		}
	</style>

</head>
<body onload="regeocoder()">
    
	<!--搜索-->
	<div class="search live-room">
		<form action="" method="post">
			<div class="search-area">
				<input type="text" class="search-text" value="<?php echo ($live_name); ?>" name="live_name" placeholder="请输入直播商名称" />
				<input type="submit" class="search-submit" value=""/>
			</div>
		</form>
	</div>
	<!-- 直播商列表 -->
	<ul class="live-room-list">
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
				<img class="live-room-img"  <?php echo ($vo["type"] ==1 ? 'src="/live/server/Onemla/Administrator/Res/Uploads/certification/personal/':'src="/live/server/Onemla/Administrator/Res/Uploads/certification/company/'); echo ($vo["public_logo"]); ?>" alt="">
				<a href="<?php echo U('WechatIndex/LiveBsn/Index');?>&bsn_id=<?php echo ($vo[bsn_id]); ?>">
					<p class="live-room-title"><?php echo ($vo["live_name"]); ?></p>
				</a>
				<p class="live-room-nubmer">
					<em><?php echo ($vo["follow_count"]); ?></em>订阅
				</p>
				<?php if($session_open_id!=''): ?><span class="live-room-choice live-room-add" name="follow" <?php echo ($vo[open_id]=='' ? '':'style="display:none"'); ?>>
					<input type="hidden" value="<?php echo ($vo["bsn_id"]); ?>">
					</span>
					<span class="live-room-choice live-room-remove" name="unfollow" <?php echo ($vo[open_id]!='' ? '':'style="display:none"'); ?>>
					<input type="hidden" value="<?php echo ($vo["bsn_id"]); ?>">
					</span><?php endif; ?>
			</li><?php endforeach; endif; else: echo "" ;endif; ?>
	</ul>

    
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
		};
		$(function(){
			$('footer').find('a').eq(0).attr('class','footer-nav-home');
			$('footer').find('a').eq(1).attr('class','footer-nav-nature');
			$('footer').find('a').eq(2).attr('class','footer-nav-region');
			$('footer').find('a').eq(3).attr('class','footer-nav-liveRoom active');

			$('footer').find('img').eq(0).attr('src','/live/server/Onemla/Res/wechat/images/icon/home.png');
			$('footer').find('img').eq(1).attr('src','/live/server/Onemla/Res/wechat/images/icon/nature.png');
			$('footer').find('img').eq(2).attr('src','/live/server/Onemla/Res/wechat/images/icon/region.png');
			$('footer').find('img').eq(3).attr('src','/live/server/Onemla/Res/wechat/images/icon/Live-room-active.png');
		});
	</script>


</body>
</html>
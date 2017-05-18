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
    
    <script type="text/javascript" src="/live/server/Onemla/administrator/Res/js/live_room.js" ></script>

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
                    直播商管理
                    <i class="gt">&gt;</i>
                    <span class="skin_color">直播间</span>
                </p>
                <div class="searchBox flex">
                    <form action="<?php echo U('MemberLive_bsn/Liveroom/index');?>" method="post">
                        <dl class="flex">
                            <dd class="inputTxt">
                                <input type="text" name="title" value="<?php echo ($title); ?>" placeholder="请输入搜索的标题" class="input_01" />
                            </dd>
                            <!--<dd class="inputTxt">-->
                                <!--<select name="status" class='input_01'>-->
                                    <!--<option value="">请选择直播状态</option>-->
                                    <!--<option value="1" <?php echo ($status==1 ? 'selected=""':''); ?>>正在直播</option>-->
                                    <!--<option value="2" <?php echo ($status==2 ? 'selected=""':''); ?>>未开始</option>-->
                                    <!--<option value="3" <?php echo ($status==3 ? 'selected=""':''); ?>>回放</option>-->
                                <!--</select>-->
                            <!--</dd>-->
                            <dd class="inputTxt">
                                <select name="channel_id" class='input_01'>
                                    <option value="">请选择频道</option>
                                    <?php if(is_array($channel)): $i = 0; $__LIST__ = $channel;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cn_vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($cn_vo["id"]); ?>" <?php echo ($channel_id==$cn_vo["id"] ?'selected=""':''); ?>><?php echo ($cn_vo["channel"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </dd>
                            <dd class="inputTxt">
                                <select name="tags_id" class='input_01'>
                                    <option value="">请选择话题</option>
                                    <?php if(is_array($tags)): $i = 0; $__LIST__ = $tags;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$t_vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($t_vo["id"]); ?>" <?php echo ($tags_id==$t_vo["id"] ? 'selected=""':''); ?>><?php echo ($t_vo["tags"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </dd>
                            <dd class="inputTxt">
                                <select name="character_id" class='input_01'>
                                    <option value="">请选择性质</option>
                                    <?php if(is_array($character)): $i = 0; $__LIST__ = $character;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ca_vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($ca_vo["id"]); ?>" <?php echo ($character_id==$ca_vo["id"] ? 'selected=""':''); ?>><?php echo ($ca_vo["character"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>

                                </select>
                            </dd>
                            <dd class="inputTxt">
                                <select name="audit_status" class='input_01'>
                                    <option value="">请选择审核状态</option>
                                    <option value="1" <?php echo ($audit_status==1 ? 'selected=""':''); ?>>待审核</option>
                                    <option value="2" <?php echo ($audit_status==2 ? 'selected=""':''); ?>>审核通过</option>
                                    <option value="3" <?php echo ($audit_status==3 ? 'selected=""':''); ?>>不通过</option>
                                </select>
                            </dd>
                            <dd class="bt">
                                <button type="submit" class="btn_01">搜索</button>
                            </dd>
                        </dl>
                    </form>
                    <a href="<?php echo U('MemberLive_bsn/liveroom/editPage');?>" class="add_bt flex btn_01">
                        <span class="add_icon"></span>添加
                    </a>
                </div>
                <div class="tableBox table_skin">
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <th width="45">
                                编号
                            </th>
                            <th width="90">
                                直播标题
                            </th>
                            <th width="130">
                                简介
                            </th>
                            <th width="130">
                                直播图片
                            </th>
                            <th width="130">
                                背景图片
                            </th>
                            <th width="130">
                                logo
                            </th>
                            <th width="90">
                                频道
                            </th>
                            <th width="120">
                                话题
                            </th>
                            <th width="45">
                                性质
                            </th>
                            <th width="55">
                                城市
                            </th>
                            <!--<th width="90">-->
                                <!--活动状态-->
                            <!--</th>-->
                            <!--<th width="90">-->
                                <!--观看数量-->
                            <!--</th>-->
                            <th width="90">
                                审核状态
                            </th>
                            <th width="150">
                                操作
                            </th>
                        </tr>
                        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                <td width="133">
                                    <?php echo ($vo["serial_number"]); ?>
                                </td>
                                <td>
                                    <?php echo ($vo["title"]); ?>
                                </td>
                                <td>
                                    <?php echo msubstr($vo[introduction],0,40,'utf-8',true);?>
                                </td>
                                <td>
                                    <?php if($vo[image]!=''): ?><img src="/live/server/Onemla/administrator/Res/Uploads/live_room/<?php echo ($vo["image"]); ?>" width="120px"><?php endif; ?>
                                </td>
                                <td>
                                    <?php if($vo[bg_image]!=''): ?><img src="/live/server/Onemla/administrator/Res/Uploads/live_room/<?php echo ($vo["bg_image"]); ?>" width="120px"><?php endif; ?>
                                </td>
                                <td>
                                    <?php if($vo[logo]!=''): ?><img src="/live/server/Onemla/administrator/Res/Uploads/live_room/<?php echo ($vo["logo"]); ?>" width="120px"><?php endif; ?>
                                </td>
                                <td>
                                    <?php echo ($vo["channel"]); ?>
                                </td>
                                <td>
                                    <?php echo ($vo["tags"]); ?>
                                </td>
                                <td>
                                    <?php echo ($vo["character"]); ?>
                                </td>
                                <td>
                                    <?php echo (getCityName($vo["city_id"])); ?>
                                </td>
                                <!--<td>-->
                                    <!--<//if condition='getLiveInfo($vo[live_id])[status]==2'>-->
                                        <!--直播中-->
                                        <!--<//elseif condition='getLiveInfo($vo[live_id])[status]==1'/>-->
                                        <!--预告-->
                                        <!--<//else/>-->
                                        <!--回放-->
                                    <!--</if>-->
                                <!--</td>-->
                                <!--<td>-->
                                    <!---->
                                <!--</td>-->
                                <td>
                                    <?php if($vo[audit_status]==1): ?>待审核
                                        <?php elseif($vo[audit_status]==2): ?>
                                        审核通过
                                        <?php else: ?>
                                        不通过<?php endif; ?>
                                </td>
                                <td class="bt">
                                    <!--<a href="<?php echo U('MemberLive_bsn/Liveroom/detail',array('id'=>$vo[room_id]));?>" class="view_bt" title="详情"><button class="handle_bt">详情</button></a>-->
                                    <!--<a href="<?php echo U('MemberLive_bsn/Liveroom/editpage',array('id'=>$vo[room_id]));?>" class="edit_bt" title="修改"><button class="handle_bt">修改</button></a>-->
                                    <!--<input type="hidden" value="<?php echo ($vo["room_id"]); ?>" name='id' id="id">-->
                                    <!--<input type="hidden" value="<?php echo ($vo["status"]); ?>" name='status'>-->
                                    <!--<a href="#" class="del_bt" title="删除"><button class="handle_bt">删除</button></a>-->
                                    <div class="bt-more">
                                        <span style="width: 64px;">更多></span>
                                        <div class="bt-more-choice">
                                            <a href="<?php echo U('MemberLive_bsn/Liveroom/detail',array('id'=>$vo[room_id]));?>" class="view_bt" title="详情"><button>详情</button></a>
                                            <a href="<?php echo U('MemberLive_bsn/Liveroom/editpage',array('id'=>$vo[room_id]));?>" class="edit_bt" title="修改"><button>修改</button></a>
                                            <input type="hidden" value="<?php echo ($vo["room_id"]); ?>" name='id' id="id">
                                            <input type="hidden" value="<?php echo ($vo["status"]); ?>" name='status'>
                                            <a href="#" class="del_bt" title="删除"><button>删除</button></a>
                                        </div>
                                    </div>
                                </td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </table>
                    <script type="text/javascript">
                        $('.bt').find('a').css('margin-left','0px');
                        $(".bt-more").on("click",function(){
                            $(this).find('.bt-more-choice').slideToggle();

                        })
                    </script>
                    <div class="tableBox_bottom flex">
                        <ul class="page_list page_skin flex">
                            <?php echo ($page_show); ?>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- box end -->
        </div>
        <!-- main end -->
    </div>




</body>
</html>
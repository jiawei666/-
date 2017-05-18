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
    <script>
        $().ready(function () {
            <?php echo ($pCitySelectJs); ?>
            $('#click').click(function () {
                //$('form').submit();
                $('#submit').click();
            })
        });
    </script>
    <style>
        *{
            margin: 0;
            padding: 0;
        }
    </style>

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
                    <form action="<?php echo U('Live_room/Index/index');?>" method="post">
                        <dl class="flex">
                            <dd class="inputTxt">
                                <input type="text" name="live_name" value="<?php echo ($live_name); ?>" placeholder="请输入搜索的直播商" class="input_01" />
                            </dd>
                            <dd class="inputTxt">
                                <input type="text" name="title" value="<?php echo ($title); ?>" placeholder="请输入搜索的标题" class="input_01" />
                            </dd>
                            <dd class="inputTxt">
                                <select name="isshow" class='input_01'>
                                    <option value="">请选择首页显示状态</option>
                                    <option value="1" <?php echo ($isshow==1 ? 'selected=""':''); ?>>显示</option>
                                    <option value="0" <?php echo ($isshow === '0' ? 'selected=""':''); ?>>未显示</option>
                                </select>
                            </dd>
                            <dd class="inputTxt">
                                <select id="province"  name="province_id" class="input_01" >
                                    <option value="">请选择地区</option>
                                    <?php echo ($region); ?>
                                </select>
                                <select id="city"  name="city_id" class="input_01" >
                                    <option value="">请选择地区</option>
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
                            <!--<dd class="inputTxt">-->
                                <!--<select name="sort" class='input_01'>-->
                                    <!--<option value="">请选择排序方式</option>-->
                                    <!--<option value="create_time" <?php echo ($sort=='create_time' ? 'selected=""':''); ?>>新增时间</option>-->
                                    <!--<option value="view_count" <?php echo ($sort=='view_count' ? 'selected=""':''); ?>>观看数量</option>-->
                                <!--</select>-->
                            <!--</dd>-->
                            <dd class="bt">
                                <button type="submit" class="btn_01">搜索</button>
                            </dd>
                        </dl>
                    </form>
                </div>
                <div class="tableBox table_skin">
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <th width="90">
                                编号
                            </th>
                            <th width="90">
                                直播商
                            </th>
                            <th width="150">
                                直播标题
                            </th>
                            <th width="200">
                                直播简介
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
                                首页显示
                            </th>
                            <th width="90">
                                冻结状态
                            </th>
                            <th width="90">
                                审核状态
                            </th>
                            <th width="120">
                                操作
                            </th>
                        </tr>
                        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                <td width="133">
                                    <?php echo ($vo["serial_number"]); ?>
                                </td>
                                <td>
                                    <?php echo ($vo["live_name"]); ?>
                                </td>
                                <td width="150">
                                    <?php echo ($vo["title"]); ?>
                                </td>
                                <td width="200">
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
                                    <?php echo ($vo["character"]); ?>
                                </td>
                                <td>
                                    <?php echo (getCityName($vo["city_id"])); ?>
                                </td>
                                <!--<td>-->
                                    <!--//<//if condition='getLiveInfo($vo[live_id])[status]==2'>-->
                                        <!--直播中-->
                                        <!--<//elseif condition='getLiveInfo($vo[live_id])[status]==1'/>-->
                                        <!--预告-->
                                        <!--<//else/>-->
                                        <!--回放-->
                                    <!--<///if>-->
                                <!--</td>-->
                                <!--<td>-->
                                    <!--//-->
                                <!--</td>-->
                                <td>
                                    <?php if($vo[isshow]==1): ?>显示
                                        <?php else: ?>
                                        未显示<?php endif; ?>
                                </td>
                                <td>
                                    <?php if($vo[lock]==1): ?>正常
                                        <?php elseif($vo[lock]==2): ?>
                                        冻结<?php endif; ?>
                                </td>
                                <td>
                                    <?php if($vo[audit_status]==1): ?>待审核
                                        <?php elseif($vo[audit_status]==2): ?>
                                        审核通过
                                        <?php else: ?>
                                        不通过<?php endif; ?>
                                </td>
                                <td class="bt" width="120">
                                    <!--<a  style="margin-left: 0px;" href="<?php echo U('MemberLive_bsn/Liveroom/detail',array('id'=>$vo[room_id],'bsn_id'=>$vo[r_bsn_id]));?>" class="view_bt" title="详情"><button class="handle_bt" style="padding: 1.7px">详情</button></a>-->
                                    <!--<a  style="margin-left: 0px;" href="#" class="<?php echo ($vo[isshow]==1 ? 'show_bt' : 'unshow_bt'); ?>" title="<?php echo ($vo[isshow]==1 ? '首页不显示' : '首页显示'); ?>"><button class="handle_bt" style="width: 50px;padding: 1.7px"><?php echo ($vo[isshow]==1 ? '不显示' : '显示'); ?></button></a>-->
                                    <!--<a  style="margin-left: 0px;" href="#" class="<?php echo ($vo[lock]==1 ? 'lock_bt' : 'unlock_bt'); ?>" title="<?php echo ($vo[lock]==1 ? '冻结' : '解冻'); ?>"><button class="handle_bt" style="padding: 1.7px"><?php echo ($vo[lock]==1 ? '冻结' : '解冻'); ?></button></a>-->
                                    <!--<a  style="margin-left: 0px;" href="#" class="audit_bt"><button class="handle_bt" style="padding: 1.7px">审核</button></a>-->
                                    <div class="bt-more">
                                        <span style="width: 64px;">更多></span>
                                        <div class="bt-more-choice">
                                            <a href="<?php echo U('MemberLive_bsn/Liveroom/detail',array('id'=>$vo[room_id],'bsn_id'=>$vo[r_bsn_id]));?>" title="详情"><button>详情</button></a>
                                            <a href="#" class="<?php echo ($vo[isshow]==1 ? 'show_bt' : 'unshow_bt'); ?>" title="<?php echo ($vo[isshow]==1 ? '首页不显示' : '首页显示'); ?>"><button><?php echo ($vo[isshow]==1 ? '不显示' : '显示'); ?></button></a>
                                            <a href="#" class="<?php echo ($vo[lock]==1 ? 'lock_bt' : 'unlock_bt'); ?>" title="<?php echo ($vo[lock]==1 ? '冻结' : '解冻'); ?>"><button><?php echo ($vo[lock]==1 ? '冻结' : '解冻'); ?></button></a>
                                            <a href="#" class="audit_bt"><button>审核</button></a>
                                            <input type="hidden" value="<?php echo ($vo["room_id"]); ?>" name='id' id="id">
                                            <input type="hidden" value="<?php echo ($vo["audit_status"]); ?>" name='status'>
                                            <input type="hidden" value="<?php echo ($vo["live_id"]); ?>">
                                            <input type="hidden" value="<?php echo ($vo["alink"]); ?>">
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

    <div class="layerBox" id="layerBox1">
        <div class="hintBox" style="height: 200px">
            <h3 class="title skin_bg flex">
                活动审核
                <a href="#" class="close_bt"></a>
            </h3>
            <div class="con">
                <dl class="bt_list" style="margin-top: 0px">
                    <dd class="flex">
                        <button type="submit" class="btn_01" id="audit_pass_bt" >审核通过</button>
                        <button type="submit" class="btn_01 gray" id="audit_nopass_bt" >审核不通过</button>
                    </dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="layerBox" id="layerBox2">
        <div class="hintBox">
            <h3 class="title skin_bg flex">
                活动审核
                <a href="#" class="close_bt"></a>
            </h3>
            <div class="con add_list" style="padding-top: 40px;">
                <dl class="inputBox flex2">
                    <dd class="text">
                    </dd>
                    <dd class="inputTxt">
                        <input type="text" class="input_01" name="alink" />
                    </dd>
                </dl>
                <dl class="inputBox flex2">
                    <dd class="text" style="display: none">
                        liveId
                    </dd>
                    <dd class="inputTxt" style="display:none;" id="live_id">
                        <input type="text" class="input_01" name="live_id" />
                    </dd>
                </dl>
                <script>
                    $(function(){
                        $('#audit_pass_bt').click(function(){
                            var r_id=$(this).attr('name');
                            var live_id = $('input[value='+r_id+']').next().next().val();
                            var alink = $('input[value='+r_id+']').next().next().next().val();

                            if($('dd[class="text"]').eq(0).text()=='链接:'){
                                $('input[name="alink"]').val(alink);
                                $('input[name="live_id"]').val(live_id);
                            }
                        });
                    });
                </script>
                <dl class="bt_list" style="margin-top: 35px;">
                    <dd class="flex">
                        <button type="submit" class="btn_01" name="confirm">确认</button>
                        <button type="button" class="btn_01 gray close_bt">取消</button>
                    </dd>
                </dl>
            </div>
        </div>
    </div>




</body>
</html>
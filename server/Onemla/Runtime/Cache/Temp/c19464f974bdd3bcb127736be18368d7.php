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
    
    <script type="text/javascript" src="/live/server/Onemla/administrator/Res/js/jquery.datetimepicker.full.min.js" ></script>
    <link rel="stylesheet" href="/live/server/Onemla/administrator/Res/css/jquery.datetimepicker.min.css" />
    <script type="text/javascript" src="/live/server/Onemla/administrator/Res/js/live_tags.js" ></script>
    <script>
        $(function () {
            $('.date_input').datetimepicker({
                timepicker: false,
                format: 'Y-m-d H:i:s'
            });
        });
        function setImagePreview(doc,imgobj,fontobj) {
            var docObj = document.getElementById(doc);
            var imgObjPreview = document.getElementById(imgobj);
            if (docObj.files && docObj.files[0]) {
                //火狐下，直接设img属性
                imgObjPreview.style.display = 'block';
                imgObjPreview.style.width = '100%';
                imgObjPreview.style.height = '100%';
                imgObjPreview.style.zIndex = "0";
                $(fontobj).css({"color":"#fff"})
                //imgObjPreview.src = docObj.files[0].getAsDataURL();

                //火狐7以上版本不能用上面的getAsDataURL()方式获取，需要一下方式
                imgObjPreview.src = window.URL.createObjectURL(docObj.files[0]);
            } else {
                //IE下，使用滤镜
                docObj.select();
                var imgSrc = document.selection.createRange().text;
                var localImagId = document.getElementById("localImag");
                //必须设置初始大小
                localImagId.style.width = "100%";
                localImagId.style.height = "100%";
                //图片异常的捕捉，防止用户修改后缀来伪造图片
                try {
                    localImagId.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)";
                    localImagId.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = imgSrc;
                } catch (e) {
                    alert("您上传的图片格式不正确，请重新选择!");
                    return false;
                }
                imgObjPreview.style.display = 'none';
                document.selection.empty();
            }
            return true;
        }
    </script>
    <script>
        $().ready(function () {
            <?php echo ($pCitySelectJs); ?>
            $('#click').click(function () {
                //$('form').submit();
                $('#submit').click();
            })
        });
    </script>

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
                <div class="essential skin_bg">
                    编辑信息
                </div>
                <form action="<?php echo U('MemberLive_bsn/Liveroom/edit');?>" method="post" enctype="multipart/form-data"  >
                    <ul class="essential-box">
                        <li class="essential-title">
                            <span>编号：</span>
                            <input type="text" name="number" value="<?php echo ($info[serial_number]); ?>" disabled="" />
                        </li>
                        <li class="essential-title">
                            <span>直播间标题：</span>
                            <input type="text" name="title" value="<?php echo ($info[title]); ?>" required="" />
                        </li>
                        <li class="essential-brief">
                            <span>活动简介：</span>
                            <textarea name="introduction" required="" style="resize: none;width: 350px;height: 110px;"><?php echo ($info[introduction]); ?></textarea>
                        </li>
                        <li class="essential-bg">
                            <span>缩略图片：</span>
                            <div>
                                <a>点击上传图片</a>
                                <img id="preview" src="<?php echo ($info[image] == '' ? '' : '/live/server/Onemla/administrator/Res/Uploads/live_room/'); echo ($info[image]); ?>" >
                                <input type="file" accept="image/jpg,image/png,image/jpeg" name="image" id="doc" required="required" onchange="javascript:setImagePreview('doc', 'preview', '.essential-bg div');"/>
                            </div>
                            //建议用255*165图片
                        </li>
                        <li class="essential-logo">
                            <span>背景图片：</span>
                            <div>
                                <a>点击上传图片</a>
                                <img id="previews" src="<?php echo ($info[bg_image] == '' ? '' : '/live/server/Onemla/administrator/Res/Uploads/live_room/'); echo ($info[bg_image]); ?>" >
                                <input type="file" accept="image/jpg,image/png,image/jpeg" name="bg_image" id="docs" required="required" onchange="javascript:setImagePreview('docs', 'previews', '.essential-bg div');"/>
                            </div>
                            //建议用750*310图片
                        </li>
                        <li class="essential-public">
                            <span>logo：</span>
                            <div>
                                <a>点击上传图片</a>
                                <img id="preview_public" src="<?php echo ($info[logo] == '' ? '' : '/live/server/Onemla/administrator/Res/Uploads/live_room/'); echo ($info[logo]); ?>" >
                                <input type="file" accept="image/jpg,image/png,image/jpeg" name="logo" id="doc_public" required="required" onchange="javascript:setImagePreview('doc_public', 'preview_public', '.essential-bg div');"/>
                            </div>
                        </li>
                        <li class="essential-time">
                            <span>开始时间：</span>
                            <input type="text" name="start_time" value="<?php echo ($info[start_time]); ?>" placeholder="活动时间" class="input_01 date_input date_start" required="" style="background-color: white" />
                        </li>
                        <!--<li class="essential-number">-->
                            <!--<span>直播状态：</span>-->
                            <!--<select name="status" class='input_01'>-->
                                <!--<option value="1" <?php echo ($info[status]==1 ? 'selected=""':''); ?>>正在直播</option>-->
                                <!--<option value="2" <?php echo ($info[status]==2 ? 'selected=""':''); ?>>未开始</option>-->
                                <!--<option value="3" <?php echo ($info[status]==3 ? 'selected=""':''); ?>>回放</option>-->
                            <!--</select>-->
                        <!--</li>-->
                        <li class="essential-number">
                            <span>所属频道：</span>
                            <select name="channel_id" required="" class="input_01">
                                <?php if(is_array($channel)): $i = 0; $__LIST__ = $channel;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cn_vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($cn_vo["id"]); ?>" <?php echo ($info[live_channel_id]==$cn_vo["id"] ? 'selected=""':''); ?>><?php echo ($cn_vo["channel"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </li>
                        <li class="essential-number" name="tags">
                            <span>所属话题：</span>
                            <select name="tags0_id"  class="input_01">
                                <option value="">---请选择---</option>
                                <?php if(is_array($tags)): $i = 0; $__LIST__ = $tags;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$t_vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($t_vo["id"]); ?>" <?php echo ($info[tags0_id]==$t_vo["id"] ? 'selected=""':''); ?>><?php echo ($t_vo["tags"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                            <select name="tags1_id"  class="input_01">
                                <option value="">---请选择---</option>
                                <?php if(is_array($tags)): $i = 0; $__LIST__ = $tags;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$t_vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($t_vo["id"]); ?>" <?php echo ($info[tags1_id]==$t_vo["id"] ? 'selected=""':''); ?>><?php echo ($t_vo["tags"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                            <select name="tags2_id"  class="input_01">
                                <option value="">---请选择---</option>
                                <?php if(is_array($tags)): $i = 0; $__LIST__ = $tags;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$t_vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($t_vo["id"]); ?>" <?php echo ($info[tags2_id]==$t_vo["id"] ? 'selected=""':''); ?>><?php echo ($t_vo["tags"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </li>
                        <li class="inputTxt">
                            <span>所属性质：</span>
                            <select name="character_id" required="" class="input_01">
                                <?php if(is_array($character)): $i = 0; $__LIST__ = $character;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ca_vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($ca_vo["id"]); ?>" <?php echo ($info[character_id]==$ca_vo["id"] ? 'selected=""':''); ?>><?php echo ($ca_vo["character"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </li>
                        <li class="essential-number">
                            <span>所在地区：</span>
                            <select id="province"  name="province_id" class="input_01" required>
                                <option value="">---请选择---</option>
                                <?php echo ($region); ?>
                            </select>
                            <select id="city"  name="city_id" class="input_01" required>
                                <option value="">---请选择---</option>
                            </select>
                        </li>
                        <li class="essential-number" style="padding-left: 78px;font-size: 14px">
                            <table>
                            <tr>
                                <td>
                                    参数设置：
                                </td>
                                <td>
                                    <label class="checkBox" >
                                        <input type="checkbox" name="allowChat" <?php echo ($info[allowchat]=='' ? '':'checked'); ?> />
                                        <span class="icon"></span>
                                    </label>
                                </td>
                                <td>
                                    允许聊天
                                </td>
                                <td>
                                    <label class="checkBox" >
                                        <input type="checkbox" name="allowOrder"<?php echo ($info[alloworder]=='' ? '':'checked'); ?> />
                                        <span class="icon"></span>
                                    </label>
                                </td>
                                <td>
                                    允许预约
                                </td>
                                <td>
                                    <label class="checkBox" >
                                        <input type="checkbox" name="allowShare" <?php echo ($info[allowshare]=='' ? '':'checked'); ?> />
                                        <span class="icon"></span>
                                    </label>
                                </td>
                                <td>
                                    允许分享
                                </td>
                                <td>
                                    <label class="checkBox" >
                                        <input type="checkbox" name="allowImoji" <?php echo ($info[allowimoji]=='' ? '':'checked'); ?> />
                                        <span class="icon"></span>
                                    </label>
                                </td>
                                <td>
                                    允许发表情
                                </td>
                                <td>
                                    <label class="checkBox" >
                                        <input type="checkbox" name="allowBarrage" <?php echo ($info[allowbarrage]=='' ? '':'checked'); ?> />
                                        <span class="icon"></span>
                                    </label>
                                </td>
                                <td>
                                    允许弹幕
                                </td>
                                <td>
                                    <label class="checkBox" >
                                        <input type="checkbox" name="allowCollect" <?php echo ($info[allowcollect]=='' ? '':'checked'); ?> />
                                        <span class="icon"></span>
                                    </label>
                                </td>
                                <td>
                                    允许收藏
                                </td>
                                <td>
                                    <label class="checkBox" >
                                        <input type="checkbox" name="allowReward" <?php echo ($info[allowreward]=='' ? '':'checked'); ?> />
                                        <span class="icon"></span>
                                    </label>
                                </td>
                                <td>
                                    允许打赏
                                </td>
                                <td>
                                    <label class="checkBox" >
                                        <input type="checkbox" name="chatMonitor" <?php echo ($info[chatmonitor]=='' ? '':'checked'); ?> />
                                        <span class="icon"></span>
                                    </label>
                                </td>
                                <td>
                                    聊天监控
                                </td>
                                <td>
                                    <label class="checkBox" >
                                        <input type="checkbox" name="allowGood" <?php echo ($info[allowgood]=='' ? '':'checked'); ?> />
                                        <span class="icon"></span>
                                    </label>
                                </td>
                                <td>
                                    允许点赞
                                </td>
                                <td>
                                    <label class="checkBox" >
                                        <input type="checkbox" name="allowRp"  <?php echo ($info[allowrp]=='' ? '':'checked'); ?> />
                                        <span class="icon"></span>
                                    </label>
                                </td>
                                <td>
                                    允许发红包
                                </td>
                            </tr>
                            </table>
                        </li>
                    </ul>
                    <input type="hidden" name="id" value="<?php echo ($info[room_id]); ?>"/>
                    <input type="submit" id="info-submit" value="提交" />
                </form>
            </div>
            <!-- box end -->
        </div>
        <!-- main end -->
        <script>
            $('form').submit(function(){
                $('.submit_load').show();
                $('input[type="submit"]').attr('disabled','')
            });
            if($('#preview').attr('src') !=''){
                $('input[name="image"]').removeAttr('required');
                $('#preview').prev().text('');
            }
            if($('#previews').attr('src') !=''){
                $('input[name="bg_image"]').removeAttr('required');
                $('#previews').prev().text('');
            }
            if($('#preview_public').attr('src') !=''){
                $('input[name="logo"]').removeAttr('required');
                $('#preview_public').prev().text('');
            }
            $('span[class="icon"]').css('margin-left','10px');
            $('select').css('background-color','white');
        </script>
    </div>




</body>
</html>
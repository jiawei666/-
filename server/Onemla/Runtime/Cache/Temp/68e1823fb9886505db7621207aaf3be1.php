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
    <script type="text/javascript" src="/live/server/Onemla/administrator/Res/js/user.js" ></script>

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
                    管理员管理
                    <i class="gt">&gt;</i>
                    <span class="skin_color">管理员列表</span>
                </p>
                <div class="searchBox flex">
                    <form action="<?php echo U('Home/Index/index');?>" method="post">
                        <dl class="flex">
                            <dd class="inputTxt">
                                <input type="text" name="phone" value="<?php echo ($phone); ?>" placeholder="请输入搜索的手机号" class="input_01" />
                            </dd>
                            <dd class="inputTxt">
                                <input type="text" name="user_name" value="<?php echo ($user_name); ?>" placeholder="请输入搜索的用户名" class="input_01" />
                            </dd>
                            <dl class="date_list flex2">
                                <dd class="inputTxt">
                                    <input type="text" name="date_start" value="<?php echo ($date_start); ?>" placeholder="请选择开始日期" class="input_01 date_input date_start" />
                                </dd>
                                <dd class="date_list_text">
                                    至
                                </dd>
                                <dd class="inputTxt">
                                    <input type="text" name="date_end" value="<?php echo ($date_end); ?>" placeholder="请选择结束日期" class="input_01 date_input date_end" />
                                </dd>
                            </dl>
                            <dd class="bt">
                                <button type="submit" class="btn_01">搜索</button>
                            </dd>
                        </dl>
                    </form>
                    <a href="<?php echo U('Admin/Index/register_page');?>" class="add_bt flex btn_01">
                        <span class="add_icon"></span>注册
                    </a>
                </div>
                <div class="tableBox table_skin">
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tr>
<!--                            <th width="78">
                                <label class="checkBox">
                                    <input type="checkbox" />
                                    <span class="icon"></span>
                                </label>
                            </th>-->
                            <th width="80">
                                编号
                            </th>
                            <th>
                                手机号
                            </th>
                            <th>
                                账号
                            </th>
                            <th>
                                邮箱
                            </th>
                            <th>
                                微信号
                            </th>
                            <th>
                                QQ
                            </th>
                            <th>
                                状态
                            </th>
                            <th>
                                注册时间
                            </th>
                            <th width="200">
                                操作
                            </th>
                        </tr>
                        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
<!--                                <td>
                                    <label class="checkBox">
                                        <input type="checkbox" />
                                        <span class="icon"></span>
                                    </label>
                                </td>-->
                                <td>
                                    <?php echo ($vo["id"]); ?>
                                </td>
                                <td>
                                    <?php echo ($vo["phone"]); ?>
                                </td>
                                <td>
                                    <?php echo ($vo["user_name"]); ?>
                                </td>
                                <td>
                                    <?php echo ($vo["email"]); ?>
                                </td>
                                <td>
                                    <?php echo ($vo["wechat"]); ?>
                                </td>
                                <td><?php echo ($vo["qq"]); ?></td>
                                <td><?php echo ($vo[state]==1 ? '正常' : '锁定'); ?></td>
                                <td><?php echo ($vo["create_time"]); ?></td>
                                <td class="bt">
                                    <!--<button style="color: white;background-color: #0d6aad;border: none;border-radius: 3px;padding: 4px;margin-left: 20px">详情</button>-->
                                    <a href="<?php echo U('Home/Index/userDetails',array('user_id'=>$vo[id]));?>" class="view_bt" title="详情"><button class="handle_bt">详情</button></a>
                                    <!--<a href="<?php echo U('Home/Index/chat',array('from_user_id'=>$vo[id]));?>" class="record_bt" title="聊天"></a>-->
                                    <a href="#" class="<?php echo ($vo[state]==1 ? 'lock_bt' : 'unlock_bt'); ?>" title="<?php echo ($vo[state]==1 ? '冻结' : '解冻'); ?>"><button class="handle_bt"><?php echo ($vo[state]==1 ? '冻结' : '解冻'); ?></button></a>
                                    <a href="#" class="del_bt" title="删除"><button class="handle_bt">删除</button></a>
                                    <input type="hidden" value="<?php echo ($vo["id"]); ?>" id='user_id'>
                                    <input type="hidden" value="<?php echo ($vo["state"]); ?>" id='state'>
                                    <input type="hidden" value="<?php echo ($vo["phone"]); ?>" id='phone'>
                                </td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </table>
                    <div class="tableBox_bottom flex">
<!--                        <div class="bt_list">
                            <a href="#" class="btn_01">锁定</a>
                            <a href="#" class="sel_del_bt btn_01">删除</a>
                        </div>-->
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
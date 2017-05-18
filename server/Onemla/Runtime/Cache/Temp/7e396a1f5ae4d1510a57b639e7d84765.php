<?php if (!defined('THINK_PATH')) exit();?>
<div id="nav" class="skin_bg">
    <?php if(($user_type == 1) or ($user_type == 2)): ?><dl class="<?php echo ($menu_actived == 10 ? 'active' : ''); ?>">
            <a href="#" class="flex">
                <dd class="icon flex">
                    <span class="user_icon"></span>
                </dd>
                <dd class="text nowrap">
                    用户管理
                </dd>
            </a>
            <ul>
                <li class="<?php echo ($actived == 101 ? 'active' : ''); ?>">
                    <a href="<?php echo U('Home/Index/index');?>">会员信息</a>
                </li>
            </ul>
            <ul>
                <li class="<?php echo ($actived == 102 ? 'active' : ''); ?>">
                    <a href="<?php echo U('Home/Useraudit/personal');?>">认证信息审核</a>
                </li>
            </ul>
            <!--<ul>-->
                <!--<li class="<?php echo ($actived == 103 ? 'active' : ''); ?>">-->
                    <!--<a href="<?php echo U('Home/Index/setPassword');?>">密码设置</a>-->
                <!--</li>-->
            <!--</ul>-->
        </dl>
        <!--<dl class="<?php echo ($menu_actived == 11 ? 'active' : ''); ?>">-->
            <!--<a href="#" class="flex">-->
                <!--<dd class="icon flex">-->
                    <!--<span class="order_icon"></span>-->
                <!--</dd>-->
                <!--<dd class="text nowrap">-->
                    <!--活动管理-->
                <!--</dd>-->
            <!--</a>-->
            <!--<ul>-->
                <!--<li class="<?php echo ($actived == 111 ? 'active' : ''); ?>">-->
                    <!--<a href="<?php echo U('Activity/Index/index');?>">活动管理</a>-->
                <!--</li>-->
            <!--</ul>-->
        <!--</dl>-->
        <dl class="<?php echo ($menu_actived == 12 ? 'active' : ''); ?>">
            <a href="#" class="flex">
                <dd class="icon flex">
                    <span class="price_icon"></span>
                </dd>
                <dd class="text nowrap">
                    工单管理
                </dd>
            </a>
            <ul>
                <li class="<?php echo ($actived == 121 ? 'active' : ''); ?>">
                    <a href="<?php echo U('Repair/Index/index');?>">工单管理</a>
                </li>
            </ul>
        </dl>
        <dl class="<?php echo ($menu_actived == 13 ? 'active' : ''); ?>">
            <a href="#" class="flex">
                <dd class="icon flex">
                    <span class="case_icon"></span>
                </dd>
                <dd class="text nowrap">
                    案例管理
                </dd>
            </a>
            <ul>
                <li class="<?php echo ($actived == 131 ? 'active' : ''); ?>">
                    <a href="<?php echo U('CaAndSo/Index/index');?>">案例管理</a>
                </li>
                <li class="<?php echo ($actived == 132 ? 'active' : ''); ?>">
                    <a href="<?php echo U('CaAndSo/Solution/index');?>">解决方案</a>
                </li>
            </ul>
        </dl>
        <dl class="<?php echo ($menu_actived == 14 ? 'active' : ''); ?>">
            <a href="#" class="flex">
                <dd class="icon flex">
                    <span class="live_icon"></span>
                </dd>
                <dd class="text nowrap">
                    直播管理
                </dd>
            </a>
            <ul>
                <li class="<?php echo ($actived == 140 ? 'active' : ''); ?>">
                    <a href="<?php echo U('Live_room/Index/bsn');?>">直播商管理</a>
                </li>
            </ul>
            <ul>
                <li class="<?php echo ($actived == 141 ? 'active' : ''); ?>">
                    <a href="<?php echo U('Live_room/Index/index');?>">直播间管理</a>
                </li>
            </ul>
            <ul>
                <li class="<?php echo ($actived == 143 ? 'active' : ''); ?>">
                    <a href="<?php echo U('Live_room/Banner/index');?>">Banner管理</a>
                </li>
            </ul>
            <ul>
                <li class="<?php echo ($actived == 142 ? 'active' : ''); ?>">
                    <a href="<?php echo U('Live_room/Character/index');?>">性质分类管理</a>
                </li>
            </ul>
        </dl><?php endif; ?>
    <?php if($user_type == 3): ?><dl class="<?php echo ($menu_actived == 20 ? 'active' : ''); ?>">
            <a href="#" class="flex">
                <dd class="icon flex">
                    <span class="admin_icon"></span>
                </dd>
                <dd class="text nowrap">
                    会员信息
                </dd>
            </a>
            <ul>
                <li class="<?php echo ($actived == 201 ? 'active' : ''); ?>">
                    <a href="<?php echo U('Member/Index/index');?>">会员信息</a>
                </li>
                <!--<li class="<?php echo ($actived == 200 ? 'active' : ''); ?>">-->
                    <!--<a href="<?php echo U('Member/Index/chat');?>">管理员消息</a>-->
                <!--</li>-->
                <li class="<?php echo ($actived == 202 ? 'active' : ''); ?>">
                    <a href="<?php echo U('Member/Index/setPassword');?>">密码设置</a>
                </li>
                <li class="<?php echo ($actived == 203 ? 'active' : ''); ?>">
                    <a href="<?php echo U('Member/Index/certificationPersonal');?>">认证信息</a>
                </li>
            </ul>
        </dl>
        <!--<dl class="<?php echo ($menu_actived == 21 ? 'active' : ''); ?>">-->
            <!--<a href="#" class="flex">-->
                <!--<dd class="icon flex">-->
                    <!--<span class="sales_icon"></span>-->
                <!--</dd>-->
                <!--<dd class="text nowrap">-->
                    <!--会员活动管理-->
                <!--</dd>-->
            <!--</a>-->
            <!--<ul>-->
                <!--<li class="<?php echo ($actived == 211 ? 'active' : ''); ?>">-->
                    <!--<a href="<?php echo U('MemberActivity/Index/index');?>">活动列表</a>-->
                <!--</li>-->
            <!--</ul>-->
        <!--</dl>-->
        <dl class="<?php echo ($menu_actived == 22 ? 'active' : ''); ?>">
            <a href="#" class="flex">
                <dd class="icon flex">
                    <span class="sales_icon"></span>
                </dd>
                <dd class="text nowrap">
                    会员工单管理
                </dd>
            </a>
            <ul>
                <li class="<?php echo ($actived == 221 ? 'active' : ''); ?>">
                    <a href="<?php echo U('MemberRepair/Index/index');?>">工单列表</a>
                </li>
            </ul>
        </dl>
        <?php if($is_live!=''): ?><dl class="<?php echo ($menu_actived == 23 ? 'active' : ''); ?>">
                <a href="#" class="flex">
                    <dd class="icon flex">
                        <span class="live_icon"></span>
                    </dd>
                    <dd class="text nowrap">
                        直播商管理
                    </dd>
                </a>
                <ul>
                    <li class="<?php echo ($actived == 231 ? 'active' : ''); ?>">
                        <a href="<?php echo U('MemberLive_bsn/Index/index');?>">直播商</a>
                    </li>
                </ul>
                <ul>
                    <li class="<?php echo ($actived == 232 ? 'active' : ''); ?>">
                        <a href="<?php echo U('MemberLive_bsn/Channel/index');?>">频道</a>
                    </li>
                </ul>
                <ul>
                    <li class="<?php echo ($actived == 233 ? 'active' : ''); ?>">
                        <a href="<?php echo U('MemberLive_bsn/Tags/index');?>">话题</a>
                    </li>
                </ul>
                <ul>
                    <li class="<?php echo ($actived == 234 ? 'active' : ''); ?>">
                        <a href="<?php echo U('MemberLive_bsn/Liveroom/index');?>">直播间</a>
                    </li>
                </ul>
            </dl><?php endif; endif; ?>
</div>
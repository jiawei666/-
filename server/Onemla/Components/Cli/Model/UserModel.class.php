<?php

namespace Components\Cli\Model;
use Onemla\OnemlaHelper;
use Onemla\OnemlaModel;
use Onemla\OnemlaRequest;

class UserModel extends OnemlaModel{

    /**
     * 每天执行一次
     **/
    public function update(){

        /**
         * 计算人脉关系值
         * 人脉关系值 = 我的粉色（总分40分） + 我的关注（总分10分）+ 互动留言（总分50分）
         **/

        $this -> execute('update tb_user set contacts = fans + myfollow + forums_grade where user_type in(2,3)');

        /**
         * 计算个人影响力值
         *
         * 个人影响力值 = 资料信息+人脉关系+行为偏好+活跃度+传播能力
         *
         * 总分500分，每个因子各100分
         *
         **/

        $this -> execute('update tb_user set user_popularity = info_per + contacts + action_grade + active_per + spread_grade where user_type in(2,3)');

    }

    /**
     * 每月执行一次
     **/
    public function update_m(){
        /**
         * 互动留言
         * 一个月内与你互动的粉丝人数（包括相互私信、相互评论、相互回复），
         * 每个人+1分，超过50人后，不再增加得分；
         * 必须是粉丝发了一条信息给你，你回复了一条信息给粉丝，才能+1分；
         **/



        /**
         * 行为偏好
         * 每撰写1篇故事(s.c_s_c)+5.0分，20分上限；共撰写4篇故事；
         * 每阅读1篇故事(r.r_s_c)+0.5分，20分上限；共阅读40篇故事；
         * 每评论1条(cmc.cmc + cmc2.cmc2)+0.5分，15分上限；共撰写30条评论；
         * 每点赞1次+0.2分，15分上限；共点赞75次；
         * 每分享1次+1.0分，10分上限；共分享10次；
         * 每收藏1次 +2.0分，10分上限；共收藏5篇故事；
         *
         * 多表外连的情况下 把数据量大的表放在前面会好一点
         *
         **/

        $this -> execute('update tb_user as u
                        left join (/**获取阅读故事数量**/
                            select user_id,count(*) as r_s_c from tb_read where s_or_t_u = 0 group by user_id
                        ) as r on u.user_id = r.user_id
                        left join (/**获取任务点赞数量**/
                            select user_id,count(*) as zan from tb_thumb_up group by user_id
                        ) as zan on u.user_id = zan.user_id
                        left join (/**获取故事点赞数量**/
                            select user_id,count(*) as zan2 from tb_thumb_up2 group by user_id
                        ) as zan2 on u.user_id = zan2.user_id
                        left join (/**获取任务评论数量**/
                            select user_id,count(*) as cmc from tb_comment group by user_id
                        ) as cmc on u.user_id = cmc.user_id
                        left join (/**获取故事评论数量**/
                            select user_id,count(*) as cmc2 from tb_comment2 group by user_id
                        ) as cmc2 on u.user_id = cmc2.user_id
                        left join (/**获取分享数量**/
                            select user_id,count(*) as sc from tb_share group by user_id
                        ) as share on u.user_id = share.user_id
                        left join (/**获取收藏数量**/
                            select user_id,count(*) as co from tb_collect group by user_id
                        ) as co on u.user_id = co.user_id
                        left join (/**获取撰写故事数量**/
                            select user_id,count(*) as c_s_c from tb_story group by user_id
                        ) as s on u.user_id = s.user_id
                        set action_grade = (
                            (case when s.c_s_c > 4 then 20 else s.c_s_c*5 end)
                            +(case when r.r_s_c > 40 then 20 else r.r_s_c*0.5 end)
                            +(case when (cmc.cmc + cmc2.cmc2) > 30 then 15 else (cmc.cmc + cmc2.cmc2)*0.5 end)
                            +(case when (zan.zan + zan2.zan2) > 75 then 15 else (zan.zan + zan2.zan2)*0.2 end)
                            +(case when share.sc > 10 then 10 else share.sc end)
                            +(case when co.co > 5 then 10 else co.co*2 end)
                        )where user_type in(2,3)');


        /**
         * 活跃度
         * 每成功登陆1天+5分，1个月内登陆20天以上+100分，超过20天后，不再增加得分；
         **/
        $this -> execute('update tb_user set active_per = (case when login_count > 20 then 100 else login_count*5 end) where user_type in(2,3)');

    }

}
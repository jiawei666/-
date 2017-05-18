/**
 * Created by Administrator on 2017/5/4 0004.
 */
$(function(){
    /*
     * 加载更多
     * */
    $('div[name="more"]').click(function () {
        var url = "index.php?m=WechatIndex&c=Index&a=reloadMore";
        var number =$('input[name="number"]').attr('value');
        var channel = $('input[name="channel_id"]').val();
        var bsn = $('input[name="bsn_id"]').val();
        if(channel!='' && bsn!='') {
            var where = "`live_channel_id`=" + channel + " and `r_bsn_id`="+bsn+" and `lock`=1 and `audit_status`=2";
        }else {
            alert('请求失败');
            return;
        }
        $.post(url,{number:number,where:where},function(data){
            $.each(data['dataArr'],function(name,value){
                if(value.status==2){
                    var trailer = "<p class='home-list-href underway' href='javascript:void(0)'> 直播中 </p>"
                }else if(value.status==1){
                    var trailer = "<p class='home-list-href trailer' href='javascript:void(0)'> 预告 </p>"
                }else{
                    var trailer = "<p class='home-list-href playback' href='javascript:void(0)'> 回放 </p>"
                }

                $('div[name="roomList"]').append(
                    "<div class='home-list'><a href='"+value.alink+"' name='view_count'><input type='hidden' value='"+value.room_id+"'><img class='home-list-img' src='Administrator/Res/Uploads/live_room/"+value.image+"'/><div class='home-list-container'><h4 class='home-list-title'>"+value.title+"</h4><p class='home-list-time'>"+value.start_time+"</p><p class='home-list-number'> "+value.view_count+" </p>"+trailer+"</div></a> </div>"
                )
            });
            if(number*3 >= data['total']){
                $('div[name="more"]').find('p').text('亲，已经加载完了');
                $('div[name="more"]').attr('class','loading_done');
                $('div[name="more"]').attr('disabled','disabled');
            }
            $('input[name="number"]').attr('value',++number);
        })

    })
});
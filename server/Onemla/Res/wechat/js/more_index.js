/**
 * Created by Eminem on 2017/5/3.
 */
$(function(){
    /*
     * 加载更多
     * */
    $('div[name="more"]').click(function () {
        var url = "index.php?m=WechatIndex&c=Index&a=reloadMore";
        var number = $('input[name="number"]').attr('value');
        $.post(url,{number:number,where:"`isshow`=1 and `lock`=1 and `audit_status`=2",},function(data){
            $.each(data['dataArr'],function(name,value){
                if(value.status==2){
                    var trailer = "<a class='underway' href='javascript:void(0)'>直播中</a>"
                }else if(value.status==1){
                    var trailer = "<a class='trailer' href='javascript:void(0)'>预告</a>"
                }else{
                    var trailer = "<a class='playback' href='javascript:void(0)'>回放</a>"
                }
                $('div[name="roomList"]').append(
                    "<div class='index-list' > <a href="+value.alink+" name='view_count'> <input type='hidden' value="+value.room_id+"> <img src='Administrator/Res/Uploads/live_room/"+value.bg_image+"' /> </a> <p class='index-list-time'>"+value.start_time+"</p> <div class='index-list-href'> <p class='index-list-brief'>"+value.title+"</p>"+trailer+"<p class='index-list-eye'>"+value.view_count+"</p></div></div>"
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
/**
 * Created by Administrator on 2017/4/11 0011.
 */
$(function(){
    $('.del_bt').click(function(){
        var id = $(this).siblings("#id").val();
        swal({
            title: "您确定要删除吗？",
            text: "您确定要删除当前直播间吗？",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: true,
            showLoaderOnConfirm: false,
            confirmButtonText: "是的，我要删除",
            confirmButtonColor: "#ec6c62"

        }, function () {
            var url = "index.php?m=MemberLive_bsn&c=Liveroom&a=delete";
            $.post(url, {id: id}, function (data) {
            }, 'json').done(function (data) {
                if (data.flag == "1") {
                    swal({title:"操作成功", text:"已删除当前直播间！",type: "success", timer: 700});
                    location.reload();
                } else {
                    swal({title:"OMG", text:"删除失败！",type: "error", timer: 700});
                }
            }).error(function (data) {
                swal({title:"OMG", text:"删除操作失败了！",type: "error", timer: 700});
            });
        });
    });

    /**
     * 显示
     */
    $('.show_bt').click(function () {
        var id = $(this).siblings("#id").val();
        swal({
            title: "您确定不显示吗？",
            text: "您确定不在首页显示当前直播间吗？",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            confirmButtonText: "是的，我确定",
            confirmButtonColor: "#ec6c62"

        }, function () {
            var url = "index.php?m=Live_room&c=Index&a=isshow";
            $.post(url, {id: id,isshow:0}, function (data) {
            }, 'json').done(function (data) {
                if (data.flag == "1") {
                    swal({title:"操作成功!", text:"已成功不显示！",type: "success", timer: 700});
                    location.reload();
                } else {
                    swal({title:"OMG", text:"当前直播间已不显示！",type: "error", timer: 700});
                }
            }).error(function (data) {
                swal("OMG", "操作失败了!", "error");
            });
        });
    });

    /**
     * 不显示
     */
    $('.unshow_bt').click(function () {
        var id = $(this).siblings("#id").val();
        swal({
            title: "您确定显示吗？",
            text: "您确定在首页显示当前直播间吗？",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            confirmButtonText: "是的，我确定",
            confirmButtonColor: "#ec6c62"


        }, function () {
            var url = "index.php?m=Live_room&c=Index&a=isshow";
            $.post(url, {id: id,isshow:1}, function (data) {

            }, 'json').done(function (data) {
                if (data.flag == "1") {
                    swal({title:"操作成功!", text:"已经成功显示！",type: "success", timer: 700});
                    location.reload();
                } else {
                    swal({title:"OMG", text:"当前直播间已显示!",type: "error", timer: 700});
                }
            }).error(function (data) {
                swal("OMG", "操作失败了!", "error");
            });
        });
    });

    /**
     * 冻结
     * */
    $('.lock_bt').click(function () {
        var id = $(this).siblings("#id").val();
        swal({
            title: "您确定要冻结吗？",
            text: "您确定要冻结当前直播间吗？",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            confirmButtonText: "是的，我要冻结",
            confirmButtonColor: "#ec6c62"

        }, function () {
            var url = "index.php?m=Live_room&c=Index&a=lock";
            $.post(url, {id: id,lock:2}, function (data) {

            }, 'json').done(function (data) {
                if (data.flag == "1") {
                    swal({title:"操作成功!", text:"已成功冻结当前直播间！",type: "success", timer: 700});
                    location.reload();
                } else {
                    swal({title:"OMG", text:data.msg,type: "error", timer: 700});
                }
            }).error(function (data) {
                swal("OMG", "冻结操作失败了!", "error");
            });
        });
    });

    /**
     * 解冻
     */
    $('.unlock_bt').click(function () {
        var id = $(this).siblings("#id").val();
        swal({
            title: "您确定要解冻吗？",
            text: "您确定要解冻当前用户吗？",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            confirmButtonText: "是的，我要解冻",
            confirmButtonColor: "#ec6c62"
        }, function () {
            var url = "index.php?m=Live_room&c=Index&a=lock";
            $.post(url, {id: id,lock:1}, function (data) {

            }, 'json').done(function (data) {
                if (data.flag == "1") {
                    swal({title:"操作成功!", text:"解冻成功！",type: "success", timer: 700});
                    location.reload();
                } else {
                    swal({title:"OMG", text:data.msg,type: "error", timer: 700});
                }
            }).error(function (data) {
                swal("OMG", "解冻操作失败了!", "error");
            });
        });
    });

    /**
     * 审核
     */
    $(".audit_bt").click(function () {
        $('#layerBox1').attr('class','layerBox show');
        id=$(this).next().val();
        if($(this).next().next().val()==1){
            $('#audit_pass_bt').show();
            $('#audit_pass_bt').attr('name',id);
            $('#audit_nopass_bt').show();
            $('#audit_nopass_bt').css('margin-left','0px');
            $('#audit_pass_bt').css('margin-left','0px');
        }
        if($(this).next().next().val()==2){
            $('#audit_pass_bt').hide();
            $('#audit_nopass_bt').show();
            $('#audit_nopass_bt').css('margin-left','115px');
            $('#audit_pass_bt').css('margin-left','0px');
            //$('input[name="reason"]').show();
        }
        if($(this).next().next().val()==3){
            $('#audit_pass_bt').attr('name',id);
            $('#audit_pass_bt').show();
            $('#audit_pass_bt').css('margin-left','115px');
            $('#audit_nopass_bt').css('margin-left','0px');
            $('#audit_nopass_bt').hide();
            //$('input[name="reason"]').hide();
        }
    });


    $('#audit_pass_bt').click(function(){
        $('dd[class="text"]').eq(0).text('链接:');
        $('dd[class="text"]').eq(1).show();
        $('#live_id').show();

        $('button[name="confirm"]').attr('id','pass');
        $('#layerBox1').attr('class','layerBox');
        $('#layerBox2').attr('class','layerBox show');
    });

    $('#audit_nopass_bt').click(function(){
        $('input[name="alink"]').val('');
        $('input[name="live_id"]').val('');
        $('dd[class="text"]').eq(0).text('理由:');
        $('dd[class="text"]').eq(1).hide();
        $('#live_id').hide();
        $('button[name="confirm"]').attr('id','nopass');
        $('#layerBox1').attr('class','layerBox');
        $('#layerBox2').attr('class','layerBox show');
    });

    $('button[name="confirm"]').click(function () {
        if($(this).attr('id')=='pass'){
            if($('input[name="alink"]').val()==''){
                alert('审核通过须写上活动链接');
            }else {
                var url = "index.php?m=Live_room&c=Index&a=audit";
                $.post(url, {id:id,audit_res:2,alink:$('input[name="alink"]').val(),live_id:$('input[name="live_id"]').val()}, function (data) {
                }, 'json').done(function (data) {
                    if (data.flag == "1") {
                        swal({title:"操作成功", text:"已成功审核！",type: "success", timer: 700});
                        location.reload();
                    } else {
                        swal({title:"OMG", text:"审核失败！",type: "error", timer: 700});
                    }
                }).error(function (data) {
                    swal({title:"OMG", text:"审核操作失败！",type: "error", timer: 700});
                });
            }
        }else{
            if($('input[name="alink"]').val()==''){
                alert('不通过必须写上理由');
            }else {
                var url = "index.php?m=Live_room&c=Index&a=audit";
                $.post(url, {id:id,audit_res: 3,reason:$('input[name="alink"]').val()}, function (data) {
                }, 'json').done(function (data) {
                    if (data.flag == "1") {
                        swal({title:"操作成功", text:"已成功审核！",type: "success", timer: 700});
                        location.reload();
                    } else {
                        swal({title:"OMG", text:"审核失败！",type: "error", timer: 700});
                    }
                }).error(function (data) {
                    swal({title:"OMG", text:"审核操作失败！",type: "error", timer: 700});
                });
            }
        }

    });

});
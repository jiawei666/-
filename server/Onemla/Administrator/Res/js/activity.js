$(function () {
    /**
     * 审核
     */
    $(".audit_bt").click(function () {
        $('#layerBox1').attr('class','layerBox show');
        id=$(this).next().val();
        if($(this).next().next().val()==1){
            $('#audit_pass_bt').show();
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
            $('#audit_pass_bt').show();
            $('#audit_pass_bt').css('margin-left','115px');
            $('#audit_nopass_bt').css('margin-left','0px');
            $('#audit_nopass_bt').hide();
            //$('input[name="reason"]').hide();
        }
    });

    $('#audit_pass_bt').click(function () {
        var url = "index.php?m=Activity&c=Index&a=audit";
        $.post(url, {id:id,audit_res:2}, function (data) {
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
    });

    $('#audit_nopass_bt').click(function(){
        $('#layerBox1').attr('class','layerBox');
        $('#layerBox2').attr('class','layerBox show');
    });

    $('#nopass').click(function () {
        if($('input[name="reason"]').val()==''){
            alert('不通过必须写上理由');
        }else {
            var url = "index.php?m=Activity&c=Index&a=audit";
            $.post(url, {id:id,audit_res: 3,reason:$('input[name="reason"]').val()}, function (data) {
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
    });



    /**
     * 删除
     */
    $('.del_bt').click(function () {
        var id = $(this).siblings("#id").val();
        swal({
            title: "您确定要删除吗？",
            text: "您确定要删除当前活动数据吗？",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: true,
            showLoaderOnConfirm: false,
            confirmButtonText: "是的，我要删除",
            confirmButtonColor: "#ec6c62"

        }, function () {
            var url = "index.php?m=Activity&c=Index&a=delete";
            $.post(url, {id: id}, function (data) {

            }, 'json').done(function (data) {
                if (data.flag == "1") {
                    swal({title:"操作成功", text:"已删除当前活动数据！",type: "success", timer: 700});
                    //$('.confirm').click(function () {   //额外绑定一个事件，当确定执行之后返回成功的页面的确定按钮，点击之后刷新当前页面或者跳转其他页面
                    //
                    //});
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
     * 会员活动管理--删除
     */
    $('.member_del_bt').click(function () {
        var id = $(this).siblings("#id").val();
        swal({
            title: "您确定要删除吗？",
            text: "您确定要删除当前活动数据吗？",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: true,
            showLoaderOnConfirm: false,
            confirmButtonText: "是的，我要删除",
            confirmButtonColor: "#ec6c62"

        }, function () {
            var url = "index.php?m=MemberActivity&c=Index&a=delete";
            $.post(url, {id: id}, function (data) {

            }, 'json').done(function (data) {
                if (data.flag == "1") {
                    swal({title:"操作成功", text:"已删除当前活动数据！",type: "success", timer: 700});
                    //$('.confirm').click(function () {   //额外绑定一个事件，当确定执行之后返回成功的页面的确定按钮，点击之后刷新当前页面或者跳转其他页面
                    //
                    //});
                    location.reload();
                } else {
                    swal({title:"OMG", text:"删除失败！",type: "error", timer: 700});
                }
            }).error(function (data) {
                swal({title:"OMG", text:"删除操作失败了！",type: "error", timer: 700});
            });
        });
    })
})
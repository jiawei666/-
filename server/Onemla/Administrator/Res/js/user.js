$(function () {
    $('.date_input').datetimepicker({
        timepicker: false,
        format: 'Y-m-d'
    });

    $('.date_list').each(function () {
        var $this = $(this);
        $this.find('.date_start').datetimepicker({
            timepicker: false,
            format: 'Y-m-d',
            onShow: function (ct) {
                this.setOptions({
                    maxDate: $this.find('.date_end').val() ? $this.find('.date_end').val() : false
                })
            },
            timepicker: false
        });
        $this.find('.date_end').datetimepicker({
            timepicker: false,
            format: 'Y-m-d',
            onShow: function (ct) {
                this.setOptions({
                    minDate: $this.find('.date_start').val() ? $this.find('.date_start').val() : false
                })
            },
            timepicker: false
        });
    });

    /**
     * 锁定
     */
    $('.lock_bt').click(function () {
        var user_id = $(this).siblings("#user_id").val();
        var state = $(this).siblings("#state").val();
        var phone = $(this).siblings("#phone").val();
        swal({
            title: "您确定要冻结吗？",
            text: "您确定要冻结当前用户吗？",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            confirmButtonText: "是的，我要冻结",
            confirmButtonColor: "#ec6c62"

        }, function () {
            var url = "index.php?m=Home&c=Index&a=freeze";
            $.post(url, {user_id: user_id,state:state,phone:phone}, function (data) {

            }, 'json').done(function (data) {
                if (data.flag == "1") {
                    swal({title:"操作成功!", text:data.msg,type: "success", timer: 700});
                    location.reload();
                } else {
                    swal({title:"OMG", text:data.msg,type: "error", timer: 700});
                }
            }).error(function (data) {
                swal("OMG", "锁定操作失败了!", "error");
            });
        });
    });

    /**
     * 解锁
     */
    $('.unlock_bt').click(function () {
        var user_id = $(this).siblings("#user_id").val();
        var state = $(this).siblings("#state").val();
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
            var url = "index.php?m=Home&c=Index&a=freeze";
            $.post(url, {user_id: user_id,state:state}, function (data) {

            }, 'json').done(function (data) {
                if (data.flag == "1") {
                    swal({title:"操作成功!", text:data.msg,type: "success", timer: 700});
                    location.reload();
                } else {
                    swal({title:"OMG", text:data.msg,type: "error", timer: 700});
                }
            }).error(function (data) {
                swal("OMG", "解锁操作失败了!", "error");
            });
        });
    });
    
    
    /**
     * 删除
     */
    $('.del_bt').click(function () {
        var user_id = $(this).siblings("#user_id").val();
        swal({
            title: "您确定要删除吗？",
            text: "您确定要删除当前用户吗？",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            confirmButtonText: "是的，我要删除",
            confirmButtonColor: "#ec6c62"

        }, function () {
            var url = "index.php?m=Home&c=Index&a=delete";
            $.post(url, {user_id: user_id}, function (data) {

            }, 'json').done(function (data) {
                if (data.flag == "1") {
                    swal({title:"操作成功!", text:"删除用户成功！",type: "success", timer: 700});
                    //$('.confirm').click(function () {   //额外绑定一个事件，当确定执行之后返回成功的页面的确定按钮，点击之后刷新当前页面或者跳转其他页面
                    //
                    //});
                    location.reload();
                } else {
                    swal("OMG", "删除失败！", "error");
                }
            }).error(function (data) {
                swal("OMG", "删除操作失败了!", "error");
            });
        });
    })


});
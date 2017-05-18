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
    })

    /**
     * 锁定
     */
    $('.lock_bt').click(function () {
        var user_id = $(this).siblings("#user_id").val();
        var state = $(this).siblings("#state").val();
        swal({
            title: "您确定要锁定吗？",
            text: "您确定要锁定当前用户吗？",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            confirmButtonText: "是的，我要锁定",
            confirmButtonColor: "#ec6c62"

        }, function () {
            var url = "index.php?m=Home&c=Index&a=freeze";
            $.post(url, {user_id: user_id,state:state}, function (data) {

            }, 'json').done(function (data) {
                if (data.flag == "1") {
                    swal("操作成功!", "已成功锁定当前用户！", "success");
                    $('.confirm').click(function () {   //额外绑定一个事件，当确定执行之后返回成功的页面的确定按钮，点击之后刷新当前页面或者跳转其他页面
                        location.reload();
                    });
                } else {
                    swal("OMG", "已经锁定！", "error");
                }
            }).error(function (data) {
                swal("OMG", "锁定操作失败了!", "error");
            });
        });
    })

    /**
     * 解锁
     */
    $('.unlock_bt').click(function () {
        var user_id = $(this).siblings("#user_id").val();
        var state = $(this).siblings("#state").val();
        swal({
            title: "您确定要解开锁定吗？",
            text: "您确定要解锁当前用户吗？",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            confirmButtonText: "是的，我要解锁",
            confirmButtonColor: "#ec6c62"

        }, function () {
            var url = "index.php?m=Home&c=Index&a=freeze";
            $.post(url, {user_id: user_id,state:state}, function (data) {

            }, 'json').done(function (data) {
                if (data.flag == "1") {
                    swal("操作成功!", "已成功解锁当前用户！", "success");
                    $('.confirm').click(function () {   //额外绑定一个事件，当确定执行之后返回成功的页面的确定按钮，点击之后刷新当前页面或者跳转其他页面
                        location.reload();
                    });
                } else {
                    swal("OMG", "当前用户没有锁定！", "error");
                }
            }).error(function (data) {
                swal("OMG", "解锁操作失败了!", "error");
            });
        });
    })
    
    
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
                    swal("操作成功!", "已成功删除当前用户！", "success");
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


})
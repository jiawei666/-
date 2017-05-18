$(function () {
    /**
     * 审核
     */
    $(".reply").click(function () {
        var id = $(this).siblings("#id").val();
        swal({
            title: "输入框来了",
            text: "请输入回复内容",
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            animation: "slide-from-top",
            inputPlaceholder: "回复内容"
        }, function (inputValue) {
            if (inputValue === false)
                return false;
            if (inputValue === '') {
                swal.showInputError("请输入回复内容！");
                return false
            }
//            swal("棒极了!", "您填写的是: " + inputValue, "success");
            var url = "index.php?m=Repair&c=Index&a=reply";
            $.post(url, {id:id,inputValue: inputValue}, function (data) {

            }, 'json').done(function (data) {
                if (data.flag == "1") {
                    swal("操作成功!", "已成功回复！", "success");
                    $('.confirm').click(function () {   //额外绑定一个事件，当确定执行之后返回成功的页面的确定按钮，点击之后刷新当前页面或者跳转其他页面
                        location.reload();
                    });
                } else {
                    swal("OMG", "回复失败！", "error");
                }
            }).error(function (data) {
                swal("OMG", "回复操作失败了!", "error");
            });
        });
    });



    /**
     * 删除
     */
    $('.del').click(function () {
        var id = $(this).siblings("#id").val();
        swal({
            title: "您确定要删除吗？",
            text: "您确定要删除当前工单数据吗？",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            confirmButtonText: "是的，我要删除",
            confirmButtonColor: "#ec6c62"

        }, function () {
            var url = "index.php?m=Repair&c=Index&a=delete";
            $.post(url, {id: id}, function (data) {

            }, 'json').done(function (data) {
                if (data.flag == "1") {
                    swal("操作成功!", "已成功删除当前工单数据！", "success");
                    $('.confirm').click(function () {   //额外绑定一个事件，当确定执行之后返回成功的页面的确定按钮，点击之后刷新当前页面或者跳转其他页面
                        location.reload();
                    });
                } else {
                    swal("OMG", "删除失败！", "error");
                }
            }).error(function (data) {
                swal("OMG", "删除操作失败了!", "error");
            });
        });
    })
    
    /**
     * 会员活动管理--删除
     */
    $('.MemberDel').click(function () {
        var id = $(this).siblings("#id").val();
        swal({
            title: "您确定要删除吗？",
            text: "您确定要删除当前活动数据吗？",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            confirmButtonText: "是的，我要删除",
            confirmButtonColor: "#ec6c62"

        }, function () {
            var url = "index.php?m=MemberActivity&c=Index&a=delete";
            $.post(url, {id: id}, function (data) {

            }, 'json').done(function (data) {
                if (data.flag == "1") {
                    swal("操作成功!", "已成功删除当前活动数据！", "success");
                    $('.confirm').click(function () {   //额外绑定一个事件，当确定执行之后返回成功的页面的确定按钮，点击之后刷新当前页面或者跳转其他页面
                        location.reload();
                    });
                } else {
                    swal("OMG", "删除失败！", "error");
                }
            }).error(function (data) {
                swal("OMG", "删除操作失败了!", "error");
            });
        });
    })


})
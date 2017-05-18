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
            closeOnConfirm: true,
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
                    swal({title:"操作成功", text:"已成功审核！",type: "success", timer: 2000});
                    //$('.confirm').click(function () {   //额外绑定一个事件，当确定执行之后返回成功的页面的确定按钮，点击之后刷新当前页面或者跳转其他页面
                    //
                    //});
                    location.reload();
                } else {
                    swal({title:"OMG", text:"审核失败！",type: "error", timer: 2000});
                }
            }).error(function (data) {
                swal({title:"OMG", text:"审核操作失败！",type: "error", timer: 2000});
            });
        });
    });



    /**
     * 删除
     */
    $('.del_bt').click(function () {
        var id = $(this).siblings("#id").val();
        swal({
            title: "您确定要删除吗？",
            text: "您确定要删除当前工单数据吗？",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: true,
            showLoaderOnConfirm: false,
            confirmButtonText: "是的，我要删除",
            confirmButtonColor: "#ec6c62"

        }, function () {
            var url = "index.php?m=Repair&c=Index&a=delete";
            $.post(url, {id: id}, function (data) {

            }, 'json').done(function (data) {
                if (data.flag == "1") {
                    swal({title:"操作成功", text:"已删除当前工单！",type: "success", timer: 2000});
                    //$('.confirm').click(function () {   //额外绑定一个事件，当确定执行之后返回成功的页面的确定按钮，点击之后刷新当前页面或者跳转其他页面
                    //
                    //});
                    location.reload();
                } else {
                    swal({title:"OMG", text:"删除失败！",type: "error", timer: 2000});
                }
            }).error(function (data) {
                swal({title:"OMG", text:"删除操作失败！",type: "error", timer: 2000});
            });
        });
    })
    
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
                    swal({title:"操作成功", text:"已删除当前工单！",type: "success", timer: 2000});
                    //$('.confirm').click(function () {   //额外绑定一个事件，当确定执行之后返回成功的页面的确定按钮，点击之后刷新当前页面或者跳转其他页面
                    //
                    //});
                    location.reload();
                } else {
                    swal({title:"OMG", text:"删除失败！",type: "error", timer: 2000});
                }
            }).error(function (data) {
                swal({title:"OMG", text:"删除操作失败！",type: "error", timer: 2000});
            });
        });
    })


});
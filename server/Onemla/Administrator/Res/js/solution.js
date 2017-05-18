/**
 * Created by Eminem on 2017/3/18.
 */
$(function(){
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
            var url = "index.php?m=CaAndSo&c=Solution&a=delete";
            $.post(url, {id: id}, function (data) {

            }, 'json').done(function (data) {
                if (data.flag == "1") {
                    swal({title:"操作成功", text:"已删除当前方案！",type: "success", timer: 700});
                    //$('.confirm').click(function () {   //额外绑定一个事件，当确定执行之后返回成功的页面的确定按钮，点击之后刷新当前页面或者跳转其他页面
                    //
                    //});
                    location.reload();
                } else {
                    swal({title:"OMG", text:"删除失败！",type: "error", timer: 700});
                }
            }).error(function (data) {
                swal({title:"OMG", text:"删除操作失败！",type: "error", timer: 700});
            });
        });
    })
})

/**
 * Created by Administrator on 2017/5/2 0002.
 */
$(function(){
    $('.del_bt').click(function(){
        var id = $(this).siblings("#id").val();
        swal({
            title: "您确定要删除吗？",
            text: "您确定要删除当前banner吗？",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: true,
            showLoaderOnConfirm: false,
            confirmButtonText: "是的，我要删除",
            confirmButtonColor: "#ec6c62"

        }, function () {
            var url = "index.php?m=Live_room&c=Banner&a=delete";
            $.post(url, {id: id}, function (data) {
                if (data.flag == "1") {
                    swal({title:"操作成功", text:"已删除当前性质数据！",type: "success", timer: 700});
                    location.reload();
                } else {
                    swal({title:"OMG", text:"删除失败！",type: "error", timer: 700});
                }
            }).error(function(){
                swal({title:"OMG", text:"删除操作失败！",type: "error", timer: 700});
            });
        });
    })
});
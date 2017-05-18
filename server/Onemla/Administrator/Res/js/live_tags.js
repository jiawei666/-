/**
 * Created by Administrator on 2017/4/11 0011.
 */
$(function(){
    $('.del_bt').click(function(){
        var id = $(this).siblings("#id").val();
        swal({
            title: "您确定要删除吗？",
            text: "您确定要删除当前频道吗？",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: true,
            showLoaderOnConfirm: false,
            confirmButtonText: "是的，我要删除",
            confirmButtonColor: "#ec6c62"

        }, function () {
            var url = "index.php?m=MemberLive_bsn&c=Tags&a=delete";
            $.post(url, {id: id}, function (data) {
            }, 'json').done(function (data) {
                if (data.flag == "1") {
                    swal({title:"操作成功", text:"已删除当前频道！",type: "success", timer: 700});
                    location.reload();
                } else {
                    swal({title:"OMG", text:"删除失败！",type: "error", timer: 700});
                }
            }).error(function (data) {
                swal({title:"OMG", text:"删除操作失败了！",type: "error", timer: 700});
            });
        });
    });

    /*话题不能重复JS*/
    //$('li[name="tags"]').find('select').change(function(){
    //    var url = "index.php?m=MemberLive_bsn&c=Liveroom&a=selectTags";
    //    var option = $('li[name="tags"]').find('select option:selected');
    //    var select = $('li[name="tags"]').find('select');
    //    $.post(url, {tags1: option.eq(0).text(),tags2:option.eq(1).text(),tags3:option.eq(2).text()}, function (data) {
    //        select.eq(0).html(data.a);
    //        select.eq(1).html(data.b);
    //        select.eq(2).html(data.c);
    //    }, 'json')
    //})
});


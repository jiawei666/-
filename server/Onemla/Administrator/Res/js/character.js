/**
 * Created by Administrator on 2017/4/11 0011.
 */
$(function(){
   $('.del_bt').click(function(){
       var id = $(this).siblings("#id").val();
       swal({
           title: "您确定要删除吗？",
           text: "当前性质下的直播间也会被删除,你确定要删除吗?",
           type: "warning",
           showCancelButton: true,
           closeOnConfirm: true,
           showLoaderOnConfirm: false,
           confirmButtonText: "是的，我要删除",
           confirmButtonColor: "#ec6c62"

       }, function () {
           var url = "index.php?m=Live_room&c=Character&a=delete";
           $.post(url, {id: id}, function (data) {
           }, 'json').done(function (data) {
               if (data.flag == "1") {
                   swal({title:"操作成功", text:"已删除当前性质数据！",type: "success", timer: 700});
                   location.reload();
               } else if(data.flag=='2'){
                   swal({title:"OMG", text:"删除失败！",type: "error", timer: 700});
               }else if(data.flag=='3'){
                   swal({title:"OMG", text:"删除失败！当前性质被绑定",type: "error", timer: 3000});
               }
           }).error(function (data) {
               swal({title:"OMG", text:"删除操作失败了！",type: "error", timer: 700});
           });
       });
   })
});
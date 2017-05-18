/**
 * Created by Eminem on 2017/4/19.
 */
$(function() {
    $('#follow,span[name="follow"]').click(function () {
        var bsn_id = $(this).find('input').val();
        var url = "index.php?m=WechatIndex&c=LiveBsn&a=follow";
        $.post(url, {bsn_id: bsn_id,type:1}, function (data) {
        }, 'json').done(function (data) {
            if (data.flag == "1") {
                location.reload();
                alert('关注成功');
            } else {
                alert(data.msg);
            }
        }).error(function (data) {
            alert('连接服务器失败')
        });
    });
    $('span[name="unfollow"]').click(function () {
        var bsn_id = $(this).find('input').val();
        var url = "index.php?m=WechatIndex&c=LiveBsn&a=follow";
        $.post(url, {bsn_id: bsn_id,type:2}, function (data) {
        }, 'json').done(function (data) {
            if (data.flag == "1") {
                location.reload();
                alert('取消关注成功');
            } else {
                alert(data.msg);
            }
        }).error(function (data) {
            alert('连接服务器失败')
        });
    })
});
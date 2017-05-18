function isphone(phone){
    var pattern = /^1[34578]\d{9}$/;
    return pattern.test(phone);
}

function checkphone(o){
    var phone = $('input[name="phone"]').val();
    if(isphone(phone)){
        o.css('background-color','#f8b551');
        o.removeAttr('disabled');
    }else {
        o.css('background-color','#9e9e9a');
        o.attr('disabled','disabled');
    }
}


$(function () {
    var wait=60;
    function time(o) {
        if (wait == 0) {
            if(isphone($('input[name="phone"]').val())){
                $('#btn').css('background-color','#f8b551');
                $('#btn').removeAttr('disabled');
            }
            //o.removeAttribute("disabled");
            o.value="免费获取验证码";
            wait = 60;
        } else {
            o.setAttribute("disabled", true);
            o.value=wait+"秒后可以重新发送";
            wait--;
            setTimeout(function() {time(o)}, 1000)
            $('#btn').css('background-color','#9e9e9a');
            $('#btn').css('border','1px solid #e0e0e0');
        }
    }

    document.getElementById("btn").onclick=function(){
        $.post('index.php?m=Member&c=Index&a=sendmessage',{phone:$('input[name="phone"]').val()},function(data) {
        }).done(function(data){
            var arr = JSON.parse(data);
            if(arr.res==1){
                $('input[type="submit"]').attr('disabled',false);
                // $('form').attr('action',"{:U('Member/Index/edit')}");
                alert('发送成功');
                time(document.getElementById("btn"));
            }else {
                $('input[type="submit"]').attr('disabled',true);
                alert(arr.msg);
            }
        }).error(function(data){
            alert('发送失败');
        });
    };
});

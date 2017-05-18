/**
 * Created by Administrator on 2017/4/7 0007.
 */
/**
 * Created by Administrator on 2017/3/16 0016.
 */
function isphone(phone){
    var pattern = /^1[34578]\d{9}$/;
    return pattern.test(phone);
}
function checkphone(){
    var phone = $('input[name="phone"]').val();
    if(isphone(phone)){
        $('#btn').removeAttr('disabled');
        $('#btn').css('background-color','');
    }else {
        $('#btn').css('background-color','#9e9e9a');
        o.setAttribute("disabled", true);
    }
}


$(function () {

    //var phone = $('#register-tel').val();
    //if(isphone(phone)){
    //    $('#btn').css('background-color','#f8b551');
    //    $('#btn').removeAttr('disabled');
    //}else {
    //    $('#btn').css('background-color','#9e9e9a');
    //    $('#btn').css('border','1px solid #e0e0e0');
    //    $('#btn').attr('disabled','disabled');
    //}

    var wait=60;
    function time(o) {
        if (wait == 0) {
            o.removeAttribute("disabled");
            if(isphone($('input[name="phone"]').val())){
                $('#btn').css('background-color','');
                $('#btn').removeAttr('disabled');
            }
            o.value="发送验证码";
            wait = 60;
        } else {
            //$('#btn').css('background-color','#9e9e9a');
            //$('#btn').css('border','1px solid #e0e0e0');
            //$('#btn').attr('disabled','disabled');

            o.setAttribute("disabled", true);
            o.value="等待"+wait+"秒";
            wait--;
            setTimeout(function() {
                    time(o)
                },
                1000)
        }
    }
    $('#btn').click(function(){
        $.post('index.php?m=User&c=Findpsd&a=sendmessage',{phone:$('input[name="phone"]').val()},function(data) {
        }).done(function(data){
            if(data.flag==1){
                time(document.getElementById("btn"));
                $('input[type="submit"]').attr('disabled',false);
                alert('发送成功');
                $('#btn').css('background-color','#9e9e9a');
                $('#btn').attr('disabled','disabled');
            }else {
                alert(data.msg);
            }
        }).error(function(data){
            alert('发送失败');
        });
    });

});

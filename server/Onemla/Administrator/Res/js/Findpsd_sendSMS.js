function isphone(phone){
    var pattern = /^1[34578]\d{9}$/;
    return pattern.test(phone);
}
function checkphone(){
    var phone = $('input[name="phone"]').val();
    if(isphone(phone)){
        $('#btn').css('background-color','#f8b551');
        $('#btn').removeAttr('disabled');
    }else {
        $('#btn').css('background-color','#9e9e9a');
        $('#btn').css('border','1px solid #e0e0e0');
        $('#btn').attr('disabled','disabled');
    }
}


$(function(){

    var phone = $('input[name="phone"]').val();
    if(isphone(phone)){
        $('#btn').css('background-color','#f8b551');
        $('#btn').removeAttr('disabled');
    }else {
        $('#btn').css('background-color','#9e9e9a');
        $('#btn').css('border','1px solid #e0e0e0');
        $('#btn').attr('disabled','disabled');
    }

    var wait=60;
    function time(o) {
        if (wait == 0) {
            //o.removeAttribute("disabled");
            if(isphone($('input[name="phone"]').val())){
                $('#btn').css('background-color','#f8b551');
                $('#btn').removeAttr('disabled');
            }
            o.value="免费获取验证码";
            wait = 60;
        } else {
            o.setAttribute("disabled", true);
            o.value=wait+"秒后可以重新发送";
            wait--;
            setTimeout(function() {
                    time(o)
                },
                1000)
            $('#btn').css('background-color','#9e9e9a');
            $('#btn').css('border','1px solid #e0e0e0');
        }
    }



    document.getElementById("btn").onclick=function(){
        if(!$('input[name="phone"]').val()=='')
        {//手机号码
            var phone=$('input[name="phone"]').val();
            if(!isphone(phone)){
                alert('请输入正确的手机号');
                return false;
            }else{
                $.post('index.php?m=User&c=Findpsd&a=sendmessage',{phone:$('input[name="phone"]').val()},function(data) {
                }).done(function(data){
                    var arr = JSON.parse(data);
                    if(arr.res==1){
                        time(document.getElementById("btn"));
                        $('input[type="submit"]').attr('disabled',false);
                        alert('发送成功');
                        $('#btn').css('background-color','#9e9e9a');
                        $('#btn').css('border','1px solid #e0e0e0');
                        $('#btn').attr('disabled','disabled');
                    }else {
                        $('input[type="submit"]').attr('disabled',true);
                        alert(arr.msg);
                    }
                }).error(function(data){
                    alert('发送失败');
                });
            }
        }else{
            $('input[name="phone"]').attr('required','true');
        }
    };
});

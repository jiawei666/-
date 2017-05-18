


function isemail ( email ) {
    var reg1 = /([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/;
    return reg1.test( email );
}
function checkemail(){
    var email = $('input[name="email"]').val();
    if(isemail(email)){
        $('#btn').removeAttr('disabled');
        $('#btn').css('background-color','#f8b551');
    }else {
        $('#btn').css('background-color','#9e9e9a');
        o.setAttribute("disabled", true);
    }
}

$(function(){

    var email = $('input[name="email"]').val();
    if(isemail(email)){
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
            if(isemail($('input[name="email"]').val())){
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
        if(!$('input[name="email"]').val()=='')
        {//邮箱账号
            var email=$('input[name="email"]').val();
            if(!isemail(email)){
                alert('请输入正确的邮箱');
                return false;
            }else{
                $.post('index.php?m=User&c=Findpsd&a=sendEmail',{email:$('input[name="email"]').val()},function(data) {
                }).done(function(data){
                    if(data.flag==1){
                        time(document.getElementById("btn"));
                        $('input[type="submit"]').attr('disabled',false);
                        alert(data.msg);
                        $('#btn').css('background-color','#9e9e9a');
                        $('#btn').css('border','1px solid #e0e0e0');
                        $('#btn').attr('disabled','disabled');
                    }else{
                        $('input[type="submit"]').attr('disabled',true);
                        alert(data.msg);
                    }
                }).error(function(){
                    alert('发送失败');
                })
            }
        }else{
            $('input[name="email"]').attr('required','true');
        }
    };
});

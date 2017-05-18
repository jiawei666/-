
    ////////////////////////////////////------------------------///////////////////////////////////////////////
    function registercheck()
    {
        if(!$('input[name="user_name"]').val()==''){
            if($.trim($('input[name="user_name"]').val()).length==0){
                alert('用户名不能为空');
                return false;
            }
        }else {
            $('input[name="user_name"]').attr('required','required');
        }

        if(!$('input[name="email"]').val()=='')
        {//邮箱
            function isemail ( email ) {
                var reg1 = /([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/;
                return reg1.test( email );
            }
            var email=$('input[name="email"]').val();
            if(!isemail(email)){
                alert('请输入正确的邮箱地址');
                return false;
            }
        }else{
            $('input[name="email"]').attr('required','true');
        }

        if(!$('input[name="phone"]').val()=='')
        {//手机号码
            function isPhone(phone) {
                var pattern = /^1[34578]\d{9}$/;
                return pattern.test(phone);
            }
            var phone=$('input[name="phone"]').val();
            if(!isPhone(phone)){
                alert('请输入正确的手机号');
                return false;
            }
        }else {
            $('input[name="phone"]').attr('required','true');
        }

        if(!$('input[name="wechat"]').val()=='')
        {//微信号
            $('input[name="qq"]').removeAttr('required');
            function iswechat(wechat) {
                var pattern =/^[a-zA-Z][\w-]{5,19}$/;
                return pattern.test(wechat);
            }
            function isphone(phone){
                var pattern = /^1[34578]\d{9}$/;
                return pattern.test(phone);
            }
            var wechat=$('input[name="wechat"]').val();
            if(!(iswechat(wechat) || isphone(wechat))){
                alert('请输入正确的微信号');
                return false;
            }
        }else {
            $('input[name="qq"]').attr('required','true');
        }

        if(!$('input[name="qq"]').val()=='')
        {//QQ号
            $('input[name="wechat"]').removeAttr('required');
            function isqq(qq) {
                var pattern = /^[1-9][0-9]{4,}$/;
                return pattern.test(qq);
            }
            var qq=$('input[name="qq"]').val();
            if(!isqq(qq)){
                alert('请输入正确的QQ号');
                return false;
            }
        }else {
            $('input[name="wechat"]').attr('required','true');
        }

        if(!$('input[id="identity_card_id"]').val()=='')
        {//身份证号码

            function is_id_card_id ( card_id ) {
                var card_type = $('select[name="document_type"]').val();
                if(card_type==1){
                    var reg = /^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/;
                }else if(card_type==2){
                    var reg = /^[a-zA-Z0-9]{5,21}$/;
                }else if(card_type==3){
                    var reg = /^[a-zA-Z0-9]{7,21}$/;//军人证
                }else{
                    var reg = /[a-zA-Z0-9]*/;
                }
                return reg.test( card_id );
            }
            var id_card_id=$('input[id="identity_card_id"]').val();
            if(!is_id_card_id(id_card_id)){
                alert('请输入正确的证件号码');
                return false;
            }
        }else{
            //显示 load.png

            $('input[id="identity_card_id"]').attr('required','true');
        }

        if(!$('input[id="business_card_id"]').val()=='')
        {//营业执照
            function iscard_id ( card_id ) {
                var reg =/^[a-zA-Z0-9]{5,21}$/;
                return reg.test( card_id );
            }
            var card_id=$('input[id="business_card_id"]').val();
            if(!iscard_id(card_id)){
                alert('请输入正确的营业执照号');
                return false;
            }
        }else{
            //显示 load.png
            //$('.submit_load').show();
            $('input[id="business_card_id"]').attr('required','true');
        }
    }


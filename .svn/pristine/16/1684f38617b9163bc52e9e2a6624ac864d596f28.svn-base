$(function(){
	var $contactForm = $(".login-form");
		$contactForm.on('submit', function (event) {
			var User = $.trim($(this).find('input[name=User]').val());
			var Password = $.trim($(this).find('input[name=Password]').val());
			var confirmPassword = $.trim($(this).find('input[name=confirmPassword]').val());
			var Email = $.trim($(this).find('input[name=Email]').val());
			var Wx = $.trim($(this).find('input[name=Wx]').val());
			var Qq = $.trim($(this).find('input[name=QQ]').val());
			var Tel = $.trim($(this).find('input[name=tel]').val());
			var testCode = $.trim($(this).find('input[name=testCode]').val());
			var isCheckbox = $.trim($(this).find('input[name=isCheckbox]').val());
			var ispass=/^[0-9A-Za-z]{6,18}$/;
			var isWx=/^[a-zA-Z\d_]{5,}$/;  
			var isEmail=/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/;
			var isQq = /^[1-9][0-9]{4,10}$/; 
			var isTel=/^(13|14|15|17|18)\d{9}$/;
	if(User.length == 0){
		alert("用户名不能为空");
		return false;
	}
	if(!ispass.test(Password)){
		alert("请输入密码，密码由6位到18位字母或数字组成");
		return false;
	}
	if(!ispass.test(confirmPassword)){
		alert("请确认密码，密码由6位到18位字母或数字组成");
		return false;
	}
	if(Password !== confirmPassword){
		alert("两次输入的密码不一致，请重新输入");
		return false;
	}
	if(!isEmail.test(Email)){
		alert("请输入有效的邮箱");
		return false;
	}
	if(!isWx.test(Wx)){
		alert("请输入有效的微信号");
		return false;
	}
	if(!isQq.test(Qq)){
		alert("请输入有效的QQ");
		return false;
	}
	if(!isTel.test(Tel)){
		alert("请输入有效的电话");
		return false;
	}
	if(testCode.length == 0){
		alert("验证码不能为空");
		return false;
	}
	if (!$(".isCheckbox").is(":checked")){
		alert("请仔细阅读...并勾选");
		return false;
	}	
	return true;
})
})
	
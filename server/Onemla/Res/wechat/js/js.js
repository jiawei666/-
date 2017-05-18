var html=document.getElementsByTagName('html')[0];
function getFontSize(){
	var width=document.documentElement.clientWidth||document.body.clientWidth;
	var fontSize=(100/750)*width;
	return fontSize;
}
html.style.fontSize=getFontSize()+"px"; 
window.onresize=function(){
	setTimeout(function(){
		html.style.fontSize=getFontSize()+'px';
	},100)
};
$(function(){
	$('div[class="city-search-box"]').bind('DOMNodeInserted', function() {
		window.location.href = "index.php?m=WechatIndex&c=Index&a=region&city="+$(this).text();
	})
});

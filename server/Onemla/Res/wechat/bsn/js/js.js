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
}

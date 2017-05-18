var imgsrc= $("footer a.active .footer-nav-icon img")[0].src;
console.log(imgsrc);
var n=imgsrc.replace(/.png/, "-active.png")
$("footer a.active img").attr('src',n); 
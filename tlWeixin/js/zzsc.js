$().ready(function() {
$('.send').on('click',(function() {
	$('html, body').animate({scrollTop:0}, 'fast');
setTimeout(function() {
$('#plate').removeClass('front');
$('#container').removeClass('beginning');
$('.curvable').addClass('curved');
setTimeout(function() {
$('#container').addClass('hover');
setTimeout(function() {
$('#container').addClass('fly_away_first');
setTimeout(function() {
$('#container').addClass('fly_away');
setTimeout(function(){
	var eventId = $('#hiddenInputEventId').val();
	$.ajax({
		type: "get",
		url: "PVAdd.php?eventId="+eventId+""
	});
	window.location.href="http://mp.weixin.qq.com/s?__biz=MjM5ODgwMzcyNA==&mid=200065964&idx=1&sn=d7a856ab41773c4c81894cbc7b1c3097#rd";
},1000);
}, 600);
}, 1600);

}, 1600);
}, 200);
}));
});
function aa(){
	location='index.html';
}

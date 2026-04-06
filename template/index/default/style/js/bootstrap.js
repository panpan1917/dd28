$(window).load(function() {
$("#status").fadeOut();
$("#preloader").delay(300).fadeOut("slow");
})
document.writeln("<div id=\"status\" class=\"btn-radius\">");
document.writeln("<p class=\"center-text\">正在加载...</p>");
document.writeln("</div>");
document.writeln("<div id=\"preloader\">");
document.writeln("</div>");
document.writeln("");

$(function() {
/*列表展开折叠*/
	var LanMu = $(".lanmu-list");
	var lanMuSun = LanMu.children("dd");
	/*if ((lanMuSun.size()) > 10) {
		LanMu.children("dd:gt(5)").hide();
		$(".listmore").show();
	}*/
	$(".listmore").bind("click", function() {
		if (!$(".listmore").hasClass("ListMoreOn")) {
			$(".listmore").addClass("ListMoreOn");
			//LanMu.children("dd:gt(5)").slideDown();
			$(".lanmu-list dd").removeClass('dn');
			$(".listmore").hide();
		} else {
			$(".listmore").removeClass("ListMoreOn");
			//LanMu.children("dd:gt(5)").slideUp();
			LanMu.removeClass('dn');
			$(".listmore").html("查看更多 ↓");
		}
	});
});



function test(Names){
	var Name
	for (var i=1;i<4;i++){	//  更改数字4可以改变选择的内容数量，在下拉总数值的基础上+1.比如：下拉菜单有5个值，则4变成6
		var tempname="mune_x"+i                                                                            
		var NewsHot="x"+i	//  "X"是ID名称，比如：ID命名为"case1"，这里的"X"即为"case"
		if (Names==tempname){
			Nnews=document.getElementById(NewsHot)
			Nnews.style.display='';
		}else{
			Nnews=document.getElementById(NewsHot)
			Nnews.style.display='none';   
		}
	}
}
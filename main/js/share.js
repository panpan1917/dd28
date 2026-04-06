$(function(){
	$('#share').css('top',getScroll().top + (getInner().height - parseInt(getStyle($('#share').first(),'height')))/2 + 'px');
	
	addEvent(window,'scroll',function(){
		//$('#share').css('top',getScroll().top + (getInner().height - parseInt(getStyle($('#share').first(),'height')))/2 + 'px');   //版本较低的浏览器会有抖动现象.
		$('#share').animate({		// 解决抖动现象.
			attr : 'y',
			target : getScroll().top + (getInner().height - parseInt(getStyle($('#share').first(),'height')))/2,
			t:30,
			step :10
		});
	})
	
	//百度分享收缩效果.
	$('#share').hover(function(){
		$(this).animate({
			ttr : 'x',
			target : 0
		})
	},function(){
		$(this).animate({
			attr : 'x',
			target : -211
		})
	});
})
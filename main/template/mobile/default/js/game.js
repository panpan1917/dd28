$(function(){
	//急速类
	$('#js').on('show.bs.collapse',function(){
		$('.js_bg').css('background-image','url(template/mobile/default/img/game_list/down1.png)');
	})
	$('#js').on('hide.bs.collapse',function(){
		$('.js_bg').css('background-image','url(template/mobile/default/img/game_list/up1.png)');
	})
	//北京类
	$('#bj').on('show.bs.collapse',function(){
		$('.bj_bg').css('background-image','url(template/mobile/default/img/game_list/down3.png)');
	})
	$('#bj').on('hide.bs.collapse',function(){
		$('.bj_bg').css('background-image','url(template/mobile/default/img/game_list/up3.png)');
	})

	//pk类
	$('#pk').on('show.bs.collapse',function(){
		$('.pk_bg').css('background-image','url(template/mobile/default/img/game_list/down2.png)');
	})
	$('#pk').on('hide.bs.collapse',function(){
		$('.pk_bg').css('background-image','url(template/mobile/default/img/game_list/up2.png)');
	})

	//加拿大类
	$('#jnd').on('show.bs.collapse',function(){
		$('.jnd_bg').css('background-image','url(template/mobile/default/img/game_list/down4.png)');
	})
	$('#jnd').on('hide.bs.collapse',function(){
		$('.jnd_bg').css('background-image','url(template/mobile/default/img/game_list/up4.png)');
	})

	//蛋蛋类
	$('#dd').on('show.bs.collapse',function(){
		$('.dd_bg').css('background-image','url(template/mobile/default/img/game_list/down5.png)');
	})
	$('#dd').on('hide.bs.collapse',function(){
		$('.dd_bg').css('background-image','url(template/mobile/default/img/game_list/up5.png)');
	})

	//其它类
	$('#qt').on('show.bs.collapse',function(){
		$('.qt_bg').css('background-image','url(template/mobile/default/img/game_list/down6.png)');
	})
	$('#qt').on('hide.bs.collapse',function(){
		$('.qt_bg').css('background-image','url(template/mobile/default/img/game_list/up6.png)');
	})	
})
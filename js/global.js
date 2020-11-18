// Избегаем ошибок `консоли` в тех браузерах которые лажают с консолью.
if(!(window.console && console.log)){
	(function(){
		var noop = function() {};
		var methods = ['assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error', 'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log', 'markTimeline', 'profile', 'profileEnd', 'markTimeline', 'table', 'time', 'timeEnd', 'timeStamp', 'trace', 'warn'];
		var length = methods.length;
		var console = window.console = {};
		while(length--){
			console[methods[length]] = noop;
		}
	}());
}

if(window.jQuery){
	// Small extension that caches JQuery selectors
	jQuery.extend({
		cacheCollection: {},
		c: function(selector){
			if(typeof jQuery.cacheCollection[selector] != 'undefined' && jQuery.cacheCollection[selector].length > 0){
				return jQuery.cacheCollection[selector];
			}else if(jQuery(selector).length > 0){
				jQuery.cacheCollection[selector] = jQuery(selector);
				return jQuery.cacheCollection[selector];
			}else{
				return false;
			}
		}
	});
}

$(function(){
	// global VARS
	var D = $(document),
		W = $(window);
	// Main script that runs after full loading of the DOM tree
	// Run the jsCarousel ext
	if($.c(".jsCarousel")) $.c(".jsCarousel").jcarousel({});
	// Run the timeMachine ext
	if($.c("div.historyWrapper"))
		$.c("div.historyWrapper").timeMachine({
			ajaxUrl:'http://localhost/picup/ajax.php',
			selectedTime: {
				historyForward: '1351513066',
				historyBack: '1351513066'
			}
		});
		
	if($.c(".upgallery")) $.c(".upgallery").upgallery({margin:[140, 0, 200, 0]});
	if($.c(".commentsgallery")) $.c(".commentsgallery").upgallery({
		margin:[140, 0, 200, 0],
		helpers:{thumbs:false}
	});

	if($.c("div.searchForm")){
		var searchBlock = $.c("div.searchForm");
		var textBlock = $.c("div.searchForm input[type=text]");
		var searchButt = $.c("div.searchForm input[type=image]");
		$.c("div.searchForm input[type=text]").on('focus', function(){
			textBlock.data('width', textBlock.width());
			var pos = $.c("div.iconMenu").offset();
			var spos = $.c("div.searchForm").position();
			var animLength = (parseInt(spos.left)-parseInt(pos.left+$("div.iconMenu").outerWidth()))-10 > 200 ? 200:(parseInt(spos.left)-parseInt(pos.left+$("div.iconMenu").outerWidth()))-10;
			animLength = animLength < 0 ? 0:animLength;
			searchBlock.stop().animate({opacity:1}, 100);
			textBlock.stop().animate({'width':'+='+animLength}, 100);
			searchButt.stop().animate({opacity:1}, 100);
		}).on('blur', function(){
			searchBlock.stop().animate({opacity:0.5}, 100);
			textBlock.stop().animate({'width':textBlock.data('width')}, 100);
			searchButt.stop().animate({opacity:0}, 100);
		});
	}

	if($.c("aside.rightSide > .activitiesList")){
		var height = W.height();

		$.c("aside.rightSide > .activitiesList").css({'height':(height-120),'overflow':'auto'});
		$.c("aside.rightSide > .activitiesList").mCustomScrollbar({
			advanced:{
				updateOnContentResize:true
			},
			callbacks:{
				onTotalScroll:function(){
					$.c("aside.rightSide > .activitiesList").find('div.hblock').append('<div class="newsItem major clearfix"><a href="#" class="userAvatar"><img src="images/mediumava.png" alt="" title="Александра Оголева"/></a><a href="#" class="userName">Александра Оголева</a><span class="action">Оставила комментарий</span><span class="newsDevider"></span>Я принимаю участие в бесплатном вебинаре «Секреты саундпродюсирования или как создать клевый музыкальный продукт».<span class="newsDevider"></span><span class="newsDate">09.08.2012</span><a href="#" class="newsAction">Апнуть</a></div>');
				}
			}
		});
	}
	
	//leftSide height
	if($.c('aside.leftSide')){
		var sideHeight = $.c('aside.leftSide').outerHeight(),
			height = D.height();
		if(height > sideHeight) $.c('aside.leftSide').css('height', (height-60));
	}

	if($.c(".galleryHover")){
		$.c(".galleryHover").hover(function(){
			if($(this).find(".itemHover").length > 0){
				$(this).find(".itemHover").stop().css({'display':'block', 'opacity':0}).animate({opacity:1}, 150, function(){$(this).css({'opacity':1, 'display':'block'});});
			}
		},
		function(){
			if($(this).find(".itemHover").length > 0){
				$(this).find(".itemHover").stop().css({'display':'block', 'opacity':1}).animate({opacity:0}, 150, function(){$(this).css({'opacity':0, 'display':'none'});});
			}
		});
	}

	$(window).on('resize.picup', function(){
		if($.c("div.historyWrapper")) $.c("div.historyWrapper").timeMachine('__resize');
		
		//activitiesList
		var height = W.height();
		if($.c("aside.rightSide > .activitiesList"))
			$.c("aside.rightSide > .activitiesList").css({'height':(height-120),'overflow':'auto'});
		//leftside
		if($.c('aside.leftSide')){
			$.c('aside.leftSide').css('height', 'auto');
			DHeight = D.height();
			var sideHeight = $.c('aside.leftSide').outerHeight();
			if(DHeight > sideHeight){
				$.c('aside.leftSide').css('height', (DHeight-60));
			}
		}
	});
	/*
	if($.wupdater){
		$.wupdater('qwerty', {href:'http://picup.loc/ajax.php', vars:{foo:'bar'}, updateInterval: 3000});
	}*/
});
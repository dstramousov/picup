(function (window, document, $, undefined){
	"use strict";

	var W = $(window),
		D = $(document),
		AL = $.alert = function(){
			AL.open.apply(this, arguments);
		},
		CAL = $.closeAlert = function(){
			AL.close.apply(this, arguments);
		},
		isString = function(str){
			return str && $.type(str) === "string";
		};
		
	$.extend(AL,{
		defaults:{
			type:'corner',
			autohide:true,
			parent : $('body'),
			showtime: 1000,
			hideonclick: true,
			cornertpl: {
				wrap: '<div class="upalert-contentWrapper" tabindex="-1"><div class="upalert-outer"><div class="upalert-inner"></div></div></div>',
				body: '',
				closeBtn:'',
				title: ''
			},
			modaltpl: {
				wrap: '<div class="uppopup-contentWrapper"><div class="uppopup-outer"><div class="uppopup-inner"></div></div></div>',
				body: '<div class="uppopup-body"></div>',
				closeBtn:'<a href="#close" class="uppopup-closeBtn"><img src="images/spacer.gif" class="icon popup-close" alt="" title="" /></a>',
				title: '<div class="uppopup-title"></div>',
				overlay: '<div class="uppopup-overlay uppopup-overlay-fixed"></div>'
			},
			infotpl: {
				wrap: '<div class="upalert-contentWrapper" tabindex="-1"><div class="upalert-outer"><div class="upalert-inner"></div></div></div>',
				body: '',
				closeBtn:'',
				title: ''
			},
			wrapCSS:'',
			overlay: true
		},
		opts:{},
		activeAls:{
			corner:[],
			modal:[],
			info:[]
		},
		currentData:null,
		dataType:null,
		current:null,
		coming: null,
		isActive:false,
		isOpen:false,
		wrap:null,
		skin:null,
		outer:null,
		inner:null,
		
		open:function(el, opts){
			AL.currentData = el;
			var obj = AL.currentData;
			// Extend the defaults
			AL.opts = $.extend(true, {}, AL.defaults, opts);
			AL._open(obj);
		},
		_open:function(curobj){
			var data = AL.currentData,
				coming = {},
				type,
				margin,
				padding,
				obj = {};

			if(!isString(curobj)){
				obj = {
					objtype:'element',
					element:curobj
				}
			}else{
				obj = {
					objtype:'text',
					message:curobj
				}
			}

			coming = $.extend(true, {}, AL.opts, obj);

			margin  = coming.margin;
			padding = coming.padding;

			if($.type(margin) === 'number'){
				coming.margin = [margin, margin, margin, margin];
			}

			if($.type(padding) === 'number'){
				coming.padding = [padding, padding, padding, padding];
			}

			AL.coming = coming;

			switch(coming.type){
				case 'corner': AL.cornerShow(); break;
				case 'modal': AL.modalShow(); break;
				case 'info': AL.infoShow(); break;
			}
		},
		close: function(el, opts){
			alert(opts);
		},
		cornerShow: function(){
			var current = AL.coming;
			current.wrap = $(current.cornertpl.wrap).addClass('upalert-type-corner '+current.wrapCSS).appendTo(current.parent);
			$.extend(current, {
				skin  : $('.upalert-outer', current.wrap),
				outer : $('.upalert-outer', current.wrap),
				inner : $('.upalert-inner', current.wrap)
			});
			current.inner.append(current.message);
			current.height = current.wrap.outerHeight();
			current.width = current.wrap.outerWidth();

			current.wrap.css('bottom', -1*current.height);
			var bottom = 0;
			for(var x in AL.activeAls.corner) bottom += AL.activeAls.corner[x].height;

			current.wrap.animate({opacity:1, 'bottom':bottom}, 300);
			var newIndex = AL.activeAls.corner.length;
			AL.activeAls.corner[newIndex] = current;
			AL.bindEvents(newIndex, 'corner');
		},
		modalShow: function(){
			var current = AL.coming;
			current.wrap = $(current.modaltpl.wrap).addClass('upalert-type-modal '+current.wrapCSS).appendTo(current.parent);
			current.wrap.css('opacity', 0);
			$.extend(current, {
				outer: $('.uppopup-outer', current.wrap),
				inner: $('.uppopup-inner', current.wrap)
			});

			$(current.modaltpl.title).appendTo(current.inner);
			$(current.modaltpl.body).appendTo(current.inner);
			$.extend(current, {
				title: $('.uppopup-title', current.inner),
				body: $('.uppopup-body', current.inner)
			});

			var height = current.wrap.outerHeight();
			var width = current.wrap.outerWidth();

			current.wrap.css({'top':W.height()/2-height/2+D.scrollTop(),'left':W.width()/2-width/2});
			if(current.overlay){
				current.overlayLayout = $(current.modaltpl.overlay).prependTo(current.parent);
				current.overlayLayout.append(current.wrap);
			}

			current.wrap.stop(true).animate({opacity:1}, 300);
		},
		infoShow: function(){
			var current = AL.coming;
			current.wrap = $(current.infotpl.wrap).addClass('upalert-type-info '+current.wrapCSS).appendTo(current.parent);
			$.extend(current, {
				skin  : $('.upalert-outer', current.wrap),
				outer : $('.upalert-outer', current.wrap),
				inner : $('.upalert-inner', current.wrap)
			});
			current.inner.append(current.message);
			current.height = current.wrap.outerHeight();
			current.width = current.wrap.outerWidth();

			current.wrap.css({'top':-1*current.height, 'marginRight':-1*(current.width/2)});
			var top = 0;
			for(var x in AL.activeAls.info) top += AL.activeAls.info[x].height;

			current.wrap.animate({opacity:1, 'top':top}, 300);
			var newIndex = AL.activeAls.info.length;
			AL.activeAls.info[newIndex] = current;
			AL.bindEvents(newIndex, 'info');
		},
		remove_corner:function(index, type){
			if(AL.activeAls[type][index].timer != null) clearTimeout(AL.activeAls[type][index].timer);
			var height = AL.activeAls[type][index].height;
			AL.activeAls[type][index].wrap.stop(true).animate({opacity:0}, 400, function(){
				$(this).remove();
				AL.activeAls[type][index].wrap.off('click');
				delete AL.activeAls[type][index];
				for(var ind in AL.activeAls[type]){
					if(ind > index)
						AL.activeAls[type][ind].wrap.css('bottom', '-='+height);
				}
			});
		},
		remove_info:function(index, type){
			var height = AL.activeAls[type][index].height;
			AL.activeAls[type][index].wrap.stop(true).animate({opacity:0}, 400, function(){
				$(this).remove();
				AL.activeAls[type][index].wrap.off('click');
				delete AL.activeAls[type][index];
				for(var ind in AL.activeAls[type]){
					if(ind > index)
						AL.activeAls[type][ind].wrap.css('top', '-='+height);
				}
			});
		},
		bindEvents: function(index, type){
			AL.activeAls[type][index].timer = null;
			if(AL.activeAls[type][index].autohide)
				AL.activeAls[type][index].timer = setTimeout(function(){AL['remove_'+type](index, type);}, AL.activeAls[type][index].showtime);
			if(AL.activeAls[type][index].hideonclick){
				AL.activeAls[type][index].wrap.css('cursor', 'pointer');
				AL.activeAls[type][index].wrap.on('click', function(e){
					if(!$(e.target).is('a') && !$(e.target).parent().is('a'))
						AL['remove_'+type](index, type);
				});
			}
		}
	});
	
	$.fn.alert = function(options){
		var index,
			that = $(this),
			selector = this.selector || '',
			run = function(e){
				var what = $(this).blur(), idx = index, relType, relVal;
				if(!(e.ctrlKey || e.altKey || e.shiftKey || e.metaKey) && !what.is('.alert-wrap')){
					// Stop an event from bubbling if everything is fine
					if(AL.open(what, options) !== false){
						e.preventDefault();
					}
				}
			};
		
		options = options || {};
		index = options.index || 0;
		if(!AL.isString(options)){
			if(!selector || options.live === false) {
				that.off('click.alrt-start').on('click.alrt-start', run);
			}else{
				D.undelegate(selector, 'click.alrt-start').delegate(selector+":not('.alert-item')", 'click.alrt-start', run);
			}
		}else{
			run();	
		}
		return this;
	};
	
	D.ready(function() {
		if($.scrollbarWidth === undefined){
			$.scrollbarWidth = function() {
				var parent = $('<div style="width:50px;height:50px;overflow:auto"><div/></div>').appendTo('body'),
					child  = parent.children(),
					width  = child.innerWidth() - child.height(99).innerWidth();
				parent.remove();
				return width;
			};
		}

		if($.support.fixedPosition === undefined){
			$.support.fixedPosition = (function(){
				var elem  = $('<div style="position:fixed;top:20px;"></div>').appendTo('body'),
					fixed = ( elem[0].offsetTop === 20 || elem[0].offsetTop === 15 );
				elem.remove();
				return fixed;
			}());
		}

		$.extend(AL.defaults, {
			scrollbarWidth : $.scrollbarWidth(),
			fixed  : $.support.fixedPosition,
			parent : $('body')
		});
	});
}(window, document, jQuery));
(function (window, document, $, undefined){
	"use strict";

	var W = $(window),
		D = $(document),
		UP = $.upgallery = function(){
			UP.open.apply(this, arguments);
		},
		isQuery	= function(obj) {
			return obj && obj.hasOwnProperty && obj instanceof $;
		},
		isString = function(str){
			return str && $.type(str) === "string";
		},
		getScalar = function(value, dim){
			var value_ = parseInt(value, 10);

			if(dim && isPercentage(value)){
				value_ = UP.getViewport()[dim] / 100 * value_;
			}
			return Math.ceil(value_);
		},
		getValue = function(value, dim){
			return getScalar(value, dim)+'px';
		},
		isPercentage = function(str) {
			return isString(str) && str.indexOf('%') > 0;
		};

	$.extend(UP,{
		defaults:{
			padding : 0,
			margin  : 100,

			width     : 800,
			height    : 600,
			minWidth  : 100,
			minHeight : 100,
			maxWidth  : 9999,
			maxHeight : 9999,
			
			autoResize  : true,
			autoCenter  : true,
			autoSize   :true,
			fitToView  :true,
			aspectRatio : false,
			topRatio    : 0.5,
			leftRatio   : 0.5,
			arrows     :true,
			closeBtn   :true,
			closeClick :false,
			nextClick  :false,
			preload    :3,
			tpl: {
				wrap     : '<div class="upgallery-contentWrapper" tabindex="-1"><div class="upgallery-outer"><div class="upgallery-inner"></div></div></div>',
				image    : '<img class="upgallery-image" src="{href}" title="" alt="" />',
				error    : '<p class="upgallery-error">The requested content cannot be loaded.<br/>Please try again later.</p>',
				closeBtn : '<a href="#close" title="Close" class="upgallery-closeBtn"><img src="images/spacer.gif" title="" class="icon galleryclose" /></a>',
				next     : '<a href="#next" class="upgallery-next"><img src="images/spacer.gif" title="" class="icon gallerynext" /></a>',
				prev     : '<a href="#previous" class="upgallery-previous"><img src="images/spacer.gif" title="" class="icon galleryprev" /></a>'
			},
			// Properties for each animation type
			// Opening fancyBox
			openEffect  : 'fade', // 'elastic', 'fade' or 'none'
			openSpeed   : 250,
			openEasing  : 'swing',
			openOpacity : true,
			openMethod  : 'zoomIn',

			// Closing fancyBox
			closeEffect  : 'fade', // 'elastic', 'fade' or 'none'
			closeSpeed   : 250,
			closeEasing  : 'swing',
			closeOpacity : true,
			closeMethod  : 'zoomOut',

			// Changing next gallery item
			nextEffect : 'elastic', // 'elastic', 'fade' or 'none'
			nextSpeed  : 250,
			nextEasing : 'swing',
			nextMethod : 'changeIn',

			// Changing previous gallery item
			prevEffect : 'elastic', // 'elastic', 'fade' or 'none'
			prevSpeed  : 250,
			prevEasing : 'swing',
			prevMethod : 'changeOut',
			// Enabled helpers
			helpers : {
				overlay : {
					closeClick : true,
					speedOut   : 200,
					showEarly  : true,
					css        : {}
				},
				title:{},
				thumbs:{},
				comments:{
					url: 'http://localhost/picup/ajax.php',
					params: {'getcomments':'Y'}
				}
			},
			direction : {
				next : 'left',
				prev : 'right'
			}
		},
		group    : {},
		opts     : {},
		previous : null,
		coming   : null,
		current  : null,
		isActive : false,
		isOpen   : false,
		wrap  : null,
		skin  : null,
		outer : null,
		inner : null,
		helpers: {},

		open: function(group, opts){
			if(!group) return;
			if(!$.isPlainObject(opts)) opts = {};
			if(false === UP.close(true)) return;
			if(!$.isArray(group)) group = isQuery(group) ? $(group).get() : [group];
			$.each(group, function(i, element){
				var obj = {},
					href,
					title,
					content,
					type,
					rez,
					hrefParts,
					selector;

				if($.type(element) === "object"){
					// Check if is DOM element
					if(element.nodeType) element = $(element);
					if (isQuery(element)){
						obj = {
							href    : element.attr('href'),
							title   : element.attr('title'),
							isDom   : true,
							element : element
						};

						if ($.metadata) {
							$.extend(true, obj, element.metadata());
						}
					}else{
						obj = element;
					}
				}

				href  = opts.href  || obj.href || (isString(element) ? element : null);
				title = opts.title !== undefined ? opts.title : obj.title || '';

				content = opts.content || obj.content;
				type    = content ? 'html' : (opts.type  || obj.type);

				if(!type && obj.isDom) {
					type = element.data('upgallery-type');
					if(!type){
						rez  = element.prop('class').match(/upgallery\.(\w+)/);
						type = rez ? rez[1] : null;
					}
				}

				if (isString(href)){
					// Try to guess the content type
					if(!type)
						if(UP.isImage(href)) type = 'image'
				}

				if(!content){
					if(!type && !href && obj.isDom) {
						type    = 'inline';
						content = element;
					}
				}

				$.extend(obj, {
					href     : href,
					type     : type,
					content  : content,
					title    : title,
					selector : selector
				});

				group[i] = obj;
			});
			// Extend the defaults
			UP.opts = $.extend(true, {}, UP.defaults, opts);

			// All options are merged recursive except keys
			if (opts.keys !== undefined) {
				UP.opts.keys = opts.keys ? $.extend({}, UP.defaults.keys, opts.keys) : false;
			}
			UP.group = group;
			return UP._start(UP.opts.index);
		},
		_start: function(index){
			var coming = {},
				obj,
				href,
				type,
				margin,
				padding;

			index = getScalar(index);
			obj = UP.group[index] || null;

			if(!obj) return false;
			coming = $.extend(true, {}, UP.opts, obj);

			// Convert margin and padding properties to array - top, right, bottom, left
			margin  = coming.margin;
			padding = coming.padding;

			if($.type(margin) === 'number'){
				coming.margin = [margin, margin, margin, margin];
			}

			if($.type(padding) === 'number') {
				coming.padding = [padding, padding, padding, padding];
			}

			// 'modal' propery is just a shortcut
			if (coming.modal) {
				$.extend(true, coming, {
					closeBtn   : false,
					closeClick : false,
					nextClick  : false,
					arrows     : false,
					mouseWheel : false,
					keys       : null,
					helpers: {
						overlay : {
							closeClick : false
						}
					}
				});
			}

			// 'autoSize' property is a shortcut, too
			if(coming.autoSize){
				coming.autoWidth = coming.autoHeight = true;
			}

			if(coming.width === 'auto'){
				coming.autoWidth = true;
			}

			if(coming.height === 'auto'){
				coming.autoHeight = true;
			}

			coming.group  = UP.group;
			coming.index  = index;

			// Give a chance for callback or helpers to update coming item (type, title, etc)
			UP.coming = coming;
			if(false === UP.trigger('beforeLoad')){
				UP.coming = null;
				return;
			}

			type = coming.type;
			href = coming.href;
			if(!type){
				UP.coming = null;

				//If we can not determine content type then drop silently or display next/prev item if looping through gallery
				if(UP.current && UP.router && UP.router !== 'jumpto') {
					UP.current.index = index;
					return UP[UP.router](UP.direction);
				}
				return false;
			}
			UP.isActive = true;
			if(type === 'image'){
				coming.autoHeight = coming.autoWidth = false;
				coming.scrolling  = 'visible';
			}

			if(type === 'image'){
				coming.aspectRatio = true;
			}

			// Build the neccessary markup
			coming.wrap = $(coming.tpl.wrap).addClass('upgallery-desktop upgallery-type-'+type+' upgallery-tmp ' +coming.wrapCSS).prependTo(coming.parent);

			$.extend(coming, {
				skin  : $('.upgallery-outer',  coming.wrap),
				outer : $('.upgallery-outer', coming.wrap),
				inner : $('.upgallery-inner', coming.wrap)
			});

			$.each(["Top", "Right", "Bottom", "Left"], function(i, v){
				coming.skin.css('padding'+v, getValue(coming.padding[i]));
			});

			UP.trigger('onReady');

			// Check before try to load; 'inline' and 'html' types need content, others - href
			if(type === 'inline' || type === 'html'){
				if(!coming.content || !coming.content.length){
					return UP._error('content');
				}
			}else if(!href){
				return UP._error('href');
			}

			if(type === 'image'){
				UP._loadImage();
			}
		},
		_loadImage:function(){
			// Reset preload image so it is later possible to check "complete" property
			var img = UP.imgPreload = new Image();

			img.onload = function(){
				this.onload = this.onerror = null;
				UP.coming.width  = this.width;
				UP.coming.height = this.height;
				UP._afterLoad();
			};

			img.onerror = function(){
				this.onload = this.onerror = null;
				UP._error('image');
			};

			img.src = UP.coming.href;
			if(img.complete === undefined || !img.complete){
				UP.showLoading();
			}
		},
		next: function(direction){
			var event = null;
			if(!isString(direction) && direction.type == 'click'){
				var event = direction;
			}
			
			var current = UP.current;
			if(current){
				if(!isString(direction)){
					direction = current.direction.next;
				}
				if(event != null) event.preventDefault();
				UP.jumpto(current.index + 1, direction, 'next');
			}
		},
		prev: function(direction){
			var event = null;
			if(!isString(direction) && direction.type == 'click'){
				var event = direction;
			}
			
			var current = UP.current;
			if(current){
				if(!isString(direction)){
					direction = current.direction.prev;
				}
				if(event != null) event.preventDefault();
				UP.jumpto(current.index - 1, direction, 'prev');
			}
		},
		jumpto: function(index, direction, router ) {
			var current = UP.current;
			if(!current){
				return;
			}
			index = getScalar(index);
			UP.direction = direction || current.direction[ (index >= current.index ? 'next' : 'prev') ];
			UP.router    = router || 'jumpto';
			if (current.loop) {
				if (index < 0) {
					index = current.group.length + (index % current.group.length);
				}
				index = index % current.group.length;
			}
			if(current.group[index] !== undefined){
				UP.cancel();
				UP._start(index);
			}
		},
		cancel: function(){
			var coming = UP.coming;
			if (!coming || false === UP.trigger('onCancel')){
				return;
			}
			UP.hideLoading();
			if(UP.imgPreload){
				UP.imgPreload.onload = UP.imgPreload.onerror = null;
			}

			// If the first item has been canceled, then clear everything
			if(coming.wrap){
				coming.wrap.stop(true).trigger('onReset').remove();
			}

			if(!UP.current) UP.trigger('afterClose');
			UP.coming = null;
		},
		close: function(immediately){
			UP.cancel();

			if(false === UP.trigger('beforeClose')) return;
			UP.unbindEvents();

			if(!UP.isOpen || immediately === true) {
				$('.upgallery-wrap').stop(true).trigger('onReset').remove();
				UP._afterZoomOut();
			}else{
				UP.isOpen = UP.isOpened = false;
				UP.isClosing = true;
				$('.upgallery-item, .upgallery-nav').remove();
				UP.wrap.stop(true, true).removeClass('upgallery-opened');

				if(UP.wrap.css('position') === 'fixed'){
					UP.wrap.css(UP._getPosition(true));
				}
				UP.transitions[UP.current.closeMethod]();
			}
		},
		bindEvents: function(){
			var current = UP.current,
				keys;

			if(!current) return;

			// Changing document height on iOS devices triggers a 'resize' event,
			// that can change document height... repeating infinitely
			W.bind('orientationchange.upg resize.upg' + (current.autoCenter && !current.locked ? ' scroll.fb' : ''), UP.update);

			keys = current.keys;

			if(keys){
				D.bind('keydown.upg', function(e){
					var code   = e.which || e.keyCode,
						target = e.target || e.srcElement;

					// Ignore key combinations and key events within form elements
					if(!e.ctrlKey && !e.altKey && !e.shiftKey && !e.metaKey && !(target && (target.type || $(target).is('[contenteditable]')))){
						$.each(keys, function(i, val) {
							if(current.group.length > 1 && val[ code ] !== undefined) {
								UP[i](val[code]);
								e.preventDefault();
								return false;
							}

							if($.inArray(code, val) > -1) {
								F[i]();
								e.preventDefault();
								return false;
							}
						});
					}
				});
			}
		},
		unbindEvents: function(){
			if(UP.wrap && isQuery(UP.wrap)){
				UP.wrap.unbind('.upg');
			}
			D.unbind('.upg');
			W.unbind('.upg');
		},
		trigger: function(event, o){
			var ret, obj = o || UP.coming || UP.current;
			if(!obj) return;

			if($.isFunction(obj[event])){
				ret = obj[event].apply(obj, Array.prototype.slice.call(arguments, 1));
			}

			if(ret === false) return false;

			if(event === 'onCancel' && !UP.isOpened) {
				UP.isActive = false;
			}

			if(obj.helpers) {
				$.each(obj.helpers, function (helper, opts){
					if (opts && UP.helpers[helper] && $.isFunction(UP.helpers[helper][event])) {
						UP.helpers[helper][event](opts, obj);
					}
				});
			}
			$.event.trigger(event + '.upg');
		},
		isImage: function(str){
			return isString(str) && str.match(/\.(jp(e|g|eg)|gif|png|bmp|webp)((\?|#).*)?$/i);
		},
		showLoading: function(){
			var el, viewport;
			UP.hideLoading();
			// If user will press the escape-button, the request will be canceled
			D.bind('keypress.upg', function(e) {
				if ((e.which || e.keyCode) === 27) {
					e.preventDefault();
					UP.cancel();
				}
			});

			el = $('<div id="upgallery-loading"><div></div></div>').click(UP.cancel).appendTo('body');
			if (!UP.defaults.fixed){
				viewport = UP.getViewport();
				el.css({
					position : 'absolute',
					top  : (viewport.h * 0.5) + viewport.y,
					left : (viewport.w * 0.5) + viewport.x
				});
			}
		},
		hideLoading: function(){
			D.unbind('keypress.upg');
			$('#upgallery-loading').remove();
		},
		getViewport: function(){
			var lock = UP.current ? UP.current.locked : false,
				rez  = {
					x: W.scrollLeft(),
					y: W.scrollTop()
				};

			if(lock){
				rez.w = lock[0].clientWidth;
				rez.h = lock[0].clientHeight;
			}else{
				// See http://bugs.jquery.com/ticket/6724
				rez.w = window.innerWidth  ? window.innerWidth  : W.width();
				rez.h = window.innerHeight ? window.innerHeight : W.height();
			}
			return rez;
		},
		_setDimension: function (){
			var viewport   = UP.getViewport(),
				steps      = 0,
				canShrink  = false,
				canExpand  = false,
				wrap       = UP.wrap,
				skin       = UP.skin,
				inner      = UP.inner,
				current    = UP.current,
				width      = current.width,
				height     = current.height,
				minWidth   = current.minWidth,
				minHeight  = current.minHeight,
				maxWidth   = current.maxWidth,
				maxHeight  = current.maxHeight,
				scrolling  = current.scrolling,
				scrollOut  = current.scrollOutside ? current.scrollbarWidth : 0,
				margin     = current.margin,
				wMargin    = margin[1] + margin[3],
				hMargin    = margin[0] + margin[2],
				wPadding,
				hPadding,
				wSpace,
				hSpace,
				origWidth,
				origHeight,
				origMaxWidth,
				origMaxHeight,
				ratio,
				width_,
				height_,
				maxWidth_,
				maxHeight_,
				iframe,
				body;

			// Reset dimensions so we could re-check actual size
			wrap.add(skin).add(inner).width('auto').height('auto');

			wPadding = skin.outerWidth(true)  - skin.width();
			hPadding = skin.outerHeight(true) - skin.height();

			// Any space between content and viewport (margin, padding, border, title)
			wSpace = wMargin + wPadding;
			hSpace = hMargin + hPadding;

			origWidth  = isPercentage(width)  ? (viewport.w - wSpace) * getScalar(width)  / 100 : width;
			origHeight = isPercentage(height) ? (viewport.h - hSpace) * getScalar(height) / 100 : height;

			if (current.type === 'iframe') {
				iframe = current.content;

				if (current.autoHeight && iframe.data('ready') === 1) {
					try {
						if (iframe[0].contentWindow.document.location) {
							inner.width( origWidth ).height(9999);

							body = iframe.contents().find('body');

							if (scrollOut) {
								body.css('overflow-x', 'hidden');
							}

							origHeight = body.height();
						}

					} catch (e) {}
				}

			} else if (current.autoWidth || current.autoHeight) {
				inner.addClass( 'upgallery-tmp' );

				// Set width or height in case we need to calculate only one dimension
				if (!current.autoWidth) {
					inner.width( origWidth );
				}

				if (!current.autoHeight) {
					inner.height( origHeight );
				}

				if (current.autoWidth) {
					origWidth = inner.width();
				}

				if (current.autoHeight) {
					origHeight = inner.height();
				}

				inner.removeClass('upgallery-tmp');
			}

			width  = getScalar( origWidth );
			height = getScalar( origHeight );

			ratio  = origWidth / origHeight;

			// Calculations for the content
			minWidth  = getScalar(isPercentage(minWidth) ? getScalar(minWidth, 'w') - wSpace : minWidth);
			maxWidth  = getScalar(isPercentage(maxWidth) ? getScalar(maxWidth, 'w') - wSpace : maxWidth);

			minHeight = getScalar(isPercentage(minHeight) ? getScalar(minHeight, 'h') - hSpace : minHeight);
			maxHeight = getScalar(isPercentage(maxHeight) ? getScalar(maxHeight, 'h') - hSpace : maxHeight);

			// These will be used to determine if wrap can fit in the viewport
			origMaxWidth  = maxWidth;
			origMaxHeight = maxHeight;

			maxWidth_  = viewport.w - wMargin;
			maxHeight_ = viewport.h - hMargin;

			if (current.aspectRatio) {
				if (width > maxWidth) {
					width  = maxWidth;
					height = width / ratio;
				}

				if (height > maxHeight) {
					height = maxHeight;
					width  = height * ratio;
				}

				if (width < minWidth) {
					width  = minWidth;
					height = width / ratio;
				}

				if (height < minHeight) {
					height = minHeight;
					width  = height * ratio;
				}

			} else {
				width  = Math.max(minWidth,  Math.min(width,  maxWidth));
				height = Math.max(minHeight, Math.min(height, maxHeight));
			}

			// Try to fit inside viewport (including the title)
			if (current.fitToView){
				maxWidth  = Math.min(viewport.w - wSpace, maxWidth);
				maxHeight = Math.min(viewport.h - hSpace, maxHeight);

				inner.width( getScalar( width ) ).height( getScalar( height ) );

				wrap.width( getScalar( width + wPadding ) );

				// Real wrap dimensions
				width_  = wrap.width();
				height_ = wrap.height();

				if (current.aspectRatio) {
					while ((width_ > maxWidth_ || height_ > maxHeight_) && width > minWidth && height > minHeight) {
						if (steps++ > 19) {
							break;
						}

						height = Math.max(minHeight, Math.min(maxHeight, height - 10));
						width  = height * ratio;

						if (width < minWidth) {
							width  = minWidth;
							height = width / ratio;
						}

						if (width > maxWidth) {
							width  = maxWidth;
							height = width / ratio;
						}

						inner.width( getScalar( width ) ).height( getScalar( height ) );

						wrap.width( getScalar( width + wPadding ) );

						width_  = wrap.width();
						height_ = wrap.height();
					}

				} else {
					width  = Math.max(minWidth,  Math.min(width,  width  - (width_  - maxWidth_)));
					height = Math.max(minHeight, Math.min(height, height - (height_ - maxHeight_)));
				}
			}

			if (scrollOut && scrolling === 'auto' && height < origHeight && (width + wPadding + scrollOut) < maxWidth_) {
				width += scrollOut;
			}

			inner.width( getScalar( width ) ).height( getScalar( height ) );

			wrap.width( getScalar( width + wPadding ) );

			width_  = wrap.width();
			height_ = wrap.height();

			canShrink = (width_ > maxWidth_ || height_ > maxHeight_) && width > minWidth && height > minHeight;
			canExpand = current.aspectRatio ? (width < origMaxWidth && height < origMaxHeight && width < origWidth && height < origHeight) : ((width < origMaxWidth || height < origMaxHeight) && (width < origWidth || height < origHeight));

			$.extend(current, {
				dim : {
					width	: getValue( width_ ),
					height	: getValue( height_ )
				},
				origWidth  : origWidth,
				origHeight : origHeight,
				canShrink  : canShrink,
				canExpand  : canExpand,
				wPadding   : wPadding,
				hPadding   : hPadding,
				wrapSpace  : height_ - skin.outerHeight(true),
				skinSpace  : skin.height() - height
			});

			if (!iframe && current.autoHeight && height > minHeight && height < maxHeight && !canExpand) {
				inner.height('auto');
			}
		},
		_getPosition: function(onlyAbsolute){
			var current  = UP.current,
				viewport = UP.getViewport(),
				margin   = current.margin,
				width    = UP.wrap.width()  + margin[1] + margin[3],
				height   = UP.wrap.height() + margin[0] + margin[2],
				rez      = {
					position: 'absolute',
					top  : margin[0],
					left : margin[3]
				};

			if(current.autoCenter && current.fixed && !onlyAbsolute && height <= viewport.h && width <= viewport.w) {
				rez.position = 'fixed';

			}else if(!current.locked){
				rez.top  += viewport.y;
				rez.left += viewport.x;
			}

			rez.top  = getValue(Math.max(rez.top,  rez.top  + ((viewport.h - height) * current.topRatio)));
			rez.left = getValue(Math.max(rez.left, rez.left + ((viewport.w - width)  * current.leftRatio)));

			return rez;
		},
		reposition: function (e, onlyAbsolute) {
			var pos;

			if (UP.isOpen) {
				pos = UP._getPosition(onlyAbsolute);

				if (e && e.type === 'scroll') {
					delete pos.position;

					UP.wrap.stop(true, true).animate(pos, 200);

				} else {
					UP.wrap.css(pos);
				}
			}
		},
		_preloadImages: function(){
			var group   = UP.group,
				current = UP.current,
				len     = group.length,
				cnt     = current.preload ? Math.min(current.preload, len - 1) : 0,
				item,
				i;

			for (i = 1; i <= cnt; i += 1) {
				item = group[ (current.index + i ) % len ];

				if (item.type === 'image' && item.href) {
					new Image().src = item.href;
				}
			}
		},
		_afterZoomOut: function(){
			var current = UP.current;
			$('.upgallery-wrap').stop(true).trigger('onReset').remove();
			$.extend(UP, {
				group  : {},
				opts   : {},
				router : false,
				current   : null,
				isActive  : false,
				isOpened  : false,
				isOpen    : false,
				isClosing : false,
				wrap   : null,
				skin   : null,
				outer  : null,
				inner  : null
			});
			UP.trigger('afterClose', current);
		},
		_afterLoad: function(){
			var coming = UP.coming,
				previous = UP.current,
				placeholder = 'upgallery-placeholder',
				current,
				content,
				type,
				scrolling,
				href,
				embed;

			UP.hideLoading();

			if(!coming || UP.isActive === false) {
				return;
			}

			if(false === UP.trigger('afterLoad', coming, previous)) {
				coming.wrap.stop(true).trigger('onReset').remove();
				UP.coming = null;
				return;
			}

			if(previous){
				UP.trigger('beforeChange', previous);
				previous.wrap.stop(true).removeClass('upgallery-opened')
					.find('.upgallery-item, .upgallery-nav')
					.remove();

				if(previous.wrap.css('position') === 'fixed'){
					previous.wrap.css(UP._getPosition( true ));
				}
			}

			UP.unbindEvents();

			current   = coming;
			content   = coming.content;
			type      = coming.type;
			scrolling = coming.scrolling;

			$.extend(UP, {
				wrap  : current.wrap,
				skin  : current.skin,
				outer : current.outer,
				inner : current.inner,
				current  : current,
				previous : previous
			});

			href = current.href;

			switch (type) {
				case 'inline':
				case 'ajax':
				case 'html':
					if (current.selector) {
						content = $('<div>').html(content).find(current.selector);

					} else if (isQuery(content)) {
						if (!content.data(placeholder)) {
							content.data(placeholder, $('<div class="' + placeholder + '"></div>').insertAfter( content ).hide() );
						}

						content = content.show().detach();

						current.wrap.bind('onReset', function () {
							if ($(this).find(content).length) {
								content.hide().replaceAll( content.data(placeholder) ).data(placeholder, false);
							}
						});
					}
					break;

				case 'image':
					content = current.tpl.image.replace('{href}', href);
					break;

				case 'swf':
					content = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="100%" height="100%"><param name="movie" value="' + href + '"></param>';
					embed   = '';

					$.each(current.swf, function(name, val) {
						content += '<param name="' + name + '" value="' + val + '"></param>';
						embed   += ' ' + name + '="' + val + '"';
					});

					content += '<embed src="' + href + '" type="application/x-shockwave-flash" width="100%" height="100%"' + embed + '></embed></object>';
					break;
			}

			if(!(isQuery(content) && content.parent().is(current.inner))){
				current.inner.append(content);
			}
			// Give a chance for helpers or callbacks to update elements
			UP.trigger('beforeShow');

			// Set scrolling before calculating dimensions
			current.inner.css('overflow', scrolling === 'yes' ? 'scroll' : (scrolling === 'no' ? 'hidden' : scrolling));
			// Set initial dimensions and start position
			UP._setDimension();
			UP.trigger('afterDimensionSet');
			
			current.wrap.removeClass('upgallery-tmp');

			current.pos = $.extend({}, current.dim, UP._getPosition( true ));

			UP.isOpen = false;
			UP.coming = null;

			UP.bindEvents();

			if(!UP.isOpened){
				$('.upgallery-wrap').not(current.wrap).stop(true).trigger('onReset').remove();

			}else if(previous.prevMethod){
				UP.transitions[ previous.prevMethod ]();
			}

			UP.transitions[UP.isOpened ? current.nextMethod : current.openMethod]();

			UP._preloadImages();
		},
		_afterZoomIn: function(){
			var current = UP.current;
			if(!current) return;
			UP.isOpen = UP.isOpened = true;
			UP.wrap.addClass('upgallery-opened').css('overflow', 'visible');
			UP.reposition();
			// Assign a click event
			if (current.closeClick || current.nextClick) {
				UP.inner.css('cursor', 'pointer').bind('click.upg', function(e) {
					if (!$(e.target).is('a') && !$(e.target).parent().is('a')) {
						UP[current.closeClick ? 'close' : 'next' ]();
					}
				});
			}

			// Create a close button
			if(current.closeBtn){
				$(".upgallery-closeBtn").remove();
				$(current.tpl.closeBtn).insertBefore(UP.wrap).bind('click.upg', UP.close);
			}
			// Create navigation arrows
			if (current.arrows && UP.group.length > 1) {
				if (current.loop || current.index > 0) {
					$(current.tpl.prev).appendTo(UP.wrap).bind('click.upg', UP.prev);
				}

				if (current.loop || current.index < UP.group.length - 1) {
					$(current.tpl.next).appendTo(UP.wrap).bind('click.upg', UP.next);
				}
			}

			UP.trigger('afterShow');
		},
		afterClose: function(opts){
			var that  = this,
				speed = opts.speedOut || 0;

			if (that.overlay && !F.isActive) {
				that.overlay.fadeOut(speed || 0, function () {
					$('body').css('margin-right', that.margin);
					that.el.removeClass('fancybox-lock');
					that.overlay.remove();
					that.overlay = null;
				});
			}
		}
	});

	UP.transitions = {
		getOrigPosition: function () {
			var current  = UP.current,
				element  = current.element,
				orig     = current.orig,
				pos      = {},
				width    = 50,
				height   = 50,
				hPadding = current.hPadding,
				wPadding = current.wPadding,
				viewport = UP.getViewport();

			if (!orig && current.isDom && element.is(':visible')) {
				orig = element.find('img:first');

				if (!orig.length) {
					orig = element;
				}
			}

			if (isQuery(orig)) {
				pos = orig.offset();

				if (orig.is('img')) {
					width  = orig.outerWidth();
					height = orig.outerHeight();
				}

			} else {
				pos.top  = viewport.y + (viewport.h - height) * current.topRatio;
				pos.left = viewport.x + (viewport.w - width)  * current.leftRatio;
			}

			if (current.locked) {
				pos.top  -= viewport.y;
				pos.left -= viewport.x;
			}

			pos = {
				top     : getValue(pos.top  - hPadding * current.topRatio),
				left    : getValue(pos.left - wPadding * current.leftRatio),
				width   : getValue(width  + wPadding),
				height  : getValue(height + hPadding)
			};

			return pos;
		},

		step: function (now, fx) {
			var ratio,
				padding,
				value,
				prop       = fx.prop,
				current    = UP.current,
				wrapSpace  = current.wrapSpace,
				skinSpace  = current.skinSpace;

			if (prop === 'width' || prop === 'height') {
				ratio = fx.end === fx.start ? 1 : (now - fx.start) / (fx.end - fx.start);

				if (UP.isClosing) {
					ratio = 1 - ratio;
				}

				padding = prop === 'width' ? current.wPadding : current.hPadding;
				value   = now - padding;

				UP.skin[ prop ](  getScalar( prop === 'width' ?  value : value - (wrapSpace * ratio) ) );
				UP.inner[ prop ]( getScalar( prop === 'width' ?  value : value - (wrapSpace * ratio) - (skinSpace * ratio) ) );
			}
		},

		zoomIn: function () {
			var current  = UP.current,
				startPos = current.pos,
				effect   = current.openEffect,
				elastic  = effect === 'elastic',
				endPos   = $.extend({opacity : 1}, startPos);

			// Remove "position" property that breaks older IE
			delete endPos.position;

			if (elastic) {
				startPos = this.getOrigPosition();

				if (current.openOpacity) {
					startPos.opacity = 0.1;
				}

			} else if (effect === 'fade') {
				startPos.opacity = 0.1;
			}

			UP.wrap.css(startPos).animate(endPos, {
				duration : effect === 'none' ? 0 : current.openSpeed,
				easing   : current.openEasing,
				step     : elastic ? this.step : null,
				complete : UP._afterZoomIn
			});
		},

		zoomOut: function () {
			var current  = UP.current,
				effect   = current.closeEffect,
				elastic  = effect === 'elastic',
				endPos   = {opacity : 0.1};

			if (elastic) {
				endPos = this.getOrigPosition();

				if (current.closeOpacity) {
					endPos.opacity = 0.1;
				}
			}

			UP.wrap.animate(endPos, {
				duration : effect === 'none' ? 0 : current.closeSpeed,
				easing   : current.closeEasing,
				step     : elastic ? this.step : null,
				complete : UP._afterZoomOut
			});
		},

		changeIn: function () {
			var current   = UP.current,
				effect    = current.nextEffect,
				startPos  = current.pos,
				endPos    = { opacity : 1 },
				direction = UP.direction,
				distance  = 200,
				field;

			startPos.opacity = 0.1;

			if (effect === 'elastic') {
				field = direction === 'down' || direction === 'up' ? 'top' : 'left';

				if (direction === 'down' || direction === 'right') {
					startPos[ field ] = getValue(getScalar(startPos[ field ]) - distance);
					endPos[ field ]   = '+=' + distance + 'px';

				} else {
					startPos[ field ] = getValue(getScalar(startPos[ field ]) + distance);
					endPos[ field ]   = '-=' + distance + 'px';
				}
			}

			// Workaround for http://bugs.jquery.com/ticket/12273
			if (effect === 'none') {
				UP._afterZoomIn();
			} else {
				UP.wrap.css(startPos).animate(endPos, {
					duration : current.nextSpeed,
					easing   : current.nextEasing,
					complete : UP._afterZoomIn
				});
			}
		},

		changeOut: function(){
			var previous  = UP.previous,
				effect    = previous.prevEffect,
				endPos    = { opacity : 0.1 },
				direction = UP.direction,
				distance  = 200;

			if (effect === 'elastic') {
				endPos[ direction === 'down' || direction === 'up' ? 'top' : 'left' ] = ( direction === 'up' || direction === 'left' ? '-' : '+' ) + '=' + distance + 'px';
			}

			previous.wrap.animate(endPos, {
				duration : effect === 'none' ? 0 : previous.prevSpeed,
				easing   : previous.prevEasing,
				complete : function(){
					$(this).trigger('onReset').remove();
				}
			});
		}
	};

	UP.helpers.title = {
		beforeShow: function (opts) {
			var text = UP.current.title,
				type = opts.type,
				title,
				target,
				galleryText;

			if(!isString(text) || $.trim(text) === ''){
				return;
			}
			if(text.indexOf("|") > 0){
				var textArr = text.split("|");
				text = textArr[0];
				galleryText = textArr[1];
			}else{
				galleryText = '';
			}
			$(".upgallery-itemTitle, .upgallery-galleryTitle").remove();
			title = $('<div class="upgallery-itemTitle">' + text + '</div>');
			if(galleryText != ''){
				var galleryTitle = $('<div class="upgallery-galleryTitle">'+galleryText+'</div>');
				galleryTitle.insertBefore(UP.wrap);
			}
			title.insertBefore(UP.wrap);
		}
	};
	
	UP.helpers.comments = {
		beforeShow: function (opts){
			var title = UP.current.title;
			var tpl = '<div class="upgallery-uComments" ></div>';
			UP.outer.css('paddingRight', '330px');
			$.extend(UP, {
				comments: $(tpl).prependTo(UP.outer)
			});
			$('a.upgallery-previous, a.upgallery-next').addClass('inline');
		},
		afterDimensionSet: function(opts, obj){
			UP.comments.css('height', UP.outer.outerHeight());
		},
		afterShow: function(opts){
			$('a.upgallery-previous, a.upgallery-next').addClass('inline');
			if(opts.url != '' && typeof opts.params == 'object'){
				$.post(opts.url, opts.params, function(result){
					if(result.status == 0){
						UP.comments.html(result.html);
					}
				}, 'json');
			}
		}
	};

	UP.helpers.overlay = {
		overlay: null,

		update: function () {
			var width = '100%', offsetWidth;

			// Reset width/height so it will not mess
			this.overlay.width(width).height('100%');

			// jQuery does not return reliable result for IE
			if ($.browser.msie) {
				offsetWidth = Math.max(document.documentElement.offsetWidth, document.body.offsetWidth);

				if (D.width() > offsetWidth) {
					width = D.width();
				}

			} else if (D.width() > W.width()) {
				width = D.width();
			}

			this.overlay.width(width).height(D.height());
		},

		// This is where we can manipulate DOM, because later it would cause iframes to reload
		onReady: function (opts, obj) {
			$('.upgallery-overlay').stop(true, true);

			if(!this.overlay){
				$.extend(this, {
					overlay : $('<div class="upgallery-overlay"></div>').prependTo(obj.parent),
					margin  : D.height() > W.height() || $('body').css('overflow-y') === 'scroll' ? $('body').css('margin-right') : false,
					el : document.all && !document.querySelector ? $('html') : $('body')
				});
			}

			if (obj.fixed) {
				this.overlay.addClass('upgallery-overlay-fixed');

				if(obj.autoCenter){
					this.overlay.append(obj.wrap);
					obj.locked = this.overlay;
				}
			}

			if(opts.showEarly === true) {
				this.beforeShow.apply(this, arguments);
			}
		},

		beforeShow : function(opts, obj) {
			var overlay = this.overlay.unbind('.upg').width('auto').height('auto').css( opts.css );

			if (opts.closeClick) {
				overlay.bind('click.upg', function(e) {
					if ($(e.target).hasClass('upgallery-overlay')) {
						UP.close();
					}
				});
			}

			if(obj.fixed){
				if (obj.locked) {
					this.el.addClass('upgallery-lock');

					if (this.margin !== false) {
						//$('body').css('margin-right', getScalar( this.margin ) + obj.scrollbarWidth);
					}
				}

			} else {
				this.update();
			}

			overlay.show();
		},

		onUpdate : function(opts, obj) {
			if (!obj.fixed) {
				this.update();
			}
		},

		afterClose: function (opts) {
			var that  = this,
				speed = opts.speedOut || 0;

			// Remove overlay if exists and fancyBox is not opening
			// (e.g., it is not being open using afterClose callback)
			if (that.overlay && !UP.isActive) {
				that.overlay.fadeOut(speed || 0, function () {
					$('body').css('margin-right', that.margin);

					that.el.removeClass('upgallery-lock');

					that.overlay.remove();

					that.overlay = null;
				});
			}
		}
	};

	UP.helpers.thumbs = {
		wrap  : null,
		list  : null,
		width : 0,

		//Default function to obtain the URL of the thumbnail image
		source: function(item){
			var href;
			if (item.element) {
				href = $(item.element).find('img').attr('src');
			}

			if (!href && item.type === 'image' && item.href) {
				href = item.href;
			}

			return href;
		},
		toggle: function(){
			var that = this;
			this.wrap.toggleClass('opened');
		},
		init: function (opts, obj) {
			var that = this,
				list,
				thumbWidth  = opts.width  || 133,
				thumbHeight = opts.height || 100,
				thumbSource = opts.source || this.source;

			//Build list structure
			list = '';

			for (var n = 0; n < obj.group.length; n++) {
				list += '<li style="width:'+thumbWidth+'px;height:'+thumbHeight+'px;" onclick="javascript:jQuery.upgallery.jumpto('+n+');"></li>';
			}

			this.wrap = $('<div id="upgallery-thumbs" class="upgallery-photolist"><span class="togglelist" onclick="jQuery.upgallery.helpers.thumbs.toggle();"><img class="icon bulletup" src="images/spacer.gif" alt="" title="" /><a href="#">Еще фотографии альбома</a><img class="icon bulletup" src="images/spacer.gif" alt="" title="" /></span></div>').addClass(opts.position || 'bottom').appendTo((UP.current.wrap).parent());
			this.list = $('<div class="list"><ul class="jsCarousel jcarousel-skin-upgallery">'+list+'</ul></div>').appendTo(this.wrap);

			//Load each thumbnail
			$.each(obj.group, function (i) {
				var href = thumbSource( obj.group[ i ] );

				if (!href) {
					return;
				}

				$("<img />").load(function () {
					var width  = this.width,
						height = this.height,
						widthRatio, heightRatio, parent;

					if (!that.list || !width || !height) {
						return;
					}

					//Calculate thumbnail width/height and center it
					widthRatio  = width / thumbWidth;
					heightRatio = height / thumbHeight;

					parent = that.list.find('li').eq(i);
					if (widthRatio >= 1 && heightRatio >= 1) {
						if (widthRatio > heightRatio) {
							width  = Math.floor(width / heightRatio);
							height = thumbHeight;

						} else {
							width  = thumbWidth;
							height = Math.floor(height / widthRatio);
						}
					}

					$(this).css({
						width  : width,
						height : height,
						top    : Math.floor(thumbHeight / 2 - height / 2),
						left   : Math.floor(thumbWidth / 2 - width / 2)
					});

					parent.width(thumbWidth).height(thumbHeight);
					$(this).hide().appendTo(parent).fadeIn(300);

				}).attr('src', href);
			});

			//Set initial width
			//this.width = this.list.children().eq(0).outerWidth(true);

			//this.list.width(this.width * (obj.group.length + 1)).css('left', Math.floor($(window).width() * 0.5 - (obj.index * this.width + this.width * 0.5)));
			$(this.list.find('ul')).jcarousel({});
		},

		beforeLoad: function (opts, obj) {
			//Remove self if gallery do not have at least two items
			if (obj.group.length < 2 || obj.helpers.thumbs == false) {
				obj.helpers.thumbs = false;

				return;
			}

			//Increase bottom margin to give space for thumbs
			obj.margin[ opts.position === 'top' ? 0 : 2 ] += ((opts.height || 50) + 15);
		},

		afterShow: function (opts, obj) {
			//Check if exists and create or update list
			if (this.list) {
				this.onUpdate(opts, obj);

			} else {
				this.init(opts, obj);
			}

			//Set active element
			this.list.find('li').removeClass('active').eq(obj.index).addClass('active');
		},

		//Center list
		onUpdate: function (opts, obj) {
			if (this.list) {
				this.list.stop(true).animate({
					'left': Math.floor($(window).width() * 0.5 - (obj.index * this.width + this.width * 0.5))
				}, 150);
			}
		},

		beforeClose: function () {
			if (this.wrap) {
				this.wrap.remove();
			}

			this.wrap  = null;
			this.list  = null;
			this.width = 0;
		}
	};

	$.fn.upgallery = function(options){
		var index,
			that = $(this),
			selector = this.selector || '',
			run = function(e){
				var what = $(this).blur(), idx = index, relType, relVal;
				if(!(e.ctrlKey || e.altKey || e.shiftKey || e.metaKey) && !what.is('.upgallery-wrap')) {
					relType = options.groupAttr || 'data-upgallery-group';
					relVal  = what.attr(relType);

					if(!relVal){
						relType = 'rel';
						relVal = what.get(0)[relType];
					}

					if(relVal && relVal !== '' && relVal !== 'nofollow'){
						what = selector.length ? $.c(selector) : that;
						what = what.filter('['+relType+'="'+relVal+'"]');
						idx  = what.index(this);
					}

					options.index = idx;

					// Stop an event from bubbling if everything is fine
					if (UP.open(what, options) !== false){
						e.preventDefault();
					}
				}
			};

		options = options || {};
		index   = options.index || 0;
		if (!selector || options.live === false) {
			that.off('click.upg-start').on('click.upg-start', run);
		}else{
			D.undelegate(selector, 'click.upg-start').delegate(selector+":not('.upgallery-item, .upgallery-nav')", 'click.upg-start', run);
		}
		return this;
	};

	D.ready(function() {
		if($.scrollbarWidth === undefined){
			// http://benalman.com/projects/jquery-misc-plugins/#scrollbarwidth
			$.scrollbarWidth = function() {
				var parent = $('<div style="width:50px;height:50px;overflow:auto"><div/></div>').appendTo('body'),
					child  = parent.children(),
					width  = child.innerWidth() - child.height( 99 ).innerWidth();
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

		$.extend(UP.defaults, {
			scrollbarWidth : $.scrollbarWidth(),
			fixed  : $.support.fixedPosition,
			parent : $('body')
		});
	});
}(window, document, jQuery));
(function (window, document, $, undefined){
	"use strict";

	var W = $(window),
		D = $(document),
		WUP = function(){
			WUP.start.apply(this, arguments);
		}

	$.extend(WUP,{
		defaults:{
			updateInterval: 1000,
			format: 'json',
			calltype: 'post'
		},
		opts: {},
		updateCallers: {},

		start: function(namespace, opts){
			var current = {};
			current.opts = $.extend(true, {}, WUP.defaults, opts);
			current.namespace = namespace;
			if(typeof WUP.updateCallers[namespace] == 'undefined'){
				current.ticker = function(){
					if(current.opts.calltype == 'post'){
						WUP.post(current);
					}else{
						WUP.get(current);
					}
				}
				WUP.updateCallers[namespace] = setInterval(function(){current.ticker();}, current.opts.updateInterval);
			}
		},
		stop: function(namespace){
			if(WUP.updateCallers[namespace] != 'undefined'){
				clearInterval(WUP.updateCallers[namespace]);
				delete WUP.updateCallers[namespace];
				return true;
			}
			return false;
		},
		post: function(obj){
			if(obj.opts.href == 'undefined'){
				WUP.stop(obj.namespace);
				return false;
			}
			$.post(obj.opts.href, obj.opts.vars, function(result){WUP.parseResult(result);}, obj.opts.format);
		},
		get: function(obj){
			if(obj.opts.href == 'undefined'){
				WUP.stop(obj.namespace);
				return false;
			}
			$.get(obj.opts.href, obj.opts.vars, function(result){WUP.parseResult(result);}, obj.opts.format);
		},
		parseResult: function(obj){
			if(obj.status == 'ok'){
				WUP[obj.resulttype+'__'+obj.type](obj.text, obj.settings);
			}
		},
		message__corner: function(text, settings){
			$.alert(text, settings);
		}
	});

	D.ready(function(){
		$.extend(WUP.defaults, {
			parent : $('body')
		});

		jQuery.extend({'wupdater':WUP});
	});
}(window, document, jQuery));
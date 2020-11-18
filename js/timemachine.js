// timeMachine plugin
(function (window, document, $, undefined){
	var objects = {
			tooltip: null,
			timeSelector:$("#clockHolder"),
			tooltiptimer:null,
			wrappertimer:null,
			collection: null,
			historyWrapper: null
		},
		timeholderWrap = '<form action="{action}" method="post" />',
		formElems = '<input type="hidden" name="getnews" value="Y" /><input type="hidden" name="time[from]" value="" /><input type="hidden" name="time[to]" value="" /><input type="hidden" name="type" value="" />',
		offset,
		elheight,
		ajaxUrl = null,
		timeFormCallback = 'timeCallback',
		selectedTime = {
			'from': null, // red button
			'to': null // green button
		};

	var methods = {
		init: function(options){
			if(!$.isPlainObject(options)) options = {};
			if(options.ajaxUrl) ajaxUrl = options.ajaxUrl;
			if(options.selectedTime){
				selectedTime.to = options.selectedTime.historyForward;
				selectedTime.from = options.selectedTime.historyBack;
			}
			
			return this.each(function(){
				var $this = $(this),
					data = $this.data('timeMachine'),
					$collection = !data ? $this.find('a.item'):data.collection,
					wheight = $(window).height()-122;

				objects.historyWrapper = $this;
				objects.collection = $collection;

				if($collection.length > 0){
					elheight = wheight/$collection.length;
					$collection.css({'height':elheight, 'lineHeight':elheight+'px'});
					$collection.each(function(){
						$(this).on('click', function(e){
							e.preventDefault();
							$collection.removeClass('active');
							$(this).addClass('active');
							offset = $(e.target).offset();
							methods.initTooltip();
							methods.fillTooltip();
						});
					});
				}

				$this.find("a.historyBack, a.historyForward").on('click', function(e){
					e.preventDefault();
					methods.showTimeSelector($(this).attr('class'));
				});

				if(objects.timeSelector.parent().nodeName != 'FORM'){
					objects.timeSelector.wrap(function(){
						return timeholderWrap.replace('{action}', ajaxUrl);
					});
					objects.timeSelector.prepend(formElems);
					//save form, form objects and set event
					objects.timeForm = objects.timeSelector.parent();
					objects.formTime = {'from':objects.timeSelector.find('input[name="time[from]"]'), 'to':objects.timeSelector.find('input[name="time[to]"]')};
					objects.formType = objects.timeSelector.find('input[name="type"]');
					objects.timeForm.on('submit', function(e){
						e.preventDefault();
						var newTime = methods.__getTimeSelectorDate();
						selectedTime[objects.formType.val()] = methods.__dateToTimestamp(newTime);
						
						objects.formTime['to'].val(selectedTime['to']);
						objects.formTime['from'].val(selectedTime['from']);
						
						var side = objects.historyWrapper.parent();
						objects.fader = $('<div id="timeMachineFader" style="width:'+side.outerWidth()+'px;height:'+side.height()+'"></div>');
						side.append(objects.fader);
						
						$.post(ajaxUrl, $(objects.timeForm).serializeArray(), function(result){
							if(typeof methods[timeFormCallback] == 'function')
								methods[timeFormCallback].apply(this, [result]);
							else
								timeFormCallback.apply(this, [result]);
						}, 'json');
						methods.hideTimeSelector();
					});
				}

				objects.timeSelector.find('.calendarItem').on('mousewheel', function(e, delta, deltaX, deltaY){
					e.preventDefault();
					var scrollAmount = deltaY,
						currentValue = parseInt($(this).find('span').text()),
						currentVType = $(this).find('span').attr('class'),
						newVal = 0;

						newVal = currentValue + scrollAmount;
						setNewDateVal(currentVType, newVal, $(this).find('span'));
				});
				
				var setNewDateVal = function(currentVType, newVal, span){
					switch(currentVType){
							case 'day':
								if(newVal < 1) newVal = 31;
								if(newVal > 31) newVal = 1;
							break;
							case 'month':
								if(newVal < 1) newVal = 12;
								if(newVal > 12) newVal = 1;
							break;
							case 'year':
								if(newVal < 0) newVal = 99;
								if(newVal > 99) newVal = 0;
							break;
							case 'hour':
								if(newVal < 0) newVal = 23;
								if(newVal > 23) newVal = 0;
							break;
							case 'minute':
								if(newVal < 0) newVal = 59;
								if(newVal > 59) newVal = 0;
							break;
						}
						if(newVal < 10) newVal = '0'+newVal;
						span.text(newVal);
				}
				
				objects.timeSelector.find('a[href="#up"]').on('click', function(e){
					e.preventDefault();
					currentValue = parseInt($(this).parent().find('span').text()),
					currentVType = $(this).parent().find('span').attr('class'),
					newVal = 0;

					newVal = currentValue + 1;
					setNewDateVal(currentVType, newVal, $(this).parent().find('span'));
				});
				
				objects.timeSelector.find('a[href="#down"]').on('click', function(e){
					e.preventDefault();
					currentValue = parseInt($(this).parent().find('span').text()),
					currentVType = $(this).parent().find('span').attr('class'),
					newVal = 0;

					newVal = currentValue - 1;
					setNewDateVal(currentVType, newVal, $(this).parent().find('span'));
				});
				
				objects.timeSelector.find('a[href="#setDate"]').on('click', function(e){
					e.preventDefault();
					var dateObj = new Date();
					methods.__setDate(dateObj.getDate(), dateObj.getMonth()+1, dateObj.getFullYear().toString().substr(2));
				});
				
				objects.timeSelector.find('a[href="#setTime"]').on('click', function(e){
					e.preventDefault();
					var dateObj = new Date();
					methods.__setTime(dateObj.getHours(), dateObj.getMinutes());
				});
				
				objects.day = $.c('span.day', objects.timeSelector),
				objects.month = $.c('span.month', objects.timeSelector),
				objects.year = $.c('span.year', objects.timeSelector),
				objects.hour = $.c('span.hour', objects.timeSelector),
				objects.minute = $.c('span.minute', objects.timeSelector);

				if(!data){
					$(this).data('timeMachine',{
						target: $this,
						collection:$collection
					});
				}
			});
		},
		__getTimeSelectorDate: function(){
			var dateStr = '';
				
			dateStr = objects.day.text()+'.'+objects.month.text()+'.'+objects.year.text()+' '+objects.hour.text()+':'+objects.minute.text();
			return dateStr;
		},
		__setDate: function(day, month, year){
			objects.day.text(day);
			objects.month.text(month);
			objects.year.text(year);
		},
		__setTime: function(hour, minute){
			objects.hour.text(hour);
			objects.minute.text(minute);
		},
		__setTimeSelectorDate: function(day, month, year, hour, minute){
			if(day.indexOf('.') != false){
				var resultArray = day.match(/([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{1,2})\s([0-9]{1,2}):([0-9]{1,2})/);
				if(resultArray){
					objects.day.text(resultArray[1]);
					objects.month.text(resultArray[2]);
					objects.year.text(resultArray[3]);
					objects.hour.text(resultArray[4]);
					objects.minute.text(resultArray[5]);
				}else{
					return false;
				}
			}else{
				objects.day.text(day);
				objects.month.text(month);
				objects.year.text(year);
				objects.hour.text(hour);
				objects.minute.text(minute);
			}
		},
		__unixTimestampToDate: function(timestamp){
			var dateObj = new Date(timestamp+'000');
			var dateArr = {
				month: dateObj.getMonth()+1,
				day: dateObj.getDate(),
				year: dateObj.getFullYear().toString().substr(2),
				hour: dateObj.getHours(),
				minute: dateObj.getMinutes()
			};
			return dateArr;
		},
		__unixTimestampToDateStr: function(timestamp){
			var dateObj = new Date(timestamp+'000');
			return dateObj.getDate()+'.'+(dateObj.getMonth()+1)+'.'+(dateObj.getFullYear().toString().substr(2))+' '+dateObj.getHours()+':'+dateObj.getMinutes();
		},
		__dateToTimestamp: function(date){
			var resultArray = date.match(/([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{1,2})\s([0-9]{1,2}):([0-9]{1,2})/);
			if(resultArray){
				var dateObj = new Date('20'+resultArray[3], resultArray[2], resultArray[1], resultArray[4], resultArray[5]);
				var stamp = dateObj.getTime();
				return stamp.toString().substring(0, 10);
			}
			return false;
		},
		showTimeSelector: function(direction){
			var css = {'display':'block', 'opacity':0};
			if(!direction || direction == 'historyBack'){
				css.bottom = '0px';
				css.top = '';
				objects.formType.val('from');
			}else{
				css.top = '0px';
				css.bottom = '';
				objects.formType.val('to');
			}
			objects.timeSelector.stop().css(css).animate({'opacity':1}, 150, function(){
				$(document).on('click.tselector', function(e){
					if($(e.target).parents('.clockHolder').length <= 0){
						methods.hideTimeSelector();
					}
				});
			});
		},
		__showTooltip: function(){
			if(objects.tooltip != null)
				objects.tooltip.css({'top':offset.top+(-1*(objects.tooltip.outerHeight()/2-elheight/2))+'px', 'right':'57px'}).stop().animate({'opacity':1}, 100, function(){
					objects.tooltip.css('opacity', 1);

					var wrapper = objects.historyWrapper;
					wrapper.off('mouseleave.tooltip').one('mouseleave.tooltip', function(){
						objects.wrappertimer = setTimeout(function(){methods.removeTooltip();}, 600);
					});

					$(document).off('click.tooltip').one('click.tooltip', function(e){
						if($(e.target).parent('.historyWrapper').length <= 0)
							methods.removeTooltip();
					});
				});
		},
		hideTimeSelector: function(){
			var css = {'display':'none', 'opacity':0};
			objects.timeSelector.stop().animate({'opacity':0}, 150, function(){$(this).css(css);});
			$(document).off('.tselector');
		},
		initTooltip: function(){
			if(objects.tooltip){objects.tooltip.stop().animate({opacity:0},100, function(){objects.tooltip.css('opacity', 0);});}
			$tooltip = '<div id="timeMachineTooltip" class="history tooltip" style="right:57px;opacity:0;filter:alpha(opactity=0);">'+
				'<a href="#photo" onclick="$.alert(\'trololo\', {type:\'modal\'});return false;"><img class="icon photo" src="images/spacer.gif" title="" /><span class="photo"></span></a>'+
				'<a href="#ups"><img class="icon ups" src="images/spacer.gif" title="" /><span class="ups"></span></a>'+
				'<a href="#article"><img class="icon article" src="images/spacer.gif" title="" /><span class="article"></span></a>'+
				'<a href="#video"><img class="icon video inactive" src="images/spacer.gif" title="" /><span class="video"></span></a>'+
				'<a href="#user"><img class="icon user inactive" src="images/spacer.gif" title="" /><span class="user"></span></a>'+
				'<a href="#comment"><img class="icon comment inactive" src="images/spacer.gif" title="" /><span class="comment"></span></a>'+
				'</div>';
			if(objects.tooltip == null){
				$('body').prepend($tooltip);
				objects.tooltip = $("#timeMachineTooltip");

				objects.tooltip.on('mouseleave.tooltip', function(){
					objects.tooltiptimer = setTimeout(function(){methods.removeTooltip();}, 300);
				});

				objects.tooltip.on('mouseenter.tooltip', function(){
					if(objects.tooltiptimer != null){
						clearTimeout(objects.tooltiptimer);
						objects.tooltiptimer = null;
					}
					if(objects.wrappertimer != null){
						clearTimeout(objects.wrappertimer);
						objects.wrappertimer = null;
					}
				});
			}
		},
		fillTooltip: function(data){
			var $tooltip = objects.tooltip;
			if($tooltip != null){
				$tooltip.find('span.photo').text('1');
				$tooltip.find('span.ups').text('2');
				$tooltip.find('span.article').text('3');
				$tooltip.find('span.video').text('');
				$tooltip.find('span.user').text('');
				$tooltip.find('span.comment').text('');
			}
			methods.__showTooltip();
		},
		removeTooltip: function(){
			if(objects.tooltip != null){
				objects.tooltip.stop().animate({opacity:0},100, function(){
					$(this).remove();
					objects.collection.removeClass('active');
				});
			}
			objects.tooltip = null;
			if(objects.timer != null){
				clearTimeout(objects.timer);
				objects.timer = null;
			}
		},
		__resize: function(){
			var wheight = $(window).height()-122,
				$collection = objects.collection;

			if($collection.length > 0){
				var elheight = wheight/$collection.length;
				$collection.css({'height':elheight, 'lineHeight':elheight+'px'});
			}
		},
		timeCallback: function(result){
			objects.fader.remove();
			if(result.newslent.status == 0){
				$("div.mCSB_container").empty().append(result.newslent.body);
				$.c("aside.rightSide > .activitiesList").mCustomScrollbar("update");
				$.alert("Получено новостей: "+result.newslent.countitems, {type:'info', autohide: true});
			}
			
			if(result.datelent.body != ''){
				objects.collection.remove();
				objects.historyWrapper.find('a.historyForward').after(result.datelent.body);
				
				var $collection = objects.historyWrapper.find('a.item');
				objects.collection = $collection;
				objects.historyWrapper.data('timeMachine',{collecton:$collection});
				methods.__resize();
			}
		}
	};

	$.fn.timeMachine = function(method){
		if(methods[method]){
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		}else if(typeof method === 'object' || !method){
			return methods.init.apply(this, arguments);
		}else{
			$.error('Method '+method+' does not exist in jQuery.timeMachine');
		}
	};
}(window, document, jQuery));
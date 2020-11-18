						<div id="clockHolder" class="clockHolder">
							<div class="date">
								<span><a href="#setDate"><img src="{php} echo base_url(); {/php}images/spacer.gif" class="icon calendar" alt="" title="" /></a>Дата</span>
								<div class="calendarItem">
									<a href="#up" class="arrowUp"><img src="{php} echo base_url(); {/php}images/spacer.gif" alt="" title="" class="icon dateArrowUp"/></a>
									<a href="#down" class="arrowDown"><img src="{php} echo base_url(); {/php}images/spacer.gif" alt="" title="" class="icon dateArrowDown"/></a>
									<span class="day">{$timemashine_today_d}</span>
								</div>
								<div class="calendarItem">
									<a href="#up" class="arrowUp"><img src="{php} echo base_url(); {/php}images/spacer.gif" alt="" title="" class="icon dateArrowUp"/></a>
									<a href="#down" class="arrowDown"><img src="{php} echo base_url(); {/php}images/spacer.gif" alt="" title="" class="icon dateArrowDown"/></a>
									<span class="month">{$timemashine_today_m}</span>
								</div>
								<div class="calendarItem">
									<a href="#up" class="arrowUp"><img src="{php} echo base_url(); {/php}images/spacer.gif" alt="" title="" class="icon dateArrowUp"/></a>
									<a href="#down" class="arrowDown"><img src="{php} echo base_url(); {/php}images/spacer.gif" alt="" title="" class="icon dateArrowDown"/></a>
									<span class="year">{$timemashine_today_y}</span>
								</div>
							</div>
							<div class="clock clearfix">
								<span><a href="#setTime"><img src="{php} echo base_url(); {/php}images/spacer.gif" class="icon clock" alt="" title="" /></a>Время</span>
								<div class="calendarItem">
									<a href="#up" class="arrowUp"><img src="{php} echo base_url(); {/php}images/spacer.gif" alt="" title="" class="icon dateArrowUp"/></a>
									<a href="#down" class="arrowDown"><img src="{php} echo base_url(); {/php}images/spacer.gif" alt="" title="" class="icon dateArrowDown"/></a>
									<span class="hour">{$timemashine_today_h}</span>
								</div>
								<div class="devider">:</div>
								<div class="calendarItem">
									<a href="#up" class="arrowUp"><img src="{php} echo base_url(); {/php}images/spacer.gif" alt="" title="" class="icon dateArrowUp"/></a>
									<a href="#down" class="arrowDown"><img src="{php} echo base_url(); {/php}images/spacer.gif" alt="" title="" class="icon dateArrowDown"/></a>
									<span class="minute">{$timemashine_today_mm}</span>
								</div>
							</div>
							<input type="submit" value="OK" />
						</div>
						<a href="#" class="historyForward"><img src="{php} echo base_url(); {/php}images/spacer.gif" class="icon arrowup" alt="" title="" /></a>
							{include file="profile/rightTimeMashineWrapperDates.tpl"}
						<a href="#" class="historyBack"><img src="{php} echo base_url(); {/php}images/spacer.gif" class="icon arrowdown" alt="" title="" /></a>
					</div>

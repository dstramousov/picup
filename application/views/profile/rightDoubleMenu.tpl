<a href="#" class="active"><img src="{php} echo base_url(); {/php}images/spacer.gif" class="icon news" alt="" title="Новости" /></a>
<a href="#"><img src="{php} echo base_url(); {/php}images/spacer.gif" class="icon home" alt="" title="Домой" /></a>
<a href="#"><img src="{php} echo base_url(); {/php}images/spacer.gif" class="icon calendar" alt="" title="Календарь" /></a>
<span class="title">
	<a href="#historyForward" onclick="$(this).timeMachine('showTimeSelector', 'historyForward');return false;" class="color green decnone historyForward">
		{$timemashine_startdate}
	</a>
	&nbsp;-&nbsp;
	<a href="#historyBack" onclick="$(this).timeMachine('showTimeSelector', 'historyBack');return false;" class="color red decnone historyBack">
		{$timemashine_todaydate}
	</a>
</span>
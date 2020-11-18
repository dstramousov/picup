<form action="?" method="post" enctype="multipart/form-data" name="sa_entity" id="sa_entity">
<input type="hidden" name="action" value="update_article">
<input type="hidden" name="article_id" value="[% $article_id %]">

<table class="table-box" align="center" cellpadding="2" cellspacing="2" border="1" width="100%">

	{foreach from=$obj key=k item=i}
		<tr>				
			<td align="left">{$i.display_lable}</td>
			<td>{$i.input}</td>
		</tr>
	{/foreach}

<tr>
	<td></td>
	<td>
		<input type="submit" value="Записать">
		<input type="button" value="Отменить" onclick="JavaScript: window.history.back()">	
	</td>
</tr>
</table>

{$obj_js}

</form>
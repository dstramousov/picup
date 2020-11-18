{* 
	<a href="{php}echo base_url(); {/php}admin/view/{$current_model}/order_by={$several_objects_header_information[iter]}">{ $several_objects_header_information_uf[iter] }</a>  
*}

<form action="{php} echo base_url();{/php}/admin/view/{$current_model}" method="post" name="filtrator">

<table class="table-box" align="center" cellpadding="2" cellspacing="2" border="0" width="100%">
<tr>
	<td style="padding:10px">
		<h4>Просмотр { $current_model }</h4>
	</td>
</tr>
</table>

<table class="table-box" align="center" cellpadding="2" cellspacing="2" border="0" width="100%">

{ if empty($several_objects_data) }

<tr>
	<td class="empty">
		{include file="admin/search_admin_main_applet.tpl"}
		<br/ >
		<br/ >
		Данных в таблице <b>{ $current_model }</b> не обраруженно.
	</td>
</tr>

{ else }



<tr>
	<td colspan={$several_objects_header_information_count_th+1} class="cnt">
		<!-- <form action="{php} echo base_url();{/php}/admin/view/{$current_model}" method="post" name="filtrator"> -->
			{include file="admin/search_admin_main_applet.tpl"}
			<br/ >
			<br/ >
			<input type=hidden name="action" value="filtratoraction">
			{ $nav_str }
		<!-- </form>  -->
	</td>
</tr>
<tr>
	<td colspan={$several_objects_header_information_count_th+1} class="lft">
		<input type="button"  onClick="location='{php}echo base_url();{/php}admin/edit/{$current_model}'" value="Добавить" />
	</td>
</tr>

<tr class="tb-hdr">

{ section name=iter loop=$several_objects_header_information_uf }
		
		{ if $several_objects_header_information_uf[iter]|is_array }
			<th class="tb-hdr_no_order_by" nowrap="nowrap">
			{$several_objects_header_information_uf[iter].title}
			</th>	
		{else}
			<th class="tb-hdr" nowrap="nowrap">
			{ include file="admin/browse_title_order_dir.tpl" order_by=$order_by item_name="$several_objects_header_information[iter]" item_title="$several_objects_header_information_uf[iter]" }
			</th>	
		{/if}
{ /section }
	<th class="tb-hdr" width="40px">действия</th>
</tr>

<!--<form name="frm_browse_items" method="post">-->
<!--<input type=hidden name="action" value="">-->

{ section name=iter loop=$several_objects_data }
<tr class="{ cycle values="tb-even,tb-odd" }">
	{ section name=internal_iter loop=$several_objects_data[iter] }
		{ assign var="fieldname" value=$several_objects_header_information[$smarty.section.internal_iter.index] }		
		{ if $several_objects_data[iter].$fieldname|is_array }
			<td class="{ $several_objects_data[iter].$fieldname.class }" bgcolor="{ $several_objects_data[iter].$fieldname.color }" width="{ $several_objects_data[iter].$fieldname.width }px">
				{ $several_objects_data[iter].$fieldname.val }
			</td>
		{else}
			<td class="tb-cnt" width="100px">
				{ $several_objects_data[iter].$fieldname }
			</td>
		{/if}				
	{ /section }
	
	{ assign var="fieldname" value="id" }
	
    <td class="tb-cnt" "nowrap">
		{ if $several_objects_data[iter].$fieldname|is_array }			
			<a class=action href="{php}echo base_url();{/php}admin/edit/{$current_model}/{$several_objects_data[iter].$fieldname.val}" title="{ $str_operation_edit } { $str_singular_record }"><img src="{php}echo base_url();{/php}images/action_edit.jpg" width="16" height="16" border="0" alt="{ $str_action_edit } { $str_singular_record }"  /></a>
			<a class=action href="{php}echo base_url();{/php}admin/delete/{$current_model}/{$several_objects_data[iter].$fieldname.val}" title="{ $str_operation_delete } { $str_singular_record }"><img src="{php}echo base_url();{/php}images/action_delete.jpg" width="16" height="16" border="0" alt="{ $str_action_delete } { $str_singular_record }"  /></a>
		{else}
			<a class=action href="{php}echo base_url();{/php}admin/edit/{$current_model}/{$several_objects_data[iter].id}" title="{ $str_operation_edit } { $str_singular_record }"><img src="{php}echo base_url();{/php}images/action_edit.jpg" width="16" height="16" border="0" alt="{ $str_action_edit } { $str_singular_record }"  /></a>
			<a class=action href="{php}echo base_url();{/php}admin/delete/{$current_model}/{$several_objects_data[iter].id}" title="{ $str_operation_delete } { $str_singular_record }"><img src="{php}echo base_url();{/php}images/action_delete.jpg" width="16" height="16" border="0" alt="{ $str_action_delete } { $str_singular_record }"  /></a>
		{/if}						
    </td>
</tr>
{ /section }

<!--</form>-->

<tr>
	<td colspan={$several_objects_header_information_count_th+1} class="cnt">
		{ $nav_str }
	</td>
</tr>

{ /if }
</table>

</form>
    	<table align="center" cellpadding="2" cellspacing="2" border="0" width="100%">
		    <tr>
			    <td class="filter-rht" width="120px">
			        Сортировать по:
				</td>
				<td class="filter-lft" >
				    <select name="filter_by" id="filter_by">
						{ section name=iter loop=$several_objects_where_information }
							{ if $several_objects_where_information_uf[iter]|is_array }
								{if $several_objects_where_information[iter] == $filter_by}
									<option selected  value="{$several_objects_where_information[iter]}">{$several_objects_where_information_uf[iter].title}</option>
								{else}
									<option value="{$several_objects_where_information[iter]}">{$several_objects_where_information_uf[iter].title}</option>
								{/if}
							{else}
								{if $several_objects_where_information[iter] == $filter_by}
									<option selected value="{$several_objects_where_information[iter]}">{$several_objects_where_information_uf[iter]}</option>
								{else}
									<option value="{$several_objects_where_information[iter]}">{$several_objects_where_information_uf[iter]}</option>
								{/if}
							{/if}
						{ /section }
						{* html_options values=$several_objects_header_information output=$several_objects_header_information_uf selected=$filter_by *}
				    </select>
				    <input type="text" name="filter" value="{$filter}" class="in200" />
			    </td>
			    <td class="filter-lft">
					<input type="submit" class="button" value="Искать" />
				</td>
				<td class="filter-lft">
							Записей на страницу:&nbsp;
					    <select name="rows" onchange="this.form.submit()">
							{ html_options values=$rows_set_val output=$rows_set_out selected=$rows }
					    </select>
				</td>
    		</tr>
    	</table>

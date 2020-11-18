<form id="login" action="{php}echo base_url();{/php}admin/googlemapapi/search" enctype="multipart/form-data" method="post"> 
	<div id="inputdata1" style="padding:10px;">
	<div id="inputdata12" ><h3>Поиск по адресу</h3></div>
		<table border="0" style="width: 100%;">
			<td>
				<input style="width: 100%;" name="addresssearch" value="{$val_addresssearch}" />
			</td></tr>
		</table>
    </div>
	<div id="inputdata3" style="padding:10px;">
		<div id="inputdata12" ><h3>Поиск по координатам</h3></div>
					Широта
					<input name="latitude" value="{$val_latitude}" /><br/><br/>
					Долгота
					<input  name="longitude" value="{$val_longitude}" />
    </div>	
	<div id="inputdata4" style="padding:10px;">
		<input value="Искать" type="submit"></td>		
		
	</div>
</form>

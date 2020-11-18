<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset={$site_charset}" />
	<meta name="description" content="{$logo_full}" />
	<meta http-equiv="Expires" content="Thu, Jan 1 1970 00:00:01 GMT" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Cache-Control" content="no-cache" />
	<meta name="keywords" content="{$site_keywords} {$site_add_keywords}" />
	<meta name="robots" content="all-index" />
	<meta name="revisit-after" content="1 days" />
	<meta name="distribution" content="global" /> 
	<meta name="rating" content="general" />
	<meta name="content-language" content="russian" />
	<meta name="author" content="{$author}" />
	<meta name="copyright" content="{$copyright}" />

	<link rel="shortcut icon" type="image/x-icon" href="{$favicon}" />

	{$additional_js}
	
	{$additional_css}

	
{$map->printHeaderJS()}
{$map->printMapJS()}

{literal}	
    <style type="text/css">
      v\:* {
        behavior:url(#default#VML);
      }
    </style>
{/literal}	

</head>
<body onload="onLoad()">
	<div id="toppanel">
		{include file="admin/main_menu.tpl"}
    </div>
	{$content}
	<!--
	<form id="login" action="{php}echo base_url();{/php}admin/googlemapapi/search" enctype="multipart/form-data" method="post"> 
	<div id="inputdata1" style="padding:10px;">
		<table border="0" style="width: 100%;">
			<tr><td style="width: 10%;">
				Поиск по адресу
			</td><td>
				<input style="width: 100%;" name="addresssearch" value="{$val_addresssearch}" />
			</td></tr>
		</table>
    </div>
	<div id="inputdata2" style="padding:10px;">
		<table border="0" style="width: 100%;">
			<tr><td style="width: 10%;">
				Наши места
			</td><td>
				<input style="width: 100%;" name="outplaces" value="{$val_outplaces}" />
			</td></tr>
		</table>
    </div>
	
	<div id="inputdata3" style="padding:10px;">
		<table border="0" style="width: 100%;">
			<tr>
				<td style="width: 70px;">
					Широта
				</td>
				<td>
					<input name="latitude" value="{$val_latitude}" />
				</td>
				<td style="width: 70px;">
					Долгота
				</td>
				<td>
					<input  name="longitude" value="{$val_longitude}" />
				</td>
			</tr>
		</table>
    </div>	
	<div id="inputdata4" style="padding:10px;">
		<input value="Искать" type="submit"></td>
	</div>
	</form>
-->	
<table border="1">
<tr><td>
    {$map->printMap()}
</td><td>
	{$map->printSidebar()}
</td></tr>
</table>

</body>
</html>

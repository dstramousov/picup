<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
    	<base href="/" />
        <meta charset="{$site_charset}" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>{$title}</title>
        <meta name="description" content="{$logo_full}" />
        <meta name="viewport" content="width=device-width" />
        
        {$additional_css}
        {$additional_js}
        
       <link rel="shortcut icon" type="image/x-icon" href="{$favicon}" />
	   
		{literal}
		<script>
			var mainObj = {
				timemachine:{
					from: '{/literal}{$timemashine_header_from}{literal}',
					to: '{/literal}{$timemashine_header_to}{literal}'
				}
			};
		</script>
		{/literal}

    </head>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="UTF8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>PiсUP :: Личный кабинет</title>
        <meta name="description" content="PicUp - лучший фото-шаринг" />
        <meta name="viewport" content="width=device-width" />
								
        <script type="text/javascript" src="http://localhost/picup/js/modernizr.js"></script> 
        
       <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
    </head> 
    
<body id="page">

<table border="0" cellpadding="0" cellspacing="0" align="center">
<tr valign="middle">
	<td>
		<form action="<?php echo base_url(); ?>setup/delete_table" method="post">
        <input type="image" alt="Удалить все таблицы из базы данных" title="Удалить все таблицы из базы данных" src="<?php echo base_url(); ?>images/setup/delete_table.jpg" style="border:none;" /> 
		</form>
	</td>
	<td>
		<form action="<?php echo base_url(); ?>setup/create_table" method="post">
		<input type="image" alt="Создать все таблицы в базе данных" title="Создать все таблицы в базе данных" src="<?php  echo base_url(); ?>images/setup/create_table.jpg"  style="border:none;" /> 
		</form>
	</td>	
	<td>
		<form action="<?php echo base_url(); ?>setup/update_table" method="post">
		<input type="image" alt="Обновить данные о таблицах" title="Обновить данные о таблицах" src="<?php  echo base_url(); ?>images/setup/update_table.jpg"  style="border:none;" /> 
		</form>
	</td>
	<td>
		<form action="<?php echo base_url(); ?>setup/insert_test" method="post">
		<input type="image" alt="Вставить тестовые данные в базу данных" title="Вставить тестовые данные в базу данных" src="<?php  echo base_url(); ?>images/setup/insert_test.jpg"  style="border:none;" /> 
		</form>
	</td>
    <td>
		<form action="<?php echo base_url(); ?>setup/insert_init" method="post">
		<input type="image" alt="Вставить первоначальные данные в базу данных" title="Вставить первоначальные данные в базу данных" src="<?php  echo base_url(); ?>images/setup/insert_init.jpg" style="border:none;" /> 
		</form>
	</td>
</tr>            
<tr><td colspan="5"><div align="center"><h3><?php echo $msg;?></h3></div></td></tr>
</table>
<!--{ include file="common/footer.tpl" } -->

</body>
</html>

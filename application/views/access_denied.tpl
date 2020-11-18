<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<title>Access dinied - Error</title>
	</head>

{literal}
<style type="text/css">

body {
background:#0000aa;
color:#ffffff;
font-family:courier;
font-size:12pt;
text-align:center;
margin-top: 100px;
}

#wrapper {
width: 900px; 
height: auto; 
margin: 0 auto;  
}

.error {
background:#fff;
color:#0000aa;
padding:2px 8px;
font-weight:bold;
}

p {
margin:30px 100px;
text-align:left;
}

a,a:hover {
color:inherit;
font:inherit;
}

a:hover {
color: #fff000;
}

.links {
text-align:center;
margin-top:30px;
}

</style>{/literal}

<body>
<span class="error">!! ACCESS DINIED !!</span>
<div id="wrapper">
<p>
Wooah! You have tried to open not allowed URL.<br />
This is wrong. <u>Please return to previous page</u>: <br />
</p>
</div>
<div class="links">
<a href="{php} echo base_url(); {/php}home">Home</a> 
</div>
</body>

</html>
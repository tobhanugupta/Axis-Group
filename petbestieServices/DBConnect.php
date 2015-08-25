<?php

$link = mysql_connect("localhost","tinkerto_shams","p@ssw0rd");
if($link) {
 $GLOBALS['link']=$link;
	if(!mysql_select_db('tinkerto_petbestie_app_db',$link)){
		echo "<h1>Invalid Database Selected.</h1>";
		die; 
	}
	
} else { 
	echo "<h1>Database Connection Error.</h1>";
	die;
}

//define("BASEURL","http://localhost/petbestie");
//define("FORGET_PASSWORD_BASEURL","http://localhost/petBestieForgetPassword");

define("BASEURL","https://tinkertoyz.net/petbestieServices");
define("FORGET_PASSWORD_BASEURL","https://tinkertoyz.net/petBestieForgetPassword");
?>

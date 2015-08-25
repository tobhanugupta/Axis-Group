<?php

$link = mysql_connect("localhost","phbjhqjx",",)W!YcCsOzUu");
if($link) {
 $GLOBALS['link']=$link;
	if(!mysql_select_db('phbjhqjx_book_of_accounts_live_db',$link)){
		echo "<h1>Invalid Database Selected.</h1>";
		die; 
	}
	
} else { 
	echo "<h1>Database Connection Error.</h1>";
	die;
}
?>
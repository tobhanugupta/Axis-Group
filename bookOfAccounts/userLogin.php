	<?php
	/* 
		Webservice for User Login.
		Project Name :  Book of Accounts
		Created By   : MD. Shamsad Ahmed
        Created Date : 10th September 2014
		Usage        : This file is used for login user with Book of Accounts .
		How it works : We simply take Username and Password from user and matches with our db entry 
		               and give success and error responses accordingly.
			Copyright@Techila Solutions
	*/
		
	//Database Connection
	//ini_set( "display_errors", 0);
	include_once('DBConnect.php');
	include_once('Abstract/classResponse.php');	
	function getUserLoginDetails()
	{
		$USERNAME = trim($_REQUEST['userName']); //Get Request From Device
		$PASSWORD = trim($_REQUEST['passWord']);
		$DEVICEID = $_REQUEST['DeviceId'];
		$rm=new Response_Methods();		
		$getArrayList = array();
		//echo $ENCRYPTEDPWD = md5($PASSWORD);	
		//echo $ENCRYPTEDPWD = base64_decode($PASSWORD);	
		//$ENCRYPTEDPWD=$PASSWORD;
		if( $USERNAME == "" || $PASSWORD == "" )
		{
			$result = $rm->fields_validation();
			return $result;
		}
		else
		{
		   $result = $rm->login_success($USERNAME,$PASSWORD,$DEVICEID);
		   return $result;
		}
	}		
	
	echo getUserLoginDetails();
?>
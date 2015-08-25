	<?php
	error_reporting(0);
	/* 
		Webservice for User Login.
		Project Name :  PetBestie
		Created By   : MD. Shamsad Ahmed
        Created Date : 06th June 2015
		Usage        : This file is used for login user with Book of Accounts .
		How it works : We simply take Username and Password from user and matches with our db entry 
		               and give success and error responses accordingly.
			Copyright@Techila Solutions
	*/
		
	//Database Connection
	//ini_set( "display_errors", 0);
	include_once('DBConnect.php');
	include_once('Abstract/classResponse.php');	
	function forgetPassword() {
//die('test');
		$rm=new Response_Methods();		
		if($_SERVER['REQUEST_METHOD']=="GET")
		{
		$result=$rm->inValidServerMethod();
		return $result;
		}
		
		
		//Check request url is https or not
       if(!empty($_SERVER["HTTPS"])){
			
			if($_SERVER["HTTPS"]!=="off") {
			
			
		$EMAILID = $rm->cleanData(trim($_POST['emailId']));
		
        if($EMAILID == "")
		{			
			$result = $rm->fields_validation();
			return $result;
		}
		
		else
		{
		
			$checkEmail=$rm->checkUNEmail($EMAILID);
			if ($checkEmail['status'] == false)
				{
					$result=$rm->emailNotAvailable();
					return $result;
				}
			else
				{
				//print_r($checkEmail);
				//die();
				 $result = $rm->sendPasswordEmail($checkEmail['user_id']);
				 return $result; 
				 }
	         }
}
		else {
			
				$result = $rm->ssl_error();
			    return $result;
			}
			
		} 
		else {
		      
			  $result = $rm->ssl_error();
			  return $result;
		}
									
		
   }	

echo forgetPassword();
?>

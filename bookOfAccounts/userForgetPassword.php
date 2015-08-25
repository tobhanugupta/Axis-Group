<?php
	/**
		* Forget Password Page.
	
	*/
	include_once('DBConnect.php'); //Database Connection
	include_once('Abstract/classResponse.php');//
	
	
function userForgetPassword() {
    
		$rm=new Response_Methods();	
		$EMAILID = $_REQUEST['emailId'];
        if($EMAILID == "")
		{			
			$result = $rm->fields_validation();
			return $result;
		}
    
		else
		{
			 $result = $rm->userForgetPassword($EMAILID); 
			 return $result; 
		}
}

echo userForgetPassword();
?>

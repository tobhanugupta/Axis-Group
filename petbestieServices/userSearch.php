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
	function userSearchList()
	{		
		
		$rm=new Response_Methods();		
		if($_SERVER['REQUEST_METHOD']=="GET")
		{
		$result=$rm->inValidServerMethod();
		return $result;
		}
		
	//Check request url is https or not
       if(!empty($_SERVER["HTTPS"])){
			
			if($_SERVER["HTTPS"]!=="off") {
			
			
			if(isset($_POST['searchType']) && $_POST['searchType']!='')
				{
				$searchType = trim($_POST['searchType']); //Get Request From Device	(trending, userName, petType)				
				}
				else
				$searchType="trending";
				
				if($searchType!="trending")
				{
				if(!isset($_POST['searchValue']) && $_POST['searchValue']=='')
						{
						$result = $rm->fields_validation();
						return $result;
						}
				}	
		
			$USERID=$_POST['userId'];//for the user who is making the search to not display his profile in the list
		    $searchValue=$_POST['searchValue'];
			
			$getArrayList = array();
	
				if($searchType == "" || $USERID=="")
				{
					$result = $rm->fields_validation();
					return $result;
				}
				else
				{
				   $result = $rm->getAllUsers($searchType,$USERID,$searchValue);
				   return $result;
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
	
	echo userSearchList();
?>
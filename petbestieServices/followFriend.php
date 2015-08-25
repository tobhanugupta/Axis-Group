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
	function followFriend()
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
			
		 $userId = trim($_POST['userId']);
		 $friendId = trim($_POST['friendId']);
		
				
		if($userId == "" || $friendId == "")
		{
			
			$result = $rm->fields_validation();		
			return $result;
		}
		
		else if($userId == $friendId)
		{
			
			$result = $rm->friendFollowSame();		
			return $result;
		}
		
		else
		{
		   	date_default_timezone_set('Asia/Calcutta'); 
            $createdDate=date('Y-m-d H:i:s');
			$getList = array();
			
			$checkFollow=$rm->checkExistingFollow($userId, $friendId);
			if($checkFollow>0)
			{
			$result = $rm->followAlreadyExists();		
			return $result;
			}
			
			//preparing list and inserting values in friends_t table
			
			$getInsertFieldValue['follower_user_id_fk']=$userId;	
			$getInsertFieldValue['following_user_id_fk']=$friendId;
			$getInsertFieldValue['follow_date_f']=$createdDate;								
								
			$lastInserted_follow_id=$rm->insert_record($getInsertFieldValue,'follow_t');
			
			if(!empty($lastInserted_follow_id))
					{
					$result=$rm->makeFollowSuccess($userId,$friendId);
					return $result;												
					}
					
					else
					{
					$result=$rm->makeFollowFail();
					return $result;	
					}
				
		}// end of else first
		
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
	
	echo followFriend();
?>
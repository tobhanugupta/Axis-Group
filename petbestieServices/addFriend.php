	<?php
	error_reporting(0);
	/**
		* Get User Details and Add into database.
		* Created by: MD. Shamsad Ahmed
	
	*/
	include_once('DBConnect.php'); //Database Connection
	include_once('Abstract/classResponse.php');//
	function addFriendRequest()
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
			
			$result = $rm->friendRequestFailforSameUser();		
			return $result;
		}
		
		else
		{
		
		
		    $checkFriend=$rm->checkExistingFriendShip($userId, $friendId);
			if($checkFriend>0)
			{
			$result=$rm->friendshipAlreadyExists();
			return $result;	
			}
			
			date_default_timezone_set('Asia/Calcutta'); 
            $createdDate=date('Y-m-d H:i:s');
			$getList = array();
			
			//preparing list and inserting values in friends_t table
			
			$getInsertFieldValue['friend_one']=$userId;	
			$getInsertFieldValue['friend_two']=$friendId;
			$getInsertFieldValue['created_date_f']=$createdDate;								
								
			$lastInserted_friend_id=$rm->insert_record($getInsertFieldValue,'friends_t');
			
			if(!empty($lastInserted_friend_id))
					{
					$result=$rm->friendRequestSuccess();
					return $result;												
					}
					
					else
					{
					$result=$rm->friendRequestFail();
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
	echo addFriendRequest();
?>
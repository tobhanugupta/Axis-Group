	<?php
	error_reporting(0);
	/**
		* Get User Details and Add into database.
		* Created by: MD. Shamsad Ahmed
	
	*/
	include_once('DBConnect.php'); //Database Connection
	include_once('Abstract/classResponse.php');//
	function confirmFriendRequest()
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
			    $sqlConfirmFriend="UPDATE friends_t SET status='1' WHERE (friend_one=$userId OR friend_two=$userId)AND(friend_one=$friendId OR friend_two=$friendId)";
			  if(mysql_query($sqlConfirmFriend,$GLOBALS['link']))
				{
				  $result=$rm->friendRequestConfirmed();
				  return $result;	
				}
			  else
				{
				  $result=$rm->friendRequestFailed();
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
	
	echo confirmFriendRequest();
?>
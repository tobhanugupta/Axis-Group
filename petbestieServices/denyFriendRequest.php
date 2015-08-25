	<?php
	error_reporting(0);
	/**
		* Get User Details and Add into database.
		* Created by: MD. Shamsad Ahmed
	
	*/
	include_once('DBConnect.php'); //Database Connection
	include_once('Abstract/classResponse.php');//
	function denyFriendRequest()
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
			    $sqlDenyFriend="delete from friends_t where status='0' and (friend_one=$userId OR friend_two=$userId)AND(friend_one=$friendId OR friend_two=$friendId)";
				mysql_query($sqlDenyFriend,$GLOBALS['link']);
				$affectedRows = mysql_affected_rows();
			  if($affectedRows>0)
				{
				  $result=$rm->friendDeniedSuccess();
				  return $result;	
				}
			  else
				{
				  $result=$rm->friendDeniedFailed();
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
	
	echo denyFriendRequest();
?>
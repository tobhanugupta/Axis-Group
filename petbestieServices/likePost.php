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
	function likePost()
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
			
		$USERID = trim($_POST['userId']); //Get Request From Device
		$POSTID = trim($_POST['postId']); //Get Request From Device
		
		$getArrayList = array();
	
		if( $USERID == "" || $POSTID == "")
		{
			$result = $rm->fields_validation();
			return $result;
		}
		else
		{
                   
                   $checkLike=$rm->checkAlreadyLiked($USERID,$POSTID);
		   if($checkLike>0)
			{
			$result=$rm->alreadyLiked();
			return $result;	
			}
		   date_default_timezone_set('Asia/Calcutta'); 
                   $user_owner_id=$rm->idToValue('user_id_fk','news_feeds_t','post_id',$POSTID); //getting user owner id
                   $createdDate=date('Y-m-d H:i:s');
		   $getInsertFieldValue['user_id_fk']=$USERID;	
		   $getInsertFieldValue['post_id_fk']=$POSTID;	
                   $getInsertFieldValue['user_owner_id_fk']=$user_owner_id;	
		   $getInsertFieldValue['likeDate_f']=$createdDate;	
		   $lastInserted_user_id=$rm->insert_record($getInsertFieldValue,'likes_t');
		   if(!empty($lastInserted_user_id))
		   {
		   $sqlLikeCountUpdate="update news_feeds_t set like_count_f= like_count_f+1 where post_id=$POSTID";
		   mysql_query($sqlLikeCountUpdate,$GLOBALS['link']);
		   $result=$rm->getParticularPost($POSTID,$USERID);
		   return $result;
		   }
		   else
		   {
		   $result=$rm->likeUnSuccessful();
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
	
	echo likePost();
?>
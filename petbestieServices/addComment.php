  	<?php
	error_reporting(0);
/*
		Webservice for add  comments.
		Project Name :  PetBestie
		Created By   : MD. Shamsad Ahmed
        Created Date : 04th August 2015
		Usage        : This file is used for login user with Book of Accounts .
		How it works : We simply take Username and Password from user and matches with our db entry 
		               and give success and error responses accordingly.
			Copyright@Techila Solutions
	*/
		
	//Database Connection
	//ini_set( "display_errors", 0);
	include_once('DBConnect.php');
	include_once('Abstract/classResponse.php');	
	function addComment()
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
			
			 
		//date_default_timezone_set('Asia/Calcutta'); 
		$comment =  $rm->cleanData($_POST['comment']); //Get Request From Device		
		$userId = $rm->cleanData($_POST['userId']);
		$postId = $rm->cleanData($_POST['postId']);
		$createdDate=date('Y-m-d H:i:s');
			
	  
       	
		$getArrayList = array();
		//echo $ENCRYPTEDPWD = md5($PASSWORD);	
		//echo $ENCRYPTEDPWD = base64_decode($PASSWORD);	
		//$ENCRYPTEDPWD=$PASSWORD;
		if( $comment == "" || $userId == "" || $postId == "" )
		{
			$result = $rm->fields_validation();
			return $result;
		}
		else
		{
		  
		  
		  	$getInsertFieldValue['comment_text_f']=$comment;	
			$getInsertFieldValue['user_id_fk']=$userId;
			$getInsertFieldValue['post_id_fk']=$postId;
			$getInsertFieldValue['comment_Date']=$createdDate;	
			$lastInsertedCommentId=$rm->insert_record($getInsertFieldValue,'comments_t');
			if($lastInsertedCommentId)
			{
		    $result = $rm->addCommentSuccessJson($postId);
		    return $result;
			}
			else
			{
			$result=$rm->addCommentFailJson();
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
	
	echo addComment();
?>
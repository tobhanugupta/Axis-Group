	<?php
	error_reporting(0);
	/**
		* Get User Details and Add into database.
		* Created by: MD. Shamsad Ahmed
	
	*/
	include_once('DBConnect.php'); //Database Connection
	include_once('Abstract/classResponse.php');//
	function signUpProfile()
	{		
		
		$rm=new Response_Methods();		
		if($_SERVER['REQUEST_METHOD']=="GET")
		{
		$result=$rm->inValidServerMethod();
		return $result;
		}
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);
		$email = trim($_POST['email']);
		$deviceId = trim($_POST['deviceId']);
		$petType = trim($_POST['petType']);
		
		//echo $IMAGE = BASEURL.'/images/';
		/*
				
		
	
		$IMAGE="no data now"
		*/
		
		
			  //Check request url is https or not
       if(!empty($_SERVER["HTTPS"])){
			
			if($_SERVER["HTTPS"]!=="off") {
		$profileId=$rm->rand_str(16, 4 );
		
				
		if($username == "" || $password == "" || $email == "" || $deviceId== "" || $petType=="")
		{
			
			$result = $rm->fields_validation();		
			return $result;
		}
		
		else
		{
			$checkUser=$rm->checkUserValidation($username,'user_name_f');
			$checkEmail=$rm->checkUserValidation($email,'email_f');
			if($checkUser==0)
				{
					$result=$rm->userExistJson();
					return $result;
				}
			if($checkEmail==0)
				{
					$result=$rm->emailExistJson();
					return $result;
				}	
			
		    $status=0;
			date_default_timezone_set('Asia/Calcutta'); 
            $createdDate=date('Y-m-d H:i:s');
			$getList = array();
			
			
			
			
			//preparing list and inserting values in user_details table
			
			$getInsertFieldValue['user_name_f']=$username;	
			$getInsertFieldValue['email_f']=$email;				
			$getInsertFieldValue['device_id_f']=$deviceId;
			$getInsertFieldValue['pet_type_f']=$petType;			
			$getInsertFieldValue['profile_id_f']=$profileId;
			$getInsertFieldValue['join_date_f']=$createdDate;								
								
			$lastInserted_user_id=$rm->insert_record($getInsertFieldValue,'user_details_t');
			
			if(!empty($lastInserted_user_id))
					{
					
					//preparing list and inserting values in login table
					$getInsertLoginDetails['password_f']=$password;
					$getInsertLoginDetails['user_name_f']=$username;	
					$getInsertLoginDetails['user_id_fk']=$lastInserted_user_id;	
					$lastInserted_login_id=$rm->insert_record($getInsertLoginDetails,'login_t');	
					if(empty($lastInserted_login_id))
					{
					$rm->delete('user_details_t','user_id',$lastInserted_user_id);
					$result=$rm->userRegisterFailJson();
					return $result;					
					}
					
					//preparing list and inserting values in friends table
					$getInsertFriendDetails['friend_one']=$lastInserted_user_id;
					$getInsertFriendDetails['friend_two']=$lastInserted_user_id;	
					$getInsertFriendDetails['created_date_f']=$createdDate;
					$getInsertFriendDetails['status']=2;	
					$lastInserted_friend_id=$rm->insert_record($getInsertFriendDetails,'friends_t');
					if(empty($lastInserted_friend_id))
					{
					$rm->delete('login_t','login_id',$lastInserted_login_id);
					$rm->delete('user_details_t','user_id',$lastInserted_user_id);
					$result=$rm->userRegisterFailJson();
					return $result;					
					}
					
					
					$IMAGEURLBASEURL=BASEURL.'/images/';					
					$userImageBaseURL="images/$username";
					
					if (!is_dir($userImageBaseURL)) 
						// is_dir - tells whether the filename is a directory
						{
							//mkdir - tells that need to create a directory
							
							mkdir($userImageBaseURL);
							mkdir($userImageBaseURL.'/profile_pics/');
							mkdir($userImageBaseURL.'/post_photos/');
						}
						
					$rand=rand(00,99999);
					$_REQUEST['profile_pic']=21;
					if(isset($_REQUEST['profile_pic']) and $_REQUEST['profile_pic']!="")
					{
					$profile_pic = trim($_POST['profile_pic']); //blob image data
					$profile_pic="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
FuMy8QyjLGS1uwHh4r//2Q==";
					$img = 'data:image/png;base64,'.$profile_pic.'';
					$img = str_replace('data:image/png;base64,', '', $img);
					$img = str_replace(' ', '+', $img);
					$data = base64_decode($img);
					$image ='petbestie_user_'.$rand.'.png';
					file_put_contents($userImageBaseURL.'/profile_pics/'.$image, $data);	
					//file_put_contents($userImageBaseURL.'/profile_pics/'.$image, $data);	
					$IMAGEURL = $IMAGEURLBASEURL.$username.'/profile_pics/'.$image;		
					
					$getUpdateProfilePic['profile_pic_f']=$IMAGEURL;	
					}
					else
					{
					$getUpdateProfilePic['profile_pic_f']="null";
					}
					$updateResult=$rm->update_record($getUpdateProfilePic,'user_details_t','user_id',$lastInserted_user_id);
					
					$result=$rm->userRegisterSuccessJson($lastInserted_user_id);
					return $result;	
											
					}
					
					else
					{
					$result=$rm->userRegisterFailJson();
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
	
	echo signUpProfile();
?>
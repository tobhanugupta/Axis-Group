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
		//$rm=new Response_Methods();
		
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
					$profile_pic = trim($_POST['profile_pic']); //blob image data
					$img = 'data:image/png;base64,'.$profile_pic.'';
					$img = str_replace('data:image/png;base64,', '', $img);
					$img = str_replace(' ', '+', $img);
					$data = base64_decode($img);
					$image ='petbestie_user_'.$rand.'.png';
					file_put_contents($userImageBaseURL.'/profile_pics/'.$image, $data);	
					//file_put_contents($userImageBaseURL.'/profile_pics/'.$image, $data);	
					$IMAGEURL = $IMAGEURLBASEURL.$username.'/profile_pics/'.$image;		
					
					$getUpdateProfilePic['profile_pic_f']=$IMAGEURL;	
					$updateResult=$rm->update_record($getUpdateProfilePic,'user_details_t','user_id',$lastInserted_user_id);
					
					$result=$rm->userRegisterSuccessJson($lastInserted_user_id);
					return $result;	
											
					}
					
					else
					{
					$result=$rm->userRegisterFailJson();
					return $result;	
					}
				
				
					
				
		}
									
		
   }		
	
	echo signUpProfile();
?>
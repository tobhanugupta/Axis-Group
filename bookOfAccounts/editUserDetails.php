	<?php
	/**
		* Update User Details in Database.
	
	*/
	include_once('DBConnect.php'); //Database Connection
	include_once('Abstract/classResponse.php');//
	function editUserDetails()
	{		
	    //$companyId = $_REQUEST['companyId'];
		$userFname = trim($_REQUEST['userFname']);
		$userLname = trim($_REQUEST['userLname']);
		$age = trim($_REQUEST['age']);
		$sex = trim($_REQUEST['sex']);
		$emailId = trim($_REQUEST['emailId']);
		$address = trim($_REQUEST['address']);
		$userType = trim($_REQUEST['userType']);
		$userID = trim($_REQUEST['userID']);		
		
		/*
		$userImage = trim($_REQUEST['image']);		
		$userImage = trim($_REQUEST['image']['name']);		
		*/
		
						
		$adminId = $_REQUEST['adminId'];		
		$adminId='e-002';// need to be send in request..for time being using hardcoded values.		
		$fullName=$userFname.' '.$userLname;		
		//$Mobile = $_REQUEST['Phone'];		
		$rm=new Response_Methods();				
		if($userFname == "" || $userLname == "" || $age == "" || $sex == "" || $emailId == "" || $address == "" || $userType == "" || $userID == "")
		{
			$result = $rm->fields_validation();		
			return $result;
		}			
		else
		{		
			date_default_timezone_set('Asia/Calcutta'); 
			$createdDate=date('Y-m-d H:i:s');
			$getRegisterFieldValue['user_fname']=$userFname;	
			$getRegisterFieldValue['user_lname']=$userLname;
			$getRegisterFieldValue['user_age']=$age;	
			$getRegisterFieldValue['user_sex']=$sex;
			$getRegisterFieldValue['user_email_id']=$emailId;	
			$getRegisterFieldValue['user_address']=$address;
			$getRegisterFieldValue['last_modified_date']=$createdDate;
			//$lastInserted_user_id=$rm->insert_record($getRegisterFieldValue,'user_details_t');	
			$affectedRows=$rm->update_record($getRegisterFieldValue,'user_details_t','user_id',$userID);
			if($affectedRows>=0)
				{	
				$result=$rm->userModifiedSuccess();
				return $result;				
				}
			else
				{
				$result=$rm->userModifiedFail();
				return $result;
				}	
		}		
   }
	
	echo editUserDetails();
?>
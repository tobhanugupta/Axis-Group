	<?php

	/**

		* Get Member Details and Add into database.

	

	*/

	include_once('DBConnect.php'); //Database Connection

	include_once('Abstract/classResponse.php');//

	function addUserDetails()

	{		

	    //$companyId = $_REQUEST['companyId'];

		$userFname = trim($_REQUEST['userFname']);

		$userLname = trim($_REQUEST['userLname']);

		$age = trim($_REQUEST['age']);

		$sex = trim($_REQUEST['sex']);

		$emailId = trim($_REQUEST['emailId']);

		$address = trim($_REQUEST['address']);

		$userType = trim($_REQUEST['userType']);
		
		$companyID= trim($_REQUEST['companyID']);


		/*

		$userImage = trim($_REQUEST['image']);

		

		$userImage = trim($_REQUEST['image']['name']);

		

		*/

		

		$adminId = $_REQUEST['adminId'];


		

		//$adminId='e-002';// need to be send in request..for time being using hardcoded values.

		

		$fullName=$userFname.' '.$userLname;

		

		//$Mobile = $_REQUEST['Phone'];

		

		$rm=new Response_Methods();

				

		if($userFname == "" || $userLname == "" || $age == "" || $sex == "" || $emailId == "" || $address == "" || $userType == "")

		{

			//$sendMail=$rm->sendMailPasswordDetails($adminEmail,'test14521','sdfwd45487','Asif Anwar');
			
			$result = $rm->fields_validation();
			return $result;

		}

		else
		{
	
			   $status=0;

			date_default_timezone_set('Asia/Calcutta'); 

           		 $createdDate=date('Y-m-d H:i:s');

			//$max_login_id=$rm->getMaxID('login_t','login_user_id');

									

			$passWord_Ran = substr(md5(rand(0, 1000000)), 0, 8);	

			//$ENCRYPTEDPWD = base64_encode($passWord_Ran);	
			$ENCRYPTEDPWD = $passWord_Ran ;					

			$getList = array();

			

			//$getFieldValue['emp_id']=$userName;

			$getInsertFieldValue['password']=$ENCRYPTEDPWD;

			$getInsertFieldValue['usertype']=$userType;						

			$lastInserted_login_user_id=$rm->insert_record($getInsertFieldValue,'login_t');

					

					if(!empty($lastInserted_login_user_id))

					{					

						$threeDigitID=str_pad($lastInserted_login_user_id, 3, "0", STR_PAD_LEFT);	

						$userName="e-".$threeDigitID;

						$sqlUpdate="update login_t set emp_id='$userName' where login_user_id=$lastInserted_login_user_id";

						mysql_query($sqlUpdate,$GLOBALS['link']);

						//$PROFILE_PICTURE = $userData['profilePicture']['name'];

					   /*

						$filTemLoc = $_FILES['image']['tmp_name'];

						$moveresult = move_uploaded_file($filTemLoc,"userPics/".$userImage);

						

						$getRegisterFieldValue['user_image']=$userImage;

						

						user image code commented

						*/

						$getRegisterFieldValue['user_fname']=$userFname;	

						$getRegisterFieldValue['user_lname']=$userLname;

						$getRegisterFieldValue['user_age']=$age;	

						$getRegisterFieldValue['user_sex']=$sex;

						$getRegisterFieldValue['user_email_id']=$emailId;	

						$getRegisterFieldValue['user_address']=$address;

						$getRegisterFieldValue['login_user_id']=$lastInserted_login_user_id;
						$getRegisterFieldValue['company_id']=$companyID;
							

						$getRegisterFieldValue['user_created_date']=$createdDate;
                        $lastInserted_user_id=$rm->insert_record($getRegisterFieldValue,'user_details_t');	

						if(!empty($lastInserted_user_id))

							{
							if($adminId){	
                             			   	$adminEmail=$rm->getAdminEmailID($adminId);
 //$adminEmail="shamsad@techilasolutions.com";
 //die();						      
							$sendMail=$rm->sendMailPasswordDetails($adminEmail,$userName,$passWord_Ran,$fullName);
							  }

							  $result=$rm->userRegisterSuccessJson($lastInserted_user_id);

							   return $result;				

							}

						else

							{

							$result=$rm->userRegisterFailJson();

							return $result;

							}	

						

					}

					else

					{

					$result = $rm->userRegisterFailJson();

					return $result;								

					}

				

				

			}

		

   }		

	

	echo addUserDetails();

?>
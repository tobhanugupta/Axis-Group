	<?php
	/*
	Webservice For Get User Details.
	Created By   : MD. Shamsad Ahmed
	Created Date : 29 September 2014
	Usage        : Get All Users Details.
	Copyright@Techila Solutions
   	*/


	include_once('DBConnect.php'); //Database Connection
	include_once('Abstract/classResponse.php');//
	function getUserDetails()
	{	
		   $companyID= trim($_REQUEST['companyID']);
		   $rm=new Response_Methods();
		   if($companyID== "" )
			{
			$result = $rm->fields_validation();	
			return $result;
			}					
           		$userDetailsArray = array();
			$dataResultSet=$rm->getSpecificDetails($companyID,'user_details_t','company_id');
			//$dataResultSet=$rm->getAllDetails('user_details_t');
			if(mysql_num_rows($dataResultSet) > 0)
				{
					while($row=mysql_fetch_array($dataResultSet))
					{					
					/*					
					$getUserFields['userID']=$row['user_id'];			
					if($row['user_image']!='')					
					$userPic=$row['user_image'];
					else
					$userPic='noImage.jpg';					
					$getUserFields['image']="http://phbjharkhand.in/bookOfAccounts/userPics/$userPic";
					$getUserFields['login_user_id']=$row['login_user_id'];
					$cdate=$row['user_created_date'];
					$getUserFields['createdDate']=date('Y/m/d', strtotime($cdate));
					*/
					$getUserFields['userID']=$row['user_id'];
					$getUserFields['userFname']=$row['user_fname'];
					$getUserFields['userLname']=$row['user_lname'];
					$getUserFields['age']=$row['user_age'];
					$getUserFields['sex']=$row['user_sex'];
					$getUserFields['emailId']=$row['user_email_id'];
					$userType=$rm->getUserType($row['login_user_id']);
					
					$getUserFields['userType']=$userType;
					$getUserFields['address']=$row['user_address'];						
										
					array_push($userDetailsArray,$getUserFields);					
					}
					
				$result=$rm->get_anything_details_success($userDetailsArray,'User');		
				return $result;
		    	}
				else
				{			
					$result=$rm->get_anything_details_fail('User');
					return $result;
				}
			
	}		
	
	echo getUserDetails();
?>
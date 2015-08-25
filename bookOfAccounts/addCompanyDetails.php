<?php

	/*

		Webservice for Company Registration.

		Project Name :  Book of Accounts

		Created By   : MD. Shamsad Ahmed

        Created Date : 17th September 2014

		Usage        : This file is used for registering company with Book of Accounts .

		How it works : We simply take values from device, register it and give success and error responses accordingly.

			Copyright@Techila Solutions

	*/

	include_once('DBConnect.php'); //Database Connection

	include_once('Abstract/classResponse.php');//

	function addCompanyDetails()

	{		
        $userID = trim($_REQUEST['userID']); 
		
		$companyName = trim($_REQUEST['companyName']); //Get Request From Device

		$companyTanNo = trim($_REQUEST['companyTanNo']);

	    $companyPanNo = trim($_REQUEST['companyPanNo']);

		$companyAddress = trim($_REQUEST['companyAddress']);

		$rm=new Response_Methods();

		

		if( $companyName == "" || $companyTanNo == "" || $companyPanNo == "" || $companyAddress == "" )

		{		

			$result = $rm->fields_validation();

			return $result;

		}

		else

		{		     

			date_default_timezone_set('Asia/Calcutta'); 

            $createdDate=date('Y-m-d H:i:s');

			$getList = array();
            
			$getFieldValue['login_user_id'] =$userID;
			
			$getFieldValue['company_name'] =$companyName;

			$getFieldValue['company_tan_no'] =$companyTanNo;

			$getFieldValue['company_pan_no'] =$companyPanNo;

			$getFieldValue['company_address'] =$companyAddress;

			$getFieldValue['company_created_date'] =$createdDate;

			

			$lastInsertId=$rm->insert_record($getFieldValue,'company_details_t');

				

				if(!empty($lastInsertId))

				{					

					$result=$rm->companyRegisterSuccessJson($lastInsertId);

					return $result;

				}

				else

				{

				$result = $rm->companyRegisterFailJson();

				return $result;								

				}

		}

   }		

	

	echo addCompanyDetails();

?>
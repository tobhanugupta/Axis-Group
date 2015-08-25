	<?php
	/*
	Webservice For send Email Details in and Email.
	Created By   : MD. Shamsad Ahmed
	Created Date : 24 September 2014
	Usage        : Get All Details Using Bank ID.
	Copyright@Techila Solutions
   */


	include_once('DBConnect.php'); //Database Connection
	include_once('Abstract/classResponse.php');//
	function getBankDetails()
	{		
		 
			$rm=new Response_Methods();		
            		$bankDetailsArray = array();
			$email_id = trim($_REQUEST['emailID']);
			$fullName= trim($_REQUEST['fullName']);
			$bankidsCheck=trim($_REQUEST['bankIDs']);
			//$bankids=array(33,34);
			//$bankidsCheck="33-34";
			$stringToArray=explode(",",$bankidsCheck);
			//print_r ($stringToArray);
			//die();
			$dataResultSet=$rm->getSpecificINDetails($stringToArray,'bank_details_t','bank_id');
			if(mysql_num_rows($dataResultSet) > 0)
				{
					while($row=mysql_fetch_array($dataResultSet))
					{					
					$getBankFields['bankID']=$row['bank_id'];
					$getBankFields['companyID']=$row['company_id'];
					$getBankFields['bankName']=$row['bank_name'];
					$getBankFields['custName']=$row['account_holder_name'];
					$getBankFields['accNumber']=$row['account_number'];					
					//$getBankFields['bankAddress']=$row['bank_address'];
					$getBankFields['ifsc']=$row['bank_ifsc'];
					//$getBankFields['micr']=$row['bank_micr'];
					$getBankFields['accType']=$row['account_type'];
					$getBankFields['initialBalance']=$row['initial_bank_balance'];						
					$cdate=$row['bank_created_date'];
					$getBankFields['createdDate']=date('Y/m/d', strtotime($cdate));					
					array_push($bankDetailsArray,$getBankFields);					
					}
					
					//print_r($bankDetailsArray);
					$rm->sendMailBankDetails($email_id,$bankDetailsArray,$fullName);
					$result=$rm->get_anything_details_success($bankDetailsArray,'Bank');		
					return $result;
		    	}
				else
				{			
					$result=$rm->get_anything_details_fail('Bank');
					return $result;
				}
			
	}		
	
	echo getBankDetails();
?>
	<?php
	/*
	Webservice For Get Bank Details.
	Created By   : MD. Shamsad Ahmed
	Created Date : 24 September 2014
	Usage        : Get All Details Using Bank ID.
	Copyright@Techila Solutions
   */


	include_once('DBConnect.php'); //Database Connection
	include_once('Abstract/classResponse.php');//
	function getAllBankDetails()
	{		
		   
		    //$bankID = trim($_REQUEST['bankID']);
			$rm=new Response_Methods();		
            $bankDetailsArray = array();
			//$companyID = trim($_REQUEST['companyID']);
			//$dataResultSet=$rm->getSpecificDetails($companyID,'bank_details_t','company_id');
			$dataResultSet=$rm->getAllDetails('bank_details_t');
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
					
				$result=$rm->get_anything_details_success($bankDetailsArray,'Bank');		
				return $result;
		    	}
				else
				{			
					$result=$rm->get_anything_details_fail('Bank');
					return $result;
				}
			
	}		
	
	echo getAllBankDetails();
?>
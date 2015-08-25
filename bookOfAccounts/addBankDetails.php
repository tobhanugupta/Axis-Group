	<?php
	/**
		* Get Member Details and Add into database.
	
	*/
	include_once('DBConnect.php'); //Database Connection
	include_once('Abstract/classResponse.php');//
	function addBankDetails()
	{
echo 'test';		
	    //$companyId = $_REQUEST['companyId'];
		$companyID = trim($_REQUEST['companyID']);
		$custName = trim($_REQUEST['custName']);
		$accNumber = trim($_REQUEST['accNumber']);
		$bankName = trim($_REQUEST['bankName']);
		/*
		$bankAddress = trim($_REQUEST['bankAddress']);		
		if(isset($_REQUEST['micr']))
		$micr = trim($_REQUEST['micr']);
		else
		$micr = "NA";
		*/
		$ifsc = trim($_REQUEST['ifsc']);		
		$accType = trim($_REQUEST['accType']);
		$initialBalance = trim($_REQUEST['initialBalance']);
		
	
		//$Mobile = $_REQUEST['Phone'];
		
		$rm=new Response_Methods();
				
		if($companyID == "" || $custName == "" || $accNumber == "" || $bankName == "" || $ifsc == "" || $accType == "" || $initialBalance == "" )
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
		
			/*
			$result=$rm->getListDetails('user_id','user_fname','user_details_t');
			if(mysql_num_rows($result)>0)
				{
					while($row=mysql_fetch_array($result))
					{
						echo $row['0'].' '.$row['1'];
						echo '<br/>';
					}
				}
				
				
				
			//sample codes check for class Functions
			
			$result=$rm->getSpecificDetails(6,'user_details_t','user_id');	
			if(mysql_num_rows($result)>0)
				{
					while($row=mysql_fetch_array($result))
					{
						echo $row['0'].' '.$row['1'];
						echo '<br/>';
					}
				}
				
				*/
				
				
				
			$getList = array();
		
			$getInsertFieldValue['company_id']=$companyID;
			$getInsertFieldValue['account_holder_name']=$custName;	
			$getInsertFieldValue['account_number']=$accNumber;
			$getInsertFieldValue['bank_name']=$bankName;
			//$getInsertFieldValue['bank_address']=$bankAddress;		
			$getInsertFieldValue['bank_ifsc']=$ifsc;
			//$getInsertFieldValue['bank_micr']=$micr;		
			$getInsertFieldValue['account_type']=$accType;
			$getInsertFieldValue['initial_bank_balance']=$initialBalance;	
			$getInsertFieldValue['bank_created_date']=$createdDate;							
			$lastInserted_bank_id=$rm->insert_record($getInsertFieldValue,'bank_details_t');
					
				if(!empty($lastInserted_bank_id))
				{					
					$result=$rm->bankRegisterSuccessJson($lastInserted_bank_id);
					return $result;
				}
				else
				{
				$result = $rm->bankRegisterFailJson();
				return $result;								
				}
				
			}
		
   }		
	
	echo addBankDetails();
?>
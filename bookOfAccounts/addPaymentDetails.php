	<?php
	/**
		* Adding Payment Details to Database.	
	*/
	
	include_once('DBConnect.php'); //Database Connection
	include_once('Abstract/classResponse.php');//
	function addPaymentDetails()
	{		
	    //$companyId = $_REQUEST['companyId'];
		$companyID = trim($_REQUEST['companyID']);
		$fromBankID = trim($_REQUEST['fromBankID']);
		$toBankID = trim($_REQUEST['toBankID']);
		//$paymentDate = trim($_REQUEST['paymentDate']);
		$paymentReason = trim($_REQUEST['paymentReason']);
		$amount = trim($_REQUEST['amount']);		
		$paymentType = trim($_REQUEST['paymentType']);		
		$userType = trim($_REQUEST['userType']);
		$userID = trim($_REQUEST['userID']);
		$user_request_id= trim($_REQUEST['user_request_id']);
		$payment_id= trim($_REQUEST['payment_id']); // in case of user
		
		
		$affectedRowsPayment=-1;
		
		$rm=new Response_Methods();
				
		if($companyID == "" || $fromBankID == "" || $paymentReason == "" || $amount == "" || $paymentType == "")
		{
			$result = $rm->fields_validation();		
			return $result;
		}
		
		else
		{           
		    	date_default_timezone_set('Asia/Calcutta'); 
            		$createdDate=date('Y-m-d H:i:s');				
			$getList = array();
			$mailData["paymentDetails"] = array();									
			//inserting payment details			
			$getInsertFieldValue['company_id']=$companyID;
			$getInsertFieldValue['payment_from_bank_id']=$fromBankID;	
			$getInsertFieldValue['payment_to_bank_id']=$toBankID;
			//$getInsertFieldValue['payment_date']=$paymentDate;
			$getInsertFieldValue['payment_reason']=$paymentReason;
			$getInsertFieldValue['login_user_id']=$userID ;
			$getInsertFieldValue['amount']=$amount;
			$getInsertFieldValue['payment_type']=$paymentType;
			$getInsertFieldValue['payment_created_date']=$createdDate;
			
			$checkRecords = mysql_query("SELECT user_request_id FROM user_requests_t WHERE user_request_id='$user_request_id'");
			$checkRecords = mysql_num_rows($checkRecords);
			if($userType=='Admin'){
				$checkRecords=1;
			}
			if($checkRecords > 0){
			
			if(strtolower($userType)=="user")
			{
				$affectedRowsPayment=$rm->update_record($getInsertFieldValue,'payment_details_t','payment_id',$payment_id);	
			}
			else if(strtolower($userType)=="admin")
			{
				$lastInserted_payment_id=$rm->insert_record($getInsertFieldValue,'payment_details_t');	
			}									
			
			$mailData=$getInsertFieldValue;
			$mailData['userID']=$userID;
			//array_push($mailData["paymentDetails"], $getInsertFieldValue);
			
			if(!empty($lastInserted_payment_id))
				{
					//Do Transactions by updating banks current balance
					$fromBankBalance=$rm->idToValue('initial_bank_balance','bank_details_t','bank_id',$fromBankID);	
					$toBankBalance=$rm->idToValue('initial_bank_balance','bank_details_t','bank_id',$toBankID);	
					
					if($fromBankBalance < $amount)
					{
					$result = $rm->insufficient_balance();		
					return $result;					
					}
					if($fromBankID!=$toBankID)
					{
					$fromBankBalance=$fromBankBalance-$amount;
					$toBankBalance=$toBankBalance+$amount;
					}
					
					$updateFromBankBalance['initial_bank_balance']=$fromBankBalance;
					$affectedRowsFrom=$rm->update_record($updateFromBankBalance,'bank_details_t','bank_id',$fromBankID);
					
					$updateToBankBalance['initial_bank_balance']=$toBankBalance;
					$affectedRowsTo=$rm->update_record($updateToBankBalance,'bank_details_t','bank_id',$toBankID);	
				}				
			
					//check payment type and insert details accordingly(cheque/net)
					if(strtolower($paymentType)=="cheque")
					{
					
					//insert cheque details
					$mailData["chequeDetails"] = array();
					if(!empty($lastInserted_payment_id))
					{
					$payment_id_inserted=$lastInserted_payment_id;
					}
					else
					{
					$payment_id_inserted=$payment_id;
					}
					$getChequeDetails['payment_id']=$payment_id_inserted;	
					$getChequeDetails['cheque_number']=trim($_REQUEST['chequeNo']);
					$getChequeDetails['cheque_date']=trim($_REQUEST['chequeDate']);
					$getChequeDetails['to_whom_issued']=trim($_REQUEST['chequeIssued']);
					//$getChequeDetails['cheque_amount']=trim($_REQUEST['chequeAmount']);
					$getChequeDetails['cheque_amount']=$amount;
					$getChequeDetails['cheque_created_date']=$createdDate;							
					$lastInserted_cheque_id=$rm->insert_record($getChequeDetails,'cheque_details_t');
					array_push($mailData["chequeDetails"], $getChequeDetails);
					}
					else if(strtolower($paymentType)=="net")
					{
					//insert NET Banking Details
					$mailData["netDetails"] = array();
					if(!empty($lastInserted_payment_id))
					{
					$payment_id_inserted=$lastInserted_payment_id;
					}
					else
					{
					$payment_id_inserted=$payment_id;
					}
					$getNetBankingDetails['payment_id']=$payment_id_inserted;	
					//$getNetBankingDetails['type']=trim($_REQUEST['netBankingType']);					
					$getNetBankingDetails['nbd_created_date']=$createdDate;							
					$lastInserted_cheque_id=$rm->insert_record($getNetBankingDetails,'net_banking_details_t');
					array_push($mailData["netDetails"], $getNetBankingDetails);
					//print_r($getNetBankingDetails);
					}	
					
					if(strtolower($userType)=="user")
					{
						$adminId=1;
						if($adminId!=0)
							{
							$adminEmail=$rm->getAdminEmailID($adminId);	
							//print_r($mailData);;					
							$sendMail=$rm->sendTransactionDetails($adminEmail,$mailData);	
							}
					}
					
					
					if(strtolower($userType)=="user" && $affectedRowsPayment>=0)
						{						
					$result=$rm->paymentRegisterSuccessJson($payment_id);	
					$sqlUpdate="update user_requests_t set status='Paid' where user_request_id=$user_request_id";
					mysql_query($sqlUpdate,$GLOBALS['link']);
					return $result;
						}
						
					else if(strtolower($userType)=="admin" && !empty($lastInserted_payment_id))
						{						
					$result=$rm->paymentRegisterSuccessJson($lastInserted_payment_id);
					$sqlUpdate="update user_requests_t set status='Paid' where user_request_id=$user_request_id";
					mysql_query($sqlUpdate,$GLOBALS['link']);
					return $result;
						}									
						
					
					if(empty($lastInserted_payment_id) && strtolower($userType)=="admin")
					{
					$result = $rm->paymentRegisterFailJson();
					return $result;								
					}
					if($affectedRowsPayment<0 && strtolower($userType)=="user")					{
					$result = $rm->paymentRegisterFailJson();
					return $result;								
					}		
               }else{
			        $errorCode = "2";
					$errorMsg = "User Request Not Exist";
					$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response
					return $newData;
			   }
			}
			
		
   }		
	
	echo addPaymentDetails();
?>
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
		
		$rm=new Response_Methods();
				
		if($companyID == "" || $fromBankID == "" || $toBankID == "" || $paymentReason == "" || $amount == "" || $paymentType == "")
		{
			$result = $rm->fields_validation();		
			return $result;
		}
		
		else
		{           
		    date_default_timezone_set('Asia/Calcutta'); 
            $createdDate=date('Y-m-d H:i:s');				
			$getList = array();			
			
			//inserting payment details			
			$getInsertFieldValue['company_id']=$companyID;
			$getInsertFieldValue['payment_from_bank_id']=$fromBankID;	
			$getInsertFieldValue['payment_to_bank_id']=$toBankID;
			//$getInsertFieldValue['payment_date']=$paymentDate;
			$getInsertFieldValue['payment_reason']=$paymentReason;
			$getInsertFieldValue['amount']=$amount;
			$getInsertFieldValue['payment_type']=$paymentType;
			$getInsertFieldValue['payment_created_date']=$createdDate;							
			$lastInserted_payment_id=$rm->insert_record($getInsertFieldValue,'payment_details_t');	
			if(!empty($lastInserted_payment_id))
				{
					//Do Transactions by updating bank current balance
					$fromBankBalance=$rm->idToValue('initial_bank_balance','bank_details_t','bank_id',$fromBankID);	
					$toBankBalance=$rm->idToValue('initial_bank_balance','bank_details_t','bank_id',$toBankID);	
					
					if($fromBankID!=$toBankID)
					{
					$fromBankBalance=$fromBankBalance-$amount;
					$toBankBalance=$toBankBalance+$amount;
					}
					
					$updateFromBankBalance['initial_bank_balance']=$fromBankBalance;
					$affectedRowsFrom=$rm->update_record($updateFromBankBalance,'bank_details_t','bank_id',$fromBankID);
					
					$updateToBankBalance['initial_bank_balance']=$toBankBalance;
					$affectedRowsTo=$rm->update_record($updateToBankBalance,'bank_details_t','bank_id',$toBankID);				
			
					//check payment type and insert details accordingly(cheque/net)
					if(strtolower($paymentType)=="cheque")
					{
					//insert cheque details
					//echo 'test';
					$getChequeDetails['payment_id']=$lastInserted_payment_id;	
					$getChequeDetails['cheque_number']=trim($_REQUEST['chequeNo']);
					$getChequeDetails['cheque_date']=trim($_REQUEST['chequeDate']);
					$getChequeDetails['to_whom_issued']=trim($_REQUEST['chequeIssued']);
					//$getChequeDetails['cheque_amount']=trim($_REQUEST['chequeAmount']);
					$getChequeDetails['cheque_amount']=$amount;
					$getChequeDetails['cheque_created_date']=$createdDate;							
					$lastInserted_cheque_id=$rm->insert_record($getChequeDetails,'cheque_details_t');
					}
					else if(strtolower($paymentType)=="net")
					{
					//insert NET Banking Details
					$getNetBankingDetails['payment_id']=$lastInserted_payment_id;	
					$getNetBankingDetails['type']=trim($_REQUEST['netBankingType']);					
					$getNetBankingDetails['nbd_created_date']=$createdDate;							
					$lastInserted_cheque_id=$rm->insert_record($getNetBankingDetails,'net_banking_details_t');
					}
					
					
					$result=$rm->paymentRegisterSuccessJson($lastInserted_payment_id);
					return $result;
				}
				else
				{
				$result = $rm->paymentRegisterFailJson();
				return $result;								
				}		

				
			}
			
		
   }		
	
	echo addPaymentDetails();
?>
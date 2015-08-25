	<?php
	/**
		* Update User Details in Database.
	
	*/
	include_once('DBConnect.php'); //Database Connection
	include_once('Abstract/classResponse.php');//
	include_once ('gcm_server_php/GCM.php');
	function updateRequestStatus()
	{		
		$userRequestID = trim($_REQUEST['user_request_id']);
		$requestStatus = trim($_REQUEST['status']);
		$lastInserted_payment_id=0;
		$rm=new Response_Methods();				
		if($userRequestID == "" || $requestStatus == "")
		{
			$result = $rm->fields_validation();		
			return $result;
		}			
		else
		{
			  if($requestStatus=='Accepted')
				{					
					
					$fromBankID=$rm->idToValue('payment_from_bank_id','user_requests_t','user_request_id',$userRequestID);	
					$toBankID=$rm->idToValue('payment_to_bank_id','user_requests_t','user_request_id',$userRequestID);
					$amount=$rm->idToValue('amount','user_requests_t','user_request_id',$userRequestID);
					
					$fromBankBalance=$rm->idToValue('initial_bank_balance','bank_details_t','bank_id',$fromBankID);	
					$toBankBalance=$rm->idToValue('initial_bank_balance','bank_details_t','bank_id',$toBankID);	
					
					if($fromBankBalance < $amount){
						$result = $rm->insufficient_balance();		
						return $result;
						
					}else{
								if($fromBankID!=$toBankID){
									$fromBankBalance=$fromBankBalance-$amount;
									$toBankBalance=$toBankBalance+$amount;
								}
								
								$updateFromBankBalance['initial_bank_balance']=$fromBankBalance;
								$affectedRowsFrom=$rm->update_record($updateFromBankBalance,'bank_details_t','bank_id',$fromBankID);
								
								$updateToBankBalance['initial_bank_balance']=$toBankBalance;
								$affectedRowsTo=$rm->update_record($updateToBankBalance,'bank_details_t','bank_id',$toBankID);
								
								$getInsertFieldValue['amount']=$amount;	
								$getInsertFieldValue['payment_from_bank_id']=$fromBankID;	
								$getInsertFieldValue['payment_to_bank_id']=$toBankID;	
								$getInsertFieldValue['user_request_id']=$userRequestID ;										
								$lastInserted_payment_id=$rm->insert_record($getInsertFieldValue,'payment_details_t');

								$login_user_id=$rm->idToValue('login_user_id','user_requests_t','user_request_id',$userRequestID );
							    $gcm_regid=$rm->getUserGCMREGID($login_user_id);						
								if($gcm_regid!="" || $gcm_regid!="NA")
								{
								$gcm = new GCM();					
								$registatoin_ids = array($gcm_regid);
								$msg="Request ".$requestStatus ;
								$message = array("Response" =>$msg);				
								$resultPush = $gcm->send_notification($registatoin_ids, $message);			
								}
								
								$getRegisterFieldValue['status']=$requestStatus;	
								$affectedRows=$rm->update_record($getRegisterFieldValue,'user_requests_t','user_request_id',$userRequestID);
								
								if($affectedRows>=0){
									$result=$rm->requestStatusSuccess($lastInserted_payment_id);	
									return $result;
								}else{
									$result=$rm->requestStatusFail();
				                    return $result;
								}
						   }			
				}
		}		
   }
	
	echo updateRequestStatus();
?>
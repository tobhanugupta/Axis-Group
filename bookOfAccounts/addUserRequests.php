	<?php
	/**
		* Adding Payment Details to Database.
	
	*/
	include_once('DBConnect.php'); //Database Connection
	include_once('Abstract/classResponse.php');//
	include_once ('gcm_server_php/GCM.php');
	
	function addUserRequest()
	{		
	    //$companyId = $_REQUEST['companyId'];
		$login_user_id = trim($_REQUEST['userID']);
		$fromBankID = trim($_REQUEST['fromBankID']);
		$toBankID = trim($_REQUEST['toBankID']);
		//$paymentDate = trim($_REQUEST['paymentDate']);
		//$paymentReason = trim($_REQUEST['paymentReason']);
		$amount = trim($_REQUEST['amount']);		
		$paymentType = trim($_REQUEST['paymentType']);	
		
		$rm=new Response_Methods();
				
		if($login_user_id == "" || $fromBankID == "" || $amount == "" || $paymentType == "")
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
			$getInsertFieldValue['login_user_id']=$login_user_id;
			$companyID=$rm->idToValue('company_id','user_details_t','login_user_id',$login_user_id);
			$getInsertFieldValue['payment_from_bank_id']=$fromBankID;	
			$getInsertFieldValue['payment_to_bank_id']=$toBankID;
			//$getInsertFieldValue['payment_date']=$paymentDate;
			//$getInsertFieldValue['payment_reason']=$paymentReason;
			$getInsertFieldValue['amount']=$amount;
			$getInsertFieldValue['payment_type']=$paymentType;
			$getInsertFieldValue['request_created_date']=$createdDate;			
			$getInsertFieldValue['company_id']=$companyID;							
			$lastInserted_user_request_id=$rm->insert_record($getInsertFieldValue,'user_requests_t');	
			if(!empty($lastInserted_user_request_id))
				{
					//Do Transactions by updating bank current balance	
					
					/* Sending Push Notification to Admin */
					
					$gcm_regid=$rm->getUserGCMREGID(2);						
					if($gcm_regid!="" || $gcm_regid!="NA")
					{
					$gcm = new GCM();					
					$registatoin_ids = array($gcm_regid);
					$msg="User Payment Request Made. Please Check";
					$message = array("Response" =>$msg);				
					$resultPush = $gcm->send_notification($registatoin_ids, $message);				
						
					}	
								
					$result=$rm->userRequestSuccessJson($lastInserted_user_request_id);
					return $result;
				}
				else
				{
				$result = $rm->userRequestFailJson();
				return $result;								
				}		

				
			}
			
		
   }		
	
	echo addUserRequest();
?>
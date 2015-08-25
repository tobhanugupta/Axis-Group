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
	function getPaymentReports()
	{		
		   	
		       $userID = trim($_REQUEST['userID']);
		       $payment_type = trim($_REQUEST['payment_type']);
		       $from_date = trim($_REQUEST['from_date']);
		       $to_date = trim($_REQUEST['to_date']);
		       
		       
			$rm=new Response_Methods();		
		        $paymentDetailsArray = array();
		        if($userID == "" || $payment_type == "" || $from_date == "" || $to_date == "")
				{		
			
			$result = $rm->fields_validation();		
			return $result;
		      		 }
		        else
		        {		        
			$login_user_id=$rm->idToValue('login_user_id','user_details_t','user_id',$userID);	
			echo $rm->getPaymentDetails($login_user_id,$payment_type,$from_date,$to_date);
			}
			
			
	}		
	
	echo getPaymentReports();
?>
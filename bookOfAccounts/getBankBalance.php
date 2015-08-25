	<?php
	/**
		* Adding Payment Details to Database.
	
	*/
	include_once('DBConnect.php'); //Database Connection
	include_once('Abstract/classResponse.php');//
	function getBankBalance()
	{		
	    //$companyId = $_REQUEST['companyId'];
		$bank_id = trim($_REQUEST['bankID']);		
		//$amount = trim($_REQUEST['amount']);	
		
		$rm=new Response_Methods();
				
		if($bank_id == "")
		{
			$result = $rm->fields_validation();		
			return $result;
		}
		
		else
		{           
		   
		    $currentBalance=$rm->idToValue('initial_bank_balance','bank_details_t','bank_id',$bank_id);	
			if($currentBalance!='NA')
			{								
							
				$result = $rm->bankBalance($currentBalance);
				return $result;			
				
			}
			else
			{
			$result=$rm->get_anything_details_fail('Bank');
			return $result;
			}
			
		}
				
			
		
   }		
	
	echo getBankBalance();
?>
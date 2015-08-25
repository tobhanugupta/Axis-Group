	<?php
	/**
		* DeActivate Users.
	
	*/
	include_once('DBConnect.php'); //Database Connection
	include_once('Abstract/classResponse.php');//
	function userDeActivate()
	{		
	    $user_id = $_REQUEST['userID'];		
		
		$rm=new Response_Methods();
				
		if($user_id == "" )
		{
			
			$result = $rm->fields_validation();		
			return $result;
		}
		
		else
		{
		
			$getUpdateFieldValue['user_status']=0;	
			$affectedRows=$rm->user_deactivate($user_id);	
					
			//$affectedRows=$rm->update_record($getUpdateFieldValue,'login_t','login_user_id',$login_id);
				if($affectedRows>=0)
				{					
					$result=$rm->userDeactivateSuccess();
					return $result;
				}
				
				else
				{
				$result = $rm->userDeactivateFail();
				return $result;								
				}
				
			}
		
   }		
	
	echo userDeActivate();
?>
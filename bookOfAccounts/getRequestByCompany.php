	<?php
	/*
	Webservice For Get User Request.
	Created By   : MD. Shamsad Ahmed
	Created Date : 31 October 2014
	Usage        : Get All Users Request.
	Copyright@Techila Solutions
   */


	include_once('DBConnect.php'); //Database Connection
	include_once('Abstract/classResponse.php');//
	function getCompanyUserRequests()
	{	
		   $companyID = trim($_REQUEST['companyID']);
			$rm=new Response_Methods();		
            $userRequestsDetailsArray = array();
			$dataResultSet=$rm->getSpecificDetailsTwo($companyID,'Pending','user_requests_t','company_id','status');
			//$dataResultSet=$rm->getAllDetails('user_details_t');
			if(mysql_num_rows($dataResultSet) > 0)
				{
					while($row=mysql_fetch_array($dataResultSet))
					{
					$getUserRequestsFields['user_request_id']=$row['user_request_id'];					
					$getUserRequestsFields['payment_from_bank_id']=$row['payment_from_bank_id'];
					$getUserRequestsFields['loginID']=$row['login_user_id'];
					$userFname=$rm->idToValue('user_fname','user_details_t','login_user_id',$row['login_user_id']);
					$userLname=$rm->idToValue('user_lname','user_details_t','login_user_id',$row['login_user_id']);
					$name=$userFname." ".$userLname;
					$getUserRequestsFields['name']=$name;
					$getUserRequestsFields['loginID']=$row['login_user_id'];
					$getUserRequestsFields['payment_to_bank_id']=$row['payment_to_bank_id'];
					//$getUserRequestsFields['payment_date']=$row['payment_date'];
				$getUserRequestsFields['fBank']=$rm->idToValue('bank_name','bank_details_t','bank_id',$row['payment_from_bank_id']);					
				$getUserRequestsFields['tBank']=$rm->idToValue('bank_name','bank_details_t','bank_id',$row['payment_to_bank_id']);
					//$getUserRequestsFields['payment_reason']=$row['payment_reason'];
					$getUserRequestsFields['amount']=$row['amount'];
			
					$getUserRequestsFields['payment_type']=$row['payment_type'];
											
					$cdate=$row['request_created_date'];
					$getUserRequestsFields['request_created_date']=date('Y/m/d', strtotime($cdate));
					$getUserRequestsFields['request_created_time']=date('H:i:s', strtotime($cdate));
					$getUserRequestsFields['status']=$row['status'];
					
				
								
					
									
					array_push($userRequestsDetailsArray,$getUserRequestsFields);					
					}
					
				$result=$rm->get_anything_details_success($userRequestsDetailsArray,'User Request');		
				return $result;
		    	}
				else
				{			
					$result=$rm->get_anything_details_fail('User Request');
					return $result;
				}
			
	}		
	
	echo getCompanyUserRequests();
?>
	<?php
	/*
	Webservice For Get Company ID and Name for dropdown.
	Created By   : MD. Shamsad Ahmed
	Created Date : 24 September 2014
	Usage        : Get All Details Using Company ID.
	Copyright@Techila Solutions
   */


	include_once('DBConnect.php'); //Database Connection
	include_once('Abstract/classResponse.php');//
	function getCompanyList()
	{			
			$rm=new Response_Methods();
		    $companyDetailsArray = array();			
			//$dataResultSet=$rm->getSpecificDetails($companyID,'company_details_t','company_id');
			$dataResultSet=$rm->getAllDetails('company_details_t');
			if(mysql_num_rows($dataResultSet) > 0){				
				while($row=mysql_fetch_array($dataResultSet)){									
					$getCompanyFields['companyID']=$row['company_id'];
					$getCompanyFields['companyName']=$row['company_name'];									
					array_push($companyDetailsArray,$getCompanyFields);
				}
				$result=$rm->get_anything_details_success($companyDetailsArray,'Company');	
				return $result;
		    }
			else{
			
				$result=$rm->get_anything_details_fail('Company');	
				return $result;
				}
	}		
	
	echo getCompanyList();
?>
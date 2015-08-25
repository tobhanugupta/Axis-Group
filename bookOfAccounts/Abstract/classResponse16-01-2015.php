<?php

/* 
Class having json response methods and some database query methods 
Created by: MD. Shamsad Ahmed
*/
//echo $GLOBALS['link'];

class Response_Methods {

	/* return json for empty fields */
	public function fields_validation() {			
			$errorCode = "0";
			$errorMsg = "Please Send All Fields";
			$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response
			echo $newData;
		}
		
		
		
		
		
	public function login_success($USERNAME,$ENCRYPTEDPWD,$DEVICEID)
		{
			$getArrayList=array();
			$dataQueryInfo = "SELECT login_user_id,usertype,device_id FROM login_t WHERE emp_id= '$USERNAME' AND password='$ENCRYPTEDPWD' AND user_status=1";
			$rm=new Response_Methods();
			$dataResultSet = mysql_query($dataQueryInfo,$GLOBALS['link']);
			if(mysql_num_rows($dataResultSet) > 0)
			{
					while($row=mysql_fetch_array($dataResultSet))
					{														
						$login_user_id=$row['login_user_id'];
						$deviceID=$row['device_id'];
						$usertype=$row['usertype'];
						$getGrpDetails['loginID']=$login_user_id;
						$getGrpDetails['type']=ucfirst($usertype);
						$companyID=$rm->idToValue('company_id','user_details_t','login_user_id',$login_user_id);
						$getGrpDetails['companyID']=$companyID;							
						array_push($getArrayList,$getGrpDetails);
					}
					
					mysql_query("UPDATE login_t SET device_id='$DEVICEID' WHERE emp_id='$USERNAME' AND password='$ENCRYPTEDPWD' AND user_status=1");				
									
				$newData=json_encode(array($getArrayList));
				$newData=str_replace('\/', '/', $newData);
				$newData=substr($newData,1,strlen($newData)-2);	
				if($deviceID!=$DEVICEID && $deviceID!='')
					{
				$newData="{\"data\":{\"Error_Code\":\"3\",\"Error_Msg\":\"Login Successful with New Device ID\",\"result\":".$newData."}}";
					}
				else
				{			
				$newData="{\"data\":{\"Error_Code\":\"1\",\"Error_Msg\":\"Login Successful\",\"result\":".$newData."}}";
				}	
				return $newData; 
			}	
			else
				{
					$errorCode = "2";
					$errorMsg = "Login Unsuccessful.Please Try Again";
					$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response
					return $newData;
				 //Login Unsuccessful
				}
	}	
	
	public function get_anything_details_success($getArrayList,$anyThing)
	{	
				$newData=json_encode(array($getArrayList));
				$newData=str_replace('\/', '/', $newData);
				$newData=substr($newData,1,strlen($newData)-2);			
				$newData="{\"data\":{\"Error_Code\":\"1\",\"Error_Msg\":\".".$anyThing." Details\",\"result\":".$newData."}}";	
				return $newData; 
	}
	
	public function get_anything_details_fail($anyThing) 
	{
		$errorCode = "2";
		$errorMsg = "No $anyThing Details Available.Please Try Again";
		$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response
		return $newData;
	}
	
	/* Generate dynamic insert query Insert record to table */
	public function insert_record($fieldValues,$table) 
	{
		//include_once('DBConnect.php');
		$size=count($fieldValues);
		$sqlInsert="insert into $table(";
		$i=1;
		foreach($fieldValues as $field=>$value)
		{
			if($i==$size)
			$sqlInsert.=$field;
			else
			$sqlInsert.=$field.",";	
			$i++;
		}
		
		$sqlInsert.=") values (";		
		$j=1;
		foreach($fieldValues as $field=>$value)
		{
		if($j==$size)
		$sqlInsert.="'$value'";
		else
		$sqlInsert.="'$value',";
		$j++;
		}
						
		$sqlInsert.=")";
					
		//echo $sqlInsert;		
		mysql_query($sqlInsert,$GLOBALS['link']);		
		$lastInsertId = mysql_insert_id();
		return $lastInsertId;		
	}
	
	public function update_record($fieldValues,$table,$keyField,$keyValue) 
	{
		//include_once('DBConnect.php');
		$size=count($fieldValues);
		$sqlUpdate="update $table set";
		$i=1;
		foreach($fieldValues as $field=>$value)
		{
			if($i==$size)
			$sqlUpdate.=" $field='$value' ";
			else
			$sqlUpdate.=" $field='$value',";	
			$i++;
		}
		
		$sqlUpdate.=" where $keyField='$keyValue'";
							
		//echo $sqlUpdate;	
		mysql_query($sqlUpdate,$GLOBALS['link']);		
		$affectedRows = mysql_affected_rows();
		return $affectedRows;		
	}
	
	public function user_deactivate($user_id) 
	{	
	$sqlUpdate="update login_t set user_status=0 where user_status=1 and login_user_id=(select login_user_id from user_details_t where user_id=$user_id)";
	mysql_query($sqlUpdate,$GLOBALS['link']);		
	$affectedRows = mysql_affected_rows();
	return $affectedRows;	
	}
	
	/*Return json for Company Successful Company Registration */
	
	public function companyRegisterSuccessJson($lastInsertId)
	{
		$getList=array();
		$dataQueryInfo = "SELECT * FROM company_details_t WHERE company_id='$lastInsertId'";
		$dataResultSet = mysql_query($dataQueryInfo,$GLOBALS['link']);						
			if(mysql_num_rows($dataResultSet) > 0){
			
				while($row=mysql_fetch_array($dataResultSet)){					
					$getGroupDetails['companyID']=$row['company_id'];
					$getGroupDetails['companyName']=$row['company_name'];	
					$getGroupDetails['companyTanNo']=$row['company_tan_no'];
					$getGroupDetails['companyPanNo']=$row['company_pan_no'];
					$getGroupDetails['companyAddress']=$row['company_address'];					
					$cdate=$row['company_created_date'];
					$getGroupDetails['createdDate']=date('Y/m/d', strtotime($cdate));							
					array_push($getList,$getGroupDetails);//converting array to string
				}
					$newData=json_encode(array($getList));
					$newData=str_replace('\/', '/', $newData);
					$newData=substr($newData,1,strlen($newData)-2);
				
					$newData="{\"data\":{\"Error_Code\":\"1\",\"Error_Msg\":\"Success\",\"result\":".$newData."}}";
					return $newData;   
			}
			else{			
					$errorCode = "2";
					$errorMsg = "Result Not Found";
					$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; 
					return $newData;
			    }	
	}	
	
	public function bankRegisterSuccessJson($lastInsertId)
	{
		$getList=array();
		$dataQueryInfo = "SELECT * FROM bank_details_t WHERE bank_id='$lastInsertId'";
		$dataResultSet = mysql_query($dataQueryInfo,$GLOBALS['link']);
								
			if(mysql_num_rows($dataResultSet) > 0){						
				while($row=mysql_fetch_array($dataResultSet)){	
					$getGroupDetails['bankID']=$row['bank_id'];
					$getGroupDetails['companyID']=$row['company_id'];
					$getGroupDetails['bankName']=$row['bank_name'];
					$getGroupDetails['custName']=$row['account_holder_name'];
					$getGroupDetails['accNumber']=$row['account_number'];					
					//$getBankFields['bankAddress']=$row['bank_address'];
					$getGroupDetails['ifsc']=$row['bank_ifsc'];
					//$getBankFields['micr']=$row['bank_micr'];
					$getGroupDetails['accType']=$row['account_type'];
					$getGroupDetails['initialBalance']=$row['initial_bank_balance'];						
					$cdate=$row['bank_created_date'];
					$getGroupDetails['createdDate']=date('Y/m/d', strtotime($cdate));					
					array_push($getList,$getGroupDetails);//converting array to string
				}					
					$newData=json_encode(array($getList));
					$newData=str_replace('\/', '/', $newData);
					$newData=substr($newData,1,strlen($newData)-2);				
					$newData="{\"data\":{\"Error_Code\":\"1\",\"Error_Msg\":\"Success\",\"result\":".$newData."}}";
					return $newData;   
			}
			else{			
					$errorCode = "2";
					$errorMsg = "Result Not Found";
					$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; 
					return $newData;
				}	
	}
	
	public function userRegisterSuccessJson($lastInsertId)
	{
		$getList=array();
		 $rm=new Response_Methods();
		$dataQueryInfo = "SELECT user_fname,user_lname,user_age,user_sex, user_email_id, user_address, login_user_id FROM user_details_t WHERE user_id='$lastInsertId'";
		$dataResultSet = mysql_query($dataQueryInfo,$GLOBALS['link']);
		
			if(mysql_num_rows($dataResultSet) > 0){
			
				while($row=mysql_fetch_array($dataResultSet)){
					
					//$getGroupDetails['userFullName']=ucfirst($row['user_fname']).' '.ucfirst($row['user_lname']);
					$getGroupDetails['userFname']=$row['user_fname'];
					$getGroupDetails['userLname']=$row['user_lname'];
					$getGroupDetails['age']=$row['user_age'];
					$getGroupDetails['sex']=$row['user_sex'];
					$getGroupDetails['emailId']=$row['user_email_id'];
					$getGroupDetails['address']=$row['user_address'];
					$login_user_id=$row['login_user_id'];
					$userType=$rm->getUserType($row['login_user_id']);
					$getGroupDetails['userType']=$userType;						
					
									
					array_push($getList,$getGroupDetails);//converting array to string
				}
					$newData=json_encode(array($getList));
					$newData=str_replace('\/', '/', $newData);
					$newData=substr($newData,1,strlen($newData)-2);
				
					$newData="{\"data\":{\"Error_Code\":\"1\",\"Error_Msg\":\"Success\",\"result\":".$newData."}}";
					return $newData;   
			}
			else{			
					$errorCode = "2";
					$errorMsg = "Result Not Found";
					$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; 
					return $newData;
				}	
	}
	
	
	public function paymentRegisterSuccessJson($lastInsertId)
	{
		//echo 'test Insert';	
		$getList=array();
		$dataQueryInfo = "SELECT * FROM payment_details_t WHERE payment_id='$lastInsertId'";
		$dataResultSet = mysql_query($dataQueryInfo,$GLOBALS['link']);
		
			if(mysql_num_rows($dataResultSet) > 0){
			
				while($row=mysql_fetch_array($dataResultSet)){
					
					$getGroupDetails['paymentID']=$row['payment_id'];	
					//$getGroupDetails['paymentBankID']=$row['payment_bank_id'];	
									
					array_push($getList,$getGroupDetails);//converting array to string
				}
					$newData=json_encode(array($getList));
					$newData=str_replace('\/', '/', $newData);
					$newData=substr($newData,1,strlen($newData)-2);
				
					$newData="{\"data\":{\"Error_Code\":\"1\",\"Error_Msg\":\"Success\",\"result\":".$newData."}}";					
					return $newData;   
			}
			else{			
					$errorCode = "2";
					$errorMsg = "Result Not Found";
					$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; 
					return $newData;
				}	
	}
	
	
	public function userModifiedSuccess()
	{
				$errorCode = "1";
				$errorMsg = "User Details Updated Successfully";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}
	
	
	public function userModifiedFail()
	{
				$errorCode = "2";
				$errorMsg = "User Details not Updated. Please try again";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}
	
	
	
	
	public function userDeactivateSuccess()
	{
				$errorCode = "1";
				$errorMsg = "User Deactivated Successfully";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}
	
	
	public function userDeactivateAlready()
	{
				$errorCode = "3";
				$errorMsg = "User Already Deactivated. Try again.";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}
	
	public function userDeactivateFail()
	{
				$errorCode = "2";
				$errorMsg = "User Deactivation Failed. Try again.";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}
	
	public function paymentRegisterFailJson()
	{
				$errorCode = "2";
				$errorMsg = "Payment Process Failed";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}
	
	
	public function companyRegisterFailJson()
	{
				$errorCode = "2";
				$errorMsg = "Company Registration Failed";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}
	
	public function bankRegisterFailJson()
	{
				$errorCode = "2";
				$errorMsg = "Bank Registration Failed";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}
	
	public function userRegisterFailJson()
	{
				$errorCode = "2";
				$errorMsg = "User Registration Failed";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}
	
	
	public function getMaxID($table,$id)
	{
				$sqlSelectMaxID="select max($id) from $table";
				$result=mysql_query($sqlSelectMaxID,$GLOBALS['link']);
				while($row=mysql_fetch_array($result))
				{
					 $maxID=$row[0];
				}
				return $maxID;
	}	
	
	public function getAdminEmailID($id)
	{
				$sqlSelect="select user_email_id from user_details_t where login_user_id='$id'";
				$result=mysql_query($sqlSelect);
				//$adminEmailId=0;
				while($row=mysql_fetch_array($result))
				{
					 $adminEmailId=$row['user_email_id'];
				}
				return $adminEmailId;
	}
	
	public function getUserType($login_user_id)
	{
				$sqlSelect="select userType from login_t where login_user_id='$login_user_id'";
				$result=mysql_query($sqlSelect,$GLOBALS['link']);
				$userType='NA';
				while($row=mysql_fetch_array($result))
				{
					 $userType=$row[0];
				}
				return $userType;
	}
	
	public function sendMailPasswordDetails($to,$userName,$passWord_Ran,$fullName)
	{	
					// subject
					$subject = 'Book of Account Apps User Password Details';
					// message
					$message ='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
							   <html xmlns="http://www.w3.org/1999/xhtml">
								<head>
								 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
								</head>
								<body style="font-family:Helvetica;">
								<div style="width:100%; margin:0px; padding:0px;" align="center">
								   <div style="width:50.5%;margin:0px auto;background:black; height:auto;padding:5px 2.5px 5px 2.5px;text-align:left;color:white;">
				                    <img src="http://phbjharkhand.in/bookOfAccounts/images/techila_logo.png">
								   </div>
								   <div style="width:50%;margin:0px auto;border:solid 1px gray;height:350px;padding:5px 3.5px 5px 5px;">
								   <table width="100%" align="center">
									<tr>
									<td>
									Dear Admin,<br/>
									<p>Welcome to Book of Account App</p>							
									<p style="font-size:15px;">Please find the login credentials of '.ucwords($fullName).':</p>
									</td>
									</tr>
									</table>
								    <table width="40%" align="left" cellpadding="5" cellspacing="2" style="margin-top:5%;">
									<tr>
									<td>
									<h4><strong>Username :</strong></h4></td><td style="font-size:14px; color:#396db5;">'.$userName.'</td>
									</tr>
									<tr>
									<td>
									<h4><strong>Password:</strong></h4></td><td font-size:14px; color:#396db5;">'.$passWord_Ran.'</td></tr>
									<tr>
									<td><p>Regards,</p><p>Book of Accounts</p></td>
									</tr>
								   </div>
								   </div>
								</body>
							</html>';
														
					  $headers="";;
					  $headers.= "MIME-version: 1.0\n";
                      $headers.= "Content-type: text/html; charset= iso-8859-1\n";
                      $headers.= "From: info@phbjharkhand.in\r\n";
                      mail($to, $subject, $message, $headers);
	}
	
	
	public function sendMailBankDetails($to,$bankDetailsArray, $fullName)
	{	
					// subject
					 $subject = 'Book of Account Apps Bank Details';
					// message
					 $message ='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
							   <html xmlns="http://www.w3.org/1999/xhtml">
								<head>
								 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
								</head>
								<body style="font-family:Helvetica;">
								<div style="width:100%; margin:0px; padding:0px;" align="center">
								   <div style="width:50.5%;margin:0px auto;background:black; height:auto;padding:5px 2.5px 5px 2.5px;text-align:left;color:white; float:left; position:relative;">
				                    <img src="http://phbjharkhand.in/bookOfAccounts/images/techila_logo.png">
								   </div>
								   <div style="width:50%;margin:0px auto;border:solid 1px gray;height:auto;padding:5px 3.5px 5px 5px; float:left; position:relative;">
								   <table width="100%" align="center">
									<tr>
									<td>
									Dear '.$fullName.',<br/>
									<p>Welcome to BOA App</p>							
									<p style="font-size:15px;">Please find the bank details below:</p>
									</td>
									</tr>
									</table>
								    <table width="70%" align="left" cellpadding="2" cellspacing="2" style="margin-top:3%;">';
									$i=1;
									foreach($bankDetailsArray as $bank)
									{									
									$message.='<tr>
									<td>
									<h4 style="padding:5px; margin:2px; font-size:14px;"><strong>Bank Name :</strong></h4></td><td style="font-size:14px; color:#396db5;">'.$bank["bankName"].'</td>
									</tr>
									<tr>
									<td>
									<h4 style="padding:5px; margin:2px; font-size:14px;"><strong>Account Holder Name:</strong></h4></td><td style="font-size:14px; color:#396db5;">'.$bank["custName"].'</td></tr>
									
									<tr>
									<td>
									<h4 style="padding:5px; margin:2px; font-size:14px;"<strong>Account Number:</strong></h4></td><td style="font-size:14px; color:#396db5;">'.$bank["accNumber"].'</td></tr>
									
									<tr>
									<td>
									<h4 style="padding:5px; margin:2px; font-size:14px;"><strong>IFSC Code:</strong></h4></td><td style="font-size:14px; color:#396db5;">'.$bank["ifsc"].'</td></tr>
									
									<tr>
									<td>
									<h4 style="padding:5px; margin:2px; font-size:14px;"><strong>Account Type:</strong></h4></td><td style="font-size:14px; color:#396db5;">'.$bank["accType"].'</td></tr>';
									
									if($i!==count($bankDetailsArray))
									{
									$message.='<tr><td colspan="2" style="border-bottom:1px dashed #396db5;">&nbsp;</td></tr>';
									}
									$i++;
									}
									
									
									$message.='<tr>
									<td><p>Regards,</p><p>Book of Accounts</p></td>
									</tr>
								   </div>
								   </div>
								</body>
							</html>';
														
					  //echo $message;
							
					  $headers="";
					  $headers.= "MIME-version: 1.0\n";
                      $headers.= "Content-type: text/html; charset= iso-8859-1\n";
                      $headers.= "From: info@phbjharkhand.in\r\n";
                      mail($to, $subject, $message, $headers);
	}
	
	function getListDetails($id,$name,$table)
	{
	$sqlList="select $id,$name from $table";
	$result=mysql_query($sqlList,$GLOBALS['link']);
	return $result;
	}
	
	function getSpecificDetails($id,$table,$field)
	{
	$sqlList="select * from $table where $field=$id";
	$result=mysql_query($sqlList,$GLOBALS['link']);
	return $result;
	}
	
	function getSpecificINDetails($ids,$table,$field)
	{
	$sqlList="select * from $table where $field IN(";
	$size=count($ids);
	for($i=0;$i<count($ids); $i++)
	{		
		if($i==($size-1))
		$sqlList.=$ids[$i];
		else
		$sqlList.=$ids[$i].",";				
	}
	$sqlList.=")";
	//echo $sqlList;
	$result=mysql_query($sqlList,$GLOBALS['link']);
	return $result;
	}
	
	function getAllDetails($table)
	{
	$sqlList="select * from $table";
	$result=mysql_query($sqlList,$GLOBALS['link']);
	return $result;
	}
	
	/* Update User Profile Details*/
	public function profileUpdate() {
		
		$errorCode = "1";
        $errorMsg = "Profile Updated Successfully";
        $newData = "{\"data\":{\"Error_Code\":\"" . $errorCode . "\",\"Error_Msg\":\"" .$errorMsg. "\"}}";
        echo $newData;
	}
	
	/* If Trip Details Is Register Successfully Then Send Response To Device*/
	public function tripDetailRegisterSuccess($lastInsertId) {
		
		$errorCode = "1";
	    $errorMsg = "Trip Details Inserted Successfully";
		$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"tripID\":\"".$lastInsertId."\"}}";
		echo $newData;
	}
	
	/* If EmailID Exist Then Send  Response To Device*/
	public function checkEmailID() {
		
		$errorCode = "2";
		$errorMsg = "Email Address Already Exist,Please Enter Another Address";
		$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response
		echo $newData;
	}
	
	/* If Login Success Then Send Success Response To Device*/
	
	/* If Login Success Then Send Success Response To Device*/
	
	/* 
	public function login_success($ID,$userType) {
		if($userType=='customer')
		{ 
			$tname ='userID';
			$GetUserData = mysql_query("SELECT * FROM user_registration WHERE USER_ID='$ID'");
			while($row = mysql_fetch_array($GetUserData)){
				
				$fName = $row['FIRST_NAME'];
				$lName = $row['LAST_NAME'];
				$email = $row['EMAIL_ID'];
				$mobile = $row['MOBILE_NUMBER'];
				$address = $row['ADDRESS_LINE1'];
				$profileImg = 'http://phbjharkhand.in/speedyTaxi/images/'.$row['PROFILE_PICTURE'];
			}
		}
		else
		{
			$tname ='userID';
			$GetUserData = mysql_query("SELECT * FROM driver_registration WHERE DRIVER_ID='$ID'");
			while($row = mysql_fetch_array($GetUserData)){
				
				$fName = $row['FIRST_NAME'];
				$lName = $row['LAST_NAME'];
				$email = $row['EMAIL_ID'];
				$mobile = $row['MOBILE_NUMBER'];
				$address = $row['ADDRESS_LINE1'];
				$profileImg = 'http://phbjharkhand.in/speedyTaxi/images/'.$row['PROFILE_PICTURE'];
			}
		}
		$newData="{\"data\":{\"Error_Code\":\"1\",\"Error_Msg\":\"Login Success\",\"userType\":".$userType.",\"".$tname."\":".$ID.",\"firstName\":\"" .$fName. "\",\"lastName\":\"" .$lName. "\",\"emailId\":\"" .$email. "\",\"mobileNumber\":\"" .$mobile. "\",\"addressOne\":\"" .$address. "\",\"profileImage\":\"" .$profileImg. "\"}}";
		echo $newData; 
	}
	*/
	
	/* If Login Failure Then Send Success Response To Device*/
	
	public function loginFail() {
		
		$errorCode = "2";
		$errorMsg = "Login Unsuccessful.Please Try Again";
		$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response
		echo $newData;
	}
	
	/* If Json Data Is Empty Then Send Response To Device*/
	public function noJsonData() {
		
		$errorCode = "3";
		$errorMsg = "No Json Data";
		$newData = "{\"data\":{\"Error_Code\":\"" . $errorCode . "\",\"Error_Msg\":\"" . $errorMsg . "\"}}";
		echo $newData;
			//No JSON DATA
	}
	
	/* Customer Forgot Password */
	public function userForgetPassword($EMAILID) {	
		/* Select user  as per email*/
			$QueryInfo = "SELECT password,emp_id FROM login_t WHERE login_user_id IN (select login_user_id from user_details_t where user_email_id='$EMAILID')";
			$Resultset = mysql_query($QueryInfo);
			if (mysql_num_rows($Resultset) > 0) {
				while ($result = mysql_fetch_array($Resultset)) {
					$pwd = $result['password'];
					$userName = $result['emp_id'];
					//$pwd = base64_decode($pwd);
				}				
				//Send Mail If the Record is Found
				$to = $EMAILID;
				// subject
				$subject = 'Book of Account User Password Information';
				// message
				$message ='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
							   <html xmlns="http://www.w3.org/1999/xhtml">
								<head>
								 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
								</head>
								<body style="font-family:Helvetica;">
								<div style="width:100%; margin:0px; padding:0px;" align="center">
								   <div style="width:50.5%;margin:0px auto;background:black; height:auto;padding:5px 2.5px 5px 2.5px;text-align:left;color:white">
								   <img src="http://phbjharkhand.in/bookOfAccounts/images/techila_logo.png">
								   </div>
								   <div style="width:50%;margin:0px auto;border:solid 1px gray;height:350px;padding:5px 3.5px 5px 5px;">
								   <table width="100%" align="center">
									<tr>
									<td>
									Dear User,<br/>
									<p>Welcome to Book of Account App</p>							
									<p style="font-size:15px;">Please find your login credentials:</p>
									</td>
									</tr>
									</table>
								    <table width="40%" align="left" cellpadding="5" cellspacing="2">									
									<tr>
									<td>
									<h4><strong>Username:</strong></h4></td><td style="font-size:14px; color:#396db5;">'.$userName.'</td>
									</tr>									
									<tr>
									<td>
									<h4><strong>Password:</strong></h4></td><td style="font-size:14px; color:#396db5;">'.$pwd.'</td>
									</tr>									
									<tr>
									<td><p>Regards,</p><p>Book of Accounts</p></td>
									</tr>
								   </div>
								   </div>
								</body>
							</html>';
							 							 
				$headers="";			 
				$headers.= "MIME-version: 1.0\n";
				$headers.= "Content-type: text/html; charset= iso-8859-1\n";
				$headers.= "From: info@phbjharkhand.in\r\n";
				mail($to, $subject, $message, $headers);
				
				/*Send the Success Message*/
				$errorCode = "1";
				$errorMsg = "Password is successfully sent to Your Registered Email Id ";
				$newData = "{\"data\":{\"Error_Code\":\"" . $errorCode . "\",\"Error_Msg\":\"" . $errorMsg . "\"}}";
				echo $newData;
			} 
			else{
				 /*Send the Failed Message*/
				$errorCode = "2";
				$errorMsg = "This User is Not Registered with us..Please Register With Us";
				$newData = "{\"data\":{\"Error_Code\":\"" . $errorCode . "\",\"Error_Msg\":\"" . $errorMsg . "\"}}";
				echo $newData;
			}
	}
	
	public function idToValue($value,$table,$field,$id)
	{
				$sqlSelect="select $value from $table where $field='$id'";
				$result=mysql_query($sqlSelect,$GLOBALS['link']);
				$valueReturn='NA';
				while($row=mysql_fetch_array($result))
				{
					 $valueReturn=$row[0];
				}
				return $valueReturn;
	}
	
	public function insufficientAmountJson() {
		
		$errorCode = "3";
		$errorMsg = "Insufficient Amount. Transaction is not possible.";
		$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response
		echo $newData;
	}
	
	public function sufficientAmountJson() {
		
		$errorCode = "1";
		$errorMsg = "Sufficient Amount.";
		$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response	
		echo $newData;
	}
	
	public function bankBalance($currentBalance) {
		
		$errorCode = "1";
		$errorMsg = "Bank Balance";
		$getList=array();
		$getGroupDetails['current_balance']=$currentBalance;							
		array_push($getList,$getGroupDetails);//converting array to string
		$newData=json_encode(array($getList));
		$newData=str_replace('\/', '/', $newData);
		$newData=substr($newData,1,strlen($newData)-2);				
		$newData="{\"data\":{\"Error_Code\":\"1\",\"Error_Msg\":\"Success\",\"result\":".$newData."}}";
		echo $newData;
	}
	
	public function sendTransactionDetails($to,$mailData)
	{	
					$rm=new Response_Methods();
					// subject
					$subject = 'Book of Account Apps Transaction Details';
					//print_r($mailData);
					 $fromBankBalance=$rm->idToValue('initial_bank_balance','bank_details_t','bank_id',$mailData['payment_from_bank_id']);	
					 $toBankBalance=$rm->idToValue('initial_bank_balance','bank_details_t','bank_id',$mailData['payment_to_bank_id']);	
					 $fromBankName=$rm->idToValue('bank_name','bank_details_t','bank_id',$mailData['payment_from_bank_id']);	
					 $toBankName=$rm->idToValue('bank_name','bank_details_t','bank_id',$mailData['payment_to_bank_id']);
					 $fromBankAccNo=$rm->idToValue('account_number','bank_details_t','bank_id',$mailData['payment_from_bank_id']);	
					 $toBankAccNo=$rm->idToValue('account_number','bank_details_t','bank_id',$mailData['payment_to_bank_id']);	
					 $companyName=$rm->idToValue('company_name','company_details_t','company_id',$mailData['company_id']);
					 $userFirstName=$rm->idToValue('user_fname','user_details_t','login_user_id',$mailData['userID']);
					 $userLastName=$rm->idToValue('user_lname','user_details_t','login_user_id',$mailData['userID']);
					 $paymentTypeMsg="";
					 if($mailData['payment_type']=="cheque")
					 {
						foreach($mailData as $key=>$val)
						{
							if($key=="chequeDetails")
								{
									foreach($val as $keyCheque=>$valCheque)
										{
										
										$paymentTypeMsg='<tr><td colspan="2"><h3><strong>Cheque Details:</strong></h4></td></tr>';
										$paymentTypeMsg.='<tr><td><h4><strong>Cheque Number:</strong></h4></td><td style="font-size:14px; color:#396db5;">'.$valCheque['cheque_number'].'</td></tr>';
										$paymentTypeMsg.='<tr><td><h4><strong>Cheque Issued Date:</strong></h4></td><td style="font-size:14px; color:#396db5;">'.$valCheque['cheque_date'].'</td></tr>';
										
										$paymentTypeMsg.='<tr><td><h4><strong>To Whom Issued:</strong></h4></td><td style="font-size:14px; color:#396db5;">'.$valCheque['to_whom_issued'].'</td></tr>';
										
																				
											
										}
								}
						}
						
					 }
					 else if($mailData['payment_type']=="net")
					 {
					 foreach($mailData as $key=>$val)
						{
							if($key=="netDetails")
								{
									foreach($val as $keyCheque=>$valCheque)
										{
										$paymentTypeMsg='<tr><td colspan="2"><h3><strong>Net Banking Details:</strong></h4></td></tr>';
										$paymentTypeMsg.='<tr><td><h4><strong>Net Issued Date:</strong></h4></td><td style="font-size:14px; color:#396db5;">'.$valCheque['cheque_date'].'</td></tr>';
										
										$paymentTypeMsg.='<tr><td><h4><strong>To Whom Issued:</strong></h4></td><td style="font-size:14px; color:#396db5;">'.$valCheque['to_whom_issued'].'</td></tr>';
																				
											
										}
								}
						}
					 }
					
					
					
					// message
				
					 $message ='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
							   <html xmlns="http://www.w3.org/1999/xhtml">
								<head>
								 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
								</head>
								<body style="font-family:Helvetica;">
								<div style="width:100%; margin:0px; padding:0px;" align="center">
								   <div style="width:50.5%;margin:0px auto;background:black; height:auto;padding:5px 2.5px 5px 2.5px;text-align:left;color:white;">
				                    <img src="images/techila_logo.png">
								   </div>
								   <div style="width:50%;margin:0px auto;border:solid 1px gray;height:1200px;padding:5px 3.5px 5px 5px;">
								   <table width="100%" align="center">
									<tr>
									<td>
									Dear Admin,<br/>
									<p>Welcome to Book of Account App</p>							
									<p style="font-size:15px;">Please find the transaction credentials :</p>
									</td>
									</tr>
									</table>
								    <table width="70%" align="left" cellpadding="5" cellspacing="2" style="margin-top:5%;">
									<tr>
									<td>
									<h4><strong>From Account :</strong></h4></td><td style="font-size:14px; color:#396db5;">'.$fromBankName.'</td>
									</tr>
									<tr>
									<td>
									<h4><strong>Destination Account:</strong></h4></td><td style="font-size:14px; color:#396db5;">'.$toBankName.'</td></tr>
									<tr>
									<td>
									<h4><strong>User:</strong></h4></td><td style="font-size:14px; color:#396db5;">'.$userFirstName.' '.$userLastName.'</td>
									</tr>
									<tr>
									<td>
									<h4><strong>Company:</strong></h4></td><td style="font-size:14px; color:#396db5;">'.$companyName.'</td></tr>
									<tr>
									<td>
									<h4><strong>Amount Transferred :</strong></h4></td><td style="font-size:14px; color:#396db5;">'.$mailData['amount'].'</td>
									</tr>
									<tr>
									<td>
									<h4><strong>Payment Type:</strong></h4></td><td style="font-size:14px; color:#396db5;">'.$mailData['payment_type'].'</td></tr>
									
									<tr>
									<td>
									<h4><strong>Payment Reason :</strong></h4></td><td style="font-size:14px; color:#396db5;">'.$mailData['payment_reason'].'</td>
									</tr>
									<tr>
									<td>
									<h4><strong>Available Balance in '.$fromBankName.':</strong></h4></td><td style="font-size:14px; color:#396db5;">'.$fromBankBalance.'</td></tr>
									<tr>
									<td>
									<h4><strong>Available Balance in '.$toBankName.':</strong></h4></td><td style="font-size:14px; color:#396db5;">'.$toBankBalance.'</td>
									</tr>';
									
									$message.=$paymentTypeMsg;
									
									$message.='<tr>
									<td><p>Regards,</p><p>Book of Accounts</p></td>
									</tr>
								   </div>
								   </div>
								</body>
							</html>';
							
							//echo $message;
													
					  $headers="";;
					  $headers.= "MIME-version: 1.0\n";
                      $headers.= "Content-type: text/html; charset= iso-8859-1\n";
                      $headers.= "From: info@phbjharkhand.in\r\n";
                      mail($to, $subject, $message, $headers);
	}
	
	
		public function userRequestSuccessJson($lastInsertId)
	{
		//echo 'test Insert';	
		$getList=array();
		$dataQueryInfo = "SELECT status FROM user_requests_t WHERE user_request_id='$lastInsertId'";
		$dataResultSet = mysql_query($dataQueryInfo,$GLOBALS['link']);
		$getGroupDetails['user_request_id']=$lastInsertId;
		
			if(mysql_num_rows($dataResultSet) > 0){
			
				while($row=mysql_fetch_array($dataResultSet)){
					
					
					$getGroupDetails['status']=$row['status'];	
									
					array_push($getList,$getGroupDetails);//converting array to string
				}
					$newData=json_encode(array($getList));
					$newData=str_replace('\/', '/', $newData);
					$newData=substr($newData,1,strlen($newData)-2);
				
					$newData="{\"data\":{\"Error_Code\":\"1\",\"Error_Msg\":\"Success\",\"result\":".$newData."}}";
					return $newData;   
			}
			else{			
					$errorCode = "2";
					$errorMsg = "Result Not Found";
					$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; 
					return $newData;
				}	
	}
	
	public function userRequestFailJson()
	{
				$errorCode = "2";
				$errorMsg = "User Request Not Made. Please try again";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}
	
	
	public function getUserGCMREGID($login_user_id)
	{
				$sqlSelect="select GCM_REGID from GCM_USERS_T where device_id=(select device_id from login_t where login_user_id='$login_user_id')";
				$result=mysql_query($sqlSelect,$GLOBALS['link']);
				$GCM_REGID='NA';
				while($row=mysql_fetch_array($result))
				{
					 $GCM_REGID=$row[0];
				}
				return $GCM_REGID;
	}
	
	
	
	public function requestStatusSuccess($lastInserted_payment_id)
	{
				
				$errorCode = "1";
				$errorMsg = "User Requests Status Updated Successfully";
				if($lastInserted_payment_id!=0)
				{
					$getList=array();
					$getGroupDetails['payment_id']=$lastInserted_payment_id;										
					array_push($getList,$getGroupDetails);//converting array to string
						
					$newData=json_encode(array($getList));
					$newData=str_replace('\/', '/', $newData);
					$newData=substr($newData,1,strlen($newData)-2);				
					$newData="{\"data\":{\"Error_Code\":\"1\",\"Error_Msg\":\"Success\",\"result\":".$newData."}}";
				}
				else
				{
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				}
				return $newData;
	}
	
	
	public function requestStatusFail()
	{
				$errorCode = "2";
				$errorMsg = "User Requests Status not Updated. Please try again";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}
	
	
	
	
	function getSpecificDetailsTwo($id1,$id2,$table,$field1,$field2)
	{
	$sqlList="select * from $table where $field1=$id1 and $field2='$id2'";
	$result=mysql_query($sqlList,$GLOBALS['link']);
	return $result;
	}
	
	
	
	public function getPendingRequest($company_id)
	{
				$sqlSelect="select count(user_request_id) from user_requests_t where company_id='$company_id' and status='Pending'";
				$result=mysql_query($sqlSelect,$GLOBALS['link']);
				$pending_request_count=0;
				//$pending_request_count=mysql_num_rows($result);
				
				while($row=mysql_fetch_array($result))
				{
					 $pending_request_count=$row[0];
				}
				
				return $pending_request_count;
	}
	
	
	
	function getSpecificDetailsRequest($id1,$id2,$table,$field1,$field2)
	{
	$sqlList="select * from $table where $field1=$id1 and $field2 !='$id2'";
	$result=mysql_query($sqlList,$GLOBALS['link']);
	return $result;
	}
	
	
	public function insufficient_balance() {			
			$errorCode = "3";
			$errorMsg = "Insufficient Amount";
			$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response
			echo $newData;
		}
		
		
		
		
		
		
		
		
	public function getPaymentDetails($login_user_id,$payment_type,$date1,$date2)		
		{
		
		$date1=$date1.' 00:00:00';
		$date2=$date2.' 23:59:59';
		$rm=new Response_Methods();
		
		if($payment_type==1)
		{
			$sqlSelect="select payment_from_bank_id, payment_created_date, amount from payment_details_t where login_user_id=$login_user_id and payment_created_date BETWEEN '$date1' and '$date2' and lower(payment_type)='withdrawal' ";
		
		$dataResultSet = mysql_query($sqlSelect,$GLOBALS['link']);
		
		$getList=array();
			if(mysql_num_rows($dataResultSet) > 0){
			
				while($row=mysql_fetch_array($dataResultSet)){
					
					$from_bank_id=$row['payment_from_bank_id'];
					$fromBankName=$rm->idToValue('bank_name','bank_details_t','bank_id',$from_bank_id);
					$getGroupDetails['fromBankName']=$fromBankName;
					$getGroupDetails['payment_date']=$row['payment_created_date'];
					$getGroupDetails['amount']=$row['amount'];
					$getGroupDetails['type']='withdrawl';					
									
					array_push($getList,$getGroupDetails);//converting array to string
				}
					$newData=json_encode(array($getList));
					$newData=str_replace('\/', '/', $newData);
					$newData=substr($newData,1,strlen($newData)-2);
				
					$newData="{\"data\":{\"Error_Code\":\"1\",\"Error_Msg\":\"Success\",\"result\":".$newData."}}";
					return $newData;   
			}
			else{			
					$errorCode = "2";
					$errorMsg = "Result Not Found";
					$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; 
					return $newData;
				}
				
		}
		
		
		
		else if($payment_type==2)
		{
			$sqlSelect="select payment_id, payment_from_bank_id, payment_to_bank_id, payment_created_date, amount, payment_type from payment_details_t where login_user_id=$login_user_id and payment_created_date BETWEEN '$date1' and '$date2' and (lower(payment_type)='cheque' or lower(payment_type)='net'   )";
		
		$dataResultSet = mysql_query($sqlSelect,$GLOBALS['link']);
		
		$getList=array();
			if(mysql_num_rows($dataResultSet) > 0){
			
				while($row=mysql_fetch_array($dataResultSet)){
					
					$from_bank_id=$row['payment_from_bank_id'];
					$to_bank_id=$row['payment_to_bank_id'];
					
					$payment_id=$row['payment_id'];
					
					$fromBankName=$rm->idToValue('bank_name','bank_details_t','bank_id',$from_bank_id);
					$toBankName=$rm->idToValue('bank_name','bank_details_t','bank_id',$to_bank_id);
					
					$getGroupDetails['fromBankName']=$fromBankName;
					$getGroupDetails['toBankName']=$toBankName;
					
					$payment_type=$row['payment_type'];
					if(strtolower($payment_type)=="cheque")
					{
					$cheque_date=$rm->idToValue('cheque_date','cheque_details_t','payment_id',$payment_id);
					$getGroupDetails['cheque_date']=$cheque_date;
					$getGroupDetails['type']='cheque';
					}
					else
					{
					$getGroupDetails['payment_date']=$row['payment_created_date'];
					$getGroupDetails['type']='net';
					}
					$getGroupDetails['amount']=$row['amount'];					
									
					array_push($getList,$getGroupDetails);//converting array to string
				}
					$newData=json_encode(array($getList));
					$newData=str_replace('\/', '/', $newData);
					$newData=substr($newData,1,strlen($newData)-2);
				
					$newData="{\"data\":{\"Error_Code\":\"1\",\"Error_Msg\":\"Success\",\"result\":".$newData."}}";
					return $newData;   
			}
			else{			
					$errorCode = "2";
					$errorMsg = "Result Not Found";
					$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; 
					return $newData;
				}
				
		}
		
		
				
		}
		
		
		
	
	
	
	
	
	
	
	
	
	
}// End of class
?>
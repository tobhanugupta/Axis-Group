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
		
		
	public function login_success($USERNAME,$ENCRYPTEDPWD)
		{
			$getArrayList=array();
			$dataQueryInfo = "SELECT login_user_id,usertype FROM login_t WHERE emp_id= '$USERNAME' AND password='$ENCRYPTEDPWD' AND user_status=1";
			$dataResultSet = mysql_query($dataQueryInfo,$GLOBALS['link']);
			if(mysql_num_rows($dataResultSet) > 0)
			{
					while($row=mysql_fetch_array($dataResultSet))
					{														
						$login_user_id=$row['login_user_id'];
						$usertype=$row['usertype'];
						$getGrpDetails['loginID']=$login_user_id;
						$getGrpDetails['type']=ucfirst($usertype);					
						array_push($getArrayList,$getGrpDetails);
					}
									
				$newData=json_encode(array($getArrayList));
				$newData=str_replace('\/', '/', $newData);
				$newData=substr($newData,1,strlen($newData)-2);			
				$newData="{\"data\":{\"Error_Code\":\"1\",\"Error_Msg\":\"Login Successful\",\"result\":".$newData."}}";	
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
		$dataQueryInfo = "SELECT user_fname,user_lname FROM user_details_t WHERE user_id='$lastInsertId'";
		$dataResultSet = mysql_query($dataQueryInfo,$GLOBALS['link']);
		
			if(mysql_num_rows($dataResultSet) > 0){
			
				while($row=mysql_fetch_array($dataResultSet)){
					
					$getGroupDetails['userFullName']=ucfirst($row['user_fname']).' '.ucfirst($row['user_lname']);						
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
					$getGroupDetails['paymentBankID']=$row['payment_bank_id'];	
									
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
				$sqlSelect="select user_email_id from user_details_t where login_user_id=(select login_user_id from login_t where emp_id='$id')";
				$result=mysql_query($sqlSelect,$GLOBALS['link']);
				$adminEmailId=0;
				while($row=mysql_fetch_array($result))
				{
					 $adminEmailId=$row[0];
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
					$pwd = base64_decode($pwd);
				}				
				//Send Mail If the Record is Found
				$to = $EMAILID;
				// subject
				$subject = 'Book of Account User Password Information';
				// message
				echo $message ='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
							   <html xmlns="http://www.w3.org/1999/xhtml">
								<head>
								 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
								</head>
								<body style="font-family:Helvetica;">
								<div style="width:100%; margin:0px; padding:0px;" align="center">
								   <div style="width:50.5%;margin:0px auto;background:black; height:auto;padding:5px 2.5px 5px 2.5px;text-align:left;color:white">
								   <img src="images/techila_logo.png">
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
				//echo $newData;
			} 
			else{
				 /*Send the Failed Message*/
				$errorCode = "2";
				$errorMsg = "This User is Not Registered with us..Please Register With Us";
				$newData = "{\"data\":{\"Error_Code\":\"" . $errorCode . "\",\"Error_Msg\":\"" . $errorMsg . "\"}}";
				echo $newData;
			}
	}
}
?>
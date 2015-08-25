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
		
	/* Check ssl certificate error */
	public function ssl_error() {			
		
		$errorCode = "403";
		$errorMsg = "HTTPS Required";
		$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response
		echo $newData;
	}
	
	public function login_success($USERNAME,$PASSWORD)
		{
			$getArrayList=array();
			//$dataQueryInfo = "SELECT login_id,user_name_f FROM login_t WHERE user_name_f= '$USERNAME' AND password_f='$PASSWORD'";
			$dataQueryInfo = "SELECT u.user_id,u.user_name_f,u.description_f,u.profile_id_f,u.email_f, u.profile_pic_f,u.pet_type_f,u.pet_name_f,u.pet_dob_f,u.species_f FROM user_details_t u, login_t l WHERE (u.user_id=l.user_id_fk) and (l.user_name_f= '$USERNAME' AND BINARY l.password_f='$PASSWORD')";
			
			$followArray=array();
			$dataResultSet = mysql_query($dataQueryInfo,$GLOBALS['link']);
			$rm=new Response_Methods();
			$getGrpDetails=array();
			if(mysql_num_rows($dataResultSet) > 0)
			{
			
					while($row=mysql_fetch_array($dataResultSet))
					{														
						$login_user_id=$row['login_id'];
						$user_id=$row['user_id'];
						$username=$row['user_name_f'];
						$device_id=$row['device_id_f'];
						$email=$row['email_f'];
						$description=$row['description_f'];	
						$profile_id=$row['profile_id_f'];
						$profile_pic=$row['profile_pic_f'];

                                                $petType="";
                                                if($row['pet_type_f'])
                                                $petType=$row['pet_type_f'];
						$petName=$row['pet_name_f'];
						$petDob=$row['pet_dob_f'];
                                                $petSpecies=$row['species_f']; 
						
						/*
						$getGrpDetails['device_id']=$device_id;
						$getGrpDetails['email']=$email;
						$getGrpDetails['description']=$description;
						$getGrpDetails['friend_count']=$friend_count;	
						*/
						$getGrpDetails['user_id']=$user_id;
						$getGrpDetails['userName']=$username;
                                                $getGrpDetails['pet_type']=$petType;	
						$getGrpDetails['pet_name']=$petName;	
						$getGrpDetails['pet_dob']=$petDob;	
						$getGrpDetails['pet_bio']=$description;	
                                                $getGrpDetails['species']=$petSpecies;  
						//$getGrpDetails['profileId']=$profile_id;	
						$getGrpDetails['profile_pic_url']=$profile_pic;	
						$followArray=$rm->followerFollowingCount($user_id);
						//print_r($followArray);
						$getGrpDetails['followersCount']=$followArray['followers'];	
						$getGrpDetails['followingCount']=$followArray['following'];	
						array_push($getArrayList,$getGrpDetails);
						
					}
					
					
				
						
					
										
				//print_r($getArrayList);						
				$newData=json_encode($getArrayList);
				$newData=str_replace('\/', '/', $newData);
				$newData=substr($newData,1,strlen($newData)-2);	
				$newData="{\"data\":{\"Error_Code\":\"1\",\"Error_Msg\":\"Login Successful\",\"result\":".$newData."}}";					
				return $newData; 
			}	
			else
				{
					$errorCode = "0";
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
				                    <img src="images/techila_logo.png">
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
	
	
	
	
	
	public function getUserDeviceId($login_user_id)
	{
				$sqlSelect="select device_id from login_t where login_user_id='$login_user_id'";
				$result=mysql_query($sqlSelect,$GLOBALS['link']);
				$deviceID='NA';
				while($row=mysql_fetch_array($result))
				{
					 $deviceID=$row[0];
				}
				return $deviceID;
	}
	
	
	public function userRegisterSuccessJson($lastInsertId)
	{
		$getList=array();
		$dataQueryInfo = "SELECT user_id,user_name_f,profile_pic_f,pet_name_f,pet_type_f,pet_dob_f,description_f,species_f FROM user_details_t WHERE user_id='$lastInsertId'";
		$dataResultSet = mysql_query($dataQueryInfo,$GLOBALS['link']);
		$rm=new Response_Methods();
		$followArray=array();
		$getGroupDetails=array();
		
			if(mysql_num_rows($dataResultSet) > 0){
			
				while($row=mysql_fetch_array($dataResultSet)){
					
					$getGroupDetails['user_id']=$row['user_id'];		
					$getGroupDetails['profile_pic_url']=$row['profile_pic_f'];	
					$getGroupDetails['userName']=$row['user_name_f'];

                                        $getGroupDetails['pet_name']=$row['pet_name_f'];	
					$getGroupDetails['pet_type']=$row['pet_type_f'];	
					$getGroupDetails['pet_dob']=$row['pet_dob_f'];	
					$getGroupDetails['pet_bio']=$row['description_f'];	
                                        $getGroupDetails['species']=$row['species_f'];		
					//echo 'test';
					$followArray=$rm->followerFollowingCount($getGroupDetails['user_id']);
						//print_r($followArray);
					$getGroupDetails['followersCount']=$followArray['followers'];	
					$getGroupDetails['followingCount']=$followArray['following'];	
					//echo 'test2';
					//print_r($getGroupDetails);	
					array_push($getList,$getGroupDetails);//converting array to string
				}
					$newData=json_encode($getList);
					$newData=str_replace('\/', '/', $newData);
					$newData=substr($newData,1,strlen($newData)-2);
				
					$newData="{\"data\":{\"Error_Code\":\"1\",\"Error_Msg\":\"Success\",\"result\":".$newData."}}";
					return $newData;   
			}
			else{			
					$errorCode = "0";
					$errorMsg = "Registration Failed";
					$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; 
					return $newData;
				}	
	}
	
	
	public function userRegisterFailJson()
	{
				$errorCode = "0";
				$errorMsg = "User Registration Failed";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}
	
	public function userExistJson()
	{
				$errorCode = "2";
				$errorMsg = "User Registration Failed As Username Already Exists";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}
	
	/* If EmailID Exist Then Send  Response To Device*/
	public function emailExistJson() {
		
		$errorCode = "3";
		$errorMsg = "Email Address Already Exist,Please Enter Another Address";
		$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response
		echo $newData;
	}
	
	


function rand_str( $len, $rep )
{
  // The alphabet the random string consists of
  $abc = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

  // The default length the random key should have
  $defaultLength = 3;

  // The default count of repetitions of a char in the random string
  $defaultRepetitions = 1;

  // Ensure $len is a valid number
  // Should be less than or equal to strlen( $abc ) but at least $defaultLength
  $len = max( min( intval( $len ), strlen( $abc )), $defaultLength );

  // Max. repetitions of a char
  // Should be less than or equal to $len but at least $defaultRepetitions
  $rep = max( min( intval( $rep ), $len ), $defaultRepetitions );

  // Expand $abc. Of course you may concatenate only parts of $abc here, too.
  $abc = str_repeat( $abc, $rep );

  // Return snippet of random string as random string
  return substr( str_shuffle( $abc ), 0, $len );
}


function checkUserValidation($value,$field)
{
$dataQueryInfo = "SELECT $field FROM user_details_t WHERE $field='$value'";
$dataResultSet = mysql_query($dataQueryInfo,$GLOBALS['link']);		
if(mysql_num_rows($dataResultSet) > 0)			
	return 0;
else
	return 1;
	
}


//function for getting follower and following count against user id

function followerFollowingCount($user_id)
{
$dataQueryFollowList = "SELECT (SELECT count(*) FROM follow_t WHERE follower_user_id_fk = $user_id ) AS following,(SELECT count(*) FROM follow_t WHERE following_user_id_fk = $user_id) AS followers";
				$FollowResultSet = mysql_query($dataQueryFollowList,$GLOBALS['link']);
				$follow=array();
				if(mysql_num_rows($FollowResultSet) > 0)
				{			
					while($rowFollow=mysql_fetch_array($FollowResultSet))
					{														
						$following=$rowFollow['following'];
						$followers=$rowFollow['followers'];
						$follow=array('followers'=>$followers,'following'=>$following);	
						//print_r($follow);
						//die();
						return $follow;
					}
				}	
}



function delete($table,$field,$value)
{
$sqlDelete="delete from $table where $field=$value";
mysql_query($sqlDelete,$GLOBALS['link']);
}


function getPosts($userid)
{

$sqlFetchPosts="SELECT U.user_name_f, U.profile_pic_f, N.post_id, N.post_description_f, N.post_date_f,N.user_id_fk,N.post_image_f, N.like_count_f
FROM user_details_t U, news_feeds_t N, friends_t F
WHERE 
N.user_id_fk = U.user_id
AND 

CASE
WHEN F.friend_one = $userid
THEN F.friend_two = N.user_id_fk
WHEN F.friend_two= $userid
THEN F.friend_one= N.user_id_fk
END

AND 
F.status > '0'
ORDER BY N.post_id DESC;";

			$dataResultSet = mysql_query($sqlFetchPosts,$GLOBALS['link']);
			$rm=new Response_Methods();
			$getGrpDetails=array();
			$getArrayList=array();
			if(mysql_num_rows($dataResultSet) > 0)
			{			
					while($row=mysql_fetch_array($dataResultSet))
					{														
						
						
						$post_id=$row['post_id'];
						$post_description=$row['post_description_f'];
						$post_image=$row['post_image_f'];						
						$like_count=$row['like_count_f'];
						$post_date=$row['post_date_f'];
						
						$post_user_id=$row['user_id_fk'];
						$user_name=$row['user_name_f'];
						$profilePic=$row['profile_pic_f'];

                                                $checkLike=$rm->checkAlreadyLiked($userid,$post_id);
						 
						   if($checkLike>0)							
							$like_status="1";
						   else
						   $like_status="0";
						
						
					
						$getGrpDetails['post_id']=$post_id;
						$getGrpDetails['post_description']=$post_description;
						$getGrpDetails['post_image_url']=$post_image;
						$getGrpDetails['like_count']=$like_count;
                                                $getGrpDetails['like_status']=$like_status;	
						$getGrpDetails['post_date']=$post_date;	
						
						$getGrpDetails['user_name']=$user_name;//username posted
						$getGrpDetails['post_user_id']=$post_user_id;//userid posted newsfeed
						$getGrpDetails['profilePic']=$profilePic;	
						
						//$getGrpDetails['profileId']=$profile_id;	
						/*
						$followArray=$rm->followerFollowingCount($userid);
						//print_r($followArray);
						$getGrpDetails['followersCount']=$followArray['followers'];	
						$getGrpDetails['followingCount']=$followArray['following'];
                                                 */	
						array_push($getArrayList,$getGrpDetails);
						
					}
										
				//print_r($getArrayList);						
				$followArray=$rm->followerFollowingCount($userid);
						//print_r($followArray);
				$followersCount=$followArray['followers'];	
				$followingCount=$followArray['following'];					
				$newData=json_encode(array($getArrayList));
				$newData=str_replace('\/', '/', $newData);
				$newData=substr($newData,1,strlen($newData)-2);	
				$newData="{\"data\":{\"Error_Code\":\"1\",\"Error_Msg\":\"News Feed List\",\"followersCount\":\"$followersCount\",\"followingCount\":\"$followingCount\",\"result\":".$newData."}}";					
				return $newData; 
			}	
			else
				{
					$errorCode = "0";
					$errorMsg = "No Newsfeeds Available";
					$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response	
					return $newData;
				 //Login Unsuccessful
				}



}




public function likeSuccessful()
	{
				$errorCode = "2";
				$errorMsg = "Like Done Successfully";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}

public function alreadyLiked()
	{
				$errorCode = "0";
				$errorMsg = "This post is already liked by you.";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}

public function likeUnSuccessful()
	{
				$errorCode = "0";
				$errorMsg = "Like Not Done";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}	
	
public function postCreationSuccessJson()
	{
				$errorCode = "1";
				$errorMsg = "News Feed Created Successfully";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}
	
	public function postCreationFailJson()
	{
				$errorCode = "0";
				$errorMsg = "News Feed Not Created";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}	




/* updated on 20-07-2015 */				
function getPhotos($userid)
{

			$sqlFetchPhotos="select post_id,post_image_f from news_feeds_t where user_id_fk=$userid and post_image_f is not null order by post_id";
			$dataResultSet = mysql_query($sqlFetchPhotos,$GLOBALS['link']);
			$rm=new Response_Methods();
			$getGrpDetails=array();
			$getArrayList=array();
			if(mysql_num_rows($dataResultSet) > 0)
			{			
					while($row=mysql_fetch_array($dataResultSet))
					{	
						
						$post_id=$row['post_id'];
						$post_image=$row['post_image_f'];
						$getGrpDetails['photo_image_url']=$post_image;
						$getGrpDetails['post_id']=$post_id;	
						$getPostIds[]=$post_id;	
						array_push($getArrayList,$getGrpDetails);
					}
										
				//print_r($getArrayList);	
				$msxImageId= max($getPostIds);					
				$newData=json_encode(array($getArrayList));
				$newData=str_replace('\/', '/', $newData);
				$newData=substr($newData,1,strlen($newData)-2);	
				$newData="{\"data\":{\"Error_Code\":\"5\",\"Error_Msg\":\"User Photos List\",\"postId\":\"$msxImageId\",\"result\":".$newData."}}";					
				return $newData; 
			}	
			else
				{
					$errorCode = "15";
					$errorMsg = "No Photos Available";
					$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response	
					return $newData;
				 //Login Unsuccessful
				}



}

/* old Function upto 19-07-2015
function getPhotos($userid)
{

			$sqlFetchPhotos="select post_id,post_image_f from news_feeds_t where user_id_fk=$userid and post_image_f is not null order by post_id";
			$dataResultSet = mysql_query($sqlFetchPhotos,$GLOBALS['link']);
			$rm=new Response_Methods();
			$getGrpDetails=array();
			$getArrayList=array();
			if(mysql_num_rows($dataResultSet) > 0)
			{			
					while($row=mysql_fetch_array($dataResultSet))
					{	
						
						$post_id=$row['post_id'];
						$post_image=$row['post_image_f'];
						$getGrpDetails['photo_image_url'][]=$post_image;
						$getArrayList[]=$post_id;	
						//array_push($getArrayList,$getGrpDetails);
					}
										
				//print_r($getArrayList);	
				$msxImageId= max($getArrayList);					
				$newData=json_encode(array($getGrpDetails));
				$newData=str_replace('\/', '/', $newData);
				$newData=substr($newData,1,strlen($newData)-2);	
				$newData="{\"data\":{\"Error_Code\":\"5\",\"Error_Msg\":\"User Photos List\",\"postId\":\"$msxImageId\",\"result\":".$newData."}}";					
				return $newData; 
			}	
			else
				{
					$errorCode = "0";
					$errorMsg = "No Photos Available";
					$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response	
					return $newData;
				 //Login Unsuccessful
				}



}

*/


public function friendRequestSuccess()
	{
				$errorCode = "1";
				$errorMsg = "Friendship Request Sent Successfully.";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}
	
public function friendRequestFail()
	{
				$errorCode = "13";
				$errorMsg = "Friendship Request not sent.";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}	
	
public function friendRequestFailforSameUser()
	{
				$errorCode = "11";
				$errorMsg = "Friendship Request could not be sent to same user.";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}	
	
	
	
public function checkExistingFriendShip($userId, $friendId)
	{
				
				 $sqlCheckFriend="SELECT friend_id FROM friends_t WHERE(friend_one=$userId OR friend_two=$userId) AND (friend_one=$friendId OR friend_two=$friendId)";
				$dataResultSet = mysql_query($sqlCheckFriend,$GLOBALS['link']);
				$countRows=mysql_num_rows($dataResultSet);
				return $countRows;
				
	}	
	
public function friendshipAlreadyExists()
	{
				$errorCode = "12";
				$errorMsg = "Friendship Already Exists between them.";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}
	
	
public function friendRequestConfirmed()
	{
				$errorCode = "2";
				$errorMsg = "Friendship Confirmed Successfully.";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}
	
public function friendRequestFailed()
	{
				$errorCode = "14";
				$errorMsg = "Friendship not confirmed.";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}	
	

public function getAllFriends($USERID)
	{
		 $sqlFetchAllFriends="SELECT F.status, U.user_name_f, U.profile_pic_f FROM user_details_t U, friends_t F WHERE
CASE WHEN F.friend_one = $USERID THEN F.friend_two = U.user_id WHEN F.friend_two= $USERID THEN F.friend_one= U.user_id END AND
F.status='1'";

			$dataResultSet = mysql_query($sqlFetchAllFriends,$GLOBALS['link']);
			$rm=new Response_Methods();
			$getGrpDetails=array();
			$getArrayList=array();
			if(mysql_num_rows($dataResultSet) > 0)
			{			
					while($row=mysql_fetch_array($dataResultSet))
					{	
						
						$profile_pic=$row['profile_pic_f'];
						$user_name=$row['user_name_f'];
						$getGrpDetails['user_name']=$user_name;	
						$getGrpDetails['profile_pic_url']=$profile_pic;	
						array_push($getArrayList,$getGrpDetails);
					}
										
				//print_r($getArrayList);						
				$newData=json_encode(array($getArrayList));
				$newData=str_replace('\/', '/', $newData);
				$newData=substr($newData,1,strlen($newData)-2);	
				$newData="{\"data\":{\"Error_Code\":\"1\",\"Error_Msg\":\"Friend List\",\"result\":".$newData."}}";					
				return $newData; 
			}	
			else
				{
					$errorCode = "0";
					$errorMsg = "No Friends Yet";
					$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response	
					return $newData;
				 //Login Unsuccessful
				}
				
	}
	
	
public function friendFollowSame()
{
			$errorCode = "16";
			$errorMsg = "User Cannot follow himself.";
			$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
			return $newData;
}	
	
public function makeFollowSuccess($userId,$friendId)
{

			$followArrayUser=array();
                        $followArrayFriend=array();
			$getGrpDetails=array();
			$getArrayList=array();
			
			$rm=new Response_Methods();

			$followArrayUser=$rm->followerFollowingCount($userId);			
			$getGrpDetails['followersCountUser']=$followArrayUser['followers'];	
			$getGrpDetails['followingCountUser']=$followArrayUser['following'];	
			
			$followArrayFriend=$rm->followerFollowingCount($friendId);			
			$getGrpDetails['followersCountFriend']=$followArrayFriend['followers'];	
			$getGrpDetails['followingCountFriend']=$followArrayFriend['following'];	
	
			array_push($getArrayList,$getGrpDetails);
			
			$newData=json_encode($getArrayList);
			$newData=str_replace('\/', '/', $newData);
			$newData=substr($newData,1,strlen($newData)-2);	
			$newData="{\"data\":{\"Error_Code\":\"3\",\"Error_Msg\":\"Follow Done Successfully\",\"result\":".$newData."}}";					
			return $newData;
			

}

public function makeFollowFail()
{

			$errorCode = "18";
			$errorMsg = "Follow Not Done.";
			$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
			return $newData;

}

public function followAlreadyExists()
	{
				$errorCode = "17";
				$errorMsg = "Follow Relationship Already Exists between them.";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
	}


public function checkExistingFollow($userId, $friendId)
	{
				
				 $sqlCheckFollow="SELECT follow_id FROM follow_t WHERE(follower_user_id_fk=$userId OR following_user_id_fk=$userId) AND (follower_user_id_fk=$friendId OR following_user_id_fk=$friendId)";
				$dataResultSet = mysql_query($sqlCheckFollow,$GLOBALS['link']);
				$countRows=mysql_num_rows($dataResultSet);
				return $countRows;
				
	}	
	
	
function getPhotosUrlOnly($userid)
{

			$sqlFetchPhotos="select post_image_f from news_feeds_t where user_id_fk=$userid and post_image_f is not null order by post_id";
			$dataResultSet = mysql_query($sqlFetchPhotos,$GLOBALS['link']);
			$rm=new Response_Methods();
			$getGrpDetails=array();
			$getArrayList=array();
			if(mysql_num_rows($dataResultSet) > 0)
			{			
					while($row=mysql_fetch_array($dataResultSet))
					{	
						
						$post_image=$row['post_image_f'];
						$getGrpDetails['post_image_url']=$post_image;	
						array_push($getArrayList,$getGrpDetails);
					}
			}
			return $getArrayList;
}						


public function getFriendDetails($USERID, $FRIENDID)
	{
		   $sqlFetchAllFriends="SELECT F.status, U.user_name_f, U.profile_pic_f,U.description_f FROM user_details_t U, friends_t F WHERE
CASE WHEN F.friend_one = $USERID THEN F.friend_two = U.user_id WHEN F.friend_two= $USERID THEN F.friend_one= U.user_id END AND
U.user_id=$FRIENDID";

			$dataResultSet = mysql_query($sqlFetchAllFriends,$GLOBALS['link']);
			$rm=new Response_Methods();
			$picsArray=$rm->getPhotosUrlOnly($FRIENDID);
			
			$getGrpDetails=array();
			$getArrayList=array();
			
			if(mysql_num_rows($dataResultSet) > 0)
			{			
					while($row=mysql_fetch_array($dataResultSet))
					{	
												
						$user_name=$row['user_name_f'];
						$profile_pic=$row['profile_pic_f'];
						$user_description=$row['description_f'];
						$friend_status=$row['status'];
						
							
						
						$getGrpDetails['user_name']=$user_name;	
						$getGrpDetails['profile_pic_url']=$profile_pic;						
						$getGrpDetails['about_user']=$user_description;	
						$getGrpDetails['friend_status']=$friend_status;	
						
						$checkFollow=$rm->checkExistingFollow($USERID, $FRIENDID);
						if($checkFollow>0)
						{
						$getGrpDetails['follow_status']=1;		
						}
						else
						{
						$getGrpDetails['follow_status']=0;					
						}
						
						$followArray=$rm->followerFollowingCount($FRIENDID);						
						$getGrpDetails['followersCount']=$followArray['followers'];	
						$getGrpDetails['followingCount']=$followArray['following'];	
						
					}
										
				//print_r($getArrayList);	
				if(!empty($picsArray))
				{
				$getGrpDetails['photo_url']=$picsArray;	
				}
				else
				{
				$getGrpDetails['photo_url']=null;	
				}
					
				array_push($getArrayList,$getGrpDetails);		
				$newData=json_encode(array($getArrayList));
				$newData=str_replace('\/', '/', $newData);
				$newData=substr($newData,1,strlen($newData)-2);	
				$newData="{\"data\":{\"Error_Code\":\"1\",\"Error_Msg\":\"Friend Profile with Post Photos\",\"result\":".$newData."}}";					
				return $newData; 
			}	
			else
				{
					$errorCode = "0";
					$errorMsg = "No Friends Yet";
					$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response	
					return $newData;
				 //Login Unsuccessful
				}
				
	}

public function inValidServerMethod()
	{

			$errorCode = "0";
			$errorMsg = "Invalid Server Method is called.";
			$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
			return $newData;

	}

public function getAllUsers($searchType,$USERID,$searchValue)
	{
	
		
               if($searchType=="username")
		{
		  //search users on the basis of username
		  $username=$searchValue;
		 $sqlFetchAllUsers="SELECT U.user_id, U.user_name_f, U.profile_pic_f,MAX(N.like_count_f) as like_count FROM user_details_t U left join news_feeds_t N on N.user_id_fk = U.user_id where (U.user_id<>$USERID and U.user_name_f LIKE '%$username%') group by U.user_id order by U.user_id desc";
                $errorSuccessCode=2;
		}

               else if($searchType=="petType")
		{
		
		//search users on the basis of petType
		 $petType=$searchValue;
		 $sqlFetchAllUsers="SELECT U.user_id, U.user_name_f, U.profile_pic_f,MAX(N.like_count_f) as like_count FROM user_details_t U left join news_feeds_t N on N.user_id_fk = U.user_id where (U.user_id<>$USERID  and U.pet_type_f LIKE '%$petType%') group by U.user_id order by like_count  desc";
		 $errorSuccessCode=3;
		}

               else if(trim($searchType)=="species")
		{
		
		//search users on the basis of petType
		 $species=$searchValue;
		 $sqlFetchAllUsers="SELECT U.user_id, U.user_name_f, U.profile_pic_f,MAX(N.like_count_f) as like_count, U.species_f FROM user_details_t U left join news_feeds_t N on N.user_id_fk = U.user_id where (U.user_id<>$USERID  and U.species_f LIKE '%$species%') group by U.user_id order by like_count  desc";
		 $errorSuccessCode=4;
		}

               else
                {
                   $sqlFetchAllUsers="SELECT U.user_id, U.user_name_f, U.profile_pic_f,MAX(N.like_count_f) as like_count FROM user_details_t U left join news_feeds_t N on N.user_id_fk = U.user_id where (U.user_id<>$USERID and U.user_id>43)group by U.user_id order by like_count desc";
                   $errorSuccessCode=1;	
                }
	

			$dataResultSet = mysql_query($sqlFetchAllUsers,$GLOBALS['link']);
			$rm=new Response_Methods();
			$getGrpDetails=array();
			$getArrayList=array();
			if(mysql_num_rows($dataResultSet) > 0)
			{			
					while($row=mysql_fetch_array($dataResultSet))
					{	
						
						$friend_user_id=$row['user_id'];
						$user_name=$row['user_name_f'];
						$profile_pic=$row['profile_pic_f'];
						$like_count=$row['like_count'];
						
						$getGrpDetails['user_id']=$friend_user_id;	
						$getGrpDetails['user_name']=$user_name;	
						$getGrpDetails['profile_pic_url']=$profile_pic;	
						$getGrpDetails['like_count']=$like_count;
$aboutUser=$rm->idToValue('description_f','user_details_t','user_id',$friend_user_id); //getting user description
						
                                                 if($aboutUser)      
						$getGrpDetails['about_user']=$aboutUser;//setting default description text
                                                  else    
                                                $getGrpDetails['about_user']="Hi User, This is just a dummy description"; //setting default description text           
						
						 $checkFriend=$rm->checkExistingFriendShip($USERID, $friend_user_id); //checking friend relation
							if($checkFriend==0)
							{
							$getGrpDetails['friend_status']="-1";
							}
							else
							{
							$friendStatus=$rm->getFriendStatus($USERID,$friend_user_id);
							$getGrpDetails['friend_status']=$friendStatus;
							}	
						
						$checkFollow=$rm->checkExistingFollow($USERID, $friend_user_id); //check whether any follow relationship exists between them
						if($checkFollow>0)
						{
						$getGrpDetails['follow_status']="1";		
						}
						else
						{
						$getGrpDetails['follow_status']="0";					
						}
						
						$followArray=$rm->followerFollowingCount($friend_user_id);						
						$getGrpDetails['followersCount']=$followArray['followers'];	
						$getGrpDetails['followingCount']=$followArray['following'];		
						array_push($getArrayList,$getGrpDetails);
					}
										
				//print_r($getArrayList);						
				$newData=json_encode(array($getArrayList));
				$newData=str_replace('\/', '/', $newData);
				$newData=substr($newData,1,strlen($newData)-2);	
				$newData="{\"data\":{\"Error_Code\":\"$errorSuccessCode\",\"Error_Msg\":\"Users List\",\"result\":".$newData."}}";					
				return $newData; 
			}	
			else
				{
					$errorCode = "0";
					$errorMsg = "No Users Found";
					$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response	
					return $newData;
				 //Search Unsuccessful
				}
				
	}

function getParticularPost($postId, $userId)
{

$sqlFetchPosts="SELECT U.user_name_f, U.profile_pic_f, N.post_id, N.post_description_f, N.post_date_f,N.user_id_fk,N.post_image_f, N.like_count_f
FROM user_details_t U, news_feeds_t N
WHERE 
N.user_id_fk = U.user_id
AND 
N.post_id=$postId";

			$dataResultSet = mysql_query($sqlFetchPosts,$GLOBALS['link']);
			$rm=new Response_Methods();
			$getGrpDetails=array();
			$getArrayList=array();
			if(mysql_num_rows($dataResultSet) > 0)
			{			
					while($row=mysql_fetch_array($dataResultSet))
					{														
						
						
						$post_id=$row['post_id'];
						$post_description=$row['post_description_f'];
						$post_image=$row['post_image_f'];						
						$like_count=$row['like_count_f'];
						$post_date=$row['post_date_f'];
						
						$post_user_id=$row['user_id_fk'];
						$user_name=$row['user_name_f'];
						$profilePic=$row['profile_pic_f'];
						
					
						 $checkLike=$rm->checkAlreadyLiked($userId,$post_id);
						 
						   if($checkLike>0)							
							$like_status="1";
						   else
						   $like_status="0";
						   
					
						$getGrpDetails['post_id']=$post_id;
						$getGrpDetails['post_description']=$post_description;
						$getGrpDetails['post_image_url']=$post_image;
						$getGrpDetails['like_count']=$like_count;	
						$getGrpDetails['like_status']=$like_status;
						$getGrpDetails['post_date']=$post_date;	
						
						$getGrpDetails['user_name']=$user_name;//username posted
						$getGrpDetails['post_user_id']=$post_user_id;//userid posted newsfeed
						$getGrpDetails['profilePic']=$profilePic;	
						
						//$getGrpDetails['profileId']=$profile_id;	
						/*
						$followArray=$rm->followerFollowingCount($userid);
						//print_r($followArray);
						$getGrpDetails['followersCount']=$followArray['followers'];	
						$getGrpDetails['followingCount']=$followArray['following'];	
						*/
						array_push($getArrayList,$getGrpDetails);
						
					}
										
				//print_r($getArrayList);						
				$newData=json_encode($getArrayList);
				$newData=str_replace('\/', '/', $newData);
				$newData=substr($newData,1,strlen($newData)-2);	
				$newData="{\"data\":{\"Error_Code\":\"2\",\"Error_Msg\":\"Like Done Successfully\",\"result\":".$newData."}}";					
				return $newData; 
			}	
			else
				{
					$errorCode = "0";
					$errorMsg = "No Newsfeeds Available";
					$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response	
					return $newData;
				 //Login Unsuccessful
				}



}


	
		public function getFriendStatus($userId, $friendId)
		{
				
				//echo $userId.' '.$friendId;
				$sqlCheckFriend="SELECT friend_one,friend_two,status FROM friends_t WHERE(friend_one=$userId OR friend_two=$userId) AND (friend_one=$friendId OR friend_two=$friendId)";
				$dataResultSet = mysql_query($sqlCheckFriend,$GLOBALS['link']);
				$countRows=mysql_num_rows($dataResultSet);
				if($countRows > 0)
				{			
					$row=mysql_fetch_array($dataResultSet,MYSQLI_ASSOC);
					$status=$row['status'];
				}
								
				if($row['status']==0)
				{
					if($row['friend_one']==$userId)
					{
					$status="3"; // Message for Friend Request Sent (Sender)
					}
					else
					{
					$status="4"; //Button to show accept/decline friendship(Receiver)
					}
				}
					
				return $status;	
				
		}
		
		
		
		
		
		public function getFriendRequests($userId)
		{
			
				$getGrpDetails=array();
				$getArrayList=array();
				$sqlCheckFriend="SELECT friend_one,friend_two,status FROM friends_t WHERE(friend_one=$userId OR friend_two=$userId) order by friend_id desc";
				$dataResultSet = mysql_query($sqlCheckFriend,$GLOBALS['link']);
				$countRows=mysql_num_rows($dataResultSet);
				if($countRows > 0)
				{			
					while($row=mysql_fetch_array($dataResultSet))
					{
						if($row['status']==0)
						{
							if($row['friend_one']!=$userId)
							{
							
							$friend_user_id=$row['friend_one'];	
							$getGrpDetails[]=$friend_user_id;	
							//array_push($getArrayList,$getGrpDetails);
							//$status=3; //Button to show accept/decline friendship(Receiver)
							}
						
						}
										
					}
					
				}			
				
				//print_r($getGrpDetails);	
				return $getGrpDetails;	
				
		}
		
		
		public function getLikedUserIds($userId)
		{
			
				$getGrpDetails=array();
				$getArrayList=array();
				
				//$sqlgetLikePosts="SELECT user_id_fk,post_id_fk FROM likes_t where post_id_fk IN(select post_id from news_feeds_t where user_id_fk=$userId) and like_status_f=1";
			
				
				$sqlgetLikePosts="SELECT U.user_id, U.user_name_f, U.profile_pic_f, N.post_id,N.post_image_f FROM likes_t L, user_details_t U, news_feeds_t N where U.user_id=L.user_id_fk and N.post_id=L.post_id_fk and L.user_owner_id_fk=$userId and L.like_status_f=1 and L.user_id_fk <> $userId order by L.like_id desc";
				//die();
				$dataResultSet = mysql_query($sqlgetLikePosts,$GLOBALS['link']);
				$countRows=mysql_num_rows($dataResultSet);
				if($countRows>0)
				{
				while($row=mysql_fetch_array($dataResultSet))
					{	
						$user_id=$row['user_id'];	
						$user_name=$row['user_name_f'];
						$profile_pic=$row['profile_pic_f'];	
						
						$post_id=$row['post_id'];
						$post_image=$row['post_image_f'];
						
						$getGrpDetails['notificationType']="P";				
						$getGrpDetails["user_id"]=$user_id;
						$getGrpDetails["user_name"]=$user_name;
						$getGrpDetails["profile_pic"]=$profile_pic;
						
						
						$getGrpDetails["post_id"]=$post_id;
						$getGrpDetails["post_image"]=$post_image;
						array_push($getArrayList,$getGrpDetails);	
					}
				}
				
				
				//print_r($getArrayList);
				
				return $getArrayList;	
				
		}
		
		
		
		public function notificationList($userId)
		{
		
		     	$getGrpDetails=array();
				$getArrayList=array();
				$getUserIds=array();
				$rm=new Response_Methods();
				$getUserIds=$rm->getFriendRequests($userId);
				//print_r($getUserIds);
				$matches = implode(',', $getUserIds);
				$sqlgetUserFriendRequests="SELECT user_id,user_name_f,profile_pic_f FROM user_details_t WHERE user_id IN($matches) order by user_id desc";
				$dataResultSet = mysql_query($sqlgetUserFriendRequests,$GLOBALS['link']);
				$countRows=mysql_num_rows($dataResultSet);
				if($countRows>0)
				{
				while($row=mysql_fetch_array($dataResultSet))
					{	
						
						$user_id=$row['user_id'];
						$user_name=$row['user_name_f'];
						$profile_pic=$row['profile_pic_f'];
					
						$getGrpDetails['notificationType']="F";
						$getGrpDetails['user_id']=$user_id;	
						$getGrpDetails['user_name']=$user_name;	
						$getGrpDetails['profile_pic_url']=$profile_pic;	
						
						
						array_push($getArrayList,$getGrpDetails);
						}
				}
				
				
				
				
				//print_r($getArrayList);
				///return($getArrayList);	
				
				$rm=new Response_Methods();
				$getArrayLiked=$rm->getLikedUserIds($userId);
				//print_r($getArrayLiked);
				$fullList=array_merge($getArrayList,$getArrayLiked);
				//echo count($fullList);
				//print_r($fullList);
				if(count($fullList)>0)
				{
				
				$newData=json_encode(array($fullList));
				$newData=str_replace('\/', '/', $newData);
				$newData=substr($newData,1,strlen($newData)-2);					
				$newData="{\"data\":{\"Error_Code\":\"1\",\"Error_Msg\":\"Notification List\",\"result\":".$newData."}}";								
				return $newData; 
				
				}	
				else
				{
					$errorCode = "0";
					$errorMsg = "No Notifications Found";
					$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response	
					return $newData;
				 //Notification Empty
				}
				
				
			
				
				
		}	
		
		
function checkAlreadyLiked($userId,$postId)
{
 				$sqlCheckLike="SELECT like_id FROM likes_t WHERE user_id_fk=$userId and post_id_fk=$postId";
				$dataResultSet = mysql_query($sqlCheckLike,$GLOBALS['link']);
				$countRows=mysql_num_rows($dataResultSet);
				return $countRows;

}	



function changeLikeStatusSuccess()
{
 				$errorCode = "3";
				$errorMsg = "Like Status Changed.";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
}	


function changeLikeStatusFail()
{
 				$errorCode = "19";
				$errorMsg = "Like Status Not Changed.";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
}

function friendDeniedSuccess()
{
 				$errorCode = "20";
				$errorMsg = "Friendship Declined.";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
}	


function friendDeniedFailed()
{
 				$errorCode = "0";
				$errorMsg = "Friendship not Declined.";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
}

/* updated on 20-07-2015 */
function getNextPhotos($userid,$postId)
{

			$sqlFetchPhotos="select post_id,post_image_f from news_feeds_t where user_id_fk=$userid and post_image_f is not null and post_id > $postId order by post_id";
			$dataResultSet = mysql_query($sqlFetchPhotos,$GLOBALS['link']);
			$rm=new Response_Methods();
			$getGrpDetails=array();
			$getArrayList=array();
			if(mysql_num_rows($dataResultSet) > 0)
			{			
					while($row=mysql_fetch_array($dataResultSet))
					{	
						
						$post_id=$row['post_id'];
						$post_image=$row['post_image_f'];
						$getGrpDetails['photo_image_url']=$post_image;
						$getGrpDetails['post_id']=$post_id;	
						$getPostIds[]=$post_id;	
						array_push($getArrayList,$getGrpDetails);
					}
										
				//print_r($getArrayList);	
				$msxImageId= max($getPostIds);					
				$newData=json_encode(array($getArrayList));
				$newData=str_replace('\/', '/', $newData);
				$newData=substr($newData,1,strlen($newData)-2);	
				$newData="{\"data\":{\"Error_Code\":\"5\",\"Error_Msg\":\"User Photos List\",\"postId\":\"$msxImageId\",\"result\":".$newData."}}";					
				return $newData; 
			}	
			else
				{
					$errorCode = "15";
					$errorMsg = "No Photos Available";
					$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response	
					return $newData;
				 //Login Unsuccessful
				}



}


/*
function getNextPhotos($userid,$postId)
{

			$sqlFetchPhotos="select post_id,post_image_f from news_feeds_t where user_id_fk=$userid and post_image_f is not null and post_id > $postId order by post_id";
			$dataResultSet = mysql_query($sqlFetchPhotos,$GLOBALS['link']);
			$rm=new Response_Methods();
			$getGrpDetails=array();
			$getArrayList=array();
			if(mysql_num_rows($dataResultSet) > 0)
			{			
					while($row=mysql_fetch_array($dataResultSet))
					{	
						
						$post_id=$row['post_id'];
						$post_image=$row['post_image_f'];
						$getGrpDetails['photo_image_url'][]=$post_image;
						$getArrayList[]=$post_id;	
						//array_push($getArrayList,$getGrpDetails);
					}
										
				//print_r($getArrayList);	
				$msxImageId= max($getArrayList);					
				$newData=json_encode(array($getGrpDetails));
				$newData=str_replace('\/', '/', $newData);
				$newData=substr($newData,1,strlen($newData)-2);	
				$newData="{\"data\":{\"Error_Code\":\"5\",\"Error_Msg\":\"User Photos List\",\"postId\":\"$msxImageId\",\"result\":".$newData."}}";					
				return $newData; 
			}	
			else
				{
					$errorCode = "0";
					$errorMsg = "No Photos Available";
					$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response	
					return $newData;
				 //Login Unsuccessful
				}



}

*/



/* User Forgot Password */
	public function forgetPassword($EMAILID) {	
		/* Select user  as per email*/
			$QueryInfo = "SELECT user_name_f,password_f FROM login_t WHERE user_id_fk IN (select user_id from user_details_t where email_f='$EMAILID')";
			$Resultset = mysql_query($QueryInfo);
			if (mysql_num_rows($Resultset) > 0) {
				while ($result = mysql_fetch_array($Resultset)) {
					$pwd = $result['password_f'];
					$userName = $result['user_name_f'];
					//$pwd = base64_decode($pwd);
				}				
				//Send Mail If the Record is Found
				$to = $EMAILID;
				// subject
				$subject = 'Petbesite User Password Information';
				// message
				$message ='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
							   <html xmlns="http://www.w3.org/1999/xhtml">
								<head>
								 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
								</head>
								<body style="font-family:Helvetica;">
								<div style="width:100%; margin:0px; padding:0px;" align="center">
								   <div style="width:50.5%;margin:0px auto;height:auto;padding:5px 2.5px 5px 2.5px;text-align:left;color:white">
								   <img src="'.BASEURL.'/images/tinkerlogo.png">
								   </div>
								   <div style="width:50%;margin:0px auto;border:solid 0px gray;height:350px;padding:5px 3.5px 5px 5px;">
								   <table width="100%" align="center">
									<tr>
									<td>
									Dear User,<br/>
									<p>Welcome to petbestie App</p>							
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
									<td><p>Regards,</p><p>Petbestie</p></td>
									</tr>
								   </div>
								   </div>
								</body>
							</html>';
							 							 
				$headers="";			 
				$headers.= "MIME-version: 1.0\n";
				$headers.= "Content-type: text/html; charset= iso-8859-1\n";
				$headers.= "From: shamsad@techilasolutions.com\r\n";
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




//Comment Functions

public function cleanData($text) {
 return mysql_real_escape_string(addslashes(trim(strip_tags($text))));
 }
public function addCommentSuccessJson($post_id)
{
 				
				
			$getArrayList=array();
			//$dataQueryInfo = "SELECT login_id,user_name_f FROM login_t WHERE user_name_f= '$USERNAME' AND password_f='$PASSWORD'";
			/*$dataQueryInfo = "SELECT u.user_id,u.user_name_f,c.comment_text_f,c.comment_date,c.comment_id FROM user_details_t u, comments_t c WHERE (u.user_id=c.user_id_fk) and (c.comment_id=$comment_id)";*/
	$dataQueryInfo = "SELECT u.user_id,u.user_name_f,c.comment_text_f,c.comment_date,c.comment_id FROM user_details_t u, comments_t c WHERE (u.user_id=c.user_id_fk) and (c.post_id_fk=$post_id) order by c.comment_id desc";
			
			$followArray=array();
			$dataResultSet = mysql_query($dataQueryInfo,$GLOBALS['link']);
			$rm=new Response_Methods();
			$getGrpDetails=array();
			if(mysql_num_rows($dataResultSet) > 0)
			{
			
					while($row=mysql_fetch_array($dataResultSet))
					{														
						$username=$row['user_name_f'];
						$userId=$row['user_id'];
						$commentText=$row['comment_text_f'];
						$commentDate=$row['comment_date'];
						$commentId=$row['comment_id'];
					
						$getGrpDetails['commentedUserName']=$username;
						$getGrpDetails['commentedUserId']=$userId;
						$getGrpDetails['commentText']=$commentText;	
						$getGrpDetails['commentDate']=$commentDate;	
						$getGrpDetails['commentId']=$commentId;
				
						
						array_push($getArrayList,$getGrpDetails);
						
					}
										
				//print_r($getArrayList);						
				$newData=json_encode(array($getArrayList));
				$newData=str_replace('\/', '/', $newData);
				$newData=substr($newData,1,strlen($newData)-2);	
				$newData="{\"data\":{\"Error_Code\":\"1\",\"Error_Msg\":\"Comment Posted Successful\",\"result\":".$newData."}}";					
				return $newData; 
			}	
			else
				{
					$errorCode = "0";
					$errorMsg = "Comment not Posted.Please Try Again";
					$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response	
					return $newData;
				 //Comment Unsuccessful
				}
}


public function addCommentFailJson()
{
 				$errorCode = "0";
				$errorMsg = "Comment not Added.";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
}

public function deleteComment($commentId)
{
 		
		$sqlDeleteComment="delete from comments_t where comment_id=$commentId";
		mysql_query($sqlDeleteComment,$GLOBALS['link']);		
		$affectedRows = mysql_affected_rows();
		return $affectedRows;	

}


public function deleteCommentSuccessJson()
{
 				$errorCode = "1";
				$errorMsg = "Comment Deleted Successfully.";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
}


public function deleteCommentFailJson()
{
 				$errorCode = "0";
				$errorMsg = "Comment not Deleted.";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
}


public function updateCommentSuccessJson($commentId)
{
 				$errorCode = "1";
				$errorMsg = "Comment Updated Successfully.";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
}


public function updateCommentFailJson()
{
 				$errorCode = "0";
				$errorMsg = "Comment not Updated.";
				$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}";
				return $newData;
}


public function getComments($postId,$userId)
{
$sqlGetComments="select comment_id,comment_text_f,user_id_fk from comments_t where user_id_fk=$userId and post_id_fk=$postId order by comment_id desc";
$dataResultSet = mysql_query($sqlGetComments,$GLOBALS['link']);

$getArrayList=array();
$getGrpDetails=array();
if(mysql_num_rows($dataResultSet) > 0)
			{			
					while($row=mysql_fetch_array($dataResultSet))
					{	
						
						$comment_id=$row['comment_id'];
						$comment_text=$row['comment_text_f'];
						$comment_user_id=$row['user_id_fk'];
						
						$getGrpDetails['comment_id']=$comment_id;
						$getGrpDetails['comment_text']=$comment_text;	
						$getGrpDetails['commented_by']=$comment_user_id;						
						array_push($getArrayList,$getGrpDetails);
					}
				
			}
//print_r($getArrayList);
//die();			
return $getArrayList;							


}

public function getAllPostComments($postId,$loggedInUserId)
{
 				
				
			$getArrayList=array();
			//$dataQueryInfo = "SELECT login_id,user_name_f FROM login_t WHERE user_name_f= '$USERNAME' AND password_f='$PASSWORD'";
			$dataQueryInfo = "SELECT u.user_id,u.user_name_f,c.comment_text_f,c.comment_date,c.comment_id,n.post_id FROM user_details_t u, comments_t c,news_feeds_t n WHERE (u.user_id=c.user_id_fk) and (n.post_id=c.post_id_fk) and (c.post_id_fk=$postId) order by c.comment_id desc";
			
			$dataResultSet = mysql_query($dataQueryInfo,$GLOBALS['link']);
			$rm=new Response_Methods();
			$getGrpDetails=array();
			if(mysql_num_rows($dataResultSet) > 0)
			{
			
					$countComments=mysql_num_rows($dataResultSet);
					while($row=mysql_fetch_array($dataResultSet))
					{														
						$username=$row['user_name_f'];
						$commentedUserId=$row['user_id'];
						$commentText=$row['comment_text_f'];
						$commentDate=$row['comment_date'];
						$commentId=$row['comment_id'];
					
						$getGrpDetails['commentedUserName']=$username;
						$getGrpDetails['commentedUserId']=$commentedUserId;
						$getGrpDetails['commentText']=$commentText;	
						$getGrpDetails['commentDate']=$commentDate;	
						$getGrpDetails['commentId']=$commentId;
						
						if($loggedInUserId==$commentedUserId)
						$getGrpDetails['editDeleteFlag']="1";
						else
						$getGrpDetails['editDeleteFlag']="0";						
						//$getGrpDetails['commentCount']=$countComments;
						array_push($getArrayList,$getGrpDetails);
						
					}
										
				//print_r($getArrayList);						
				$newData=json_encode(array($getArrayList));
				$newData=str_replace('\/', '/', $newData);
				$newData=substr($newData,1,strlen($newData)-2);	
				$newData="{\"data\":{\"Error_Code\":\"1\",\"Error_Msg\":\"Comment List\",\"countComments\":\"$countComments\",\"result\":".$newData."}}";					
				return $newData; 
			}	
			else
				{
					$errorCode = "0";
					$errorMsg = "No Comments Available";
					$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response	
					return $newData;
				 //Comment Unsuccessful
				}
}



public function createPostThumbs($data,$userImageBaseURL,$image)
{

$src = imagecreatefromstring($data);				
$width = imagesx($src); 
$height = imagesy($src);

$newwidth=60;
//$newheight=80;
$newheight=($height/$width)*$newwidth;
$tmp=imagecreatetruecolor($newwidth,$newheight);


$newwidth1=50;
//$newheight1=25;
$newheight1=($height/$width)*$newwidth1;
$tmp1=imagecreatetruecolor($newwidth1,$newheight1);


imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);

//echo $src;

$filename = $userImageBaseURL.'/post_photos/60x72'.$image;
$filename1 = $userImageBaseURL.'/post_photos/50x60'.$image;

imagejpeg($tmp,$filename,100);
imagejpeg($tmp1,$filename1,100);

imagedestroy($src);
imagedestroy($tmp);
imagedestroy($tmp1);

$thumbNames=array('60x72'.$image,'50x60'.$image);
return $thumbNames;
}



/* Password Reset Functionalites */

public function emailAvailable() {
		
		$errorCode = "1";
		$errorMsg = "User Found";
		$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response
		echo $newData;
	}
public function emailNotAvailable() {
		
		$errorCode = "0";
		$errorMsg = "No Such User Found";
		$newData = "{\"data\":{\"Error_Code\":\"".$errorCode."\",\"Error_Msg\":\"".$errorMsg."\"}}"; //Json Format Response
		echo $newData;
	}	
 	
	
public function checkUNEmail($email)
{
	
	$sqlCheckEmail="SELECT `user_id` FROM `user_details_t` WHERE `email_f` = '$email' LIMIT 1";
	$result=mysql_query($sqlCheckEmail, $GLOBALS['link']);
	$emailCount=mysql_num_rows($result);
	if($emailCount>0)
	{
	$row=mysql_fetch_array($result);
	$user_id=$row['user_id'];
	$error = array('status'=>true,'user_id'=>$user_id);
	}
	else

	{
	$error = array('status'=>false,'user_id'=>0);
	}
	
	return $error;
	
	
}


	
public function sendPasswordEmail($user_id)
{
        $rm=new Response_Methods();
        $rm->delete('recoveryemails_users',user_id_f,$user_id);
     
	$sqlFetch="SELECT `user_name_f`,`email_f`,`pet_name_f` FROM `user_details_t` WHERE `user_id` = $user_id LIMIT 1";
	$result=mysql_query($sqlFetch, $GLOBALS['link']);
	$numRows=mysql_num_rows($result);
	if($numRows>0)
	{
	$row=mysql_fetch_array($result);
	$user_name=$row['user_name_f'];
	$user_email_id=$row['email_f'];
	$pet_name=$row['pet_name_f'];
	if(!$pet_name)
	$pet_name="user";
	$expFormat = mktime(date("H"), date("i"), date("s"), date("m")  , date("d")+1, date("Y"));
	$expDate = date("Y-m-d H:i:s",$expFormat);
	$key = md5($user_name . '_' . $user_email_id . rand(0,10000) .$expDate . PW_SALT);
	$sqlInsertKey="INSERT INTO recoveryemails_users(user_id_f,recovery_key,expDate) VALUES ($user_id,'$key','$expDate')";
		if(mysql_query($sqlInsertKey,$GLOBALS['link']))
		{
		
				$passwordLink = "<a href=".FORGET_PASSWORD_BASEURL."/ForgotPassword.php?status=recover&email=" . $key . "&u=" . urlencode(base64_encode($user_id)) . ">".FORGET_PASSWORD_BASEURL."/ForgotPassword.php?status=recover&email=" . $key . "&u=" . urlencode(base64_encode($user_id)) . "</a>";
				$message = "Dear ".ucwords($pet_name).",\r\n\r\n";
				$message .= "Your username is: $user_name\r\n\r\n";
				$message .= "Please visit the following link to reset your password:\r\n";
				$message .= "-----------------------\r\n";
				$message .= "$passwordLink\r\n";
				$message .= "-----------------------\r\n";
				$message .= "Please be sure to copy the entire link into your browser. The link will expire after 1 days for security reasons.\r\n\r\n";
				$message .= "If you did not request this forgotten password email, no action is needed, your password will not be reset as long as the link above is not visited.\r\n\r\n";
				$message .= "Thanks,\r\n";
				$message .= "-- Petbesties Team";

				$headers .= "From: Petbesties <shamsad@techilasolutions.com> \n";
				$headers .= "To-Sender: $user_name <$user_email_id> \n";
				$headers .= "X-Mailer: PHP\n"; // mailer
				$headers .= "Reply-To: shamsad@techilasolutions.com\n"; // Reply address
				$headers .= "Return-Path: shamsad@techilasolutions.com\n"; //Return Path for errors
				$headers .= "Content-Type: text/html; charset=iso-8859-1"; //Enc-type
				$subject = "Petbestie-Reset Password";
				$message=str_replace("\r\n","<br/ >",$message);
				//die($message);
				@mail($user_email_id,$subject,$message,$headers);
				/*Send the Success Message*/
				$errorCode = "1";
				$errorMsg = "Reset Password Link Successfully Sent to your email Id. ";
				$newData = "{\"data\":{\"Error_Code\":\"" . $errorCode . "\",\"Error_Msg\":\"" . $errorMsg . "\"}}";
				echo $newData;
				//return str_replace("\r\n","<br/ >",$message);	
		}
		else
		{
		/*Send the Falure Message*/
				$errorCode = "0";
				$errorMsg = "Link Not Sent. Plz try again.. ";
				$newData = "{\"data\":{\"Error_Code\":\"" . $errorCode . "\",\"Error_Msg\":\"" . $errorMsg . "\"}}";
				echo $newData;
		}
	
	
	}
	
	//die($message);
	
}


public function checkEmailKey($key,$userID)
{
	//global $mySQL;
	$curDate = date("Y-m-d H:i:s");
	
	$sqlFetch="SELECT `user_id_f` FROM `recoveryemails_users` WHERE `recovery_key` = '$key' AND `user_id_f` = '$userID' AND `expDate` >= '$curDate'";

	$result=mysql_query($sqlFetch,$GLOBALS['link']);
	$numRows=mysql_num_rows($result);
	if ($numRows > 0 && $userID != '')
		{
			return array('status'=>true,'userID'=>$userID);
		}

	return false;
}


public function updateUserPassword($userID,$password,$key)
{
	
	$sqlUpdatePass="UPDATE `login_t` SET `password_f` = '$password' WHERE `user_id_fk` = $userID";
        mysql_query($sqlUpdatePass,$GLOBALS['link']);	
	$sqlDelete="DELETE FROM `recoveryemails_users` WHERE `recovery_key`='$key'";
	mysql_query($sqlDelete,$GLOBALS['link']);
	$affectedRows2=mysql_affected_rows();

			
/*
				$seeker_name=idToValue('seeker_name','seeker_sign_up','seeker_id',$userID);	
				$seeker_email_id=idToValue('seeker_email_id','seeker_sign_up','seeker_id',$userID);				
				$message = "Dear ".ucwords($seeker_name).",\r\n";
				$message .= "This is to inform you that your password has been succesfully reset.:\r\n";
				
				$message .= "Thanks,\r\n";
				$message .= "-- Our site team";
				$headers .= "From: SAAS Jobs <shamsad@techilasolutions.com> \n";
				$headers .= "To-Sender: $seeker_name <$seeker_email_id> \n";
				$headers .= "X-Mailer: PHP\n"; // mailer
				$headers .= "Reply-To: shamsad@techilasolutions.com\n"; // Reply address
				$headers .= "Return-Path: shamsad@techilasolutions.com\n"; //Return Path for errors
				$headers .= "Content-Type: text/html; charset=iso-8859-1"; //Enc-type
				$subject = "Password Reset Successfully";
				$message=str_replace("\r\n","<br/ >",$message);
				//die($message);
				if(@mail($email,$subject,$message,$headers))				
				return true;
*/		
	return true;
}
	




}
?>
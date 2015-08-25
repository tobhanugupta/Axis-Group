<?php
include_once('DBConnect.php');
include_once('Abstract/classResponse.php');
function checkProcedure()
	{
	//echo 'test';
	
	/*
	$rm=new Response_Methods();


	$sql2 = "CALL getCompanyDetails(9)";
	$result2=mysql_query($sql2);
	if(mysql_num_rows($result2)>0)
	{
		while($row2=mysql_fetch_array($result2))
		{
			echo $row2['0'].' '.$row2['1'];
			echo '<br/>';
		}
	}
	*/
	
	
	
	
	echo '<br/>';

	//$sql1 = "CALL getListDetails(user_id,user_fname,user_details_t)";
	//$rm->getListDetails('user_id','user_fname','user_details_t');
	
	$table="user_details_t";
	
	
	$sql1 = "CALL getSpecificDetails('user_details_t')";
	$result1=mysql_query($sql1);
	if(mysql_num_rows($result1)>0)
	{
		while($row1=mysql_fetch_array($result1))
		{
			echo $row1['0'].' '.$row1['1'];
			echo '<br/>';
		}
	}
	
	mysql_free_result($result1);
	
	
	
	
	

	
	
	
	
	
	}
	
	
	function testProcedure()
	{
	
	$sql1 = "CALL getSpecificDetails('bank_details_t')";
	$result1=mysql_query($sql1);
	if(mysql_num_rows($result1)>0)
	{
		while($row1=mysql_fetch_array($result1))
		{
			echo $row1['0'].' '.$row1['1'];
			echo '<br/>';
		}
	}
	
	mysql_free_result($result1);
	
	}
	
	echo checkProcedure();
	//echo testProcedure();

?>

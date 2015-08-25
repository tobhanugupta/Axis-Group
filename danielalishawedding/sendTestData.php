<html>
<head>
<title>Dynamic Form</title>
<script language="javascript" type="text/javascript" >
function changeIt(txt)
{
  var selVal = txt.value;
  createTextbox.innerHTML = " ";
 if(selVal > 0) {
		document.getElementById("getCount").value = selVal;
            for(var i = 1; i<= selVal; i++) {
                createTextbox.innerHTML = createTextbox.innerHTML +"<br/><br/><label class='label'>First Name:</label><br/><input type='text' name='fname"+i+"'><br/><br/><label class='label'>Last Name:</label><br/><input type='text' name='lname"+i+"'>"
            }
  }

//createTextbox.innerHTML = createTextbox.innerHTML +"<br><input type='text' name='fname"+i+"' >"
}
</script>
</head>
<body>

<form name="form" method="POST">
<select name="numDep" id="dropdown" onchange="changeIt(this)">
    <option value="">Please Select</option>
    <?php for($c=1;$c<=20;$c++){?>
    <option value="<?php echo $c;?>"><?php echo $c;?></option>
    <?php } ?>
</select>
<input type="hidden" name="getCount" value=""  id="getCount"/>
<div id="createTextbox"></div>
<input type="submit" name="party" value="SEND" align="middle" class="submit" />
</form> 
<?php
if(isset($_POST['party']))
{
$list = array();
$getCount = $_POST['getCount'];
	for($p=1;$p<=$getCount;$p++){
		if($_POST["fname$p"]!="" && $_POST["lname$p"]){
		$getData['fname']= $_POST["fname$p"];
		$getData['lname']= $_POST["lname$p"];
		array_push($list,$getData);
		}
	}
 /* Send Mail */
 $to = "pawan@techilasolutions.com";
 $subject = "Invitation Status";
 $date=date('d-m-Y');
 
 for($j=0;$j<sizeof($list);$j++){
 
 $resultMessage .= '
	<tr>
	<td>'.$list[$j]['fname'].'</td>
	<td>'.$list[$j]['lname'].'</td>
	<td>'.$decision.'</td>
	<td>'.$date.'</td>
	</tr>';
}
$message = "
<html>
<head>
<title>Wedding Invitation</title>
</head>
<body>
<p>Invitation Status</p>
<table border='1' cellpadding='5' cellspacing='0' width='500' align='center'>
<tr>
<th>Firstname</th>
<th>Lastname</th>
<th>Will you be attending</th>
<th>Send Date</th>
</tr>
 '".$resultMessage."'
</table>
</body>
</html>
";

// Always set content-type when sending HTML email
$headers.= "MIME-version: 1.0\n";
$headers.= "Content-type: text/html; charset= iso-8859-1\n";
$headers.= "From: info@phbjharkhand.in\r\n";
$mail=mail($to,$subject,$message,$headers);

if($mail)
{
echo "<script>alert('Your message was successfully submitted')</script>";
}
else
{
echo "<script>alert('Form Not Submitted Succesffully')</script>";
}

}
?>
</body>
</html>
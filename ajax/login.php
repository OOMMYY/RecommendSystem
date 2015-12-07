<?php
include("tools.php"); 
$idstr="ID";
$psdstr="password";
$IDstr=$_POST[$idstr];
$password=$_POST[$psdstr];
/*$IDstr=$_GET[$idstr];
$password=$_GET[$psdstr];*/
$ID=(int)$IDstr;
$con;
/*$con = mysql_connect('49.140.70.153:3306', 'adder', '142365');
if (!$con)
 {
 die('Could not connect: ' . mysql_error());
 }

mysql_select_db("library", $con);*/
getDatabaseConnect($con);

$sql="SELECT * FROM `bx-users` WHERE `User-ID`=".$ID;

//$result = mysql_query($sql);
$result =$con->query($sql);


if($result)
{	$num=$result->num_rows;
//if(mysql_num_rows($result)==1){
	if($num==1){
	$row = $result->fetch_array();
	if($row['password']==$password){echo "yes";}
	else{echo "wrongpassword";}
}else{
	echo "wrongid";
	}
	
}
else
{
  echo "wrongid";
 
}
$con->close();
?>
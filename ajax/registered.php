<?php
include("tools.php"); 
$con;
$con=getDatabaseConnect($con); //连接数据库

$ReturnData=array(); //返回给html的数据

//获取表单数据Location Age Password
$Location=$_POST['Loca'];
$Age=$_POST['Age'];
$password=$_POST['password'];
if(!is_numeric($Age)){
	$ReturnData['Information']="agenotnum";
	echo json_encode($ReturnData);
	exit;
}
$sql="INSERT INTO `bx-users`(Location,Age,password) VALUES('$Location',$Age,'$password')";
//mysql_query(sql,$con);
$result=$con->query($sql);

//$getID=$Location.$Age.$password;
if($result){
	$ReturnData['Information']="success";
}else{
	$ReturnData['Information']="fail";
	echo json_encode($ReturnData);
	exit;
}
$getID=mysqli_insert_id($con);
$ReturnData['NewID']=$getID;
echo json_encode($ReturnData);
?>
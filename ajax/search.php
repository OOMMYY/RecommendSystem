<?php
include("tools.php"); 
$con;
$search="";
$str=$_GET['searchstr'];
	$con=getDatabaseConnect($con);
	$sql="SELECT `Book-Title` FROM `bx-books` WHERE `Book-Title` LIKE '%$str%'";
$result = $con->query($sql);
$i=1;
	while ($row =$result->fetch_array()) {
		$booktitle=$row['Book-Title'];
		//$search=$search.$booktitle;
	$query="select * from `bx-books` where `Book-Title`=('$booktitle')";
	if($i==10)break;
	$bookResult =$con->query($query);
	if(	$bookResult){
		$bookRow =$bookResult->fetch_array();
			$search=$search.createImgDivByRow($bookRow);
	}
	$i++;
}
$con->close();
echo $search;

?>
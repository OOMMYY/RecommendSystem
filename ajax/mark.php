<?php
//进行评分,失败返回false,成功返回true
include("tools.php"); 
$con;
$con=getDatabaseConnect($con); //连接数据库

//获取表单数据用户ID：User-ID，书籍编号：ISBN，分数：Book-Rating
$UserID=$_POST['User-ID'];
$ISBN=$_POST['ISBN'];
$Score=$_POST['Book-Rating'];

$select_sql="SELECT * FROM `bx-book-ratings` WHERE `User-ID`=$UserID AND ISBN=$ISBN";
//是否已存在对该书的评分
if($result=$con->query($select_sql)){ 
	//已存在评分则修改
	$update_sql="UPDATE `bx-book-ratings` SET `Book-Rating`=$Score WHERE `User-ID`=$UserID AND ISBN=$ISBN";
	if($result=$con->query($update_sql)){
		return true; //更新成功
	}else{
		return false;
	}
}else{
	//不存在评分则插入
	$insert_sql="INSERT INTO `bx-book-ratings`(`User-ID`,ISBN,`Book-Rating`) VALUES($UserID,$ISBN,$Score)" ;
	if($result=$con->query($insert_sql)){
		return true; //插入成功
	}else{
		return false;
	}
}

$con->close();

?>
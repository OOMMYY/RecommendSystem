<?php
//��������,ʧ�ܷ���false,�ɹ�����true
include("tools.php"); 
$con;
$con=getDatabaseConnect($con); //�������ݿ�

//��ȡ�������û�ID��User-ID���鼮��ţ�ISBN��������Book-Rating
$UserID=$_POST['User-ID'];
$ISBN=$_POST['ISBN'];
$Score=$_POST['Book-Rating'];

$select_sql="SELECT * FROM `bx-book-ratings` WHERE `User-ID`=$UserID AND ISBN=$ISBN";
//�Ƿ��Ѵ��ڶԸ��������
if($result=$con->query($select_sql)){ 
	//�Ѵ����������޸�
	$update_sql="UPDATE `bx-book-ratings` SET `Book-Rating`=$Score WHERE `User-ID`=$UserID AND ISBN=$ISBN";
	if($result=$con->query($update_sql)){
		return true; //���³ɹ�
	}else{
		return false;
	}
}else{
	//���������������
	$insert_sql="INSERT INTO `bx-book-ratings`(`User-ID`,ISBN,`Book-Rating`) VALUES($UserID,$ISBN,$Score)" ;
	if($result=$con->query($insert_sql)){
		return true; //����ɹ�
	}else{
		return false;
	}
}

$con->close();

?>
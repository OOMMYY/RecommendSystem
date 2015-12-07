<?php
include("tools.php"); 
$con;
$idstr="userid";
$ID=$_GET[$idstr];
$recommend=getRecommend($ID);
echo $recommend;

//基于用户推荐
//计算相似度,i和j分别是用户
function sim($i,$j)  
{
	getDatabaseConnect($con);
	$i_sql="select `ISBN`,`Book-Rating` from `bx-book-ratings` where `User-ID`=$i order by `ISBN`"; //获取用户i的所有评分记录
	$j_sql="select `ISBN`,`Book-Rating` from `bx-book-ratings` where `User-ID`=$j order by `ISBN`"; //获取用户j的所有评分记录
	$i_result=$con->query($i_sql);//返回i结果集
	$j_result=$con->query($j_sql);//返回j结果集
	$num=0; //用户i和用户j的评分交集数量
	$sum=0; //用户i和用户j对每个书籍评分的总和
	$i_sum=0; //用户i在交集中对书籍评分的平方的总和
	$j_sum=0; //用户j在交集中对书籍评分的平方的总和
	$i_row=$i_result->fetch_array();
	$j_row=$j_result->fetch_array();
	//比较两个结果集
	while($i_row&&$j_row){//当有一个结果集取尽时退出循环
		if($i_row['ISBN']==$j_row['ISBN']){
		$num++;
		$sum+=$i_row['Book-Rating']*$j_row['Book-Rating'];
		$i_sum+=$i_row['Book-Rating']*$i_row['Book-Rating'];
		$j_sum+=$j_row['Book-Rating']*$j_row['Book-Rating'];
		}
		if($i_row['ISBN']<=$j_row['ISBN']){
			$i_row=$i_result->fetch_array();
		}
		if($i_row['ISBN']>=$j_row['ISBN']){
			$j_row=$i_result->fetch_array();
		}
	}
	$con->close();
	if($sum==0){return 0;}
	return $sum/(sqrt($i_sum)*sqrt($j_sum));/////////////////////////////////////$i_sum或$j_sum为0怎么处理？
}




/**
*选择相似度高的用户评价高但是目标用户还未进行评价的书籍进行推荐
*/
function getRecommend($i)  
{
	getDatabaseConnect($con);
	$a=array();//存储相似用户User-ID-相似度的映射
	$sql="select `User-ID` from `bx-book-ratings` where `ISBN` in (select `ISBN` from `bx-book-ratings` where `User-ID`=$i)";//与目标用户评过相同书的用户
	$result=$con->query($sql);
	while($row=$result->fetch_array()){
		$userid='User-ID';
		$uid=$row[$userid];
		$a[$uid]=sim($i,$uid);//计算相似度
	}
	arsort($a);//按照相似度降序排序
	//print_r($a);//打印数组，调试时用
	$b=array();//存储ISBN-ISBN映射   存储前n个相似用户评过的书
	$c=array();//存储ISBN-评分映射	 存储前n个相似用户评过的书以及他们对这些书的评分
	$n=0;
	foreach($a as $keys=>$values){//数组遍历  遍历每个数组元素时拆分为键-值对
		$sql="select `ISBN`,`Book-Rating` from `bx-book-ratings` where `User-ID`=$keys"; //该用户评过的所有书
		$result=$con->query($sql);
		while($row=$result->fetch_array()){
			$isbn=$row['ISBN'];
			$b[$isbn]=$isbn;
			$c[$isbn]=$row['Book-Rating'];
		}
		if($n==5)break;//取最相似的前5个用户
		$n++;
	}
	$e=array();//存储目标用户评过的书
	$sql="select `ISBN` from `bx-book-ratings` where `User-ID`=$i";
	$result=$con->query($sql);
	while($row=$result->fetch_array()){
		$isbn=$row['ISBN'];
		$e[$isbn]=$isbn;
	}
	$d=array_diff($b, $e);//取数组b和e的差集为d，即为相似用户们评过但是目标用户没评过的书
	foreach($d as $keys=>$values){
		if($c[$values]<5){unset($d[$keys]);}
	}//只取相似用户们评分高的书籍，可以假设大于5分为评分高
	$div="";
	//print_r($d);
	$n=0;
	foreach($d as $keys=>$values){
		$bookname_query="select * from `bx-books` where `ISBN`=$values";
		$bookname_result=$con->query($bookname_query);
		if($bookname_result){
			$bookname_row =$bookname_result->fetch_array();
			if($bookname_row){
			$div=$div.createImgDivByRow($bookname_row );
			}
		}
		if($n>10)break;//取前10本书
		$n++;
	}//通过评分数据表，获取j评分高，但i未评分的书籍，可以假设大于5分为评分高
	return $div;
}
?>
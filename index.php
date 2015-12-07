<?php
include("ajax/tools.php"); 
$con;

//////////////////////////////////////////////////获取 “随便看看” 内容 并显示
function getRandom(){
	$str3="";
getDatabaseConnect($con);

$sql=" SELECT `ISBN` FROM `bx-books` ";/*
  WHERE (ABS(CAST(
  (BINARY_CHECKSUM
  (ISBN, NEWID())) as int))
  % 100) < 10";//"SELECT * FROM `bx-books` order by RAND() LIMIT 0, 10";*/
$result =$con->query($sql);
$ran=rand(1,27000);
$x=0;
for($i=1;$i<=10;$i++){
	while($x<$ran){
	$row =$result->fetch_array();
	$x++;
	}
		$ran=rand(1,27000);
		$x=0;
		$query="select * from `bx-books` where ISBN=('$row[ISBN]')";
	$bookResult =$con->query($query);
	$bookRow =$bookResult->fetch_array();
		$str3=$str3.createImgDivByRow($bookRow);

	
}
$con->close();
return $str3;
}



////////////////////////////////////////////////////获取 “推荐” 结果 并显示
/*function recommend(){
	$recommendstr="";
if(isloged()){
		$userid=$_POST['userid'];
		$recommendstr=getRecommend($userid);
	}
return $recommendstr;
}*/
////////////////////////////////////////////////////获取 “借阅记录” 结果 并显示
function getRateRecord(){
	$rateRecord="";
	$i=1;
if(isloged()){
	$userid=$_POST['userid'];
	getDatabaseConnect($con);
	$bookid_query="select `ISBN` from `bx-book-ratings` where `User-ID` =$userid";
	$bookid_result=$con->query($bookid_query);
	while ($bookid_row =$bookid_result->fetch_array()) {
		
		$book_id=$bookid_row['ISBN'];
		$bookname_query="select * from `bx-books` where `ISBN` =('$book_id')";
		$bookname_result=$con->query($bookname_query);
		$bookRow =$bookname_result->fetch_array();
		$rateRecord=$rateRecord.createImgDivByRow($bookRow);
		$i++;
	}
		}
return $rateRecord;
}


////////////////////////////////////////////////////获取 “排行榜” 结果 并显示
function getRateMost(){
	$str3="";
getDatabaseConnect($con);getDatabaseConnect($con1);
$sql="select `ISBN` from (select ISBN,count(ISBN) as num  from `bx-book-ratings` group by ISBN ) as count order by num DESC";
$result =$con->query($sql);
for($i=1;$i<=10;$i++){
	$row =$result->fetch_array();
	$booknum=$row['ISBN'];
	$query="select * from `bx-books` where ISBN=('$booknum')";
	$bookResult =$con1->query($query);
	$bookname="Book-Title";
	$bookRow =$bookResult->fetch_array();
	$str3=$str3.createImgDivByRow($bookRow);
}

//SELECT `ISBN`,avg(`Book-Rating`) FROM `bx-book-ratings` GROUP BY `ISBN` 
$con->close();
return $str3;
}
///////////////////////////////////////////////////////////////是否已登录
function isloged(){
	return isset($_POST['userid']);
}
///////////////////////////////////////////////////////////////登录
function login(){
	$str4="<li class=\"login\"><a href=\"login.html\">登录</a></li>";
if(isloged()){
$userid=$_POST['userid'];
$str4="<li class=\"login\"><a href=\"javascript:logout()\">退出</a></li>";
$str4=$str4."<li class=\"login\"><a id=\"userid\" href=\"#\">$userid</a></li>";
}	
return $str4;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////
//$recommend=recommend();
$randombook=getRandom();
$login=login();
$mostRatedBooks=getRateMost();
$rateRecord=getRateRecord();

$str1 = <<<HTMLSTR
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title>图书推荐系统</title>
	

<head>
<link rel="shortcut icon" href="images/favicon.ico" >
<link href="./css/frame.css" charset="UTF-8" type="text/css" rel="stylesheet"/>
<script src="js/jquery-1.11.3.min.js"></script>
<script language="JavaScript" src="js/js.js">
</script>

<script type="text/javascript">
$(document).ready(function(){
//首先将#back-to-top隐藏
 $("#back-to-top").hide();
//当滚动条的位置处于距顶部100像素以下时，跳转链接出现，否则消失
$(function () {
$(window).scroll(function(){
if ($(window).scrollTop()>100){
$("#back-to-top").fadeIn(1500);
}
else
{
$("#back-to-top").fadeOut(1500);
}
});
//当点击跳转链接后，回到页面顶部位置
$("#back-to-top").click(function(){
$('body,html').animate({scrollTop:0},300);
return false;
});
});
});
</script>
</head>
<body onload="full()" >
<p id="back-to-top"><a href="#"><span></span></a></p>
	<div  class="headBackground ">
	<div style="width:80%;"class="centerDiv">
	<a href="#">
	<img id="logo" src ="images/logo1.png" > </a>
	<div    id="navigation">
			<li><a href="http://lib.jlu.edu.cn/" target="_blank">图书馆</a></li>
			<li><a href="http://www.cnki.net/" target="_blank">知网</a></li>
			<li><a href="http://www.wanfangdata.com.cn/" target="_blank">万方</a></li>
			<li><a href="http://scholar.glgoo.org/" target="_blank">谷歌学术</a></li>
			<li><a href="http://www.sslibrary.com/" target="_blank">超星</a></li>
			<select class="headSelect"  onfocus="this.defaultIndex=this.selectedIndex;" onchange="change(this)">
  <option  style="display:none;" value ="">更多</option>
  <option value="http://cstj.cqvip.com/">维普</option>
  <option value="http://apps.webofknowledge.com/UA_GeneralSearch_input.do?product=UA&search_mode=GeneralSearch&SID=Q2Xvr1wgSTBPKYOPG6k&preferencesSaved=">SCI</option>
  <option value="http://www.apabi.com/jlu/pub.mvc/Index2?pid=login&cult=CN">方正书苑</option>
  <option value="about.html">关于</option>
</select>	
	$login
	</div>
	</div>
	</div>
<div class="boxes centerDiv" id="boxes">
<div class="nav " id="nav"> 
	<a id="a1" class="nav_on" href="javascript:void(0);" onclick="changeTab(1)">发现</a>
	<a id="a2" href="javascript:void(0);" onclick="changeTab(2)">推荐</a>
	<a id="a3" href="javascript:void(0);" onclick="changeTab(3)">搜索</a>
	<a id="a4" href="javascript:void(0);" onclick="changeTab(4)">排行榜</a>
    <a id="a5" href="javascript:void(0);" onclick="changeTab(5)">评分记录</a>
	</div>
<div  id="box1"  class=" box box_on">



	<div class="leftDivPart">
	
	
	<div class="Tag"><h3>随便看看</h3></div>
	
	<div id="hang" class="leftPart">
	$randombook
	</div>
	
	</div>
	
	
	</div>

<div id="box2" class=" box">

<div class="leftDivPart">
<div class="Tag"><h3>推荐</h3></div>
	
	<div id="recommend" class="leftPart">
	
	

	
	</div>
</div>
	</div>
	
<div id="box3" class="box">
<div class="leftDivPart">

<div id="search" >
		<form action="javascript:void(0)">
			<input 	size=60 type="text" id="searchfield" name="searchfield" />
			<input style="border:none;border-radius:0;padding-left:0;"  onmousedown="changeButton()"
			onmouseup="resetButton()"
			onclick="search()" id="searchButton" type="image" value="eee"  src="/images/searchLogo1.png"/>
		</form>
	</div>
	<div id="searchtag" class="Tag search"><h3>搜索结果</h3></div>
	
	<div id="searchdiv" class="search leftPart">
	
	
	</div>
	</div>
	
	
	</div>
	
	
	
	<div id="box4" class=" box">
<div class="leftDivPart">
<div class="Tag"><h3>阅读最多</h3></div>
	<div class="leftPart">
	$mostRatedBooks
	</div>
	</div>
	
	
	</div>
	
	<div id="box5" class=" box">
<div class="leftDivPart">
<div class="Tag"><h3>评分记录</h3></div>
	<div class="leftPart">
$rateRecord
	</div>
	</div>
	</div>
	
	
	
	
	</div>
</body>
<div id="foot" >

  <p>Copyright©2015 RecommendSystem.All Rights Reserved.</p>
  <p>Contact information: <a href="http://mail.126.com/" target="_blank">maxuewei1995@126.com</a>.</p>
  <p>本站仅供学习测试所用</p>
</div>

</html>

HTMLSTR;


echo $str1;


	
?>
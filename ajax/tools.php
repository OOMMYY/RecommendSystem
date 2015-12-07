<?php
function getDatabaseConnect(&$con){
/*$con = mysql_connect('49.140.70.153:3306', 'adder', '142365');*/
$db_host="49.140.70.153:3306";                                           //连接的服务器地址
$db_user="adder";                                                  //连接数据库的用户名
$db_psw="142365";                                                  //连接数据库的密码
$db_name="bookcrossing";                                           //连接的数据库名称
$con=new mysqli($db_host,$db_user,$db_psw,$db_name);
if (!$con)
 {
 die('Could not connect: ' . mysql_error());
 }

//mysql_select_db("library", $con);
return $con;
}

function createImgDivByRow($bookRow){
	if($bookRow==null)return "";
	$bookname="Book-Title";
	$author='Book-Author';
	$year='Year-Of-Publication';
	$desc= <<<DESC
	<table>
	<tr><td>书名:</td><td>$bookRow[$bookname]</td></tr>
	<tr><td>ISBN:</td><td>$bookRow[ISBN]</td></tr>
	<tr><td>作者:</td><td>$bookRow[$author]</td></tr>
	<tr><td>出版社:</td><td>$bookRow[Publisher]</td></tr>
	<tr><td>出版年份:</td><td>$bookRow[$year]</td></tr>
	</table>
DESC;
return createImgDiv("img","img",$bookRow['Image-URL-M'],"bookcover",$desc,$bookRow[$bookname],"javascript:void(0)",$bookRow['ISBN']);
}

function createImgDiv($class,$id,$src,$alt,$desc,$title,$href,$isbn){
		$imgdiv=<<<IMGDIV
<div class=$class >
 <a href=$href  title="$title">
 <div class="imgimgdiv"> 
 <img id=$id src=$src lowsrc="images/default.jpg"  onerror="this.src=\"images/default.jpg\""  alt=$alt >
 </div>
 </a>
 
  <div class="desc">$desc</div>
<div id="star">
    <ul>
        <li class="leftstar" score="1"><a href="javascript:;">1</a></li>
        <li class="rightstar" score="2"><a href="javascript:;">2</a></li>
        <li class="leftstar" score="3"><a href="javascript:;">3</a></li>
        <li class="rightstar" score="4"><a href="javascript:;">4</a></li>
        <li class="leftstar" score="5"><a href="javascript:;">5</a></li>
		<li class="rightstar" score="6"><a href="javascript:;">6</a></li>
        <li class="leftstar" score="7"><a href="javascript:;">7</a></li>
        <li class="rightstar" score="8"><a href="javascript:;">8</a></li>
        <li class="leftstar" score="9"><a href="javascript:;">9</a></li>
        <li class="rightstar" score="10"><a href="javascript:;">10</a></li>
    </ul>
    <span></span>
    <p></p>
    <input type="hidden" id="rating" name="b" value="$isbn" size="2" /> 
</div>  


</div>
IMGDIV;
return $imgdiv;
}

 



function writeFile($writestr){
$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
fwrite($myfile, $writestr);
fwrite($myfile, "\n");
fclose($myfile);
}
?>
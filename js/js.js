 
 
function del_ff(elem){
var elem_child = elem.childNodes;
for(var i=0; i<elem_child.length;i++){
if(elem_child[i].nodeName == "#text" && !/\s/.test(elem_child.nodeValue))
{elem.removeChild(elem_child)
}
}
return elem_child;
}
 var BlogDirectory = {
    /*
        获取元素位置，距浏览器左边界的距离（left）和距浏览器上边界的距离（top）
    */
    getElementPosition:function (ele) {        
        var topPosition = 0;
        var leftPosition = 0;
        while (ele){              
            topPosition += ele.offsetTop;
            leftPosition += ele.offsetLeft;        
            ele = ele.offsetParent;     
        }  
        return {top:topPosition, left:leftPosition}; 
    },

    /*
    获取滚动条当前位置
    */
    getScrollBarPosition:function () {
        var scrollBarPosition = document.body.scrollTop || document.documentElement.scrollTop;
        return  scrollBarPosition;
    },
    
    /*
    移动滚动条，finalPos 为目的位置，internal 为移动速度
    */
    moveScrollBar:function(finalpos, interval) {

        //若不支持此方法，则退出
        if(!window.scrollTo) {
            return false;
        }

        //窗体滚动时，禁用鼠标滚轮
        window.onmousewheel = function(){
            return false;
        };
          
        //清除计时
        if (document.body.movement) { 
            clearTimeout(document.body.movement); 
        } 

        var currentpos =BlogDirectory.getScrollBarPosition();//获取滚动条当前位置

        var dist = 0; 
        if (currentpos == finalpos) {//到达预定位置，则解禁鼠标滚轮，并退出
            window.onmousewheel = function(){
                return true;
            }
            return true; 
        } 
        if (currentpos < finalpos) {//未到达，则计算下一步所要移动的距离
            dist = Math.ceil((finalpos - currentpos)/10); 
            currentpos += dist; 
        } 
        if (currentpos > finalpos) { 
            dist = Math.ceil((currentpos - finalpos)/10); 
            currentpos -= dist; 
        }
        
        var scrTop = BlogDirectory.getScrollBarPosition();//获取滚动条当前位置
        window.scrollTo(0, currentpos);//移动窗口
        if(BlogDirectory.getScrollBarPosition() == scrTop)//若已到底部，则解禁鼠标滚轮，并退出
        {
            window.onmousewheel = function(){
                return true;
            }
            return true;
        }
        
        //进行下一步移动
        var repeat = "BlogDirectory.moveScrollBar(" + finalpos + "," + interval + ")"; 
        document.body.movement = setTimeout(repeat, interval); 
    },
    
    htmlDecode:function (text){
        var temp = document.createElement("div");
        temp.innerHTML = text;
        var output = temp.innerText || temp.textContent;
        temp = null;
        return output;
    },

    /*
    创建博客目录，
    id表示包含博文正文的 div 容器的 id，
    mt 和 st 分别表示主标题和次级标题的标签名称（如 H2、H3，大写或小写都可以！），
    interval 表示移动的速度
    */
    createBlogDirectory:function (id, mt, st, interval ,i){
         //获取博文正文div容器
        var boxeselem = document.getElementById(id);
        if(!boxeselem) return false;
        //获取div中所有元素结点
        var boxes = boxeselem.getElementsByClassName("box");
		//var boxes = boxeselem.childNodes;
		//boxes=del_ff(boxeselem);
		var elem;
		/* for(var i=0; i<boxes.length; i++)
        {
            if(boxes[i].style.display == 'block')    
            {elem=boxes[i];
			}
		}*/
		elem=boxes[i];
		var nodes = elem.getElementsByTagName("*");
        //创建博客目录的div容器
        var divSideBar = document.createElement('DIV');
        divSideBar.className = 'sideBar';
        divSideBar.setAttribute('id', 'sideBar');
        var divSideBarTab = document.createElement('DIV');
        divSideBarTab.setAttribute('id', 'sideBarTab');
        divSideBar.appendChild(divSideBarTab);
        var h2 = document.createElement('H2');
        divSideBarTab.appendChild(h2);
        var txt = document.createTextNode('目录导航');
        h2.appendChild(txt);
        var divSideBarContents = document.createElement('DIV');
        divSideBarContents.style.display = 'none';
        divSideBarContents.setAttribute('id', 'sideBarContents');
        divSideBar.appendChild(divSideBarContents);
        //创建自定义列表
        var dlist = document.createElement("dl");
        divSideBarContents.appendChild(dlist);
        var num = 0;//统计找到的mt和st
        mt = mt.toUpperCase();//转化成大写
        st = st.toUpperCase();//转化成大写
        //遍历所有元素结点
        for(var i=0; i<nodes.length; i++)
        {
            if(nodes[i].nodeName == mt|| nodes[i].nodeName == st)    
            {
				
                //获取标题文本
                var nodetext = nodes[i].innerHTML.replace(/<\/?[^>]+>/g,"");//innerHTML里面的内容可能有HTML标签，所以用正则表达式去除HTML的标签
                nodetext = nodetext.replace(/&nbsp;/ig, "");//替换掉所有的&nbsp;
                nodetext = BlogDirectory.htmlDecode(nodetext);
                //插入锚        
                nodes[i].setAttribute("id", "blogTitle" + num);
                var item;
                switch(nodes[i].nodeName)
                {
                    case mt:    //若为主标题 
                        item = document.createElement("dt");
                        break;
                    case st:    //若为子标题
					//if(nodes[i].parentNode.style.display != ""){alert("p");}
                        item = document.createElement("dd");
						//alert(nodes[i].parentNode.style.display);
                        break;
                }
                
                //创建锚链接
                var itemtext = document.createTextNode(nodetext);
                item.appendChild(itemtext);
                item.setAttribute("name", num);
                item.onclick = function(){        //添加鼠标点击触发函数
                    var pos = BlogDirectory.getElementPosition(document.getElementById("blogTitle" + this.getAttribute("name")));
                    if(!BlogDirectory.moveScrollBar(pos.top, interval)) return false;
                };            
                
                //将自定义表项加入自定义列表中
                dlist.appendChild(item);
                num++;
				
			}
        }
        
        if(num == 0) return false; 
        /*鼠标进入时的事件处理*/
        divSideBarTab.onmouseenter = function(){
            divSideBarContents.style.display = 'block';
        }
        /*鼠标离开时的事件处理*/
        divSideBar.onmouseleave = function() {
            divSideBarContents.style.display = 'none';
        }

        document.body.appendChild(divSideBar);
    }
    
};

window.onload=function(){
    /*页面加载完成之后生成博客目录*/
	//alert("hey");
    BlogDirectory.createBlogDirectory("boxes","h2","h3",20,0);
	
	recommend();
	
}






function changeTab(i){
	document.getElementById("box"+i).style.display="block";
	/*document.getElementById("box"+i).className="box_on"; */
	document.getElementById("a"+i).className="nav_on";
    for (var j = 1;j <= 5;j++ ){
        if (i!=j){
				/*document.getElementById("box"+j).className="";*/
				document.getElementById("box"+j).style.display="none";
				document.getElementById("a"+j).className=""; 
        }
    }
	BlogDirectory.createBlogDirectory("boxes","h2","h3",20,i-1);
}

function full(){
  $("#nav").height($("#boxes").height());
}
  
document.onclick = function(e){
	/*if($(".bac").length==0)
	{
		if(!e) e = window.event;
		if((e.keyCode || e.which) == 13){
			var obtnLogin=document.getElementById("submit_btn")
			obtnLogin.focus();
		}
	}*/
	document.getElementById("more-items").style.display="none";
}
/*
function show_more(){
	/*alert("hello");*/
	/*var x=getLeft(document.getElementById("bn-more"));
	
	document.getElementById("more-items").style.left=x+"px";
	document.getElementById("more-items").style.display="block";
}
function hide_more(){
	/*document.getElementById("more-items").style.display="none";*/
/*}*/
function change(sobj){
	this.selectedIndex=this.defaultIndex;
/*location.href=url;*/
var docurl =sobj.options[sobj.selectedIndex].value; 
if (docurl != "") { 
open(docurl,'_blank'); 
sobj.selectedIndex=0; 
sobj.blur(); 
} 
}

function logout(){
  if( confirm("你确实要退出吗？？")){
   window.parent.location.href="index.php";
  }
  else{
   return;
  }
 }
 function changeButton(){
	/*var imgsrc=document.getElementById("searchButton").src;
	 if(imgsrc=="/images/searchLogo1.png"){*/
	 document.getElementById("searchButton").src="/images/searchLogo2.png";
	/* }
	 else{
		 document.getElementById("searchButton").src="/images/searchLogo1.png";
	 }*/
 }
  function resetButton(){
	 document.getElementById("searchButton").src="/images/searchLogo1.png";
 } 
 
 function ajax(id,url){
 var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById(id).innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET",url,true);
xmlhttp.send();
 }
 function mouseonimagediv(){

 }
 function search(){
	 //alert("hey");
	 
	 document.getElementById("searchtag").style.display="block";
	 document.getElementById("searchdiv").style.display="block";
	 str=document.getElementById("searchfield").value;
	 if (str.length==0)
  {
  document.getElementById("searchdiv").innerHTML="";
  return;
  }
ajax("searchdiv","ajax/search.php?searchstr="+str);
 }
 
 function recommend(){
 
 str=document.getElementById("userid").innerHTML;

	 if (str.length==0)
  {
  document.getElementById("recommend").innerHTML="";
  return;
  }
ajax("recommend","ajax/recommend.php?userid="+str);
 }
 

/*
//获取元素的纵坐标 
function getTop(e){ 
var offset=e.offsetTop; 
if(e.offsetParent!=null) offset+=getTop(e.offsetParent); 
return offset; 
} 
//获取元素的横坐标 
function getLeft(e){ 
var offset=e.offsetLeft; 
if(e.offsetParent!=null) offset+=getLeft(e.offsetParent); 
return offset; 
} */



$(document).ready(function(){
        $("div#star li").each(function(i){
            $("div#star li").eq(i).mouseover(function () {  //当鼠标位于li的a上改变为黄星
            initstart();
                for(var k=0;k<=i;k++){
                   if(k%2==0){    //偶数则为左星星
                        $("div#star li").eq(k).removeClass("leftstar").addClass("onleftstar");                   
                   }else{
                        $("div#star li").eq(k).removeClass("rightstar").addClass("onrightstar"); 
                   }
                }
            });
        
        $("div#star li").eq(i).click(function () {  //点击时用ajax提交分数给php
            $.ajax({
                        type: "POST",
                        url: "mark.php",
                    /**     
                        data: {score:i+1},
                        dataType: "json",
                    
                        beforeSend: function () {
                            $('#showreturn').empty();
                            $('#showreturn').html("<b>正在提交数据</b>");
                
                        },
                        success: function(data){                    
                            alert(data.userID+"  "+data.password);                                    
                        }
                    **/
                 });
        });
        
            
        });
        
        function initstart(){            //初始化为原来样子
            $("div#star li").each(function(i){
                   if(i%2==0){    
                        $("div#star li").eq(i).removeClass().addClass("leftstar");                   
                   }else{
                        $("div#star li").eq(i).removeClass().addClass("rightstar");  
                   }
            });
        }
    });
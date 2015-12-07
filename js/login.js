// JavaScript Document
//支持Enter键登录

document.onkeydown = function(e){
	if($(".bac").length==0)
	{
		if(!e) e = window.event;
		if((e.keyCode || e.which) == 13){
			var obtnLogin=document.getElementById("submit_btn")
			obtnLogin.focus();
		}
	}
}

$(function(){
	//提交表单
	$('#submit_btn').click(function(){
		show_loading();
		if($('#ID').val() == '账号'){
					
			show_err_msg('账号未填写！');	
			$('#ID').focus();
		}else if($('#password').val() == '密码'){
				show_err_msg('密码未填写！');
				$('#password').focus();
			}else{
				//ajax提交表单，#login_form为表单的ID。 如：$('#login_form').ajaxSubmit(function(data) { ... });
				var IDa="",passworda="";
				IDa=$('#ID').val();
				passworda=$('#password').val();
				//alert(IDa+""+passworda);
				/*var url="login.html";*/
			  $.post("/ajax/login.php",
				{
				ID:IDa,
				password:passworda
				},
				function(data,status){
				/*alert(IDa +"\n"+passworda);
				alert(data);*/
					if(data=="yes"){
					
	/*url="/index.php?userid="+IDa;*/
						show_msg('登录成功！  正在为您跳转...');
					/*$.post("index.php",
					{
					userid:IDa,
					});*/
				
						document.getElementById("form").submit();
						/**/

					}
					else{
						if(data=="wrongid"){
							show_err_msg('账号错误！');
							$('#ID').focus();
						}
						if(data=="wrongpassword"){
							show_err_msg('密码错误！');
							$('#password').focus();
						}  
					}
				}
			 );
					
  
					
				}
			});
		}
);



$(function(){
	//提交表单
	$('#submit_btn1').click(function(){
		//alert();
		show_loading();
		if($('#password1').val() == '密码'){
				show_err_msg('密码未填写！');
				$('#password1').focus();
			}else{

				$.ajax({
             			type: "POST",
             			url: "ajax/registered.php",
             			data: {Loca:$("#Location").val(), Age:$("#Age").val(), password:$("#password1").val()},

             			dataType: "json",

				beforeSend: function () {
					
        
    				},
             			success: function(data){
					//alert(data.NewID+"  "+data.Information);
								if(data.Information=="success"){
					                show_data("您的账号是："+data.NewID+",请牢记。");
								}else if(data.Information=="agenotnum"){
									$('#Age').val("");
									$('#Age').focus();
									show_err_msg('年龄栏应填写数字。');

								}else if(data.Information=="fail"){
									show_err_msg('注册未成功。请重试');
								}     
                      		}
        		 });
				}
			});
		}
);

function getmessage(){
		window.location.href="login.html";
}



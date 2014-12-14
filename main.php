<?php
include 'logic/functions.php';
include 'logic/privileges.php';
session_start();

if(!isset($_SESSION['username']))
	server_jump('index.php');

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<!-- bootstrap -->
	<link href="http://apps.bdimg.com/libs/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
	<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/css/bootstrap-theme.min.css"></script>
	<script src="http://apps.bdimg.com/libs/jquery/2.0.0/jquery.min.js"></script>
	<script src="http://apps.bdimg.com/libs/bootstrap/3.0.3/js/bootstrap.min.js"></script>

	<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0,user-scalable=yes">
	<meta name="renderer" content="webkit" />
    <meta http-equiv="X-UA-Compatible" content="chrome=1,IE=edge">
    <style type="text/css">
        body,button, input, select, textarea,h1 ,h2, h3, h4, h5, h6 { font-family: "Helvetica Neue", Helvetica,Microsoft YaHei,'宋体' , Tahoma, Arial, "\5b8b\4f53", sans-serif;}
    </style>
	<title>Hulluo!</title>
</head>
<body onload="tag_switch('board');<?php if(check_privilege(PRI_ACCESS_TEXTS))echo 'get_ntexts();setInterval(get_ntexts,120000);';?>">

	<nav class="navbar navbar-default" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
					<a class="navbar-brand" style="font-size:40px" href="#">南二 OA   <label class="primary-text" style="font-size:20px">by sqd</label></a>
				      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
				        <span class="sr-only">Toggle navigation</span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				      </button>
			</div>

			 <div class="collapse navbar-collapse" id="navbar-collapse">
					<ul class="nav navbar-nav">
						<li id="tag_board"><a href="javascript:tag_switch('board')"><span class="glyphicon glyphicon-comment" style="font-size: 19px;">公告栏</span></a></li>
						<?php if(check_privilege(PRI_ACCESS_COURSES))echo '<li id="tag_courses"><a href="javascript:tag_switch(\'courses\');"><span class="glyphicon glyphicon-book" style="font-size: 19px;">选修课</span></a></li>';?>
						<?php if(check_privilege(PRI_ACCESS_PROJECTS))echo '<li id="tag_projects"><a href="javascript:tag_switch(\'projects\');"><span class="glyphicon glyphicon-glass" style="font-size: 19px;">课题研究</span></a></li>';?>
						<?php if(check_privilege(PRI_ACCESS_TEXTS))echo '<li id="tag_inbox"><a href="javascript:tag_switch(\'inbox\');"><span class="glyphicon glyphicon-envelope" style="font-size: 19px;">收件箱</span><span class="badge" id="badge_ntexts">Loading...</span></a></li>';?>
						<li id="tag_controlpanel"><a href="javascript:tag_switch('controlpanel');"><span class="glyphicon glyphicon-hdd" style="font-size: 19px;">管理</span></a></li>
						<li id="tag_about"><a href="javascript:tag_switch('about');"><span class="glyphicon glyphicon-info-sign" style="font-size: 19px;">关于</span></a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<?php echo $_SESSION['name'];?><span class="glyphicon glyphicon-user"></span><b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<?php if(check_privilege(PRI_TEXT))echo '<li><a data-toggle="modal" data-target="#message_dialoge"><span class="glyphicon glyphicon-pencil"></span>写消息</a></li>';?>
									<li><a data-toggle="modal" data-target="#dlg_contact_me"><span class="glyphicon glyphicon-wrench"></span>反映问题</a></li>
									<li class="divider"></li>
									<li><a><span class="glyphicon glyphicon-tower"></span>权限:<?php echo $_SESSION['privilege'];?></a></li>
									<li><a href="javascript:logout()"><span class="glyphicon glyphicon-remove"></span>注销</a></li>
								</ul>
							</li>
					</ul>
			 </div>
		</div>
	</nav>
		<div class="container-fluid">
				<div class="row-fluid" id="content">
				</div>
		</div>
</body>
		<script type="text/javascript">
				var tag_list=Array("board","courses","projects","controlpanel","inbox","about");
				function logout(){
						$.get("logic/logout.php",
							function (data){
								if(data=="success")
									window.location.href="index.php";
								else
									alert("logout failed! :-(");
							});
				}
				function tag_switch(tagname){
						for(var i=0;i<tag_list.length;i++)
							if(document.getElementById("tag_"+tag_list[i])!=null)
								document.getElementById("tag_"+tag_list[i]).classList.remove("active");
						document.getElementById("tag_"+tagname).classList.add("active");
						$('#content').load('content/'+tagname+'.php');
				}
				function post_text(){
					$.post("logic/post_text.php",
		                {text_receiver:document.getElementById("text_receiver").value,text_content:document.getElementById("text_content").value},
		                function(result){
		                    if(result=="success"){
		                        $("#message_dialoge").modal("hide");
                                document.getElementById("text_receiver").value='';
                                document.getElementById("text_content").value='';
		                    }else{
		                        document.getElementById("post_button").classList.add("btn-danger");
		                        document.getElementById("post_button").innerHTML="Error. Retry?";
		                    }
		                });
				}
				function get_ntexts(){
					$.get("logic/get_unread_text_count.php",
		                function(result){
		                    if(result!="error"){
		                    	document.getElementById("badge_ntexts").innerText=result;
		                    }else{
		                    	document.getElementById("badge_ntexts").innerText="Server error :-(";
		                    }
		                });
				}
		</script>
		<div class="modal fade" id="message_dialoge" tabindex="-1" role="dialog" 
		   aria-labelledby="myModalLabel" aria-hidden="true">
		   <div class="modal-dialog">
		      <div class="modal-content">
		         <div class="modal-header">
		            <button type="button" class="close" 
		               data-dismiss="modal" aria-hidden="true">
		                  &times;
		            </button>
		            <h4 class="modal-title" id="myModalLabel">
		               <div class="form-group"><input id="text_receiver" class="form-control" placeholder="收信人"/></div>
		            </h4>
		         </div>
		         <div class="modal-body">
		            <div class="form-group">
		                <textarea id="text_content" class="form-control" rows="7" placeholder="内容"></textarea>
		            </div>
		         </div>
		         <div class="modal-footer">
		            <button type="button" class="btn btn-default" 
		               data-dismiss="modal">取消
		            </button>
		            <button type="button" id="post_button" class="btn btn-primary" onclick="post_text()">
		               <span class="glyphicon glyphicon-send">发送</span>
		            </button>
		         </div>
		      </div><!-- /.modal-content -->
		    </div><!-- /.modal -->
		</div>
</html>

<div class="modal fade" id="dlg_contact_me" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" 
				   data-dismiss="modal" aria-hidden="true">&times;
				</button>
			</div>
			<div class="modal-body">
				<address>
					<p>Email:<a href="mailto:dragonazd@gmail.com">dragonazd@gmail.com</a></p>
					<p>QQ:757887477</p>
					<p><a target="_blank" href="http://weibo.com/youybsy">Weibo</a></p>
					<p>github:<a target="_blank" href="http://github.com/sqd">sqd</a></p>
					<p>------Grade 2013,yh</p>
				</address>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default"
				   data-dismiss="modal">Close
				</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->
</div>
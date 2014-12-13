<?php
include 'logic/functions.php';
session_start();
if(isset($_SESSION['username']))
    server_jump('main.php');
?>
<!DOCTYPE html>
<html>
<head>
     <!-- jQuery -->
    <script src="http://libs.baidu.com/jquery/2.0.0/jquery.js"></script>
    
    <!-- bootstrap -->
    <script src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.js"></script>
	<link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.css" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta name="renderer" content="webkit" />
    <meta http-equiv="X-UA-Compatible" content="chrome=1,IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>南二 OA</title>
    <script type="text/javascript">
    function on_responsed(data){
        switch(data){
            case "success":
                window.location.href="main.php";
                break;
            case "ungranted":
                document.getElementById("login_btn").classList.add("btn-danger");
                document.getElementById("login_btn").innerHTML="Ungranted";
                break;
            case "fail":
                document.getElementById("u_p").classList.add("has-error");
                break;
            case "error":
                document.getElementById("login_btn").innerHTML="Server error :-(";
                document.getElementById("login_btn").classList.add("disabled");
                document.getElementById("login_btn").classList.add("btn-danger");
                break;
            }
    }
    function login(){
        document.getElementById("u_p").classList.remove("has-error");
        $.post("logic/login.php",{username:document.getElementById("username").value,passwd:document.getElementById("passwd").value},on_responsed,"text");
    }
        /*
    document.onkeypress=function(){
        if(event.keyCode==13)
            login();
    }
    */
    </script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-md-offset-4 col-sm-6 col-xs-8">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="hidden-xs"><h1 class="text-info">南二 OA:登录</h1></div>
                        <div class="hidden-sm hidden-md hidden-lg"><h2 class="text-info">南二 OA:登录</h2></div>
                    </div>
                </div>
                <div class="row">
                    <div class="center-block">
                        <form role="form" actoin="javascript:void(0);">
                            <div class="form-group" id="u_p">
                                <label>用户名</label><input type="text" id="username" value="tourist" class="form-control" placeholder="账户">
                                <label>密码</label><input type="password" id="passwd" class="form-control" placeholder="密码">
                            </div>
                            <a class="btn btn-default" onclick="login()" id="login_btn">登录</a><a class="btn btn-link">需要帮助?</a>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
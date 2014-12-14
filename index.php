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

    <!-- awesome font -->
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <!--[if IE 7]>
    <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css">
    <![endif]-->

    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta name="renderer" content="webkit" />
    <meta http-equiv="X-UA-Compatible" content="chrome=1,IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        body,button, input, select, textarea,h1 ,h2, h3, h4, h5, h6 { font-family: "Helvetica Neue", Helvetica,Microsoft YaHei,'宋体' , Tahoma, Arial, "\5b8b\4f53", sans-serif;}
    </style>
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
        document.getElementById("login_btn").innerHTML="<i class=\"icon-spinner icon-spin\"></i>";
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
<body onload="document.getElementById('label_js_warning').innerText=''">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-6 col-xs-8">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="hidden-xs"><h1 class="text-info">南二 OA:登录</h1></div>
                        <div class="hidden-sm hidden-md hidden-lg"><h2 class="text-info">南二 OA:登录</h2></div>
                    </div>
                </div>
                <div class="hidden-xs hidden-sm hidden-md hidden-lg">
                    <h2>你可能使用了版本较低的浏览器，显示效果可能不正确。</h2>
                    <h3>建议使用Chrome。支持浏览器:Chrome,Firefox,Safari,Opera,IE 8+</h3>
                    <h3>如果浏览器支持，可以尝试开启"极速模式"。</h3>
                </div>
                <label id="label_js_warning">你没有开启JavaScript，页面将无法正常使用。请在浏览器设置中开启JavaScript。</label>
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
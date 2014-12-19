<?php
include '../logic/privileges.php';
session_start();
if(!isset($_SESSION['username'])){
    session_destroy();
    exit();
}
if(!check_privilege(PRI_ACCESS_COURSES)){
    echo 'ungranted';
    exit();
}
?>
<div class="col-lg-6 col-lg-offset-3">
    <div class="row">
        <div class="col-lg-10">
            <form role="form" class="form-inline">
                <div class="form-group">
                    <input type="text" class="form-control" id="keyword" placeholder="关键词搜索"/>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="sponsor" placeholder="发起者搜索"/>
                </div>
                <div class="form-group">
                    <input id="av" type="checkbox"/>只显示有空余的
                    <a id="btn_filter" href="javascript:toggle_filter()" class="btn btn-default"><span class="glyphicon glyphicon-ok"></span>过滤</a>
                </div>
            </form>
        </div>
        <div class="col-lg-2">
            <button onclick="load_courses()" class="btn btn-default"><i id="reload_btn" class="icon-refresh"></i>刷新</button>
        </div>
    </div>
    <div class="row">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>名称</th>
                    <th>发起者</th>
                    <th>已报名人数</th>
                    <th width="50"></th>
                </tr>
            </thead>
            <tbody id="courses_info">
            </tbody>
        </table>
        <p class="text-center" id="loading_indicator"><i class="icon-spinner icon-spin icon-4x"></i></p>
    </div>
    <div class="row">
        <div class="col-lg-12">
        <ul class="pagination" id="pager">
        </ul>
        </div>
    </div>
    <script type="text/javascript">
        var filtered=false;
        var av=false;
        var kw="";
        var sp="";
        function load_courses(){
            document.getElementById("reload_btn").classList.add("icon-spin");
            $.get("content/inner_courses_info.php?page="+current_page+"&av="+av+"&kw="+kw+"&sp="+sp
                ,function(data){
                    document.getElementById("loading_indicator").style.display="none";
                    document.getElementById("reload_btn").classList.remove("icon-spin");
                document.getElementById("courses_info").innerHTML=data;
            });
        }
        function toggle_filter(){
            if(filtered){
                av=false;
                kw="";
                sp="";
                filtered=false;
                document.getElementById("btn_filter").classList.remove("active");
                load_courses();
            }else{
                av=document.getElementById("av").checked;
                kw=document.getElementById("keyword").value;
                sp=document.getElementById("sponsor").value;
                filtered=true;
                document.getElementById("btn_filter").classList.add("active");
                load_courses();
            }
        }
        function jump_page(p){
            if(p>0){
                document.getElementById("pager"+current_page).classList.remove("active");
                document.getElementById("pager"+p).classList.add("active");
                current_page=p;
                load_courses();
            }
        }
        function prev_page(){
            if(current_page!=1){
                jump_page(current_page-1);
            }
        }
        function next_page(){
            jump_page(current_page+1);
        }
        function forward_page(){
            jump_page(current_page+3);
        }
        function backward_page(){
            if(current_page>=4){
                jump_page(current_page-3);
            }
        }
        function enroll(no,obj){
            obj.innerHTML="<label>请求中..</label><i class=\"icon-spinner icon-spin\"></i>";
            $.get("../logic/enroll.php?no="+no,function(data){
                obj.className="btn";
                if(data=="success"){
                    obj.classList.add("btn-success");
                    obj.innerHTML="成功!";
                    load_courses();
                }else if(data=="fail"){
                    obj.classList.add("btn-warning");
                    obj.innerHTML="失败 :-(";
                    load_courses();
                }else if(data=="error"){
                    obj.classList.add("btn-danger");
                    obj.classList.add("disabled");
                    obj.innerHTML="服务器错误";
                }
            });
        }
        function unenroll(obj){
            obj.innerHTML="<label>请求中..</label><i class=\"icon-spinner icon-spin\"></i>";
            $.get("../logic/unenroll.php",function(data){
                obj.className="btn";
                if(data=="success"){
                    obj.innerHTML="成功!";
                    obj.classList.add("btn-success");
                    load_courses();
                }else if(data=="fail"){
                    obj.classList.add("btn-warning");
                    obj.value="失败 :-(";
                    load_courses();
                }else if(data=="error"){
                    obj.classList.add("btn-danger");
                    obj.classList.add("disabled");
                    obj.value="服务器错误";
                }
            });
        }
        var obj_pager=document.getElementById("pager");
        obj_pager.innerHTML+="<li><a href=\"javascript:backward_page()\">&laquo;</a></li>";
        obj_pager.innerHTML+="<li><a href=\"javascript:prev_page()\">&lt;</a></li>";
        obj_pager.innerHTML+="<li id=\"pager1\" class=\"active\"><a href=\"javascript:jump_page(1)\">1</a></li>";
        for(var i=2;i<=8;i++)
            obj_pager.innerHTML+="<li id=\"pager"+i+"\"><a href=\"javascript:jump_page("+i+")\">"+i+"</a></li>";
        obj_pager.innerHTML+="<li><a href=\"javascript:next_page()\">&gt;</a></li>";
        obj_pager.innerHTML+="<li><a href=\"javascript:forward_page()\">&raquo;</a></li>";
        var current_page=1;
        load_courses()
    </script>
</div>
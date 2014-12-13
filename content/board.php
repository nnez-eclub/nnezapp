<?php
include '../logic/privileges.php';
session_start();
if(!isset($_SESSION['username'])){
    session_destroy();
    exit();
}
?>
<div class="col-lg-6 col-lg-offset-3 col-md-8 col-sm-8 col-xs-12">
    <div class="row">
        <?php if(check_privilege(PRI_BOARD))echo '<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6"><button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#post_message"><span class="glyphicon glyphicon-pencil"></span>写公告</button></div>';?>
        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
             <button class="btn btn-primary btn-lg" onclick="load_board()"><span class="glyphicon glyphicon-refresh"></span>刷新</button>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
        <ul class="pagination" id="pager">
        </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10" id="board_info">
        </div>
    </div>
    <script type="text/javascript">
        function load_board(){
            $.get("content/inner_board_info.php?page="+current_page
                ,function(data){
                document.getElementById("board_info").innerHTML=data;
            });
        }
        function jump_page(p){
            if(p>0){
                document.getElementById("pager"+current_page).classList.remove("active");
                document.getElementById("pager"+p).classList.add("active");
                current_page=p;
                load_board();
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
        function post_message(){

            $.post("logic/post_message.php",
                {title:document.getElementById("c_title").value,content:document.getElementById("c_content").value},
                function(result){
                    if(result=="success"){
                        $("#post_message").modal("hide");
                        load_board();
                    }else{
                        document.getElementById("post_button").classList.add("btn-danger");
                        document.getElementById("post_button").innerHTML="Error. Retry?";
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
        load_board();
    </script>
</div>

<div class="modal fade" id="post_message" tabindex="-1" role="dialog" 
   aria-labelledby="post_messageLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="post_messageLabel">
               <div class="form-group"><input id="c_title" class="form-control" placeholder="标题"/></div>
            </h4>
         </div>
         <div class="modal-body">
            <div class="form-group">
                <textarea id="c_content" class="form-control" rows="7" placeholder="内容"></textarea>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" 
               data-dismiss="modal">取消
            </button>
            <button type="button" id="post_button" class="btn btn-primary" onclick="post_message()">
               发送
            </button>
         </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
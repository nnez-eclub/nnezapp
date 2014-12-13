<?php
include '../logic/privileges.php';
include '../logic/functions.php';
session_start();
if(!isset($_SESSION['username'])){
    session_destroy();
    exit();
}
?>
<div class="col-lg-6 col-lg-offset-3">
    <?php/*
    if(check_privilege(PRI_MODIFY_PASSWORD)){
        echo'
                <div class="row">
                    <div class="col-lg-4 col-lg-offset-1">
                    <h3>修改密码</h3>
                        <form role="form" actoin="javascript:void(0);">
                            <div class="form-group" id="form_modify_password">
                                <label>原密码</label><input type="text" id="username" class="form-control" placeholder="原密码">
                                <label>新密码</label><input type="password" id="passwd" class="form-control" placeholder="新密码">
                                <label>再次输入新密码</label><input type="password" id="passwd" class="form-control" placeholder="重复一遍">
                            </div>
                            <a class="btn btn-danger" onclick="modify_password()" id="login_btn">确认修改</a>
                        </form>
                    </div>
                </div>
                <hr/>
        ';
    }
    */
    ?>
</div>
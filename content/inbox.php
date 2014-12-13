<?php
include '../logic/privileges.php';
include '../logic/functions.php';
session_start();
if(!isset($_SESSION['username'])){
    session_destroy();
    exit();
}
if(!check_privilege(PRI_ACCESS_TEXTS)){
    echo 'ungranted';
    exit();
}
?>
<div class="col-lg-12">
<h1 class="text-info">Under construction</h1>
</div>
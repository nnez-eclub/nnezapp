<?php
session_start();
if(!isset($_SESSION['username'])){
    echo 'logouted';
    session_destroy();
    exit();
}
session_destroy();
echo 'success';
exit();
?>
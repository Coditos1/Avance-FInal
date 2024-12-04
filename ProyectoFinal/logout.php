<?php
session_start();
session_destroy(); 
header("Location: loggin.php"); 
exit();
?>
<?php
require "config.php";
@session_destroy();
@setcookie("REMEMBER","",time()-3600);
Header("Location:login.php");
?>
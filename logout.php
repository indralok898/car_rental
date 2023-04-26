<?php
session_start();
unset($_SESSION["user_id"]);
unset($_SESSION["user_type"]);
unset($_SESSION["email"]);
header("Location:login.php");
?>
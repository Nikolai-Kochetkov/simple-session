<?php
//Connect to database
$host = "localhost";
$user = "root";
$pass = "";
$link = mysqli_connect($host,$user,$pass);
mysqli_select_db($link, "bestdatabase");
mysqli_query($link, "SET NAMES UTF-8");
?>
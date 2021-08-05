<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_DataPool = "localhost";
$database_DataPool = "bzu_el";
$username_DataPool = "root";
$password_DataPool = "";
$DataPool = mysql_pconnect($hostname_DataPool, $username_DataPool, $password_DataPool) or trigger_error(mysql_error(),E_USER_ERROR); 
?>
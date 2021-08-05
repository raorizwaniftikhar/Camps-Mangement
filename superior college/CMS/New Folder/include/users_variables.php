<?php

$strTableName="users";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="users";

$gPageSize=20;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="SELECT ID,   `UID`,   PWD,   `Type` ";
$gsqlFrom="FROM users ";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "SELECT ID,   `UID`,   PWD,   `Type`   FROM users ";
$gstrSQL = gSQLWhere("");

include("users_settings.php");
?>
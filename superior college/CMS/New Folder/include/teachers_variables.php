<?php

$strTableName="teachers";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="teachers";

$gPageSize=20;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="SELECT TID,  Full_Name,  `UID`,  Designation,  Phone,  Address ";
$gsqlFrom="FROM teachers ";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "SELECT  TID,  Full_Name,  `UID`,  Designation,  Phone,  Address  FROM teachers  ";
$gstrSQL = gSQLWhere("");

include("teachers_settings.php");
?>
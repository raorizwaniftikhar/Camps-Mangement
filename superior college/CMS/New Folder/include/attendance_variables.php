<?php

$strTableName="attendance";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="attendance";

$gPageSize=20;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="SELECT ATID,  SID,  LRID,  Daate ";
$gsqlFrom="FROM attendance ";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "SELECT  ATID,  SID,  LRID,  Daate  FROM attendance  ";
$gstrSQL = gSQLWhere("");

include("attendance_settings.php");
?>
<?php

$strTableName="students";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="students";

$gPageSize=20;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="SELECT SID,   CID,   RNUM,   Full_Name,   `UID`,   Status,   L1,   L2,   L3,   L4,   L5,   L6,   L7 ";
$gsqlFrom="FROM students ";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "SELECT SID,   CID,   RNUM,   Full_Name,   `UID`,   Status,   L1,   L2,   L3,   L4,   L5,   L6,   L7   FROM students ";
$gstrSQL = gSQLWhere("");

include("students_settings.php");
?>
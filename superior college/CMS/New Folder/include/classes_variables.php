<?php

$strTableName="classes";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="classes";

$gPageSize=20;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="SELECT CID,   `Session`,   Semester,   Subjects ";
$gsqlFrom="FROM classes ";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "SELECT CID,   `Session`,   Semester,   Subjects   FROM classes ";
$gstrSQL = gSQLWhere("");

include("classes_settings.php");
?>
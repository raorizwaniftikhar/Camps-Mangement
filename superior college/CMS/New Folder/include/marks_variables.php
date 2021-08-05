<?php

$strTableName="marks";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="marks";

$gPageSize=20;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="SELECT MID,  SID,  LID,  SESSINAL,  MIDTERM,  FINAL ";
$gsqlFrom="FROM marks ";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "SELECT  MID,  SID,  LID,  SESSINAL,  MIDTERM,  FINAL  FROM marks  ";
$gstrSQL = gSQLWhere("");

include("marks_settings.php");
?>
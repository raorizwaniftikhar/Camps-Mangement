<?php

$strTableName="lectures";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="lectures";

$gPageSize=20;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="SELECT LID,  CID,  TID,  Name,  `Continue`,  `Type`,  `Start`,  `End`,  `Time`,  Room,  Duration,  About,  Announce ";
$gsqlFrom="FROM lectures ";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "SELECT  LID,  CID,  TID,  Name,  `Continue`,  `Type`,  `Start`,  `End`,  `Time`,  Room,  Duration,  About,  Announce  FROM lectures  ";
$gstrSQL = gSQLWhere("");

include("lectures_settings.php");
?>
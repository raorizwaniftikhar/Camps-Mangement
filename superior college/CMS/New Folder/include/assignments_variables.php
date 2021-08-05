<?php

$strTableName="assignments";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="assignments";

$gPageSize=20;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="SELECT AID,  Title,  `Type`,  Marks,  Description,  `File`,  LID,  Against,  SID ";
$gsqlFrom="FROM assignments ";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "SELECT  AID,  Title,  `Type`,  Marks,  Description,  `File`,  LID,  Against,  SID  FROM assignments  ";
$gstrSQL = gSQLWhere("");

include("assignments_settings.php");
?>
<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/students_variables.php");

$conn = db_connect();

$field = postvalue('searchField');
$value = postvalue('searchFor');
$lookupValue = postvalue('lookupValue');
$LookupSQL = "";
$response = array();
$output = "";


	if($field=="CID") 
	{
	
		$LookupSQL = "SELECT ";
		$LookupSQL .= "`CID`";
		$LookupSQL .= ",`CID`";
		$LookupSQL .= " FROM `classes` ";
		$LookupSQL .= " WHERE ";
		$LookupSQL .= "`CID` LIKE '".db_addslashes($value)."%'";
		$LookupSQL.= " ORDER BY `CID`";
		}
	if($field=="L1") 
	{
	
		$LookupSQL = "SELECT ";
		$LookupSQL .= "`LID`";
		$LookupSQL .= ",`Name`";
		$LookupSQL .= " FROM `lectures` ";
		$LookupSQL .= " WHERE ";
		$LookupSQL .= "`Name` LIKE '".db_addslashes($value)."%'";
		$LookupSQL.= " ORDER BY `Name`";
		}
	if($field=="L2") 
	{
	
		$LookupSQL = "SELECT ";
		$LookupSQL .= "`LID`";
		$LookupSQL .= ",`Name`";
		$LookupSQL .= " FROM `lectures` ";
		$LookupSQL .= " WHERE ";
		$LookupSQL .= "`Name` LIKE '".db_addslashes($value)."%'";
		$LookupSQL.= " ORDER BY `Name`";
		}
	if($field=="L3") 
	{
	
		$LookupSQL = "SELECT ";
		$LookupSQL .= "`LID`";
		$LookupSQL .= ",`Name`";
		$LookupSQL .= " FROM `lectures` ";
		$LookupSQL .= " WHERE ";
		$LookupSQL .= "`Name` LIKE '".db_addslashes($value)."%'";
		$LookupSQL.= " ORDER BY `Name`";
		}
	if($field=="L4") 
	{
	
		$LookupSQL = "SELECT ";
		$LookupSQL .= "`LID`";
		$LookupSQL .= ",`Name`";
		$LookupSQL .= " FROM `lectures` ";
		$LookupSQL .= " WHERE ";
		$LookupSQL .= "`Name` LIKE '".db_addslashes($value)."%'";
		$LookupSQL.= " ORDER BY `Name`";
		}
	if($field=="L5") 
	{
	
		$LookupSQL = "SELECT ";
		$LookupSQL .= "`LID`";
		$LookupSQL .= ",`Name`";
		$LookupSQL .= " FROM `lectures` ";
		$LookupSQL .= " WHERE ";
		$LookupSQL .= "`Name` LIKE '".db_addslashes($value)."%'";
		$LookupSQL.= " ORDER BY `Name`";
		}
	if($field=="L6") 
	{
	
		$LookupSQL = "SELECT ";
		$LookupSQL .= "`LID`";
		$LookupSQL .= ",`Name`";
		$LookupSQL .= " FROM `lectures` ";
		$LookupSQL .= " WHERE ";
		$LookupSQL .= "`Name` LIKE '".db_addslashes($value)."%'";
		$LookupSQL.= " ORDER BY `Name`";
		}
	if($field=="L7") 
	{
	
		$LookupSQL = "SELECT ";
		$LookupSQL .= "`LID`";
		$LookupSQL .= ",`Name`";
		$LookupSQL .= " FROM `lectures` ";
		$LookupSQL .= " WHERE ";
		$LookupSQL .= "`Name` LIKE '".db_addslashes($value)."%'";
		$LookupSQL.= " ORDER BY `Name`";
		}

$rs=db_query($LookupSQL,$conn);

$found=false;
while ($data = db_fetch_numarray($rs)) 
{
	if(!$found && $data[0]==$lookupValue)
		$found=true;
	$response[] = $data[0];
	$response[] = $data[1];
}


if ($output = array_chunk($response,40)) {
	foreach( $output[0] as $value ) {
		echo $value."\n";
		//echo str_replace("\n","\\n",$value)."\n";
	}
}

?>
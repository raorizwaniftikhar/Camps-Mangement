<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/students_variables.php");


$conn=db_connect();	

$response = array();

$suggestAllContent=true;
if(postvalue("start"))
	$suggestAllContent=false;

if (isset($_GET['searchFor']) && postvalue('searchFor') != '') {

	$searchFor = postvalue('searchFor');
	$searchField = GoodFieldName( postvalue('searchField') );
	
			if ( $searchField == '' || $searchField=="CID")
	{
		$field="CID";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like '%".str_replace("'","''",$searchFor)."%'" : " like '".str_replace("'","''",$searchFor)."%'";
		$whereCondition = " ".GetFullFieldName($field).$whereCondition;
		$whereCondition = whereAdd($gsqlWhere,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);

			while ($row = db_fetch_numarray($rs)) {
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
			}
		}
		}
			if ( $searchField == '' || $searchField=="RNUM")
	{
		$field="RNUM";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like '%".str_replace("'","''",$searchFor)."%'" : " like '".str_replace("'","''",$searchFor)."%'";
		$whereCondition = " ".GetFullFieldName($field).$whereCondition;
		$whereCondition = whereAdd($gsqlWhere,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);

			while ($row = db_fetch_numarray($rs)) {
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
			}
		}
		}
			if ( $searchField == '' || $searchField=="Full_Name")
	{
		$field="Full_Name";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like '%".str_replace("'","''",$searchFor)."%'" : " like '".str_replace("'","''",$searchFor)."%'";
		$whereCondition = " ".GetFullFieldName($field).$whereCondition;
		$whereCondition = whereAdd($gsqlWhere,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);

			while ($row = db_fetch_numarray($rs)) {
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
			}
		}
		}
			if ( $searchField == '' || $searchField=="UID")
	{
		$field="UID";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like '%".str_replace("'","''",$searchFor)."%'" : " like '".str_replace("'","''",$searchFor)."%'";
		$whereCondition = " ".GetFullFieldName($field).$whereCondition;
		$whereCondition = whereAdd($gsqlWhere,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);

			while ($row = db_fetch_numarray($rs)) {
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
			}
		}
		}
			if ( $searchField == '' || $searchField=="Status")
	{
		$field="Status";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like '%".str_replace("'","''",$searchFor)."%'" : " like '".str_replace("'","''",$searchFor)."%'";
		$whereCondition = " ".GetFullFieldName($field).$whereCondition;
		$whereCondition = whereAdd($gsqlWhere,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);

			while ($row = db_fetch_numarray($rs)) {
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
			}
		}
		}
			if ( $searchField == '' || $searchField=="L1")
	{
		$field="L1";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like '%".str_replace("'","''",$searchFor)."%'" : " like '".str_replace("'","''",$searchFor)."%'";
		$whereCondition = " ".GetFullFieldName($field).$whereCondition;
		$whereCondition = whereAdd($gsqlWhere,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);

			while ($row = db_fetch_numarray($rs)) {
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
			}
		}
		}
			if ( $searchField == '' || $searchField=="L2")
	{
		$field="L2";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like '%".str_replace("'","''",$searchFor)."%'" : " like '".str_replace("'","''",$searchFor)."%'";
		$whereCondition = " ".GetFullFieldName($field).$whereCondition;
		$whereCondition = whereAdd($gsqlWhere,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);

			while ($row = db_fetch_numarray($rs)) {
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
			}
		}
		}
			if ( $searchField == '' || $searchField=="L3")
	{
		$field="L3";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like '%".str_replace("'","''",$searchFor)."%'" : " like '".str_replace("'","''",$searchFor)."%'";
		$whereCondition = " ".GetFullFieldName($field).$whereCondition;
		$whereCondition = whereAdd($gsqlWhere,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);

			while ($row = db_fetch_numarray($rs)) {
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
			}
		}
		}
			if ( $searchField == '' || $searchField=="L4")
	{
		$field="L4";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like '%".str_replace("'","''",$searchFor)."%'" : " like '".str_replace("'","''",$searchFor)."%'";
		$whereCondition = " ".GetFullFieldName($field).$whereCondition;
		$whereCondition = whereAdd($gsqlWhere,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);

			while ($row = db_fetch_numarray($rs)) {
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
			}
		}
		}
			if ( $searchField == '' || $searchField=="L5")
	{
		$field="L5";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like '%".str_replace("'","''",$searchFor)."%'" : " like '".str_replace("'","''",$searchFor)."%'";
		$whereCondition = " ".GetFullFieldName($field).$whereCondition;
		$whereCondition = whereAdd($gsqlWhere,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);

			while ($row = db_fetch_numarray($rs)) {
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
			}
		}
		}
			if ( $searchField == '' || $searchField=="L6")
	{
		$field="L6";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like '%".str_replace("'","''",$searchFor)."%'" : " like '".str_replace("'","''",$searchFor)."%'";
		$whereCondition = " ".GetFullFieldName($field).$whereCondition;
		$whereCondition = whereAdd($gsqlWhere,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);

			while ($row = db_fetch_numarray($rs)) {
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
			}
		}
		}
			if ( $searchField == '' || $searchField=="L7")
	{
		$field="L7";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like '%".str_replace("'","''",$searchFor)."%'" : " like '".str_replace("'","''",$searchFor)."%'";
		$whereCondition = " ".GetFullFieldName($field).$whereCondition;
		$whereCondition = whereAdd($gsqlWhere,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);

			while ($row = db_fetch_numarray($rs)) {
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
			}
		}
		}
	db_close($conn);
}

sort($response);

if ($output = array_chunk(array_unique($response),10)) {
	foreach( $output[0] as $value ) {
		if($suggestAllContent)
		{
			$str=substr($value,0,50);
			$pos=my_stripos($str,$searchFor,0);
			if($pos===false)
				echo $str;
			else
				echo substr($str,0,$pos)."<b>".substr($str,$pos,strlen($searchFor))."</b>".substr($str,$pos+strlen($searchFor));
			echo "\n";
		}
		else
			echo  "<b>".substr($value,0,strlen($searchFor))."</b>".substr($value,strlen($searchFor),50-strlen($searchFor))."\n";
	}
}
?>
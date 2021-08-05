<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/students_variables.php");

$mode=postvalue("mode");


include('libs/xtempl.php');
$xt = new Xtempl();

$conn=db_connect(); 
$recordsCounter = 0;

//	process masterkey value
$mastertable=postvalue("mastertable");
if($mastertable!="")
{
	$_SESSION[$strTableName."_mastertable"]=$mastertable;
//	copy keys to session
	$i=1;
	while(isset($_REQUEST["masterkey".$i]))
	{
		$_SESSION[$strTableName."_masterkey".$i]=$_REQUEST["masterkey".$i];
		$i++;
	}
	if(isset($_SESSION[$strTableName."_masterkey".$i]))
		unset($_SESSION[$strTableName."_masterkey".$i]);
}
else
	$mastertable=$_SESSION[$strTableName."_mastertable"];

//$strSQL = $gstrSQL;

if($mastertable=="users")
{
	$where ="";
		$where.= GetFullFieldName("UID")."=".make_db_value("UID",$_SESSION[$strTableName."_masterkey1"]);
}
if($mastertable=="marks")
{
	$where ="";
		$where.= GetFullFieldName("SID")."=".make_db_value("SID",$_SESSION[$strTableName."_masterkey1"]);
}
if($mastertable=="attendance")
{
	$where ="";
		$where.= GetFullFieldName("SID")."=".make_db_value("SID",$_SESSION[$strTableName."_masterkey1"]);
}


$str = SecuritySQL("Search");
if(strlen($str))
	$where.=" and ".$str;
$strSQL = gSQLWhere($where);

$strSQL.=" ".$gstrOrderBy;

$rowcount=gSQLRowCount($where,0);

$xt->assign("row_count",$rowcount);
if ( $rowcount ) {
	$xt->assign("details_data",true);
	$rs=db_query($strSQL,$conn);
	$display_count=10;
	if($mode=="inline")
		$display_count*=2;
	if($rowcount>$display_count+2)
	{
		$xt->assign("display_first",true);
		$xt->assign("display_count",$display_count);
	}
	else
		$display_count = $rowcount;

	$rowinfo=array();
		
	while (($data = db_fetch_array($rs)) && $recordsCounter<$display_count) {
		$recordsCounter++;
		$row=array();
		$keylink="";
		$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["SID"]));

	//	CID - 
		    $value="";
				$value=DisplayLookupWizard("CID",$data["CID"],$data,$keylink,MODE_PRINT);
			$row["CID_value"]=$value;
	//	RNUM - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"RNUM", ""),"field=RNUM".$keylink,"",MODE_PRINT);
			$row["RNUM_value"]=$value;
	//	Full_Name - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Full_Name", ""),"field=Full%5FName".$keylink,"",MODE_PRINT);
			$row["Full_Name_value"]=$value;
	//	Status - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Status", ""),"field=Status".$keylink,"",MODE_PRINT);
			$row["Status_value"]=$value;
	//	L1 - 
		    $value="";
				$value=DisplayLookupWizard("L1",$data["L1"],$data,$keylink,MODE_PRINT);
			$row["L1_value"]=$value;
	//	L2 - 
		    $value="";
				$value=DisplayLookupWizard("L2",$data["L2"],$data,$keylink,MODE_PRINT);
			$row["L2_value"]=$value;
	//	L3 - 
		    $value="";
				$value=DisplayLookupWizard("L3",$data["L3"],$data,$keylink,MODE_PRINT);
			$row["L3_value"]=$value;
	//	L4 - 
		    $value="";
				$value=DisplayLookupWizard("L4",$data["L4"],$data,$keylink,MODE_PRINT);
			$row["L4_value"]=$value;
	//	L5 - 
		    $value="";
				$value=DisplayLookupWizard("L5",$data["L5"],$data,$keylink,MODE_PRINT);
			$row["L5_value"]=$value;
	//	L6 - 
		    $value="";
				$value=DisplayLookupWizard("L6",$data["L6"],$data,$keylink,MODE_PRINT);
			$row["L6_value"]=$value;
	//	L7 - 
		    $value="";
				$value=DisplayLookupWizard("L7",$data["L7"],$data,$keylink,MODE_PRINT);
			$row["L7_value"]=$value;
	$rowinfo[]=$row;
	}
	$xt->assign_loopsection("details_row",$rowinfo);
} else {
}
$xt->display("students_detailspreview.htm");
if($mode!="inline")
	echo "counterSeparator".postvalue("counter");
?>
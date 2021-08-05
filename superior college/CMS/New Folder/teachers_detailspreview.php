<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/teachers_variables.php");

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
		$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["TID"]));

	//	Full_Name - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Full_Name", ""),"field=Full%5FName".$keylink,"",MODE_PRINT);
			$row["Full_Name_value"]=$value;
	//	Designation - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Designation", ""),"field=Designation".$keylink,"",MODE_PRINT);
			$row["Designation_value"]=$value;
	//	Phone - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Phone", ""),"field=Phone".$keylink,"",MODE_PRINT);
			$row["Phone_value"]=$value;
	//	Address - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Address", ""),"field=Address".$keylink,"",MODE_PRINT);
			$row["Address_value"]=$value;
	$rowinfo[]=$row;
	}
	$xt->assign_loopsection("details_row",$rowinfo);
} else {
}
$xt->display("teachers_detailspreview.htm");
if($mode!="inline")
	echo "counterSeparator".postvalue("counter");
?>
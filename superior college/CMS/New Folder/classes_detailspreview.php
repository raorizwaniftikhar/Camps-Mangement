<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/classes_variables.php");

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

if($mastertable=="students")
{
	$where ="";
		$where.= GetFullFieldName("CID")."=".make_db_value("CID",$_SESSION[$strTableName."_masterkey1"]);
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
		$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["CID"]));

	//	CID - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"CID", ""),"field=CID".$keylink,"",MODE_PRINT);
			$row["CID_value"]=$value;
	//	Session - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Session", ""),"field=Session".$keylink,"",MODE_PRINT);
			$row["Session_value"]=$value;
	//	Semester - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Semester", ""),"field=Semester".$keylink,"",MODE_PRINT);
			$row["Semester_value"]=$value;
	//	Subjects - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Subjects", ""),"field=Subjects".$keylink,"",MODE_PRINT);
			$row["Subjects_value"]=$value;
	$rowinfo[]=$row;
	}
	$xt->assign_loopsection("details_row",$rowinfo);
} else {
}
$xt->display("classes_detailspreview.htm");
if($mode!="inline")
	echo "counterSeparator".postvalue("counter");
?>
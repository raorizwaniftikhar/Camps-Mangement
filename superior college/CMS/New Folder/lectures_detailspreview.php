<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/lectures_variables.php");

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

if($mastertable=="attendance")
{
	$where ="";
		$where.= GetFullFieldName("LID")."=".make_db_value("LID",$_SESSION[$strTableName."_masterkey1"]);
}
if($mastertable=="assignments")
{
	$where ="";
		$where.= GetFullFieldName("LID")."=".make_db_value("LID",$_SESSION[$strTableName."_masterkey1"]);
}
if($mastertable=="marks")
{
	$where ="";
		$where.= GetFullFieldName("LID")."=".make_db_value("LID",$_SESSION[$strTableName."_masterkey1"]);
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
		$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["LID"]));

	//	CID - 
		    $value="";
				$value=DisplayLookupWizard("CID",$data["CID"],$data,$keylink,MODE_PRINT);
			$row["CID_value"]=$value;
	//	TID - 
		    $value="";
				$value=DisplayLookupWizard("TID",$data["TID"],$data,$keylink,MODE_PRINT);
			$row["TID_value"]=$value;
	//	Name - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Name", ""),"field=Name".$keylink,"",MODE_PRINT);
			$row["Name_value"]=$value;
	//	Continue - Checkbox
		    $value="";
				$value = GetData($data,"Continue", "Checkbox");
			$row["Continue_value"]=$value;
	//	Type - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Type", ""),"field=Type".$keylink,"",MODE_PRINT);
			$row["Type_value"]=$value;
	//	Start - Short Date
		    $value="";
				$value = ProcessLargeText(GetData($data,"Start", "Short Date"),"field=Start".$keylink,"",MODE_PRINT);
			$row["Start_value"]=$value;
	//	End - Short Date
		    $value="";
				$value = ProcessLargeText(GetData($data,"End", "Short Date"),"field=End".$keylink,"",MODE_PRINT);
			$row["End_value"]=$value;
	//	Time - Time
		    $value="";
				$value = ProcessLargeText(GetData($data,"Time", "Time"),"field=Time".$keylink,"",MODE_PRINT);
			$row["Time_value"]=$value;
	//	Room - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Room", ""),"field=Room".$keylink,"",MODE_PRINT);
			$row["Room_value"]=$value;
	//	Duration - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Duration", ""),"field=Duration".$keylink,"",MODE_PRINT);
			$row["Duration_value"]=$value;
	//	About - HTML
		    $value="";
				$value = GetData($data,"About", "HTML");
			$row["About_value"]=$value;
	//	Announce - HTML
		    $value="";
				$value = GetData($data,"Announce", "HTML");
			$row["Announce_value"]=$value;
	$rowinfo[]=$row;
	}
	$xt->assign_loopsection("details_row",$rowinfo);
} else {
}
$xt->display("lectures_detailspreview.htm");
if($mode!="inline")
	echo "counterSeparator".postvalue("counter");
?>
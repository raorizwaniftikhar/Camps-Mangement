<?php
include("students_settings.php");

function DisplayMasterTableInfo_students($params)
{
	$detailtable=$params["detailtable"];
	$keys=$params["keys"];
	global $conn,$strTableName;
	$xt = new Xtempl();
	
	$oldTableName=$strTableName;
	$strTableName="students";

//$strSQL = "SELECT SID,   CID,   RNUM,   Full_Name,   `UID`,   Status,   L1,   L2,   L3,   L4,   L5,   L6,   L7   FROM students ";

$sqlHead="SELECT SID,   CID,   RNUM,   Full_Name,   `UID`,   Status,   L1,   L2,   L3,   L4,   L5,   L6,   L7 ";
$sqlFrom="FROM students ";
$sqlWhere="";
$sqlTail="";

$where="";

if($detailtable=="classes")
{
		$where.= GetFullFieldName("CID")."=".make_db_value("CID",$keys[1-1]);
}
if(!$where)
{
	$strTableName=$oldTableName;
	return;
}
	$str = SecuritySQL("Export");
	if(strlen($str))
		$where.=" and ".$str;
	
	$strWhere=whereAdd($sqlWhere,$where);
	if(strlen($strWhere))
		$strWhere=" where ".$strWhere." ";
	$strSQL= $sqlHead.$sqlFrom.$strWhere.$sqlTail;

//	$strSQL=AddWhere($strSQL,$where);

	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
	$data=db_fetch_array($rs);
	$keylink="";
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["SID"]));
	

//	CID - 
			$value="";
				$value=DisplayLookupWizard("CID",$data["CID"],$data,$keylink,MODE_PRINT);
			$xt->assign("CID_mastervalue",$value);

//	RNUM - 
			$value="";
				$value = ProcessLargeText(GetData($data,"RNUM", ""),"field=RNUM".$keylink,"",MODE_PRINT);
			$xt->assign("RNUM_mastervalue",$value);

//	Full_Name - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Full_Name", ""),"field=Full%5FName".$keylink,"",MODE_PRINT);
			$xt->assign("Full_Name_mastervalue",$value);

//	Status - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Status", ""),"field=Status".$keylink,"",MODE_PRINT);
			$xt->assign("Status_mastervalue",$value);

//	L1 - 
			$value="";
				$value=DisplayLookupWizard("L1",$data["L1"],$data,$keylink,MODE_PRINT);
			$xt->assign("L1_mastervalue",$value);

//	L2 - 
			$value="";
				$value=DisplayLookupWizard("L2",$data["L2"],$data,$keylink,MODE_PRINT);
			$xt->assign("L2_mastervalue",$value);

//	L3 - 
			$value="";
				$value=DisplayLookupWizard("L3",$data["L3"],$data,$keylink,MODE_PRINT);
			$xt->assign("L3_mastervalue",$value);

//	L4 - 
			$value="";
				$value=DisplayLookupWizard("L4",$data["L4"],$data,$keylink,MODE_PRINT);
			$xt->assign("L4_mastervalue",$value);

//	L5 - 
			$value="";
				$value=DisplayLookupWizard("L5",$data["L5"],$data,$keylink,MODE_PRINT);
			$xt->assign("L5_mastervalue",$value);

//	L6 - 
			$value="";
				$value=DisplayLookupWizard("L6",$data["L6"],$data,$keylink,MODE_PRINT);
			$xt->assign("L6_mastervalue",$value);

//	L7 - 
			$value="";
				$value=DisplayLookupWizard("L7",$data["L7"],$data,$keylink,MODE_PRINT);
			$xt->assign("L7_mastervalue",$value);
	$strTableName=$oldTableName;
	$xt->display("students_masterprint.htm");

}

// events

?>
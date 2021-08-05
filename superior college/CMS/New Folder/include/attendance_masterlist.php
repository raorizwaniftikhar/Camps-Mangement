<?php
include("attendance_settings.php");

function DisplayMasterTableInfo_attendance($params)
{
	$detailtable=$params["detailtable"];
	$keys=$params["keys"];
	global $conn,$strTableName;
	$xt = new Xtempl();
	
	$oldTableName=$strTableName;
	$strTableName="attendance";

//$strSQL = "SELECT  ATID,  SID,  LRID,  Daate  FROM attendance  ";

$sqlHead="SELECT ATID,  SID,  LRID,  Daate ";
$sqlFrom="FROM attendance ";
$sqlWhere="";
$sqlTail="";

$where="";

if($detailtable=="lectures")
{
		$where.= GetFullFieldName("LRID")."=".make_db_value("LRID",$keys[1-1]);
}
elseif($detailtable=="students")
{
		$where.= GetFullFieldName("SID")."=".make_db_value("SID",$keys[1-1]);
}
if(!$where)
{
	$strTableName=$oldTableName;
	return;
}
	$str = SecuritySQL("Search");
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
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["ATID"]));
	

//	LRID - 
			$value="";
				$value=DisplayLookupWizard("LRID",$data["LRID"],$data,$keylink,MODE_LIST);
			$xt->assign("LRID_mastervalue",$value);

//	SID - 
			$value="";
				$value=DisplayLookupWizard("SID",$data["SID"],$data,$keylink,MODE_LIST);
			$xt->assign("SID_mastervalue",$value);

//	Daate - Short Date
			$value="";
				$value = ProcessLargeText(GetData($data,"Daate", "Short Date"),"field=Daate".$keylink);
			$xt->assign("Daate_mastervalue",$value);
	$strTableName=$oldTableName;
	$xt->display("attendance_masterlist.htm");
}

// events

?>
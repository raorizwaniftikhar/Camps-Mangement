<?php
include("marks_settings.php");

function DisplayMasterTableInfo_marks($params)
{
	$detailtable=$params["detailtable"];
	$keys=$params["keys"];
	global $conn,$strTableName;
	$xt = new Xtempl();
	
	$oldTableName=$strTableName;
	$strTableName="marks";

//$strSQL = "SELECT  MID,  SID,  LID,  SESSINAL,  MIDTERM,  FINAL  FROM marks  ";

$sqlHead="SELECT MID,  SID,  LID,  SESSINAL,  MIDTERM,  FINAL ";
$sqlFrom="FROM marks ";
$sqlWhere="";
$sqlTail="";

$where="";

if($detailtable=="lectures")
{
		$where.= GetFullFieldName("LID")."=".make_db_value("LID",$keys[1-1]);
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
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["MID"]));
	

//	SID - 
			$value="";
				$value=DisplayLookupWizard("SID",$data["SID"],$data,$keylink,MODE_PRINT);
			$xt->assign("SID_mastervalue",$value);

//	LID - 
			$value="";
				$value=DisplayLookupWizard("LID",$data["LID"],$data,$keylink,MODE_PRINT);
			$xt->assign("LID_mastervalue",$value);

//	SESSINAL - 
			$value="";
				$value = ProcessLargeText(GetData($data,"SESSINAL", ""),"field=SESSINAL".$keylink,"",MODE_PRINT);
			$xt->assign("SESSINAL_mastervalue",$value);

//	MIDTERM - 
			$value="";
				$value = ProcessLargeText(GetData($data,"MIDTERM", ""),"field=MIDTERM".$keylink,"",MODE_PRINT);
			$xt->assign("MIDTERM_mastervalue",$value);

//	FINAL - 
			$value="";
				$value = ProcessLargeText(GetData($data,"FINAL", ""),"field=FINAL".$keylink,"",MODE_PRINT);
			$xt->assign("FINAL_mastervalue",$value);
	$strTableName=$oldTableName;
	$xt->display("marks_masterprint.htm");

}

// events

?>
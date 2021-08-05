<?php
include("assignments_settings.php");

function DisplayMasterTableInfo_assignments($params)
{
	$detailtable=$params["detailtable"];
	$keys=$params["keys"];
	global $conn,$strTableName;
	$xt = new Xtempl();
	
	$oldTableName=$strTableName;
	$strTableName="assignments";

//$strSQL = "SELECT  AID,  Title,  `Type`,  Marks,  Description,  `File`,  LID,  Against,  SID  FROM assignments  ";

$sqlHead="SELECT AID,  Title,  `Type`,  Marks,  Description,  `File`,  LID,  Against,  SID ";
$sqlFrom="FROM assignments ";
$sqlWhere="";
$sqlTail="";

$where="";

if($detailtable=="lectures")
{
		$where.= GetFullFieldName("LID")."=".make_db_value("LID",$keys[1-1]);
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
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["AID"]));
	

//	Title - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Title", ""),"field=Title".$keylink,"",MODE_PRINT);
			$xt->assign("Title_mastervalue",$value);

//	LID - 
			$value="";
				$value=DisplayLookupWizard("LID",$data["LID"],$data,$keylink,MODE_PRINT);
			$xt->assign("LID_mastervalue",$value);

//	Marks - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Marks", ""),"field=Marks".$keylink,"",MODE_PRINT);
			$xt->assign("Marks_mastervalue",$value);

//	Description - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Description", ""),"field=Description".$keylink,"",MODE_PRINT);
			$xt->assign("Description_mastervalue",$value);

//	File - Document Download
			$value="";
				$value = GetData($data,"File", "Document Download");
			$xt->assign("File_mastervalue",$value);
	$strTableName=$oldTableName;
	$xt->display("assignments_masterprint.htm");

}

// events

?>
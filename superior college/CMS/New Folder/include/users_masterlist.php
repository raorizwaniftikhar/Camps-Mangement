<?php
include("users_settings.php");

function DisplayMasterTableInfo_users($params)
{
	$detailtable=$params["detailtable"];
	$keys=$params["keys"];
	global $conn,$strTableName;
	$xt = new Xtempl();
	
	$oldTableName=$strTableName;
	$strTableName="users";

//$strSQL = "SELECT ID,   `UID`,   PWD,   `Type`   FROM users ";

$sqlHead="SELECT ID,   `UID`,   PWD,   `Type` ";
$sqlFrom="FROM users ";
$sqlWhere="";
$sqlTail="";

$where="";

if($detailtable=="students")
{
		$where.= GetFullFieldName("UID")."=".make_db_value("UID",$keys[1-1]);
}
elseif($detailtable=="teachers")
{
		$where.= GetFullFieldName("UID")."=".make_db_value("UID",$keys[1-1]);
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
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["ID"]));
	

//	ID - 
			$value="";
				$value = ProcessLargeText(GetData($data,"ID", ""),"field=ID".$keylink);
			$xt->assign("ID_mastervalue",$value);

//	UID - 
			$value="";
				$value = ProcessLargeText(GetData($data,"UID", ""),"field=UID".$keylink);
			$xt->assign("UID_mastervalue",$value);

//	PWD - 
			$value="";
				$value = ProcessLargeText(GetData($data,"PWD", ""),"field=PWD".$keylink);
			$xt->assign("PWD_mastervalue",$value);

//	Type - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Type", ""),"field=Type".$keylink);
			$xt->assign("Type_mastervalue",$value);
	$strTableName=$oldTableName;
	$xt->display("users_masterlist.htm");
}

// events

?>
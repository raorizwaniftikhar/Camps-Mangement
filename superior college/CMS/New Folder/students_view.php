<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/students_variables.php");





$filename="";	
$message="";

$all=postvalue("all");
$pdf=postvalue("pdf");
$mypage=1;

$id=1;

//connect database
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessView"))
	BeforeProcessView($conn);

$strWhereClause="";
if(!$all)
{
	$keys=array();
	$keys["SID"]=postvalue("editid1");

//	get current values and show edit controls

	$strWhereClause = KeyWhere($keys);


	$strSQL=gSQLWhere($strWhereClause);
}
else
{
	if ($_SESSION[$strTableName."_SelectedSQL"]!="" && @$_REQUEST["records"]=="") 
	{
		$strSQL = $_SESSION[$strTableName."_SelectedSQL"];
		$strWhereClause=@$_SESSION[$strTableName."_SelectedWhere"];
	}
	else
	{
		$strWhereClause=@$_SESSION[$strTableName."_where"];
		$strSQL=gSQLWhere($strWhereClause);
	}
	$strOrderBy=$_SESSION[$strTableName."_order"];
	if(!$strOrderBy)
		$strOrderBy=$gstrOrderBy;
	$strSQL.=" ".trim($strOrderBy);
//	order by
	$strOrderBy=$_SESSION[$strTableName."_order"];
	if(!$strOrderBy)
		$strOrderBy=$gstrOrderBy;
	$strSQL.=" ".trim($strOrderBy);
		$numrows=gSQLRowCount($strWhereClause,0);

}


$strSQLbak = $strSQL;
if(function_exists("BeforeQueryView"))
	BeforeQueryView($strSQL,$strWhereClause);
if($strSQLbak == $strSQL)
	$strSQL=gSQLWhere($strWhereClause);

if(!$all)
{
	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
}
else
{
//	 Pagination:

	$nPageSize=0;
	if(@$_REQUEST["records"]=="page" && $numrows)
	{
		$mypage=(integer)@$_SESSION[$strTableName."_pagenumber"];
		$nPageSize=(integer)@$_SESSION[$strTableName."_pagesize"];
		if($numrows<=($mypage-1)*$nPageSize)
			$mypage=ceil($numrows/$nPageSize);
		if(!$nPageSize)
			$nPageSize=$gPageSize;
		if(!$mypage)
			$mypage=1;

		$strSQL.=" limit ".(($mypage-1)*$nPageSize).",".$nPageSize;
	}
	$rs=db_query($strSQL,$conn);
}

$data=db_fetch_array($rs);

include('libs/xtempl.php');
$xt = new Xtempl();

$out="";
$first=true;

$templatefile="";

while($data)
{



	$xt->assign("show_key1", htmlspecialchars(GetData($data,"SID", "")));

$keylink="";
$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["SID"]));

////////////////////////////////////////////
//	CID - 
	$value="";
		$value=DisplayLookupWizard("CID",$data["CID"],$data,$keylink,MODE_VIEW);
			
	$xt->assign("CID_value",$value);
	$xt->assign("CID_fieldblock",true);
////////////////////////////////////////////
//	RNUM - 
	$value="";
		$value = ProcessLargeText(GetData($data,"RNUM", ""),"","",MODE_VIEW);
	$xt->assign("RNUM_value",$value);
	$xt->assign("RNUM_fieldblock",true);
////////////////////////////////////////////
//	Full_Name - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Full_Name", ""),"","",MODE_VIEW);
	$xt->assign("Full_Name_value",$value);
	$xt->assign("Full_Name_fieldblock",true);
////////////////////////////////////////////
//	UID - 
	$value="";
		$value = ProcessLargeText(GetData($data,"UID", ""),"","",MODE_VIEW);
	$xt->assign("UID_value",$value);
	$xt->assign("UID_fieldblock",true);
////////////////////////////////////////////
//	Status - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Status", ""),"","",MODE_VIEW);
	$xt->assign("Status_value",$value);
	$xt->assign("Status_fieldblock",true);
////////////////////////////////////////////
//	L1 - 
	$value="";
		$value=DisplayLookupWizard("L1",$data["L1"],$data,$keylink,MODE_VIEW);
			
	$xt->assign("L1_value",$value);
	$xt->assign("L1_fieldblock",true);
////////////////////////////////////////////
//	L2 - 
	$value="";
		$value=DisplayLookupWizard("L2",$data["L2"],$data,$keylink,MODE_VIEW);
			
	$xt->assign("L2_value",$value);
	$xt->assign("L2_fieldblock",true);
////////////////////////////////////////////
//	L3 - 
	$value="";
		$value=DisplayLookupWizard("L3",$data["L3"],$data,$keylink,MODE_VIEW);
			
	$xt->assign("L3_value",$value);
	$xt->assign("L3_fieldblock",true);
////////////////////////////////////////////
//	L4 - 
	$value="";
		$value=DisplayLookupWizard("L4",$data["L4"],$data,$keylink,MODE_VIEW);
			
	$xt->assign("L4_value",$value);
	$xt->assign("L4_fieldblock",true);
////////////////////////////////////////////
//	L5 - 
	$value="";
		$value=DisplayLookupWizard("L5",$data["L5"],$data,$keylink,MODE_VIEW);
			
	$xt->assign("L5_value",$value);
	$xt->assign("L5_fieldblock",true);
////////////////////////////////////////////
//	L6 - 
	$value="";
		$value=DisplayLookupWizard("L6",$data["L6"],$data,$keylink,MODE_VIEW);
			
	$xt->assign("L6_value",$value);
	$xt->assign("L6_fieldblock",true);
////////////////////////////////////////////
//	L7 - 
	$value="";
		$value=DisplayLookupWizard("L7",$data["L7"],$data,$keylink,MODE_VIEW);
			
	$xt->assign("L7_value",$value);
	$xt->assign("L7_fieldblock",true);

$body=array();
$body["begin"]="";

$xt->assignbyref("body",$body);
$xt->assign("style_block",true);
$xt->assign("stylefiles_block",true);
if(!$pdf && !$all)
{
	$xt->assign("back_button",true);
	$xt->assign("backbutton_attrs","onclick=\"window.location.href='students_list.php?a=return'\"");
}

$oldtemplatefile=$templatefile;
$templatefile = "students_view.htm";
if(!$all)
{
	if(function_exists("BeforeShowView"))
		BeforeShowView($xt,$templatefile,$data);
	if(!$pdf)
		$xt->display($templatefile);
	break;
}

}


?>

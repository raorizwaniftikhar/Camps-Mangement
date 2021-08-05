<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/lectures_variables.php");





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
	$keys["LID"]=postvalue("editid1");

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



	$xt->assign("show_key1", htmlspecialchars(GetData($data,"LID", "")));

$keylink="";
$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["LID"]));

////////////////////////////////////////////
//	CID - 
	$value="";
		$value=DisplayLookupWizard("CID",$data["CID"],$data,$keylink,MODE_VIEW);
			
	$xt->assign("CID_value",$value);
	$xt->assign("CID_fieldblock",true);
////////////////////////////////////////////
//	TID - 
	$value="";
		$value=DisplayLookupWizard("TID",$data["TID"],$data,$keylink,MODE_VIEW);
			
	$xt->assign("TID_value",$value);
	$xt->assign("TID_fieldblock",true);
////////////////////////////////////////////
//	Name - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Name", ""),"","",MODE_VIEW);
	$xt->assign("Name_value",$value);
	$xt->assign("Name_fieldblock",true);
////////////////////////////////////////////
//	Continue - Checkbox
	$value="";
		$value = GetData($data,"Continue", "Checkbox");
	$xt->assign("Continue_value",$value);
	$xt->assign("Continue_fieldblock",true);
////////////////////////////////////////////
//	Type - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Type", ""),"","",MODE_VIEW);
	$xt->assign("Type_value",$value);
	$xt->assign("Type_fieldblock",true);
////////////////////////////////////////////
//	Start - Short Date
	$value="";
		$value = ProcessLargeText(GetData($data,"Start", "Short Date"),"","",MODE_VIEW);
	$xt->assign("Start_value",$value);
	$xt->assign("Start_fieldblock",true);
////////////////////////////////////////////
//	End - Short Date
	$value="";
		$value = ProcessLargeText(GetData($data,"End", "Short Date"),"","",MODE_VIEW);
	$xt->assign("End_value",$value);
	$xt->assign("End_fieldblock",true);
////////////////////////////////////////////
//	Time - Time
	$value="";
		$value = ProcessLargeText(GetData($data,"Time", "Time"),"","",MODE_VIEW);
	$xt->assign("Time_value",$value);
	$xt->assign("Time_fieldblock",true);
////////////////////////////////////////////
//	Room - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Room", ""),"","",MODE_VIEW);
	$xt->assign("Room_value",$value);
	$xt->assign("Room_fieldblock",true);
////////////////////////////////////////////
//	Duration - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Duration", ""),"","",MODE_VIEW);
	$xt->assign("Duration_value",$value);
	$xt->assign("Duration_fieldblock",true);
////////////////////////////////////////////
//	About - HTML
	$value="";
		$value = GetData($data,"About", "HTML");
	$xt->assign("About_value",$value);
	$xt->assign("About_fieldblock",true);
////////////////////////////////////////////
//	Announce - HTML
	$value="";
		$value = GetData($data,"Announce", "HTML");
	$xt->assign("Announce_value",$value);
	$xt->assign("Announce_fieldblock",true);

$body=array();
$body["begin"]="";

$xt->assignbyref("body",$body);
$xt->assign("style_block",true);
$xt->assign("stylefiles_block",true);
if(!$pdf && !$all)
{
	$xt->assign("back_button",true);
	$xt->assign("backbutton_attrs","onclick=\"window.location.href='lectures_list.php?a=return'\"");
}

$oldtemplatefile=$templatefile;
$templatefile = "lectures_view.htm";
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

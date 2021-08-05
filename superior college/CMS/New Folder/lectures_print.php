<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/lectures_variables.php");


$all=postvalue("all");

include('libs/xtempl.php');
$xt = new Xtempl();

$conn=db_connect();

//	Before Process event
if(function_exists("BeforeProcessPrint"))
	BeforeProcessPrint($conn);

$strWhereClause="";

if (@$_REQUEST["a"]!="") 
{
	
	$sWhere = "1=0";	
	
//	process selection
	$selected_recs=array();
	if (@$_REQUEST["mdelete"])
	{
		foreach(@$_REQUEST["mdelete"] as $ind)
		{
			$keys=array();
			$keys["LID"]=refine($_REQUEST["mdelete1"][$ind-1]);
			$selected_recs[]=$keys;
		}
	}
	elseif(@$_REQUEST["selection"])
	{
		foreach(@$_REQUEST["selection"] as $keyblock)
		{
			$arr=split("&",refine($keyblock));
			if(count($arr)<1)
				continue;
			$keys=array();
			$keys["LID"]=urldecode($arr[0]);
			$selected_recs[]=$keys;
		}
	}

	foreach($selected_recs as $keys)
	{
		$sWhere = $sWhere . " or ";
		$sWhere.=KeyWhere($keys);
	}
//	$strSQL = AddWhere($gstrSQL,$sWhere);
	$strSQL = gSQLWhere($sWhere);
	$strWhereClause=$sWhere;
}
else
{
	$strWhereClause=@$_SESSION[$strTableName."_where"];
	$strSQL = gSQLWhere($strWhereClause);
}
if(postvalue("pdf"))
	$strWhereClause = @$_SESSION[$strTableName."_pdfwhere"];

$_SESSION[$strTableName."_pdfwhere"] = $strWhereClause;


$strOrderBy=$_SESSION[$strTableName."_order"];
if(!$strOrderBy)
	$strOrderBy=$gstrOrderBy;
$strSQL.=" ".trim($strOrderBy);

$strSQLbak = $strSQL;
if(function_exists("BeforeQueryPrint"))
	BeforeQueryPrint($strSQL,$strWhereClause,$strOrderBy);

//	Rebuild SQL if needed
if($strSQL!=$strSQLbak)
{
//	changed $strSQL - old style	
	$numrows=GetRowCount($strSQL);
}
else
{
	$strSQL = gSQLWhere($strWhereClause);
	$strSQL.=" ".trim($strOrderBy);
	$numrows=gSQLRowCount($strWhereClause,0);
}

LogInfo($strSQL);

$mypage=(integer)$_SESSION[$strTableName."_pagenumber"];
if(!$mypage)
	$mypage=1;

//	page size
$PageSize=(integer)$_SESSION[$strTableName."_pagesize"];
if(!$PageSize)
	$PageSize=$gPageSize;

$recno=1;
$records=0;	
$pageindex=1;

$maxpages=1;

if(!$all)
{	
	if($numrows)
	{
		$maxRecords = $numrows;
		$maxpages=ceil($maxRecords/$PageSize);
		if($mypage > $maxpages)
			$mypage = $maxpages;
		if($mypage<1) 
			$mypage=1;
		$maxrecs=$PageSize;
		$strSQL.=" limit ".(($mypage-1)*$PageSize).",".$PageSize;
	}
	$rs=db_query($strSQL,$conn);
	
	
	//	hide colunm headers if needed
	$recordsonpage=$numrows-($mypage-1)*$PageSize;
	if($recordsonpage>$PageSize)
		$recordsonpage=$PageSize;
		
	$xt->assign("page_number",true);
	$xt->assign("maxpages",$maxpages);
	$xt->assign("pageno",$mypage);
}
else
{
	$rs=db_query($strSQL,$conn);
	$recordsonpage = $numrows;
	$maxpages=ceil($recordsonpage/30);
	$xt->assign("page_number",true);
	$xt->assign("maxpages",$maxpages);
	
}

$colsonpage=1;
if($colsonpage>$recordsonpage)
	$colsonpage=$recordsonpage;
if($colsonpage<1)
	$colsonpage=1;


//	fill $rowinfo array
	$pages = array();
	$rowinfo = array();
	$rowinfo["data"]=array();

	while($data=db_fetch_array($rs))
	{
		if(function_exists("BeforeProcessRowPrint"))
		{
			if(!BeforeProcessRowPrint($data))
				continue;
		}
		break;
	}

	while($data && ($all || $recno<=$PageSize))
	{
		$row=array();
		$row["grid_record"]=array();
		$row["grid_record"]["data"]=array();
		for($col=1;$data && ($all || $recno<=$PageSize) && $col<=1;$col++)
		{
			$record=array();
			$recno++;
			$records++;
			$keylink="";
			$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["LID"]));


//	CID - 
			$value="";
				$value=DisplayLookupWizard("CID",$data["CID"],$data,$keylink,MODE_PRINT);
			$record["CID_value"]=$value;

//	TID - 
			$value="";
				$value=DisplayLookupWizard("TID",$data["TID"],$data,$keylink,MODE_PRINT);
			$record["TID_value"]=$value;

//	Name - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Name", ""),"field=Name".$keylink,"",MODE_PRINT);
			$record["Name_value"]=$value;

//	Continue - Checkbox
			$value="";
				$value = GetData($data,"Continue", "Checkbox");
			$record["Continue_value"]=$value;

//	Type - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Type", ""),"field=Type".$keylink,"",MODE_PRINT);
			$record["Type_value"]=$value;

//	Start - Short Date
			$value="";
				$value = ProcessLargeText(GetData($data,"Start", "Short Date"),"field=Start".$keylink,"",MODE_PRINT);
			$record["Start_value"]=$value;

//	End - Short Date
			$value="";
				$value = ProcessLargeText(GetData($data,"End", "Short Date"),"field=End".$keylink,"",MODE_PRINT);
			$record["End_value"]=$value;

//	Time - Time
			$value="";
				$value = ProcessLargeText(GetData($data,"Time", "Time"),"field=Time".$keylink,"",MODE_PRINT);
			$record["Time_value"]=$value;

//	Room - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Room", ""),"field=Room".$keylink,"",MODE_PRINT);
			$record["Room_value"]=$value;

//	Duration - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Duration", ""),"field=Duration".$keylink,"",MODE_PRINT);
			$record["Duration_value"]=$value;

//	About - HTML
			$value="";
				$value = GetData($data,"About", "HTML");
			$record["About_value"]=$value;

//	Announce - HTML
			$value="";
				$value = GetData($data,"Announce", "HTML");
			$record["Announce_value"]=$value;
			if($col<$colsonpage)
				$record["endrecord_block"]=true;
			$record["grid_recordheader"]=true;
			$record["grid_vrecord"]=true;
			$row["grid_record"]["data"][]=$record;
			
			if(function_exists("BeforeMoveNextPrint"))
				BeforeMoveNextPrint($data,$row,$col);
			while($data=db_fetch_array($rs))
			{
				if(function_exists("BeforeProcessRowPrint"))
				{
					if(!BeforeProcessRowPrint($data))
						continue;
				}
				break;
			}
		}
		if($col<=$colsonpage)
		{
			$row["grid_record"]["data"][count($row["grid_record"]["data"])-1]["endrecord_block"]=false;
		}
		$row["grid_rowspace"]=true;
		$row["grid_recordspace"] = array("data"=>array());
		for($i=0;$i<$colsonpage*2-1;$i++)
			$row["grid_recordspace"]["data"][]=true;
		
		$rowinfo["data"][]=$row;
		
		if($all && $records>=30)
		{
			$page=array("grid_row" =>$rowinfo);
			$page["pageno"]=$pageindex;
			$pageindex++;
			$pages[] = $page;
			$records=0;
			$rowinfo=array();
		}
		
	}
	if(count($rowinfo))
	{
		$page=array("grid_row" =>$rowinfo);
		if($all)
			$page["pageno"]=$pageindex;
		$pages[] = $page;
	}
	
	for($i=0;$i<count($pages);$i++)
	{
	 	if($i<count($pages)-1)
			$pages[$i]["begin"]="<div name=page class=printpage>";
		else
		    $pages[$i]["begin"]="<div name=page>";
			
		$pages[$i]["end"]="</div>";
	}

	$page=array("data"=>&$pages);
	$xt->assignbyref("page",$page);


	

$strSQL=$_SESSION[$strTableName."_sql"];

	
$body=array();
$xt->assignbyref("body",$body);
$xt->assign("grid_block",true);

$xt->assign("CID_fieldheadercolumn",true);
$xt->assign("CID_fieldheader",true);
$xt->assign("CID_fieldcolumn",true);
$xt->assign("CID_fieldfootercolumn",true);
$xt->assign("TID_fieldheadercolumn",true);
$xt->assign("TID_fieldheader",true);
$xt->assign("TID_fieldcolumn",true);
$xt->assign("TID_fieldfootercolumn",true);
$xt->assign("Name_fieldheadercolumn",true);
$xt->assign("Name_fieldheader",true);
$xt->assign("Name_fieldcolumn",true);
$xt->assign("Name_fieldfootercolumn",true);
$xt->assign("Continue_fieldheadercolumn",true);
$xt->assign("Continue_fieldheader",true);
$xt->assign("Continue_fieldcolumn",true);
$xt->assign("Continue_fieldfootercolumn",true);
$xt->assign("Type_fieldheadercolumn",true);
$xt->assign("Type_fieldheader",true);
$xt->assign("Type_fieldcolumn",true);
$xt->assign("Type_fieldfootercolumn",true);
$xt->assign("Start_fieldheadercolumn",true);
$xt->assign("Start_fieldheader",true);
$xt->assign("Start_fieldcolumn",true);
$xt->assign("Start_fieldfootercolumn",true);
$xt->assign("End_fieldheadercolumn",true);
$xt->assign("End_fieldheader",true);
$xt->assign("End_fieldcolumn",true);
$xt->assign("End_fieldfootercolumn",true);
$xt->assign("Time_fieldheadercolumn",true);
$xt->assign("Time_fieldheader",true);
$xt->assign("Time_fieldcolumn",true);
$xt->assign("Time_fieldfootercolumn",true);
$xt->assign("Room_fieldheadercolumn",true);
$xt->assign("Room_fieldheader",true);
$xt->assign("Room_fieldcolumn",true);
$xt->assign("Room_fieldfootercolumn",true);
$xt->assign("Duration_fieldheadercolumn",true);
$xt->assign("Duration_fieldheader",true);
$xt->assign("Duration_fieldcolumn",true);
$xt->assign("Duration_fieldfootercolumn",true);
$xt->assign("About_fieldheadercolumn",true);
$xt->assign("About_fieldheader",true);
$xt->assign("About_fieldcolumn",true);
$xt->assign("About_fieldfootercolumn",true);
$xt->assign("Announce_fieldheadercolumn",true);
$xt->assign("Announce_fieldheader",true);
$xt->assign("Announce_fieldcolumn",true);
$xt->assign("Announce_fieldfootercolumn",true);

	$record_header=array("data"=>array());
	for($i=0;$i<$colsonpage;$i++)
	{
		$rheader=array();
		if($i<$colsonpage-1)
		{
			$rheader["endrecordheader_block"]=true;
		}
		$record_header["data"][]=$rheader;
	}
	$xt->assignbyref("record_header",$record_header);
	$xt->assign("grid_header",true);
	$xt->assign("grid_footer",true);


$templatefile = "lectures_print.htm";
	
if(function_exists("BeforeShowPrint"))
	BeforeShowPrint($xt,$templatefile);

if(!postvalue("pdf"))
	$xt->display($templatefile);
else
{

	$xt->load_template($templatefile);
	$page = $xt->fetch_loaded();
	$pagewidth=postvalue("width")*1.05;
	$pageheight=postvalue("height")*1.05;
	$landscape=false;
	if(postvalue("all"))
	{
		if($pagewidth>$pageheight)
		{
			$landscape=true;
			if($pagewidth/$pageheight<297/210)
				$pagewidth = 297/210*$pageheight;
		}
		else
		{
			if($pagewidth/$pageheight<210/297)
				$pagewidth = 210/297*$pageheight;
		}
	}
	include("plugins/page2pdf.php");
}


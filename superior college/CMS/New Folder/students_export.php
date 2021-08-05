<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
session_cache_limiter("none");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/students_variables.php");


$conn=db_connect();
//	Before Process event
if(function_exists("BeforeProcessExport"))
	BeforeProcessExport($conn);

$strWhereClause="";

$options = "1";
if (@$_REQUEST["a"]!="") 
{
	$options = "";
	
	$sWhere = "1=0";	

//	process selection
	$selected_recs=array();
	if (@$_REQUEST["mdelete"])
	{
		foreach(@$_REQUEST["mdelete"] as $ind)
		{
			$keys=array();
			$keys["SID"]=refine($_REQUEST["mdelete1"][$ind-1]);
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
			$keys["SID"]=urldecode($arr[0]);
			$selected_recs[]=$keys;
		}
	}

	foreach($selected_recs as $keys)
	{
		$sWhere = $sWhere . " or ";
		$sWhere.=KeyWhere($keys);
	}


	$strSQL = gSQLWhere($sWhere);
	$strWhereClause=$sWhere;
	
	$_SESSION[$strTableName."_SelectedSQL"] = $strSQL;
	$_SESSION[$strTableName."_SelectedWhere"] = $sWhere;
}

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


$mypage=1;
if(@$_REQUEST["type"])
{
//	order by
	$strOrderBy=$_SESSION[$strTableName."_order"];
	if(!$strOrderBy)
		$strOrderBy=$gstrOrderBy;
	$strSQL.=" ".trim($strOrderBy);

	$strSQLbak = $strSQL;
	if(function_exists("BeforeQueryExport"))
		BeforeQueryExport($strSQL,$strWhereClause,$strOrderBy);
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

	if(!ini_get("safe_mode"))
		set_time_limit(300);
	
	if(@$_REQUEST["type"]=="excel")
		ExportToExcel();
	else if(@$_REQUEST["type"]=="word")
		ExportToWord();
	else if(@$_REQUEST["type"]=="xml")
		ExportToXML();
	else if(@$_REQUEST["type"]=="csv")
		ExportToCSV();
	else if(@$_REQUEST["type"]=="pdf")
		ExportToPDF();

	db_close($conn);
	return;
}

header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 

include('libs/xtempl.php');
$xt = new Xtempl();
if($options)
{
	$xt->assign("rangeheader_block",true);
	$xt->assign("range_block",true);
}
$body=array();
$body["begin"]="<form action=\"students_export.php\" method=get id=frmexport name=frmexport>";
$body["end"]="</form>";
$xt->assignbyref("body",$body);
$xt->display("students_export.htm");


function ExportToExcel()
{
	global $cCharset;
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;Filename=students.xls");

	echo "<html>";
	echo "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns=\"http://www.w3.org/TR/REC-html40\">";
	
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$cCharset."\">";
	echo "<body>";
	echo "<table border=1>";

	WriteTableData();

	echo "</table>";
	echo "</body>";
	echo "</html>";
}

function ExportToWord()
{
	global $cCharset;
	header("Content-type: application/vnd.ms-word");
	header("Content-Disposition: attachment;Filename=students.doc");

	echo "<html>";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$cCharset."\">";
	echo "<body>";
	echo "<table border=1>";

	WriteTableData();

	echo "</table>";
	echo "</body>";
	echo "</html>";
}

function ExportToXML()
{
	global $nPageSize,$rs,$strTableName,$conn;
	header("Content-type: text/xml");
	header("Content-Disposition: attachment;Filename=students.xml");
	if(!($row=db_fetch_array($rs)))
		return;
	global $cCharset;
	echo "<?xml version=\"1.0\" encoding=\"".$cCharset."\" standalone=\"yes\"?>\r\n";
	echo "<table>\r\n";
	$i=0;
	while((!$nPageSize || $i<$nPageSize) && $row)
	{
		echo "<row>\r\n";
		$field=htmlspecialchars(XMLNameEncode("CID"));
		echo "<".$field.">";
/*		
		if(strlen($row["CID"]))
		{
			$strdata = make_db_value("CID",$row["CID"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`CID`";
			$LookupSQL.=" FROM `classes` WHERE `CID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["CID"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			echo htmlspecialchars(GetDataInt($lookupvalue,$row,"CID", ""));
		}
*/		
		echo htmlspecialchars(DisplayLookupWizard("CID",$row["CID"],$row,"",MODE_EXPORT));
		
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("RNUM"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"RNUM",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Full_Name"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Full_Name",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Status"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Status",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("L1"));
		echo "<".$field.">";
/*		
		if(strlen($row["L1"]))
		{
			$strdata = make_db_value("L1",$row["L1"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["L1"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			echo htmlspecialchars(GetDataInt($lookupvalue,$row,"L1", ""));
		}
*/		
		echo htmlspecialchars(DisplayLookupWizard("L1",$row["L1"],$row,"",MODE_EXPORT));
		
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("L2"));
		echo "<".$field.">";
/*		
		if(strlen($row["L2"]))
		{
			$strdata = make_db_value("L2",$row["L2"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["L2"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			echo htmlspecialchars(GetDataInt($lookupvalue,$row,"L2", ""));
		}
*/		
		echo htmlspecialchars(DisplayLookupWizard("L2",$row["L2"],$row,"",MODE_EXPORT));
		
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("L3"));
		echo "<".$field.">";
/*		
		if(strlen($row["L3"]))
		{
			$strdata = make_db_value("L3",$row["L3"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["L3"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			echo htmlspecialchars(GetDataInt($lookupvalue,$row,"L3", ""));
		}
*/		
		echo htmlspecialchars(DisplayLookupWizard("L3",$row["L3"],$row,"",MODE_EXPORT));
		
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("L4"));
		echo "<".$field.">";
/*		
		if(strlen($row["L4"]))
		{
			$strdata = make_db_value("L4",$row["L4"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["L4"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			echo htmlspecialchars(GetDataInt($lookupvalue,$row,"L4", ""));
		}
*/		
		echo htmlspecialchars(DisplayLookupWizard("L4",$row["L4"],$row,"",MODE_EXPORT));
		
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("L5"));
		echo "<".$field.">";
/*		
		if(strlen($row["L5"]))
		{
			$strdata = make_db_value("L5",$row["L5"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["L5"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			echo htmlspecialchars(GetDataInt($lookupvalue,$row,"L5", ""));
		}
*/		
		echo htmlspecialchars(DisplayLookupWizard("L5",$row["L5"],$row,"",MODE_EXPORT));
		
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("L6"));
		echo "<".$field.">";
/*		
		if(strlen($row["L6"]))
		{
			$strdata = make_db_value("L6",$row["L6"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["L6"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			echo htmlspecialchars(GetDataInt($lookupvalue,$row,"L6", ""));
		}
*/		
		echo htmlspecialchars(DisplayLookupWizard("L6",$row["L6"],$row,"",MODE_EXPORT));
		
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("L7"));
		echo "<".$field.">";
/*		
		if(strlen($row["L7"]))
		{
			$strdata = make_db_value("L7",$row["L7"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["L7"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			echo htmlspecialchars(GetDataInt($lookupvalue,$row,"L7", ""));
		}
*/		
		echo htmlspecialchars(DisplayLookupWizard("L7",$row["L7"],$row,"",MODE_EXPORT));
		
		echo "</".$field.">\r\n";
		echo "</row>\r\n";
		$i++;
		$row=db_fetch_array($rs);
	}
	echo "</table>\r\n";
}

function ExportToCSV()
{
	global $rs,$nPageSize,$strTableName,$conn;
	header("Content-type: application/csv");
	header("Content-Disposition: attachment;Filename=students.csv");

	if(!($row=db_fetch_array($rs)))
		return;

	$totals=array();

	
// write header
	$outstr="";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"CID\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"RNUM\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Full_Name\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Status\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"L1\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"L2\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"L3\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"L4\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"L5\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"L6\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"L7\"";
	echo $outstr;
	echo "\r\n";

// write data rows
	$iNumberOfRows = 0;
	while((!$nPageSize || $iNumberOfRows<$nPageSize) && $row)
	{
		$outstr="";
		if($outstr!="")
			$outstr.=",";
/*
		if(strlen($row["CID"]))
		{
			$strdata = make_db_value("CID",$row["CID"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`CID`";
			$LookupSQL.=" FROM `classes` WHERE `CID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);

			$lookupvalue=$row["CID"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$outstr.='"'.htmlspecialchars(GetDataInt($lookupvalue,$row,"CID", "")).'"';
		}
*/		
		$value = DisplayLookupWizard("CID",$row["CID"],$row,"",MODE_EXPORT);
		if(strlen($value))
			$outstr.='"'.htmlspecialchars($value).'"';

		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"RNUM",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Full_Name",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Status",$format)).'"';
		if($outstr!="")
			$outstr.=",";
/*
		if(strlen($row["L1"]))
		{
			$strdata = make_db_value("L1",$row["L1"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);

			$lookupvalue=$row["L1"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$outstr.='"'.htmlspecialchars(GetDataInt($lookupvalue,$row,"L1", "")).'"';
		}
*/		
		$value = DisplayLookupWizard("L1",$row["L1"],$row,"",MODE_EXPORT);
		if(strlen($value))
			$outstr.='"'.htmlspecialchars($value).'"';

		if($outstr!="")
			$outstr.=",";
/*
		if(strlen($row["L2"]))
		{
			$strdata = make_db_value("L2",$row["L2"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);

			$lookupvalue=$row["L2"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$outstr.='"'.htmlspecialchars(GetDataInt($lookupvalue,$row,"L2", "")).'"';
		}
*/		
		$value = DisplayLookupWizard("L2",$row["L2"],$row,"",MODE_EXPORT);
		if(strlen($value))
			$outstr.='"'.htmlspecialchars($value).'"';

		if($outstr!="")
			$outstr.=",";
/*
		if(strlen($row["L3"]))
		{
			$strdata = make_db_value("L3",$row["L3"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);

			$lookupvalue=$row["L3"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$outstr.='"'.htmlspecialchars(GetDataInt($lookupvalue,$row,"L3", "")).'"';
		}
*/		
		$value = DisplayLookupWizard("L3",$row["L3"],$row,"",MODE_EXPORT);
		if(strlen($value))
			$outstr.='"'.htmlspecialchars($value).'"';

		if($outstr!="")
			$outstr.=",";
/*
		if(strlen($row["L4"]))
		{
			$strdata = make_db_value("L4",$row["L4"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);

			$lookupvalue=$row["L4"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$outstr.='"'.htmlspecialchars(GetDataInt($lookupvalue,$row,"L4", "")).'"';
		}
*/		
		$value = DisplayLookupWizard("L4",$row["L4"],$row,"",MODE_EXPORT);
		if(strlen($value))
			$outstr.='"'.htmlspecialchars($value).'"';

		if($outstr!="")
			$outstr.=",";
/*
		if(strlen($row["L5"]))
		{
			$strdata = make_db_value("L5",$row["L5"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);

			$lookupvalue=$row["L5"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$outstr.='"'.htmlspecialchars(GetDataInt($lookupvalue,$row,"L5", "")).'"';
		}
*/		
		$value = DisplayLookupWizard("L5",$row["L5"],$row,"",MODE_EXPORT);
		if(strlen($value))
			$outstr.='"'.htmlspecialchars($value).'"';

		if($outstr!="")
			$outstr.=",";
/*
		if(strlen($row["L6"]))
		{
			$strdata = make_db_value("L6",$row["L6"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);

			$lookupvalue=$row["L6"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$outstr.='"'.htmlspecialchars(GetDataInt($lookupvalue,$row,"L6", "")).'"';
		}
*/		
		$value = DisplayLookupWizard("L6",$row["L6"],$row,"",MODE_EXPORT);
		if(strlen($value))
			$outstr.='"'.htmlspecialchars($value).'"';

		if($outstr!="")
			$outstr.=",";
/*
		if(strlen($row["L7"]))
		{
			$strdata = make_db_value("L7",$row["L7"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);

			$lookupvalue=$row["L7"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$outstr.='"'.htmlspecialchars(GetDataInt($lookupvalue,$row,"L7", "")).'"';
		}
*/		
		$value = DisplayLookupWizard("L7",$row["L7"],$row,"",MODE_EXPORT);
		if(strlen($value))
			$outstr.='"'.htmlspecialchars($value).'"';

		echo $outstr;
		echo "\r\n";
		$iNumberOfRows++;
		$row=db_fetch_array($rs);
	}

//	display totals
	$first=true;

}


function WriteTableData()
{
	global $rs,$nPageSize,$strTableName,$conn;
	if(!($row=db_fetch_array($rs)))
		return;
// write header
	echo "<tr>";
	if($_REQUEST["type"]=="excel")
	{
		echo '<td style="width: 100" x:str>'.PrepareForExcel("CID").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("RNUM").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Full_Name").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Status").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("L1").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("L2").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("L3").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("L4").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("L5").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("L6").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("L7").'</td>';
	}
	else
	{
		echo "<td>CID</td>";
		echo "<td>RNUM</td>";
		echo "<td>Full_Name</td>";
		echo "<td>Status</td>";
		echo "<td>L1</td>";
		echo "<td>L2</td>";
		echo "<td>L3</td>";
		echo "<td>L4</td>";
		echo "<td>L5</td>";
		echo "<td>L6</td>";
		echo "<td>L7</td>";
	}
	echo "</tr>";

	$totals=array();
// write data rows
	$iNumberOfRows = 0;
	while((!$nPageSize || $iNumberOfRows<$nPageSize) && $row)
	{
		echo "<tr>";
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';
		if(strlen($row["CID"]))
		{
/*
			$strdata = make_db_value("CID",$row["CID"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`CID`";
			$LookupSQL.=" FROM `classes` WHERE `CID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["CID"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
				
			$strValue=GetDataInt($lookupvalue,$row,"CID", "");
*/			
			$strValue = DisplayLookupWizard("CID",$row["CID"],$row,"",MODE_EXPORT);
						if($_REQUEST["type"]=="excel")
				echo PrepareForExcel($strValue);
			else
				echo htmlspecialchars($strValue);

		}
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"RNUM",$format));
		else
			echo htmlspecialchars(GetData($row,"RNUM",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"Full_Name",$format));
		else
			echo htmlspecialchars(GetData($row,"Full_Name",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"Status",$format));
		else
			echo htmlspecialchars(GetData($row,"Status",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';
		if(strlen($row["L1"]))
		{
/*
			$strdata = make_db_value("L1",$row["L1"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["L1"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
				
			$strValue=GetDataInt($lookupvalue,$row,"L1", "");
*/			
			$strValue = DisplayLookupWizard("L1",$row["L1"],$row,"",MODE_EXPORT);
						if($_REQUEST["type"]=="excel")
				echo PrepareForExcel($strValue);
			else
				echo htmlspecialchars($strValue);

		}
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';
		if(strlen($row["L2"]))
		{
/*
			$strdata = make_db_value("L2",$row["L2"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["L2"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
				
			$strValue=GetDataInt($lookupvalue,$row,"L2", "");
*/			
			$strValue = DisplayLookupWizard("L2",$row["L2"],$row,"",MODE_EXPORT);
						if($_REQUEST["type"]=="excel")
				echo PrepareForExcel($strValue);
			else
				echo htmlspecialchars($strValue);

		}
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';
		if(strlen($row["L3"]))
		{
/*
			$strdata = make_db_value("L3",$row["L3"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["L3"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
				
			$strValue=GetDataInt($lookupvalue,$row,"L3", "");
*/			
			$strValue = DisplayLookupWizard("L3",$row["L3"],$row,"",MODE_EXPORT);
						if($_REQUEST["type"]=="excel")
				echo PrepareForExcel($strValue);
			else
				echo htmlspecialchars($strValue);

		}
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';
		if(strlen($row["L4"]))
		{
/*
			$strdata = make_db_value("L4",$row["L4"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["L4"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
				
			$strValue=GetDataInt($lookupvalue,$row,"L4", "");
*/			
			$strValue = DisplayLookupWizard("L4",$row["L4"],$row,"",MODE_EXPORT);
						if($_REQUEST["type"]=="excel")
				echo PrepareForExcel($strValue);
			else
				echo htmlspecialchars($strValue);

		}
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';
		if(strlen($row["L5"]))
		{
/*
			$strdata = make_db_value("L5",$row["L5"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["L5"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
				
			$strValue=GetDataInt($lookupvalue,$row,"L5", "");
*/			
			$strValue = DisplayLookupWizard("L5",$row["L5"],$row,"",MODE_EXPORT);
						if($_REQUEST["type"]=="excel")
				echo PrepareForExcel($strValue);
			else
				echo htmlspecialchars($strValue);

		}
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';
		if(strlen($row["L6"]))
		{
/*
			$strdata = make_db_value("L6",$row["L6"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["L6"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
				
			$strValue=GetDataInt($lookupvalue,$row,"L6", "");
*/			
			$strValue = DisplayLookupWizard("L6",$row["L6"],$row,"",MODE_EXPORT);
						if($_REQUEST["type"]=="excel")
				echo PrepareForExcel($strValue);
			else
				echo htmlspecialchars($strValue);

		}
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';
		if(strlen($row["L7"]))
		{
/*
			$strdata = make_db_value("L7",$row["L7"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["L7"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
				
			$strValue=GetDataInt($lookupvalue,$row,"L7", "");
*/			
			$strValue = DisplayLookupWizard("L7",$row["L7"],$row,"",MODE_EXPORT);
						if($_REQUEST["type"]=="excel")
				echo PrepareForExcel($strValue);
			else
				echo htmlspecialchars($strValue);

		}
	echo '</td>';
		echo "</tr>";
		$iNumberOfRows++;
		$row=db_fetch_array($rs);
	}

}

function XMLNameEncode($strValue)
{	
	$search=array(" ","#","'","/","\\","(",")",",","[","]","+","\"","-","_","|","}","{","=");
	return str_replace($search,"",$strValue);
}

function PrepareForExcel($str)
{
	$ret = htmlspecialchars($str);
	if (substr($ret,0,1)== "=") 
		$ret = "&#61;".substr($ret,1);
	return $ret;

}




function ExportToPDF()
{
	global $nPageSize,$rs,$strTableName,$conn;
		global $colwidth,$leftmargin;
	if(!($row=db_fetch_array($rs)))
		return;



	class PDF extends FPDF
	{
	//Current column
		var $col=0;
	//Ordinate of column start
		var $y0;
		var $maxheight;

	function AcceptPageBreak()
	{
		global $colwidth,$leftmargin;
		if($this->y0+$this->rowheight>$this->PageBreakTrigger)
			return true;
		$x=$leftmargin;
		if($this->maxheight<$this->PageBreakTrigger-$this->y0)
			$this->maxheight=$this->PageBreakTrigger-$this->y0;
		$this->Rect($x,$this->y0,$colwidth["CID"],$this->maxheight);
		$x+=$colwidth["CID"];
		$this->Rect($x,$this->y0,$colwidth["RNUM"],$this->maxheight);
		$x+=$colwidth["RNUM"];
		$this->Rect($x,$this->y0,$colwidth["Full_Name"],$this->maxheight);
		$x+=$colwidth["Full_Name"];
		$this->Rect($x,$this->y0,$colwidth["Status"],$this->maxheight);
		$x+=$colwidth["Status"];
		$this->Rect($x,$this->y0,$colwidth["L1"],$this->maxheight);
		$x+=$colwidth["L1"];
		$this->Rect($x,$this->y0,$colwidth["L2"],$this->maxheight);
		$x+=$colwidth["L2"];
		$this->Rect($x,$this->y0,$colwidth["L3"],$this->maxheight);
		$x+=$colwidth["L3"];
		$this->Rect($x,$this->y0,$colwidth["L4"],$this->maxheight);
		$x+=$colwidth["L4"];
		$this->Rect($x,$this->y0,$colwidth["L5"],$this->maxheight);
		$x+=$colwidth["L5"];
		$this->Rect($x,$this->y0,$colwidth["L6"],$this->maxheight);
		$x+=$colwidth["L6"];
		$this->Rect($x,$this->y0,$colwidth["L7"],$this->maxheight);
		$x+=$colwidth["L7"];
		$this->maxheight = $this->rowheight;
//	draw frame	
		return true;
	}

	function Header()
	{
		global $colwidth,$leftmargin;
	    //Page header
		$this->SetFillColor(192);
		$this->SetX($leftmargin);
//		$this->Cell($colwidth["CID"],$this->rowheight,"Class ID",1,0,'C',1);
		$this->Cell($colwidth["CID"],$this->rowheight,"Class ID",1,0,'C',1);
//		$this->Cell($colwidth["RNUM"],$this->rowheight,"Roll Number",1,0,'C',1);
		$this->Cell($colwidth["RNUM"],$this->rowheight,"Roll Number",1,0,'C',1);
//		$this->Cell($colwidth["Full_Name"],$this->rowheight,"Full Name",1,0,'C',1);
		$this->Cell($colwidth["Full_Name"],$this->rowheight,"Full Name",1,0,'C',1);
//		$this->Cell($colwidth["Status"],$this->rowheight,"Status",1,0,'C',1);
		$this->Cell($colwidth["Status"],$this->rowheight,"Status",1,0,'C',1);
//		$this->Cell($colwidth["L1"],$this->rowheight,"Lecture 1",1,0,'C',1);
		$this->Cell($colwidth["L1"],$this->rowheight,"Lecture 1",1,0,'C',1);
//		$this->Cell($colwidth["L2"],$this->rowheight,"Lecture 2",1,0,'C',1);
		$this->Cell($colwidth["L2"],$this->rowheight,"Lecture 2",1,0,'C',1);
//		$this->Cell($colwidth["L3"],$this->rowheight,"Lecture 3",1,0,'C',1);
		$this->Cell($colwidth["L3"],$this->rowheight,"Lecture 3",1,0,'C',1);
//		$this->Cell($colwidth["L4"],$this->rowheight,"Lecture 4",1,0,'C',1);
		$this->Cell($colwidth["L4"],$this->rowheight,"Lecture 4",1,0,'C',1);
//		$this->Cell($colwidth["L5"],$this->rowheight,"Lecture 5",1,0,'C',1);
		$this->Cell($colwidth["L5"],$this->rowheight,"Lecture 5",1,0,'C',1);
//		$this->Cell($colwidth["L6"],$this->rowheight,"Lecture 6",1,0,'C',1);
		$this->Cell($colwidth["L6"],$this->rowheight,"Lecture 6",1,0,'C',1);
//		$this->Cell($colwidth["L7"],$this->rowheight,"Lecture 7",1,0,'C',1);
		$this->Cell($colwidth["L7"],$this->rowheight,"Lecture 7",1,0,'C',1);
		$this->Ln($this->rowheight);
		$this->y0=$this->GetY();
	}

	}

	$pdf=new PDF();

	$leftmargin=5;
	$pagewidth=200;
	$pageheight=290;
	$rowheight=5;


	$defwidth=$pagewidth/11;
	$colwidth=array();
    $colwidth["CID"]=$defwidth;
    $colwidth["RNUM"]=$defwidth;
    $colwidth["Full_Name"]=$defwidth;
    $colwidth["Status"]=$defwidth;
    $colwidth["L1"]=$defwidth;
    $colwidth["L2"]=$defwidth;
    $colwidth["L3"]=$defwidth;
    $colwidth["L4"]=$defwidth;
    $colwidth["L5"]=$defwidth;
    $colwidth["L6"]=$defwidth;
    $colwidth["L7"]=$defwidth;
	
	$pdf->AddFont('CourierNewPSMT','','courcp1252.php');
	$pdf->rowheight=$rowheight;
	
	$pdf->SetFont('CourierNewPSMT','',8);
	$pdf->AddPage();
	

	$i=0;
	while((!$nPageSize || $i<$nPageSize) && $row)
	{
		$pdf->maxheight=$rowheight;
		$x=$leftmargin;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		if(strlen($row["CID"]))
		{
/*			$strdata = make_db_value("CID",$row["CID"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`CID`";
			$LookupSQL.=" FROM `classes` WHERE `CID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["CID"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$pdf->Cell($colwidth["CID"],$rowheight,GetDataInt($lookupvalue,$row,"CID", ""));
*/				
				
			$value = DisplayLookupWizard("CID",$row["CID"],$row,"",MODE_EXPORT);
			$pdf->Cell($colwidth["CID"],$rowheight,$value);
		}
		$x+=$colwidth["CID"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["RNUM"],$rowheight,GetData($row,"RNUM",""));
		$x+=$colwidth["RNUM"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Full_Name"],$rowheight,GetData($row,"Full_Name",""));
		$x+=$colwidth["Full_Name"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Status"],$rowheight,GetData($row,"Status",""));
		$x+=$colwidth["Status"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		if(strlen($row["L1"]))
		{
/*			$strdata = make_db_value("L1",$row["L1"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["L1"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$pdf->Cell($colwidth["L1"],$rowheight,GetDataInt($lookupvalue,$row,"L1", ""));
*/				
				
			$value = DisplayLookupWizard("L1",$row["L1"],$row,"",MODE_EXPORT);
			$pdf->Cell($colwidth["L1"],$rowheight,$value);
		}
		$x+=$colwidth["L1"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		if(strlen($row["L2"]))
		{
/*			$strdata = make_db_value("L2",$row["L2"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["L2"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$pdf->Cell($colwidth["L2"],$rowheight,GetDataInt($lookupvalue,$row,"L2", ""));
*/				
				
			$value = DisplayLookupWizard("L2",$row["L2"],$row,"",MODE_EXPORT);
			$pdf->Cell($colwidth["L2"],$rowheight,$value);
		}
		$x+=$colwidth["L2"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		if(strlen($row["L3"]))
		{
/*			$strdata = make_db_value("L3",$row["L3"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["L3"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$pdf->Cell($colwidth["L3"],$rowheight,GetDataInt($lookupvalue,$row,"L3", ""));
*/				
				
			$value = DisplayLookupWizard("L3",$row["L3"],$row,"",MODE_EXPORT);
			$pdf->Cell($colwidth["L3"],$rowheight,$value);
		}
		$x+=$colwidth["L3"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		if(strlen($row["L4"]))
		{
/*			$strdata = make_db_value("L4",$row["L4"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["L4"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$pdf->Cell($colwidth["L4"],$rowheight,GetDataInt($lookupvalue,$row,"L4", ""));
*/				
				
			$value = DisplayLookupWizard("L4",$row["L4"],$row,"",MODE_EXPORT);
			$pdf->Cell($colwidth["L4"],$rowheight,$value);
		}
		$x+=$colwidth["L4"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		if(strlen($row["L5"]))
		{
/*			$strdata = make_db_value("L5",$row["L5"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["L5"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$pdf->Cell($colwidth["L5"],$rowheight,GetDataInt($lookupvalue,$row,"L5", ""));
*/				
				
			$value = DisplayLookupWizard("L5",$row["L5"],$row,"",MODE_EXPORT);
			$pdf->Cell($colwidth["L5"],$rowheight,$value);
		}
		$x+=$colwidth["L5"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		if(strlen($row["L6"]))
		{
/*			$strdata = make_db_value("L6",$row["L6"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["L6"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$pdf->Cell($colwidth["L6"],$rowheight,GetDataInt($lookupvalue,$row,"L6", ""));
*/				
				
			$value = DisplayLookupWizard("L6",$row["L6"],$row,"",MODE_EXPORT);
			$pdf->Cell($colwidth["L6"],$rowheight,$value);
		}
		$x+=$colwidth["L6"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		if(strlen($row["L7"]))
		{
/*			$strdata = make_db_value("L7",$row["L7"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["L7"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$pdf->Cell($colwidth["L7"],$rowheight,GetDataInt($lookupvalue,$row,"L7", ""));
*/				
				
			$value = DisplayLookupWizard("L7",$row["L7"],$row,"",MODE_EXPORT);
			$pdf->Cell($colwidth["L7"],$rowheight,$value);
		}
		$x+=$colwidth["L7"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
//	draw fames
		$x=$leftmargin;
		$pdf->Rect($x,$pdf->y0,$colwidth["CID"],$pdf->maxheight);
		$x+=$colwidth["CID"];
		$pdf->Rect($x,$pdf->y0,$colwidth["RNUM"],$pdf->maxheight);
		$x+=$colwidth["RNUM"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Full_Name"],$pdf->maxheight);
		$x+=$colwidth["Full_Name"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Status"],$pdf->maxheight);
		$x+=$colwidth["Status"];
		$pdf->Rect($x,$pdf->y0,$colwidth["L1"],$pdf->maxheight);
		$x+=$colwidth["L1"];
		$pdf->Rect($x,$pdf->y0,$colwidth["L2"],$pdf->maxheight);
		$x+=$colwidth["L2"];
		$pdf->Rect($x,$pdf->y0,$colwidth["L3"],$pdf->maxheight);
		$x+=$colwidth["L3"];
		$pdf->Rect($x,$pdf->y0,$colwidth["L4"],$pdf->maxheight);
		$x+=$colwidth["L4"];
		$pdf->Rect($x,$pdf->y0,$colwidth["L5"],$pdf->maxheight);
		$x+=$colwidth["L5"];
		$pdf->Rect($x,$pdf->y0,$colwidth["L6"],$pdf->maxheight);
		$x+=$colwidth["L6"];
		$pdf->Rect($x,$pdf->y0,$colwidth["L7"],$pdf->maxheight);
		$x+=$colwidth["L7"];
		$pdf->y0+=$pdf->maxheight;
		$i++;
		$row=db_fetch_array($rs);
	}
	$pdf->Output();
}

?>
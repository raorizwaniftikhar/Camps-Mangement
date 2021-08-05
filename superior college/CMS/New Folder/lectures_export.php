<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
session_cache_limiter("none");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/lectures_variables.php");


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
$body["begin"]="<form action=\"lectures_export.php\" method=get id=frmexport name=frmexport>";
$body["end"]="</form>";
$xt->assignbyref("body",$body);
$xt->display("lectures_export.htm");


function ExportToExcel()
{
	global $cCharset;
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;Filename=lectures.xls");

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
	header("Content-Disposition: attachment;Filename=lectures.doc");

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
	header("Content-Disposition: attachment;Filename=lectures.xml");
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
		$field=htmlspecialchars(XMLNameEncode("TID"));
		echo "<".$field.">";
/*		
		if(strlen($row["TID"]))
		{
			$strdata = make_db_value("TID",$row["TID"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Full_Name`";
			$LookupSQL.=" FROM `teachers` WHERE `TID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["TID"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			echo htmlspecialchars(GetDataInt($lookupvalue,$row,"TID", ""));
		}
*/		
		echo htmlspecialchars(DisplayLookupWizard("TID",$row["TID"],$row,"",MODE_EXPORT));
		
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Name"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Name",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Continue"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Continue",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Type"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Type",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Start"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Start",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("End"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"End",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Time"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Time",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Room"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Room",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Duration"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Duration",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("About"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"About",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Announce"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Announce",""));
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
	header("Content-Disposition: attachment;Filename=lectures.csv");

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
	$outstr.= "\"TID\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Name\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Continue\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Type\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Start\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"End\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Time\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Room\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Duration\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"About\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Announce\"";
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
/*
		if(strlen($row["TID"]))
		{
			$strdata = make_db_value("TID",$row["TID"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Full_Name`";
			$LookupSQL.=" FROM `teachers` WHERE `TID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);

			$lookupvalue=$row["TID"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$outstr.='"'.htmlspecialchars(GetDataInt($lookupvalue,$row,"TID", "")).'"';
		}
*/		
		$value = DisplayLookupWizard("TID",$row["TID"],$row,"",MODE_EXPORT);
		if(strlen($value))
			$outstr.='"'.htmlspecialchars($value).'"';

		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Name",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format=FORMAT_NONE;
		$outstr.='"'.htmlspecialchars(GetData($row,"Continue",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Type",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="Short Date";
		$outstr.='"'.htmlspecialchars(GetData($row,"Start",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="Short Date";
		$outstr.='"'.htmlspecialchars(GetData($row,"End",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="Time";
		$outstr.='"'.htmlspecialchars(GetData($row,"Time",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Room",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Duration",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="HTML";
		$outstr.='"'.htmlspecialchars(GetData($row,"About",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="HTML";
		$outstr.='"'.htmlspecialchars(GetData($row,"Announce",$format)).'"';
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
		echo '<td style="width: 100" x:str>'.PrepareForExcel("TID").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Name").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Continue").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Type").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Start").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("End").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Time").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Room").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Duration").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("About").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Announce").'</td>';
	}
	else
	{
		echo "<td>CID</td>";
		echo "<td>TID</td>";
		echo "<td>Name</td>";
		echo "<td>Continue</td>";
		echo "<td>Type</td>";
		echo "<td>Start</td>";
		echo "<td>End</td>";
		echo "<td>Time</td>";
		echo "<td>Room</td>";
		echo "<td>Duration</td>";
		echo "<td>About</td>";
		echo "<td>Announce</td>";
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
	echo '<td>';
		if(strlen($row["TID"]))
		{
/*
			$strdata = make_db_value("TID",$row["TID"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Full_Name`";
			$LookupSQL.=" FROM `teachers` WHERE `TID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["TID"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
				
			$strValue=GetDataInt($lookupvalue,$row,"TID", "");
*/			
			$strValue = DisplayLookupWizard("TID",$row["TID"],$row,"",MODE_EXPORT);
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
			echo PrepareForExcel(GetData($row,"Name",$format));
		else
			echo htmlspecialchars(GetData($row,"Name",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format=FORMAT_NONE;
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"Continue",$format));
		else
			echo htmlspecialchars(GetData($row,"Continue",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"Type",$format));
		else
			echo htmlspecialchars(GetData($row,"Type",$format));
	echo '</td>';
	echo '<td>';

		$format="Short Date";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"Start",$format));
		else
			echo htmlspecialchars(GetData($row,"Start",$format));
	echo '</td>';
	echo '<td>';

		$format="Short Date";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"End",$format));
		else
			echo htmlspecialchars(GetData($row,"End",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="Time";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"Time",$format));
		else
			echo htmlspecialchars(GetData($row,"Time",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"Room",$format));
		else
			echo htmlspecialchars(GetData($row,"Room",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"Duration",$format));
		else
			echo htmlspecialchars(GetData($row,"Duration",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="HTML";
			echo GetData($row,"About",$format);
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="HTML";
			echo GetData($row,"Announce",$format);
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
		$this->Rect($x,$this->y0,$colwidth["TID"],$this->maxheight);
		$x+=$colwidth["TID"];
		$this->Rect($x,$this->y0,$colwidth["Name"],$this->maxheight);
		$x+=$colwidth["Name"];
		$this->Rect($x,$this->y0,$colwidth["Continue"],$this->maxheight);
		$x+=$colwidth["Continue"];
		$this->Rect($x,$this->y0,$colwidth["Type"],$this->maxheight);
		$x+=$colwidth["Type"];
		$this->Rect($x,$this->y0,$colwidth["Start"],$this->maxheight);
		$x+=$colwidth["Start"];
		$this->Rect($x,$this->y0,$colwidth["End"],$this->maxheight);
		$x+=$colwidth["End"];
		$this->Rect($x,$this->y0,$colwidth["Time"],$this->maxheight);
		$x+=$colwidth["Time"];
		$this->Rect($x,$this->y0,$colwidth["Room"],$this->maxheight);
		$x+=$colwidth["Room"];
		$this->Rect($x,$this->y0,$colwidth["Duration"],$this->maxheight);
		$x+=$colwidth["Duration"];
		$this->Rect($x,$this->y0,$colwidth["About"],$this->maxheight);
		$x+=$colwidth["About"];
		$this->Rect($x,$this->y0,$colwidth["Announce"],$this->maxheight);
		$x+=$colwidth["Announce"];
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
//		$this->Cell($colwidth["TID"],$this->rowheight,"Teacher Name",1,0,'C',1);
		$this->Cell($colwidth["TID"],$this->rowheight,"Teacher Name",1,0,'C',1);
//		$this->Cell($colwidth["Name"],$this->rowheight,"Lecure Name",1,0,'C',1);
		$this->Cell($colwidth["Name"],$this->rowheight,"Lecure Name",1,0,'C',1);
//		$this->Cell($colwidth["Continue"],$this->rowheight,"On Going",1,0,'C',1);
		$this->Cell($colwidth["Continue"],$this->rowheight,"On Going",1,0,'C',1);
//		$this->Cell($colwidth["Type"],$this->rowheight,"Type",1,0,'C',1);
		$this->Cell($colwidth["Type"],$this->rowheight,"Type",1,0,'C',1);
//		$this->Cell($colwidth["Start"],$this->rowheight,"Start Date",1,0,'C',1);
		$this->Cell($colwidth["Start"],$this->rowheight,"Start Date",1,0,'C',1);
//		$this->Cell($colwidth["End"],$this->rowheight,"End Date",1,0,'C',1);
		$this->Cell($colwidth["End"],$this->rowheight,"End Date",1,0,'C',1);
//		$this->Cell($colwidth["Time"],$this->rowheight,"Time",1,0,'C',1);
		$this->Cell($colwidth["Time"],$this->rowheight,"Time",1,0,'C',1);
//		$this->Cell($colwidth["Room"],$this->rowheight,"Room",1,0,'C',1);
		$this->Cell($colwidth["Room"],$this->rowheight,"Room",1,0,'C',1);
//		$this->Cell($colwidth["Duration"],$this->rowheight,"Duration",1,0,'C',1);
		$this->Cell($colwidth["Duration"],$this->rowheight,"Duration",1,0,'C',1);
//		$this->Cell($colwidth["About"],$this->rowheight,"About",1,0,'C',1);
		$this->Cell($colwidth["About"],$this->rowheight,"About",1,0,'C',1);
//		$this->Cell($colwidth["Announce"],$this->rowheight,"Announce",1,0,'C',1);
		$this->Cell($colwidth["Announce"],$this->rowheight,"Announce",1,0,'C',1);
		$this->Ln($this->rowheight);
		$this->y0=$this->GetY();
	}

	}

	$pdf=new PDF();

	$leftmargin=5;
	$pagewidth=200;
	$pageheight=290;
	$rowheight=5;


	$defwidth=$pagewidth/12;
	$colwidth=array();
    $colwidth["CID"]=$defwidth;
    $colwidth["TID"]=$defwidth;
    $colwidth["Name"]=$defwidth;
    $colwidth["Continue"]=$defwidth;
    $colwidth["Type"]=$defwidth;
    $colwidth["Start"]=$defwidth;
    $colwidth["End"]=$defwidth;
    $colwidth["Time"]=$defwidth;
    $colwidth["Room"]=$defwidth;
    $colwidth["Duration"]=$defwidth;
    $colwidth["About"]=$defwidth;
    $colwidth["Announce"]=$defwidth;
	
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
		if(strlen($row["TID"]))
		{
/*			$strdata = make_db_value("TID",$row["TID"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Full_Name`";
			$LookupSQL.=" FROM `teachers` WHERE `TID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["TID"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$pdf->Cell($colwidth["TID"],$rowheight,GetDataInt($lookupvalue,$row,"TID", ""));
*/				
				
			$value = DisplayLookupWizard("TID",$row["TID"],$row,"",MODE_EXPORT);
			$pdf->Cell($colwidth["TID"],$rowheight,$value);
		}
		$x+=$colwidth["TID"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Name"],$rowheight,GetData($row,"Name",""));
		$x+=$colwidth["Name"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Continue"],$rowheight,GetData($row,"Continue","Checkbox"));
		$x+=$colwidth["Continue"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Type"],$rowheight,GetData($row,"Type",""));
		$x+=$colwidth["Type"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Start"],$rowheight,GetData($row,"Start","Short Date"));
		$x+=$colwidth["Start"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["End"],$rowheight,GetData($row,"End","Short Date"));
		$x+=$colwidth["End"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Time"],$rowheight,GetData($row,"Time","Time"));
		$x+=$colwidth["Time"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Room"],$rowheight,GetData($row,"Room",""));
		$x+=$colwidth["Room"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Duration"],$rowheight,GetData($row,"Duration",""));
		$x+=$colwidth["Duration"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["About"],$rowheight,GetData($row,"About","HTML"));
		$x+=$colwidth["About"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Announce"],$rowheight,GetData($row,"Announce","HTML"));
		$x+=$colwidth["Announce"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
//	draw fames
		$x=$leftmargin;
		$pdf->Rect($x,$pdf->y0,$colwidth["CID"],$pdf->maxheight);
		$x+=$colwidth["CID"];
		$pdf->Rect($x,$pdf->y0,$colwidth["TID"],$pdf->maxheight);
		$x+=$colwidth["TID"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Name"],$pdf->maxheight);
		$x+=$colwidth["Name"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Continue"],$pdf->maxheight);
		$x+=$colwidth["Continue"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Type"],$pdf->maxheight);
		$x+=$colwidth["Type"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Start"],$pdf->maxheight);
		$x+=$colwidth["Start"];
		$pdf->Rect($x,$pdf->y0,$colwidth["End"],$pdf->maxheight);
		$x+=$colwidth["End"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Time"],$pdf->maxheight);
		$x+=$colwidth["Time"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Room"],$pdf->maxheight);
		$x+=$colwidth["Room"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Duration"],$pdf->maxheight);
		$x+=$colwidth["Duration"];
		$pdf->Rect($x,$pdf->y0,$colwidth["About"],$pdf->maxheight);
		$x+=$colwidth["About"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Announce"],$pdf->maxheight);
		$x+=$colwidth["Announce"];
		$pdf->y0+=$pdf->maxheight;
		$i++;
		$row=db_fetch_array($rs);
	}
	$pdf->Output();
}

?>
<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
session_cache_limiter("none");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/marks_variables.php");


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
			$keys["MID"]=refine($_REQUEST["mdelete1"][$ind-1]);
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
			$keys["MID"]=urldecode($arr[0]);
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
$body["begin"]="<form action=\"marks_export.php\" method=get id=frmexport name=frmexport>";
$body["end"]="</form>";
$xt->assignbyref("body",$body);
$xt->display("marks_export.htm");


function ExportToExcel()
{
	global $cCharset;
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;Filename=marks.xls");

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
	header("Content-Disposition: attachment;Filename=marks.doc");

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
	header("Content-Disposition: attachment;Filename=marks.xml");
	if(!($row=db_fetch_array($rs)))
		return;
	global $cCharset;
	echo "<?xml version=\"1.0\" encoding=\"".$cCharset."\" standalone=\"yes\"?>\r\n";
	echo "<table>\r\n";
	$i=0;
	while((!$nPageSize || $i<$nPageSize) && $row)
	{
		echo "<row>\r\n";
		$field=htmlspecialchars(XMLNameEncode("SID"));
		echo "<".$field.">";
/*		
		if(strlen($row["SID"]))
		{
			$strdata = make_db_value("SID",$row["SID"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Full_Name`";
			$LookupSQL.=" FROM `students` WHERE `SID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["SID"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			echo htmlspecialchars(GetDataInt($lookupvalue,$row,"SID", ""));
		}
*/		
		echo htmlspecialchars(DisplayLookupWizard("SID",$row["SID"],$row,"",MODE_EXPORT));
		
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("LID"));
		echo "<".$field.">";
/*		
		if(strlen($row["LID"]))
		{
			$strdata = make_db_value("LID",$row["LID"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["LID"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			echo htmlspecialchars(GetDataInt($lookupvalue,$row,"LID", ""));
		}
*/		
		echo htmlspecialchars(DisplayLookupWizard("LID",$row["LID"],$row,"",MODE_EXPORT));
		
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("SESSINAL"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"SESSINAL",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("MIDTERM"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"MIDTERM",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("FINAL"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"FINAL",""));
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
	header("Content-Disposition: attachment;Filename=marks.csv");

	if(!($row=db_fetch_array($rs)))
		return;

	$totals=array();

	
// write header
	$outstr="";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"SID\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"LID\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"SESSINAL\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"MIDTERM\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"FINAL\"";
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
		if(strlen($row["SID"]))
		{
			$strdata = make_db_value("SID",$row["SID"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Full_Name`";
			$LookupSQL.=" FROM `students` WHERE `SID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);

			$lookupvalue=$row["SID"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$outstr.='"'.htmlspecialchars(GetDataInt($lookupvalue,$row,"SID", "")).'"';
		}
*/		
		$value = DisplayLookupWizard("SID",$row["SID"],$row,"",MODE_EXPORT);
		if(strlen($value))
			$outstr.='"'.htmlspecialchars($value).'"';

		if($outstr!="")
			$outstr.=",";
/*
		if(strlen($row["LID"]))
		{
			$strdata = make_db_value("LID",$row["LID"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);

			$lookupvalue=$row["LID"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$outstr.='"'.htmlspecialchars(GetDataInt($lookupvalue,$row,"LID", "")).'"';
		}
*/		
		$value = DisplayLookupWizard("LID",$row["LID"],$row,"",MODE_EXPORT);
		if(strlen($value))
			$outstr.='"'.htmlspecialchars($value).'"';

		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"SESSINAL",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"MIDTERM",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"FINAL",$format)).'"';
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
		echo '<td style="width: 100" x:str>'.PrepareForExcel("SID").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("LID").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("SESSINAL").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("MIDTERM").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("FINAL").'</td>';
	}
	else
	{
		echo "<td>SID</td>";
		echo "<td>LID</td>";
		echo "<td>SESSINAL</td>";
		echo "<td>MIDTERM</td>";
		echo "<td>FINAL</td>";
	}
	echo "</tr>";

	$totals=array();
// write data rows
	$iNumberOfRows = 0;
	while((!$nPageSize || $iNumberOfRows<$nPageSize) && $row)
	{
		echo "<tr>";
	echo '<td>';
		if(strlen($row["SID"]))
		{
/*
			$strdata = make_db_value("SID",$row["SID"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Full_Name`";
			$LookupSQL.=" FROM `students` WHERE `SID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["SID"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
				
			$strValue=GetDataInt($lookupvalue,$row,"SID", "");
*/			
			$strValue = DisplayLookupWizard("SID",$row["SID"],$row,"",MODE_EXPORT);
						if($_REQUEST["type"]=="excel")
				echo PrepareForExcel($strValue);
			else
				echo htmlspecialchars($strValue);

		}
	echo '</td>';
	echo '<td>';
		if(strlen($row["LID"]))
		{
/*
			$strdata = make_db_value("LID",$row["LID"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["LID"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
				
			$strValue=GetDataInt($lookupvalue,$row,"LID", "");
*/			
			$strValue = DisplayLookupWizard("LID",$row["LID"],$row,"",MODE_EXPORT);
						if($_REQUEST["type"]=="excel")
				echo PrepareForExcel($strValue);
			else
				echo htmlspecialchars($strValue);

		}
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"SESSINAL",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"MIDTERM",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"FINAL",$format));
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
		$this->Rect($x,$this->y0,$colwidth["SID"],$this->maxheight);
		$x+=$colwidth["SID"];
		$this->Rect($x,$this->y0,$colwidth["LID"],$this->maxheight);
		$x+=$colwidth["LID"];
		$this->Rect($x,$this->y0,$colwidth["SESSINAL"],$this->maxheight);
		$x+=$colwidth["SESSINAL"];
		$this->Rect($x,$this->y0,$colwidth["MIDTERM"],$this->maxheight);
		$x+=$colwidth["MIDTERM"];
		$this->Rect($x,$this->y0,$colwidth["FINAL"],$this->maxheight);
		$x+=$colwidth["FINAL"];
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
//		$this->Cell($colwidth["SID"],$this->rowheight,"Stuednt Name",1,0,'C',1);
		$this->Cell($colwidth["SID"],$this->rowheight,"Stuednt Name",1,0,'C',1);
//		$this->Cell($colwidth["LID"],$this->rowheight,"Lecture Name",1,0,'C',1);
		$this->Cell($colwidth["LID"],$this->rowheight,"Lecture Name",1,0,'C',1);
//		$this->Cell($colwidth["SESSINAL"],$this->rowheight,"Sessional",1,0,'C',1);
		$this->Cell($colwidth["SESSINAL"],$this->rowheight,"Sessional",1,0,'C',1);
//		$this->Cell($colwidth["MIDTERM"],$this->rowheight,"Mid Term",1,0,'C',1);
		$this->Cell($colwidth["MIDTERM"],$this->rowheight,"Mid Term",1,0,'C',1);
//		$this->Cell($colwidth["FINAL"],$this->rowheight,"Final Term",1,0,'C',1);
		$this->Cell($colwidth["FINAL"],$this->rowheight,"Final Term",1,0,'C',1);
		$this->Ln($this->rowheight);
		$this->y0=$this->GetY();
	}

	}

	$pdf=new PDF();

	$leftmargin=5;
	$pagewidth=200;
	$pageheight=290;
	$rowheight=5;


	$defwidth=$pagewidth/5;
	$colwidth=array();
    $colwidth["SID"]=$defwidth;
    $colwidth["LID"]=$defwidth;
    $colwidth["SESSINAL"]=$defwidth;
    $colwidth["MIDTERM"]=$defwidth;
    $colwidth["FINAL"]=$defwidth;
	
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
		if(strlen($row["SID"]))
		{
/*			$strdata = make_db_value("SID",$row["SID"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Full_Name`";
			$LookupSQL.=" FROM `students` WHERE `SID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["SID"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$pdf->Cell($colwidth["SID"],$rowheight,GetDataInt($lookupvalue,$row,"SID", ""));
*/				
				
			$value = DisplayLookupWizard("SID",$row["SID"],$row,"",MODE_EXPORT);
			$pdf->Cell($colwidth["SID"],$rowheight,$value);
		}
		$x+=$colwidth["SID"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		if(strlen($row["LID"]))
		{
/*			$strdata = make_db_value("LID",$row["LID"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Name`";
			$LookupSQL.=" FROM `lectures` WHERE `LID` = " . $strdata;
					LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["LID"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$pdf->Cell($colwidth["LID"],$rowheight,GetDataInt($lookupvalue,$row,"LID", ""));
*/				
				
			$value = DisplayLookupWizard("LID",$row["LID"],$row,"",MODE_EXPORT);
			$pdf->Cell($colwidth["LID"],$rowheight,$value);
		}
		$x+=$colwidth["LID"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["SESSINAL"],$rowheight,GetData($row,"SESSINAL",""));
		$x+=$colwidth["SESSINAL"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["MIDTERM"],$rowheight,GetData($row,"MIDTERM",""));
		$x+=$colwidth["MIDTERM"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["FINAL"],$rowheight,GetData($row,"FINAL",""));
		$x+=$colwidth["FINAL"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
//	draw fames
		$x=$leftmargin;
		$pdf->Rect($x,$pdf->y0,$colwidth["SID"],$pdf->maxheight);
		$x+=$colwidth["SID"];
		$pdf->Rect($x,$pdf->y0,$colwidth["LID"],$pdf->maxheight);
		$x+=$colwidth["LID"];
		$pdf->Rect($x,$pdf->y0,$colwidth["SESSINAL"],$pdf->maxheight);
		$x+=$colwidth["SESSINAL"];
		$pdf->Rect($x,$pdf->y0,$colwidth["MIDTERM"],$pdf->maxheight);
		$x+=$colwidth["MIDTERM"];
		$pdf->Rect($x,$pdf->y0,$colwidth["FINAL"],$pdf->maxheight);
		$x+=$colwidth["FINAL"];
		$pdf->y0+=$pdf->maxheight;
		$i++;
		$row=db_fetch_array($rs);
	}
	$pdf->Output();
}

?>
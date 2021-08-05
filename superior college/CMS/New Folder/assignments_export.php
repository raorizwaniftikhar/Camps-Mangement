<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
session_cache_limiter("none");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/assignments_variables.php");


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
			$keys["AID"]=refine($_REQUEST["mdelete1"][$ind-1]);
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
			$keys["AID"]=urldecode($arr[0]);
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
$body["begin"]="<form action=\"assignments_export.php\" method=get id=frmexport name=frmexport>";
$body["end"]="</form>";
$xt->assignbyref("body",$body);
$xt->display("assignments_export.htm");


function ExportToExcel()
{
	global $cCharset;
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;Filename=assignments.xls");

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
	header("Content-Disposition: attachment;Filename=assignments.doc");

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
	header("Content-Disposition: attachment;Filename=assignments.xml");
	if(!($row=db_fetch_array($rs)))
		return;
	global $cCharset;
	echo "<?xml version=\"1.0\" encoding=\"".$cCharset."\" standalone=\"yes\"?>\r\n";
	echo "<table>\r\n";
	$i=0;
	while((!$nPageSize || $i<$nPageSize) && $row)
	{
		echo "<row>\r\n";
		$field=htmlspecialchars(XMLNameEncode("Title"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Title",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Marks"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Marks",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Description"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Description",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("File"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"File",""));
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
	header("Content-Disposition: attachment;Filename=assignments.csv");

	if(!($row=db_fetch_array($rs)))
		return;

	$totals=array();

	
// write header
	$outstr="";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Title\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Marks\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Description\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"File\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"LID\"";
	echo $outstr;
	echo "\r\n";

// write data rows
	$iNumberOfRows = 0;
	while((!$nPageSize || $iNumberOfRows<$nPageSize) && $row)
	{
		$outstr="";
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Title",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Marks",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Description",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format=FORMAT_NONE;
		$outstr.='"'.htmlspecialchars(GetData($row,"File",$format)).'"';
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
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Title").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Marks").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Description").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("File").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("LID").'</td>';
	}
	else
	{
		echo "<td>Title</td>";
		echo "<td>Marks</td>";
		echo "<td>Description</td>";
		echo "<td>File</td>";
		echo "<td>LID</td>";
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

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"Title",$format));
		else
			echo htmlspecialchars(GetData($row,"Title",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"Marks",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"Description",$format));
		else
			echo htmlspecialchars(GetData($row,"Description",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format=FORMAT_NONE;
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"File",$format));
		else
			echo htmlspecialchars(GetData($row,"File",$format));
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
		$this->Rect($x,$this->y0,$colwidth["Title"],$this->maxheight);
		$x+=$colwidth["Title"];
		$this->Rect($x,$this->y0,$colwidth["Marks"],$this->maxheight);
		$x+=$colwidth["Marks"];
		$this->Rect($x,$this->y0,$colwidth["Description"],$this->maxheight);
		$x+=$colwidth["Description"];
		$this->Rect($x,$this->y0,$colwidth["File"],$this->maxheight);
		$x+=$colwidth["File"];
		$this->Rect($x,$this->y0,$colwidth["LID"],$this->maxheight);
		$x+=$colwidth["LID"];
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
//		$this->Cell($colwidth["Title"],$this->rowheight,"Title",1,0,'C',1);
		$this->Cell($colwidth["Title"],$this->rowheight,"Title",1,0,'C',1);
//		$this->Cell($colwidth["Marks"],$this->rowheight,"Marks",1,0,'C',1);
		$this->Cell($colwidth["Marks"],$this->rowheight,"Marks",1,0,'C',1);
//		$this->Cell($colwidth["Description"],$this->rowheight,"Description",1,0,'C',1);
		$this->Cell($colwidth["Description"],$this->rowheight,"Description",1,0,'C',1);
//		$this->Cell($colwidth["File"],$this->rowheight,"Associated File",1,0,'C',1);
		$this->Cell($colwidth["File"],$this->rowheight,"Associated File",1,0,'C',1);
//		$this->Cell($colwidth["LID"],$this->rowheight,"Lecture",1,0,'C',1);
		$this->Cell($colwidth["LID"],$this->rowheight,"Lecture",1,0,'C',1);
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
    $colwidth["Title"]=$defwidth;
    $colwidth["Marks"]=$defwidth;
    $colwidth["Description"]=$defwidth;
    $colwidth["File"]=$defwidth;
    $colwidth["LID"]=$defwidth;
	
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
		$pdf->MultiCell($colwidth["Title"],$rowheight,GetData($row,"Title",""));
		$x+=$colwidth["Title"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Marks"],$rowheight,GetData($row,"Marks",""));
		$x+=$colwidth["Marks"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Description"],$rowheight,GetData($row,"Description",""));
		$x+=$colwidth["Description"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["File"],$rowheight,GetData($row,"File","Document Download"));
		$x+=$colwidth["File"];
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
//	draw fames
		$x=$leftmargin;
		$pdf->Rect($x,$pdf->y0,$colwidth["Title"],$pdf->maxheight);
		$x+=$colwidth["Title"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Marks"],$pdf->maxheight);
		$x+=$colwidth["Marks"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Description"],$pdf->maxheight);
		$x+=$colwidth["Description"];
		$pdf->Rect($x,$pdf->y0,$colwidth["File"],$pdf->maxheight);
		$x+=$colwidth["File"];
		$pdf->Rect($x,$pdf->y0,$colwidth["LID"],$pdf->maxheight);
		$x+=$colwidth["LID"];
		$pdf->y0+=$pdf->maxheight;
		$i++;
		$row=db_fetch_array($rs);
	}
	$pdf->Output();
}

?>
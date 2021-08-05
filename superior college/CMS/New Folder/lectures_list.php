<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/lectures_variables.php");



include('libs/xtempl.php');
$xt = new Xtempl();

$conn=db_connect();


//	process reqest data, fill session variables

$mode=LIST_SIMPLE;
if(postvalue("mode")=="lookup")
	$mode=LIST_LOOKUP;
$id=postvalue("id");
$xt->assign("id",$id);

if($mode==LIST_LOOKUP)
{	
	$lookupwhere="";
	$categoryfield="";
	$linkfield="";
	$lookupfield=postvalue("field");
	$lookupcontrol=postvalue("control");
	$lookupcategory=postvalue("category");
	$lookuptable=postvalue("table");
	$lookupparams="mode=lookup&id=".$id."&field=".rawurlencode($lookupfield)
		."&control=".rawurlencode($lookupcontrol)."&category=".rawurlencode($lookupcategory)
		."&table=".rawurlencode($lookuptable);
	if($lookuptable=="assignments" && $lookupfield=="LID") 
	{
		$linkfield = "LID";
		$dispfield = "Name";
			}
	if($lookuptable=="attendance" && $lookupfield=="LRID") 
	{
		$linkfield = "LID";
		$dispfield = "Name";
			}
	if($lookuptable=="marks" && $lookupfield=="LID") 
	{
		$linkfield = "LID";
		$dispfield = "Name";
			}
	if($lookuptable=="students" && $lookupfield=="L1") 
	{
		$linkfield = "LID";
		$dispfield = "Name";
			}
	if($lookuptable=="students" && $lookupfield=="L2") 
	{
		$linkfield = "LID";
		$dispfield = "Name";
			}
	if($lookuptable=="students" && $lookupfield=="L3") 
	{
		$linkfield = "LID";
		$dispfield = "Name";
			}
	if($lookuptable=="students" && $lookupfield=="L4") 
	{
		$linkfield = "LID";
		$dispfield = "Name";
			}
	if($lookuptable=="students" && $lookupfield=="L5") 
	{
		$linkfield = "LID";
		$dispfield = "Name";
			}
	if($lookuptable=="students" && $lookupfield=="L6") 
	{
		$linkfield = "LID";
		$dispfield = "Name";
			}
	if($lookuptable=="students" && $lookupfield=="L7") 
	{
		$linkfield = "LID";
		$dispfield = "Name";
			}
//	determine which field should be used to select values
	$lookupSelectField="";
	$lookupSelectField="CID";
	if(AppearOnListPage($dispfield))
		$lookupSelectField=$dispfield;

	if($categoryfield)
	{
		if(!strlen(GetFullFieldName($categoryfield)))
			$categoryfield="";
	}
	if(!$categoryfield)
		$lookupcategory="";
	
}

$firsttime=postvalue("firsttime");

if(!count($_POST) && !count($_GET))
{
	$sess_unset = array();
	foreach($_SESSION as $key=>$value)
		if(substr($key,0,strlen($strTableName)+1)==$strTableName."_" &&
			strpos(substr($key,strlen($strTableName)+1),"_")===false)
			$sess_unset[] = $key;
	foreach($sess_unset as $key)
		unset($_SESSION[$key]);
}

//	Before Process event
if(function_exists("BeforeProcessList"))
	BeforeProcessList($conn);

if(@$_REQUEST["a"]=="showall")
	$_SESSION[$strTableName."_search"]=0;
else if(@$_REQUEST["a"]=="search")
{
	$_SESSION[$strTableName."_searchfield"]=postvalue("SearchField");
	$_SESSION[$strTableName."_searchoption"]=postvalue("SearchOption");
	$_SESSION[$strTableName."_searchfor"]=postvalue("SearchFor");
	if(postvalue("SearchFor")!="" || postvalue("SearchOption")=='Empty')
		$_SESSION[$strTableName."_search"]=1;
	else
		$_SESSION[$strTableName."_search"]=0;
	$_SESSION[$strTableName."_pagenumber"]=1;
}
else if(@$_REQUEST["a"]=="advsearch")
{
	$_SESSION[$strTableName."_asearchnot"]=array();
	$_SESSION[$strTableName."_asearchopt"]=array();
	$_SESSION[$strTableName."_asearchfor"]=array();
	$_SESSION[$strTableName."_asearchfor2"]=array();
	$tosearch=0;
	$asearchfield = postvalue("asearchfield");
	$_SESSION[$strTableName."_asearchtype"] = postvalue("type");
	if(!$_SESSION[$strTableName."_asearchtype"])
		$_SESSION[$strTableName."_asearchtype"]="and";
	foreach($asearchfield as $field)
	{
		$gfield=GoodFieldName($field);
		$asopt=postvalue("asearchopt_".$gfield);
		$value1=postvalue("value_".$gfield);
		$type=postvalue("type_".$gfield);
		$value2=postvalue("value1_".$gfield);
		$not=postvalue("not_".$gfield);
		if($value1 || $asopt=='Empty')
		{
			$tosearch=1;
			$_SESSION[$strTableName."_asearchopt"][$field]=$asopt;
			if(!is_array($value1))
				$_SESSION[$strTableName."_asearchfor"][$field]=$value1;
			else
				$_SESSION[$strTableName."_asearchfor"][$field]=combinevalues($value1);
			$_SESSION[$strTableName."_asearchfortype"][$field]=$type;
			if($value2)
				$_SESSION[$strTableName."_asearchfor2"][$field]=$value2;
			$_SESSION[$strTableName."_asearchnot"][$field]=($not=="on");
		}
	}
	if($tosearch)
		$_SESSION[$strTableName."_search"]=2;
	else
		$_SESSION[$strTableName."_search"]=0;
	$_SESSION[$strTableName."_pagenumber"]=1;
}

//	process masterkey value
$mastertable=postvalue("mastertable");
if($mastertable!="")
{
	$_SESSION[$strTableName."_mastertable"]=$mastertable;
//	copy keys to session
	$i=1;
	while(isset($_REQUEST["masterkey".$i]))
	{
		$_SESSION[$strTableName."_masterkey".$i]=$_REQUEST["masterkey".$i];
		$i++;
	}
	if(isset($_SESSION[$strTableName."_masterkey".$i]))
		unset($_SESSION[$strTableName."_masterkey".$i]);
//	reset search and page number
	$_SESSION[$strTableName."_search"]=0;
	$_SESSION[$strTableName."_pagenumber"]=1;
}
else
	$mastertable=$_SESSION[$strTableName."_mastertable"];


if(@$_REQUEST["orderby"])
	$_SESSION[$strTableName."_orderby"]=@$_REQUEST["orderby"];

if(@$_REQUEST["pagesize"])
{
	$_SESSION[$strTableName."_pagesize"]=@$_REQUEST["pagesize"];
	$_SESSION[$strTableName."_pagenumber"]=1;
}

if(@$_REQUEST["goto"])
	$_SESSION[$strTableName."_pagenumber"]=@$_REQUEST["goto"];


//	process reqest data - end

$includes_js=array();
$includes_css=array();
$code_begin="";
$code_end="";
$html_begin="";
$html_end="";


if($mode==LIST_SIMPLE)
	$includes_js[]="include/jquery.js";


if($mode==LIST_LOOKUP)
{
	$includes_js[]="include/inlineedit.js";
//	this code must be executed after the inlineedit.js is loaded
	$afteredited_handler="";
	if($lookupSelectField)
	{
		$select_onclick='$("#display_'.$lookupcontrol.'").val($("#edit"+id+"_'.GoodFieldname($dispfield).'").attr("val")); $("#'.$lookupcontrol.'").val($("#edit"+id+"_'.GoodFieldname($linkfield).'").attr("val")); if($("#'.$lookupcontrol.'")[0].onchange) $("#'.$lookupcontrol.'")[0].onchange();RemoveFlyDiv('.$id.');';
		$afteredited_handler = 'window.inlineEditing'.$id.'.afterRecordEdited = function(id) {
			var span=$("#edit"+id+"_'.GoodFieldName($lookupSelectField).'");
			if(!span.length)
				return;
			$(span).html("<a href=#>"+$(span).html()+"</a>"); 
			$("a:first",span).click(function() {'.$select_onclick.'});
		};';
	}
	$code_end.='
		window.inlineEditing'.$id.' = new InlineEditing(\'lectures\',\'php\','.$id.');
		'.$afteredited_handler;
	if(strlen($lookupcategory))
	{
		$code_end.='window.inlineEditing'.$id.'.lookupfield = \''.jsreplace($lookupfield).'\';';
		$code_end.='window.inlineEditing'.$id.'.lookuptable = \''.jsreplace($lookuptable).'\';';
		$code_end.='window.inlineEditing'.$id.'.categoryvalue = \''.jsreplace($lookupcategory).'\';';
	}
}
else
{
	$includes_js[]="include/inlineedit.js";
	$code_end .= 'window.inlineEditing'.$id.' = new InlineEditing(\'lectures\',\'php\');';
}

$includes_js[]="include/ajaxsuggest.js";
	$includes_js[]="include/onthefly.js";
//	validation stuff
	$editValidateTypes = array();
	$editValidateFields = array();
	$addValidateTypes = array();
	$addValidateFields = array();

						$validatetype="";
					$validatetype.="IsRequired";
			$editValidateTypes[] = $validatetype;
			$editValidateFields[] = "CID";
						$validatetype="";
					$validatetype.="IsRequired";
			$editValidateTypes[] = $validatetype;
			$editValidateFields[] = "TID";
										$validatetype="";
					$validatetype.="IsRequired";
			$editValidateTypes[] = $validatetype;
			$editValidateFields[] = "Name";
			$editValidateTypes[] = "";
		$editValidateFields[] = "Continue";
						$validatetype="";
					$validatetype.="IsRequired";
			$editValidateTypes[] = $validatetype;
			$editValidateFields[] = "Type";
						$validatetype="";
					$validatetype.="IsRequired";
			$editValidateTypes[] = $validatetype;
			$editValidateFields[] = "Start";
						$validatetype="";
					$validatetype.="IsRequired";
			$editValidateTypes[] = $validatetype;
			$editValidateFields[] = "End";
										$validatetype="IsTime";
					$validatetype.="IsRequired";
			$editValidateTypes[] = $validatetype;
			$editValidateFields[] = "Time";
										$validatetype="";
					$validatetype.="IsRequired";
			$editValidateTypes[] = $validatetype;
			$editValidateFields[] = "Room";
										$validatetype="";
					$validatetype.="IsRequired";
			$editValidateTypes[] = $validatetype;
			$editValidateFields[] = "Duration";
	
						$validatetype="";
					$validatetype.="IsRequired";
			$addValidateTypes[] = $validatetype;
			$addValidateFields[] = "CID";
						$validatetype="";
					$validatetype.="IsRequired";
			$addValidateTypes[] = $validatetype;
			$addValidateFields[] = "TID";
										$validatetype="";
					$validatetype.="IsRequired";
			$addValidateTypes[] = $validatetype;
			$addValidateFields[] = "Name";
			$addValidateTypes[] = "";
		$addValidateFields[] = "Continue";
						$validatetype="";
					$validatetype.="IsRequired";
			$addValidateTypes[] = $validatetype;
			$addValidateFields[] = "Type";
						$validatetype="";
					$validatetype.="IsRequired";
			$addValidateTypes[] = $validatetype;
			$addValidateFields[] = "Start";
						$validatetype="";
					$validatetype.="IsRequired";
			$addValidateTypes[] = $validatetype;
			$addValidateFields[] = "End";
										$validatetype="IsTime";
					$validatetype.="IsRequired";
			$addValidateTypes[] = $validatetype;
			$addValidateFields[] = "Time";
										$validatetype="";
					$validatetype.="IsRequired";
			$addValidateTypes[] = $validatetype;
			$addValidateFields[] = "Room";
										$validatetype="";
					$validatetype.="IsRequired";
			$addValidateTypes[] = $validatetype;
			$addValidateFields[] = "Duration";


		$code_begin.="window.TEXT_INLINE_FIELD_REQUIRED='".jsreplace("Required field")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_ZIPCODE='".jsreplace("Field should be a valid zipcode")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_EMAIL='".jsreplace("Field should be a valid email address")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_NUMBER='".jsreplace("Field should be a valid number")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_CURRENCY='".jsreplace("Field should be a valid currency")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_PHONE='".jsreplace("Field should be a valid phone number")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_PASSWORD1='".jsreplace("Field can not be 'password'")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_PASSWORD2='".jsreplace("Field should be at least 4 characters long")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_STATE='".jsreplace("Field should be a valid US state name")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_SSN='".jsreplace("Field should be a valid Social Security Number")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_DATE='".jsreplace("Field should be a valid date")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_TIME='".jsreplace("Field should be a valid time in 24-hour format")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_CC='".jsreplace("Field should be a valid credit card number")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_SSN='".jsreplace("Field should be a valid Social Security Number")."';\r\n";
								
		if($mode==LIST_SIMPLE)
	{
		$types_separated = implode(",", $editValidateTypes);
		$fields_separated = implode(",", $editValidateFields);
		$code_end.= "inlineEditing".$id.".editValidateTypes = String('".$types_separated."').split(',');"."\r\n";
		$code_end.= "inlineEditing".$id.".editValidateFields = String('".$fields_separated."').split(',');"."\r\n";
	}
									
		if($mode==LIST_SIMPLE)
	{
		$types_separated = implode(",", $addValidateTypes);
		$fields_separated = implode(",", $addValidateFields);
		$code_end.= "inlineEditing".$id.".addValidateTypes = String('".$types_separated."').split(',');"."\r\n";
		$code_end.= "inlineEditing".$id.".addValidateFields = String('".$fields_separated."').split(',');"."\r\n";
	}
											
		//	include datepicker files
	$includes_js[]="include/calendar.js";
//	$includes.="<script type=\"text/javascript\" src=\"include/calendar.js\"></script>\r\n";
	

$includes_js[]="include/jsfunctions.js";
if($mode==LIST_SIMPLE)
	$code_begin.="\nvar bSelected=false;";
$code_begin.="\nwindow.TEXT_FIRST = \""."First"."\";".
"\nwindow.TEXT_PREVIOUS = \""."Previous"."\";".
"\nwindow.TEXT_NEXT = \""."Next"."\";".
"\nwindow.TEXT_LAST = \""."Last"."\";".
"\nwindow.TEXT_PLEASE_SELECT='".jsreplace("Please select")."';".
"\nwindow.TEXT_SAVE='".jsreplace("Save")."';".
"\nwindow.TEXT_CANCEL='".jsreplace("Cancel")."';".
"\nwindow.TEXT_INLINE_ERROR='".jsreplace("Error occurred")."';".
"\nwindow.TEXT_PREVIEW='".jsreplace("preview")."';".
"\nwindow.TEXT_HIDE='".jsreplace("hide")."';".
"\nwindow.TEXT_LOADING='".jsreplace("loading")."';".
"\nvar locale_dateformat = ".$locale_info["LOCALE_IDATE"].";".
"\nvar locale_datedelimiter = \"".$locale_info["LOCALE_SDATE"]."\";".
"\nvar bLoading=false;\r\n";
	$code_begin.="var SUGGEST_TABLE='lectures_searchsuggest.php';\r\n";
	$code_begin.="var MASTER_PREVIEW_TABLE='lectures_masterpreview.php';\r\n";
$html_begin.="<div id=\"search_suggest".$id."\"></div>";
$html_begin.="<div id=\"master_details".$id."\" onmouseover=\"RollDetailsLink.showPopup();\" onmouseout=\"RollDetailsLink.hidePopup();\"> </div>";
if($mode==LIST_SIMPLE)
	$html_begin.="<div id=\"inline_error".$id."\"></div>";

$body = array();
if($mode==LIST_SIMPLE)
	$html_begin.="<form name=\"frmSearch\" method=\"GET\" action=\"lectures_list.php\">";
else
{
	$html_begin.="<form name=\"frmSearch".$id."\" target=\"flyframe".$id."\" method=\"GET\" action=\"lectures_list.php\">";
	$html_begin.="<input type=\"Hidden\" name=\"mode\" value=\"lookup\">";
	$html_begin.="<input type=\"Hidden\" name=\"id\" value=\"".$id."\">";
	$html_begin.="<input type=\"Hidden\" name=\"field\" value=\"".htmlspecialchars($lookupfield)."\">";
	$html_begin.="<input type=\"Hidden\" name=\"control\" value=\"".htmlspecialchars($lookupcontrol)."\">";
	$html_begin.="<input type=\"Hidden\" name=\"category\" value=\"".htmlspecialchars($lookupcategory)."\">";
	$html_begin.="<input type=\"Hidden\" name=\"table\" value=\"".htmlspecialchars($lookuptable)."\">";
}
$html_begin.='<input type="Hidden" name="a" value="search">
<input type="Hidden" name="value" value="1">
<input type="Hidden" name="SearchFor" value="">
<input type="Hidden" name="SearchOption" value="">
<input type="Hidden" name="SearchField" value="">
</form>';

$includes_vars="true";

if($mode==LIST_SIMPLE)
{
	$body["begin"]="";
	foreach($includes_js as $file)
		$body["begin"].="<script type=\"text/javascript\" src=\"".$file."\"></script>";
	foreach($includes_css as $file)
		$body["begin"].="<link rel='stylesheet' href='".$file."' type='text/css' media='screen'/>";
	$body["begin"].="<script language=\"javascript\">".$code_begin."</script>";
	$body["begin"].=$html_begin;
}
elseif($mode==LIST_LOOKUP)
{
	$includes_code="var s;";
	foreach($includes_js as $file)
	{
		$pos=strrpos($file,"/");
		if($pos!==false)
			$var=substr($file,$pos+1,strlen($file)-4-$pos);
		else
			$var=substr($file,0,strlen($file)-3);
		$var.="_included";

		$includes_vars.=" && window[ '".$var."' ]";
		
		$includes_code.="if(typeof( window[ '".$var."' ] ) == 'undefined') {";
		$includes_code.="s = document.createElement('script');s.src = '".$file."';\r\n".
		"document.getElementsByTagName('HEAD')[0].appendChild(s);}\r\n";
	}
	$code_begin=$includes_code.$code_begin;
	$body["begin"].=$html_begin;
}

//	process session variables
//	order by
$strOrderBy="";
$order_ind=-1;


$recno=1;
$recid=$recno+$id;
$numrows=0;
$rowid=0;

$href="lectures_list.php?orderby=aCID";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("CID_orderlinkattrs",$orderlinkattrs);
$xt->assign("CID_fieldheader",true);
$href="lectures_list.php?orderby=aTID";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("TID_orderlinkattrs",$orderlinkattrs);
$xt->assign("TID_fieldheader",true);
$href="lectures_list.php?orderby=aName";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("Name_orderlinkattrs",$orderlinkattrs);
$xt->assign("Name_fieldheader",true);
$href="lectures_list.php?orderby=aContinue";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("Continue_orderlinkattrs",$orderlinkattrs);
$xt->assign("Continue_fieldheader",true);
$href="lectures_list.php?orderby=aType";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("Type_orderlinkattrs",$orderlinkattrs);
$xt->assign("Type_fieldheader",true);
$href="lectures_list.php?orderby=aStart";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("Start_orderlinkattrs",$orderlinkattrs);
$xt->assign("Start_fieldheader",true);
$href="lectures_list.php?orderby=aEnd";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("End_orderlinkattrs",$orderlinkattrs);
$xt->assign("End_fieldheader",true);
$href="lectures_list.php?orderby=aTime";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("Time_orderlinkattrs",$orderlinkattrs);
$xt->assign("Time_fieldheader",true);
$href="lectures_list.php?orderby=aRoom";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("Room_orderlinkattrs",$orderlinkattrs);
$xt->assign("Room_fieldheader",true);
$href="lectures_list.php?orderby=aDuration";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("Duration_orderlinkattrs",$orderlinkattrs);
$xt->assign("Duration_fieldheader",true);
$href="lectures_list.php?orderby=aAbout";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("About_orderlinkattrs",$orderlinkattrs);
$xt->assign("About_fieldheader",true);
$href="lectures_list.php?orderby=aAnnounce";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("Announce_orderlinkattrs",$orderlinkattrs);
$xt->assign("Announce_fieldheader",true);

if(@$_SESSION[$strTableName."_orderby"])
{
	$order_field=GetFieldByGoodFieldName(substr($_SESSION[$strTableName."_orderby"],1));
	$order_dir=substr($_SESSION[$strTableName."_orderby"],0,1);
	$order_ind=GetFieldIndex($order_field);

	$dir="a";
	$img="down";

	if($order_dir=="a")
	{
		$dir="d";
		$img="up";
	}

	$xt->assign_section(GoodFieldName($order_field)."_fieldheader","","<img src=\"images/".$img.".gif\" border=0>");
	
	$href="lectures_list.php?orderby=".$dir.GoodFieldName($order_field);
	$orderlinkattrs="";
	if($mode==LIST_LOOKUP)
	{
		$href.="&".$lookupparams;
		$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
	}
	$orderlinkattrs.=" href=\"".$href."\"";
	$xt->assign(GoodFieldName($order_field)."_orderlinkattrs",$orderlinkattrs);

	if($order_ind)
	{
		if($order_dir=="a")
			$strOrderBy="order by ".($order_ind)." asc";
		else 
			$strOrderBy="order by ".($order_ind)." desc";
	}
}
if(!$strOrderBy)
	$strOrderBy=$gstrOrderBy;

//	page number
$mypage=(integer)$_SESSION[$strTableName."_pagenumber"];
if(!$mypage)
	$mypage=1;

//	page size
$PageSize=(integer)$_SESSION[$strTableName."_pagesize"];
if(!$PageSize)
	$PageSize=$gPageSize;
if($mode==LIST_LOOKUP)
	$PageSize=20;

$xt->assign("rpp".$PageSize."_selected","selected");

// delete record
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
		$keys["LID"]=urldecode(@$arr[0]);
		$selected_recs[]=$keys;
	}
}

$records_deleted=0;
foreach($selected_recs as $keys)
{
	$where = KeyWhere($keys);

	$strSQL="delete from ".AddTableWrappers($strOriginalTableName)." where ".$where;
	$retval=true;
	if(function_exists("AfterDelete") || function_exists("BeforeDelete"))
	{
		$deletedrs = db_query(gSQLWhere($where),$conn);
		$deleted_values = db_fetch_array($deletedrs);
	}
	if(function_exists("BeforeDelete"))
		$retval = BeforeDelete($where,$deleted_values);
	if($retval && @$_REQUEST["a"]=="delete")
	{
		$records_deleted++;
				LogInfo($strSQL);
		db_exec($strSQL,$conn);
		if(function_exists("AfterDelete"))
			AfterDelete($where,$deleted_values);
	}
}

if(count($selected_recs))
{
	if(function_exists("AfterMassDelete"))
		AfterMassDelete($records_deleted);
}

//deal with permissions

//	table selector
$allow_assignments=true;
$allow_attendance=true;
$allow_classes=true;
$allow_lectures=true;
$allow_marks=true;
$allow_students=true;
$allow_teachers=true;
$allow_users=true;

$createmenu=false;
if($allow_users)
{
	$createmenu=true;
	$xt->assign("users_tablelink",true);
	$page="";
		$page="list";
		$strPerm = GetUserPermissions("users");
	if(strpos($strPerm, "A")!==false && strpos($strPerm, "S")===false)
		$page="add";
	$xt->assign("users_tablelink_attrs","href=\"users_".$page.".php\"");
	$xt->assign("users_optionattrs","value=\"users_".$page.".php\"");
}
if($allow_classes)
{
	$createmenu=true;
	$xt->assign("classes_tablelink",true);
	$page="";
		$page="list";
		$strPerm = GetUserPermissions("classes");
	if(strpos($strPerm, "A")!==false && strpos($strPerm, "S")===false)
		$page="add";
	$xt->assign("classes_tablelink_attrs","href=\"classes_".$page.".php\"");
	$xt->assign("classes_optionattrs","value=\"classes_".$page.".php\"");
}
if($allow_lectures)
{
	$createmenu=true;
	$xt->assign("lectures_tablelink",true);
	$page="";
		$page="list";
		$strPerm = GetUserPermissions("lectures");
	if(strpos($strPerm, "A")!==false && strpos($strPerm, "S")===false)
		$page="add";
	$xt->assign("lectures_tablelink_attrs","href=\"lectures_".$page.".php\"");
	$xt->assign("lectures_optionattrs","value=\"lectures_".$page.".php\"");
}
if($allow_students)
{
	$createmenu=true;
	$xt->assign("students_tablelink",true);
	$page="";
		$page="list";
		$xt->assign("students_tablelink_attrs","href=\"students_".$page.".php\"");
	$xt->assign("students_optionattrs","value=\"students_".$page.".php\"");
}
if($allow_teachers)
{
	$createmenu=true;
	$xt->assign("teachers_tablelink",true);
	$page="";
		$page="list";
		$xt->assign("teachers_tablelink_attrs","href=\"teachers_".$page.".php\"");
	$xt->assign("teachers_optionattrs","value=\"teachers_".$page.".php\"");
}
if($allow_attendance)
{
	$createmenu=true;
	$xt->assign("attendance_tablelink",true);
	$page="";
		$page="list";
		$xt->assign("attendance_tablelink_attrs","href=\"attendance_".$page.".php\"");
	$xt->assign("attendance_optionattrs","value=\"attendance_".$page.".php\"");
}
if($allow_marks)
{
	$createmenu=true;
	$xt->assign("marks_tablelink",true);
	$page="";
		$page="list";
		$strPerm = GetUserPermissions("marks");
	if(strpos($strPerm, "A")!==false && strpos($strPerm, "S")===false)
		$page="add";
	$xt->assign("marks_tablelink_attrs","href=\"marks_".$page.".php\"");
	$xt->assign("marks_optionattrs","value=\"marks_".$page.".php\"");
}
if($allow_assignments)
{
	$createmenu=true;
	$xt->assign("assignments_tablelink",true);
	$page="";
		$page="list";
		$xt->assign("assignments_tablelink_attrs","href=\"assignments_".$page.".php\"");
	$xt->assign("assignments_optionattrs","value=\"assignments_".$page.".php\"");
}
if($createmenu && $mode==LIST_SIMPLE)
	$xt->assign("menu_block",true);
if($mode==LIST_SIMPLE)
	$xt->assign("shiftstyle_block",true);

$strPerm = GetUserPermissions();
$allow_add=(strpos($strPerm,"A")!==false);
$allow_delete=(strpos($strPerm,"D")!==false);
$allow_edit=(strpos($strPerm,"E")!==false);
$allow_search=(strpos($strPerm,"S")!==false);
$allow_export=(strpos($strPerm,"P")!==false);
$allow_import=(strpos($strPerm,"I")!==false);



//	make sql "select" string

$strWhereClause="";

//	add search params

if(@$_SESSION[$strTableName."_search"]==1)
//	 regular search
{  
	$strSearchFor=trim($_SESSION[$strTableName."_searchfor"]);
	$strSearchOption=trim($_SESSION[$strTableName."_searchoption"]);
	if(@$_SESSION[$strTableName."_searchfield"])
	{
		$strSearchField = $_SESSION[$strTableName."_searchfield"];
		if($where = StrWhere($strSearchField, $strSearchFor, $strSearchOption, ""))
			$strWhereClause = whereAdd($strWhereClause,$where);
		else
			$strWhereClause = whereAdd($strWhereClause,"1=0");
	}
	else
	{
		$strWhere = "1=0";
		if($where=StrWhere("CID", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("TID", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("Name", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("Continue", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("Type", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("Start", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("End", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("Time", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("Room", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("Duration", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("About", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("Announce", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		$strWhereClause = whereAdd($strWhereClause,$strWhere);
	}
}
else if(@$_SESSION[$strTableName."_search"]==2)
//	 advanced search
{
	$sWhere="";
	foreach(@$_SESSION[$strTableName."_asearchfor"] as $f => $sfor)
		{
			$strSearchFor=trim($sfor);
			$strSearchFor2="";
			$type=@$_SESSION[$strTableName."_asearchfortype"][$f];
			if(array_key_exists($f,@$_SESSION[$strTableName."_asearchfor2"]))
				$strSearchFor2=trim(@$_SESSION[$strTableName."_asearchfor2"][$f]);
			if($strSearchFor!="" || true)
			{
				if (!$sWhere) 
				{
					if($_SESSION[$strTableName."_asearchtype"]=="and")
						$sWhere="1=1";
					else
						$sWhere="1=0";
				}
				$strSearchOption=trim($_SESSION[$strTableName."_asearchopt"][$f]);
				if($where=StrWhereAdv($f, $strSearchFor, $strSearchOption, $strSearchFor2,$type))
				{
					if($_SESSION[$strTableName."_asearchnot"][$f])
						$where="not (".$where.")";
					if($_SESSION[$strTableName."_asearchtype"]=="and")
	   					$sWhere .= " and ".$where;
					else
	   					$sWhere .= " or ".$where;
				}
			}
		}
		$strWhereClause = whereAdd($strWhereClause,$sWhere);
	}




if($mode==LIST_LOOKUP)
{
	if(strlen($lookupcategory))
		$strWhereClause = whereAdd($strWhereClause,GetFullFieldName($categoryfield)."=".make_db_value($categoryfield,$lookupcategory));
	if(strlen($lookupwhere))
		$strWhereClause = whereAdd($strWhereClause,$lookupwhere);
}

if($mastertable=="attendance")
{
	$where ="";
		$where.= GetFullFieldName("LID")."=".make_db_value("LID",$_SESSION[$strTableName."_masterkey1"]);
	$strWhereClause = whereAdd($strWhereClause,$where);
}
if($mastertable=="assignments")
{
	$where ="";
		$where.= GetFullFieldName("LID")."=".make_db_value("LID",$_SESSION[$strTableName."_masterkey1"]);
	$strWhereClause = whereAdd($strWhereClause,$where);
}
if($mastertable=="marks")
{
	$where ="";
		$where.= GetFullFieldName("LID")."=".make_db_value("LID",$_SESSION[$strTableName."_masterkey1"]);
	$strWhereClause = whereAdd($strWhereClause,$where);
}

$strSQL = gSQLWhere($strWhereClause);

//	order by
$strSQL.=" ".trim($strOrderBy);

//	save SQL for use in "Export" and "Printer-friendly" pages

$_SESSION[$strTableName."_sql"] = $strSQL;
$_SESSION[$strTableName."_where"] = $strWhereClause;
$_SESSION[$strTableName."_order"] = $strOrderBy;

$rowsfound=false;

//	select and display records
if($allow_search)
{
	$strSQLbak = $strSQL;
	if(function_exists("BeforeQueryList"))
		BeforeQueryList($strSQL,$strWhereClause,$strOrderBy);
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
	if(!$numrows)
	{
		$rowsfound=false;
		$message="No records found";
		$message_block=array();
		$message_block["begin"]="<span name=\"notfound_message".$id."\">";
		$message_block["end"]="</span>";
		$xt->assignbyref("message_block",$message_block);
		$xt->assign("message",$message);
	}
	else
	{
		$rowsfound=true;
		$maxRecords = $numrows;
		$xt->assign("records_found",$numrows);
		$maxpages=ceil($maxRecords/$PageSize);
		if($mypage > $maxpages)
			$mypage = $maxpages;
		if($mypage<1) 
			$mypage=1;
		$maxrecs=$PageSize;
		$xt->assign("page",$mypage);
		$xt->assign("maxpages",$maxpages);
		

//	write pagination
	if($maxpages>1)
	{
		$xt->assign("pagination_block",true);
		if($mode==LIST_SIMPLE)
			$code_end.="window.GotoPage = function(nPageNumber)
				{
					window.location='lectures_list.php?goto='+nPageNumber;
				};";
		else
			$code_end.="window.GotoPage".$id." = function (nPageNumber)
				{
					window.frames['flyframe".$id."'].location='lectures_list.php?".$lookupparams."&goto='+nPageNumber;
				};";
	
/*
		if($mode==LIST_SIMPLE)
		{
			$xt->assign("pagination","<script language=\"JavaScript\">WritePagination(".$mypage.",".$maxpages.");
			function GotoPage(nPageNumber)
			{
				window.location='lectures_list.php?goto='+nPageNumber;
			}
			</script>");
		}
*/		
		$pagination="<table rows='1' cols='1' align='center' width='95%' border='0'>";
		$pagination.="<tr valign='center'><td align='center'>";
		$counterstart = $mypage - 9; 
		if($mypage%10) 
			$counterstart = $mypage - ($mypage%10) + 1; 
		$counterend = $counterstart + 9;
		if($counterend > $maxpages) $counterend = $maxpages; 
		if($counterstart != 1) 
		{
			$pagination.="<a href='JavaScript:GotoPage".$id."(1);' style='TEXT-DECORATION: none;'>"."First"."</a>";
			$pagination.="&nbsp;:&nbsp;";
			$pagination.="<a href='JavaScript:GotoPage".$id."(".($counterstart-1).");' style='TEXT-DECORATION: none;'>"."Previous"."</a>";
			$pagination.="&nbsp;";
		}
		$pagination.="<b>[</b>"; 
		for($counter = $counterstart;$counter<=$counterend;$counter++)
		{
			if ($counter != $mypage)
				$pagination.="&nbsp;<a href='JavaScript:GotoPage".$id."(".$counter.");' style='TEXT-DECORATION: none;'>".$counter."</a>";
			else 
				$pagination.="&nbsp;<b>".$counter."</b>";
		}
		$pagination.="&nbsp;<b>]</b>";
		if ($counterend != $maxpages) 
		{
			$pagination.="&nbsp;<a href='JavaScript:GotoPage".$id."(".($counterend+1).");' style='TEXT-DECORATION: none;'>"."Next"."</a>";
			$pagination.="&nbsp;:&nbsp;";
			$pagination.="&nbsp;<a href='JavaScript:GotoPage".$id."(".($maxpages).");' style='TEXT-DECORATION: none;'>"."Last"."</a>";
		}
		$pagination.="</td></tr></table>";
		$xt->assign("pagination",$pagination);
	}

		$strSQL.=" limit ".(($mypage-1)*$PageSize).",".$PageSize;
	}
	$rs=db_query($strSQL,$conn);

//	hide colunm headers if needed
	$recordsonpage=$numrows-($mypage-1)*$PageSize;
	if($recordsonpage>$PageSize)
	$recordsonpage=$PageSize;
	$colsonpage=1;
	if($colsonpage>$recordsonpage)
		$colsonpage=$recordsonpage;
	if($colsonpage<1)
		$colsonpage=1;


//	fill $rowinfo array
	$rowinfo = array();
	$rowinfo["data"]=array();
	$shade=false;
	$editlink="";
	$copylink="";

	if($allow_add )
	{
//	add inline add row	
		$row=array();
		$row["rowattrs"]="class=\"addarea".$id."\" rowid=\"add\"";
		$row["rowspace_attrs"]="class=\"addarea".$id."\"";
		$record=array();
		$record["edit_link"]=true;
		$record["inlineedit_link"]=true;
		$record["view_link"]=true;
		$record["copy_link"]=true;
		$record["checkbox"]=true;
		$record["checkbox"]=true;
		$record["editlink_attrs"]="id=\"editlink_add".$id."\"";
				if($allow_edit)
			$record["inlineeditlink_attrs"]= "id=\"ieditlink_add".$id."\"";
		$record["copylink_attrs"]="id=\"copylink_add".$id."\"";
		$record["viewlink_attrs"]="id=\"viewlink_add".$id."\"";
		$record["checkbox_attrs"]="id=\"check_add".$id."\" name=\"selection[]\"";
		$record["CID_value"] = "<span id=\"add".$id."_CID\">&nbsp;</span>";
				if(!$allow_edit || $mode==LIST_LOOKUP)
			$record["CID_value"] = "<span id=\"ieditlink_add".$id."\"></span>".$record["CID_value"];

//	add spans for link and display fieldsif they don't appear on the List page		
		if($mode==LIST_LOOKUP && $lookupSelectField)
		{
			$span="";
			if(!AppearOnListPage($linkfield))
			{
				$span.="<span style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($linkfield)."\" ";
				$span.="></span>";
			}
			if($dispfield!=$linkfield && !AppearOnListPage($dispfield))
			{
				$span.="<span  style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($dispfield)."\" ";
				$span.="></span>";
			}
			$record[GoodFieldname($lookupSelectField)."_value"].=$span;
		}
		$record["TID_value"] = "<span id=\"add".$id."_TID\">&nbsp;</span>";
//	add spans for link and display fieldsif they don't appear on the List page		
		if($mode==LIST_LOOKUP && $lookupSelectField)
		{
			$span="";
			if(!AppearOnListPage($linkfield))
			{
				$span.="<span style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($linkfield)."\" ";
				$span.="></span>";
			}
			if($dispfield!=$linkfield && !AppearOnListPage($dispfield))
			{
				$span.="<span  style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($dispfield)."\" ";
				$span.="></span>";
			}
			$record[GoodFieldname($lookupSelectField)."_value"].=$span;
		}
		$record["Name_value"] = "<span id=\"add".$id."_Name\">&nbsp;</span>";
//	add spans for link and display fieldsif they don't appear on the List page		
		if($mode==LIST_LOOKUP && $lookupSelectField)
		{
			$span="";
			if(!AppearOnListPage($linkfield))
			{
				$span.="<span style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($linkfield)."\" ";
				$span.="></span>";
			}
			if($dispfield!=$linkfield && !AppearOnListPage($dispfield))
			{
				$span.="<span  style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($dispfield)."\" ";
				$span.="></span>";
			}
			$record[GoodFieldname($lookupSelectField)."_value"].=$span;
		}
		$record["Continue_value"] = "<span id=\"add".$id."_Continue\">&nbsp;</span>";
//	add spans for link and display fieldsif they don't appear on the List page		
		if($mode==LIST_LOOKUP && $lookupSelectField)
		{
			$span="";
			if(!AppearOnListPage($linkfield))
			{
				$span.="<span style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($linkfield)."\" ";
				$span.="></span>";
			}
			if($dispfield!=$linkfield && !AppearOnListPage($dispfield))
			{
				$span.="<span  style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($dispfield)."\" ";
				$span.="></span>";
			}
			$record[GoodFieldname($lookupSelectField)."_value"].=$span;
		}
		$record["Type_value"] = "<span id=\"add".$id."_Type\">&nbsp;</span>";
//	add spans for link and display fieldsif they don't appear on the List page		
		if($mode==LIST_LOOKUP && $lookupSelectField)
		{
			$span="";
			if(!AppearOnListPage($linkfield))
			{
				$span.="<span style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($linkfield)."\" ";
				$span.="></span>";
			}
			if($dispfield!=$linkfield && !AppearOnListPage($dispfield))
			{
				$span.="<span  style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($dispfield)."\" ";
				$span.="></span>";
			}
			$record[GoodFieldname($lookupSelectField)."_value"].=$span;
		}
		$record["Start_value"] = "<span id=\"add".$id."_Start\">&nbsp;</span>";
//	add spans for link and display fieldsif they don't appear on the List page		
		if($mode==LIST_LOOKUP && $lookupSelectField)
		{
			$span="";
			if(!AppearOnListPage($linkfield))
			{
				$span.="<span style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($linkfield)."\" ";
				$span.="></span>";
			}
			if($dispfield!=$linkfield && !AppearOnListPage($dispfield))
			{
				$span.="<span  style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($dispfield)."\" ";
				$span.="></span>";
			}
			$record[GoodFieldname($lookupSelectField)."_value"].=$span;
		}
		$record["End_value"] = "<span id=\"add".$id."_End\">&nbsp;</span>";
//	add spans for link and display fieldsif they don't appear on the List page		
		if($mode==LIST_LOOKUP && $lookupSelectField)
		{
			$span="";
			if(!AppearOnListPage($linkfield))
			{
				$span.="<span style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($linkfield)."\" ";
				$span.="></span>";
			}
			if($dispfield!=$linkfield && !AppearOnListPage($dispfield))
			{
				$span.="<span  style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($dispfield)."\" ";
				$span.="></span>";
			}
			$record[GoodFieldname($lookupSelectField)."_value"].=$span;
		}
		$record["Time_value"] = "<span id=\"add".$id."_Time\">&nbsp;</span>";
//	add spans for link and display fieldsif they don't appear on the List page		
		if($mode==LIST_LOOKUP && $lookupSelectField)
		{
			$span="";
			if(!AppearOnListPage($linkfield))
			{
				$span.="<span style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($linkfield)."\" ";
				$span.="></span>";
			}
			if($dispfield!=$linkfield && !AppearOnListPage($dispfield))
			{
				$span.="<span  style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($dispfield)."\" ";
				$span.="></span>";
			}
			$record[GoodFieldname($lookupSelectField)."_value"].=$span;
		}
		$record["Room_value"] = "<span id=\"add".$id."_Room\">&nbsp;</span>";
//	add spans for link and display fieldsif they don't appear on the List page		
		if($mode==LIST_LOOKUP && $lookupSelectField)
		{
			$span="";
			if(!AppearOnListPage($linkfield))
			{
				$span.="<span style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($linkfield)."\" ";
				$span.="></span>";
			}
			if($dispfield!=$linkfield && !AppearOnListPage($dispfield))
			{
				$span.="<span  style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($dispfield)."\" ";
				$span.="></span>";
			}
			$record[GoodFieldname($lookupSelectField)."_value"].=$span;
		}
		$record["Duration_value"] = "<span id=\"add".$id."_Duration\">&nbsp;</span>";
//	add spans for link and display fieldsif they don't appear on the List page		
		if($mode==LIST_LOOKUP && $lookupSelectField)
		{
			$span="";
			if(!AppearOnListPage($linkfield))
			{
				$span.="<span style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($linkfield)."\" ";
				$span.="></span>";
			}
			if($dispfield!=$linkfield && !AppearOnListPage($dispfield))
			{
				$span.="<span  style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($dispfield)."\" ";
				$span.="></span>";
			}
			$record[GoodFieldname($lookupSelectField)."_value"].=$span;
		}
		$record["About_value"] = "<span id=\"add".$id."_About\">&nbsp;</span>";
//	add spans for link and display fieldsif they don't appear on the List page		
		if($mode==LIST_LOOKUP && $lookupSelectField)
		{
			$span="";
			if(!AppearOnListPage($linkfield))
			{
				$span.="<span style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($linkfield)."\" ";
				$span.="></span>";
			}
			if($dispfield!=$linkfield && !AppearOnListPage($dispfield))
			{
				$span.="<span  style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($dispfield)."\" ";
				$span.="></span>";
			}
			$record[GoodFieldname($lookupSelectField)."_value"].=$span;
		}
		$record["Announce_value"] = "<span id=\"add".$id."_Announce\">&nbsp;</span>";
//	add spans for link and display fieldsif they don't appear on the List page		
		if($mode==LIST_LOOKUP && $lookupSelectField)
		{
			$span="";
			if(!AppearOnListPage($linkfield))
			{
				$span.="<span style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($linkfield)."\" ";
				$span.="></span>";
			}
			if($dispfield!=$linkfield && !AppearOnListPage($dispfield))
			{
				$span.="<span  style=\"display:none\" ";
				$span.="id=\"add".$id."_".GoodFieldname($dispfield)."\" ";
				$span.="></span>";
			}
			$record[GoodFieldname($lookupSelectField)."_value"].=$span;
		}
		if($colsonpage>1)
			$record["endrecord_block"]=true;
		$record["grid_recordheader"]=true;
		$record["grid_vrecord"]=true;
		$row["grid_record"]["data"][]=$record;
		for($i=1;$i<$colsonpage;$i++)
		{
			$rec=array();
			if($i<$colsonpage-1)
				$rec["endrecord_block"]=true;
			$row["grid_record"]["data"][]=$rec;
		}

		$row["grid_rowspace"]=true;
		$row["grid_recordspace"] = array("data"=>array());
		for($i=0;$i<$colsonpage*2-1;$i++)
			$row["grid_recordspace"]["data"][]=true;
		$rowinfo["data"][]=$row;
	}
	

//	add grid data	
	
	while($data=db_fetch_array($rs))
	{
		if(function_exists("BeforeProcessRowList"))
		{
			if(!BeforeProcessRowList($data))
				continue;
		}
		break;
	}

	while($data && $recno<=$PageSize)
	{
	
		$row=array();
		if(!$shade)
		{
			$row["rowattrs"]="class=shade onmouseover=\"this.className = 'rowselected';\" onmouseout=\"this.className = 'shade';\"";
			$shade=true;
		}
		else
		{
			$row["rowattrs"]="onmouseover=\"this.className = 'rowselected';\" onmouseout=\"this.className = '';\"";
			$shade=false;
		}
		$row["grid_record"]=array();
		$row["grid_record"]["data"]=array();
		$row["rowattrs"].=" rowid=\"".$rowid."\"";
		$rowid++;
		for($col=1;$data && $recno<=$PageSize && $col<=$colsonpage;$col++)
		{
			$recid=$recno+$id;
			$record=array();

	$editable=CheckSecurity($data[""],"Edit");
	$record["edit_link"]=$editable;
	$record["inlineedit_link"]=$editable;
	$record["view_link"]=true;
	$record["copy_link"]=true;


//	detail tables

//	key fields
	$keyblock="";
	$editlink="";
	$copylink="";
	$keylink="";
	$keyblock.= rawurlencode($data["LID"]);
	$editlink.="editid1=".htmlspecialchars(rawurlencode($data["LID"]));
	$copylink.="copyid1=".htmlspecialchars(rawurlencode($data["LID"]));
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["LID"]));

	$record["editlink_attrs"]="href=\"lectures_edit.php?".$editlink."\" id=\"editlink".$recid."\"";
	$record["inlineeditlink_attrs"]= "href=\"lectures_edit.php?".$editlink."\" onclick=\"return inlineEditing".$id.".inlineEdit('".$recid."','".$editlink."');\" id=\"ieditlink".$recid."\"";
	$record["copylink_attrs"]="href=\"lectures_add.php?".$copylink."\" id=\"copylink".$recid."\"";
	$record["viewlink_attrs"]="href=\"lectures_view.php?".$editlink."\" id=\"viewlink".$recid."\"";
	if($mode!=LIST_LOOKUP)
	{
		$record["checkbox"]=$editable;
		if($allow_export)
			$record["checkbox"]=true;
		$record["checkbox_attrs"]="name=\"selection[]\" value=\"".$keyblock."\" id=\"check".$recid."\"";
	}
	else
	{
		$checkbox_attrs="name=\"selection[]\" value=\"".htmlspecialchars(@$data[$linkfield])."\" id=\"check".$recid."\"";
		$record["checkbox"]=array("begin"=>"<input type=radio ".$checkbox_attrs.">", "data"=>array());
	}


//	CID - 
			$value="";
				$value = ProcessLargeText(GetData($data,"CID", ""),"field=CID".$keylink,"",MODE_LIST);
			$record["CID_value"]=$value;

//	TID - 
			$value="";
				$value=DisplayLookupWizard("TID",$data["TID"],$data,$keylink,MODE_LIST);
			$record["TID_value"]=$value;

//	Name - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Name", ""),"field=Name".$keylink,"",MODE_LIST);
			$record["Name_value"]=$value;

//	Continue - Checkbox
			$value="";
				$value = GetData($data,"Continue", "Checkbox");
			$record["Continue_value"]=$value;

//	Type - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Type", ""),"field=Type".$keylink,"",MODE_LIST);
			$record["Type_value"]=$value;

//	Start - Short Date
			$value="";
				$value = ProcessLargeText(GetData($data,"Start", "Short Date"),"field=Start".$keylink,"",MODE_LIST);
			$record["Start_value"]=$value;

//	End - Short Date
			$value="";
				$value = ProcessLargeText(GetData($data,"End", "Short Date"),"field=End".$keylink,"",MODE_LIST);
			$record["End_value"]=$value;

//	Time - Time
			$value="";
				$value = ProcessLargeText(GetData($data,"Time", "Time"),"field=Time".$keylink,"",MODE_LIST);
			$record["Time_value"]=$value;

//	Room - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Room", ""),"field=Room".$keylink,"",MODE_LIST);
			$record["Room_value"]=$value;

//	Duration - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Duration", ""),"field=Duration".$keylink,"",MODE_LIST);
			$record["Duration_value"]=$value;

//	About - HTML
			$value="";
				$value = GetData($data,"About", "HTML");
			$record["About_value"]=$value;

//	Announce - HTML
			$value="";
				$value = GetData($data,"Announce", "HTML");
			$record["Announce_value"]=$value;
			if(function_exists("BeforeMoveNextList"))
				BeforeMoveNextList($data,$row,$col);
			if($mode==LIST_LOOKUP && $lookupSelectField)
				$code_end.='inlineEditing'.$id.'.afterRecordEdited('.$recid.');';
			
			$span="<span ";
			$span.="id=\"edit".$recid."_CID\" ";
					$span.="val=\"".htmlspecialchars($data["CID"])."\" ";
			$span.=">";
			$record["CID_value"] = $span.$record["CID_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_TID\" ";
					$span.="val=\"".htmlspecialchars($data["TID"])."\" ";
			$span.=">";
			$record["TID_value"] = $span.$record["TID_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_Name\" ";
					$span.="val=\"".htmlspecialchars($data["Name"])."\" ";
			$span.=">";
			$record["Name_value"] = $span.$record["Name_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_Continue\" ";
					$span.="val=\"".htmlspecialchars($data["Continue"])."\" ";
			$span.=">";
			$record["Continue_value"] = $span.$record["Continue_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_Type\" ";
					$span.="val=\"".htmlspecialchars($data["Type"])."\" ";
			$span.=">";
			$record["Type_value"] = $span.$record["Type_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_Start\" ";
					$span.="val=\"".htmlspecialchars($data["Start"])."\" ";
			$span.=">";
			$record["Start_value"] = $span.$record["Start_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_End\" ";
					$span.="val=\"".htmlspecialchars($data["End"])."\" ";
			$span.=">";
			$record["End_value"] = $span.$record["End_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_Time\" ";
					$span.="val=\"".htmlspecialchars($data["Time"])."\" ";
			$span.=">";
			$record["Time_value"] = $span.$record["Time_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_Room\" ";
					$span.="val=\"".htmlspecialchars($data["Room"])."\" ";
			$span.=">";
			$record["Room_value"] = $span.$record["Room_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_Duration\" ";
					$span.="val=\"".htmlspecialchars($data["Duration"])."\" ";
			$span.=">";
			$record["Duration_value"] = $span.$record["Duration_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_About\" ";
					$span.="val=\"".htmlspecialchars($data["About"])."\" ";
			$span.=">";
			$record["About_value"] = $span.$record["About_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_Announce\" ";
					$span.="val=\"".htmlspecialchars($data["Announce"])."\" ";
			$span.=">";
			$record["Announce_value"] = $span.$record["Announce_value"]."</span>";
		//	add spans with the link and display field values to the row
			if($mode==LIST_LOOKUP && $lookupSelectField)
			{
				$span="";
				if(!AppearOnListPage($linkfield))
				{
					$span.="<span ";
					$span.="id=\"edit".$recid."_".GoodFieldname($linkfield)."\" ";
					$span.="val=\"".htmlspecialchars($data[$linkfield])."\" ";
					$span.="></span>";
				}
				if($dispfield!=$linkfield && !AppearOnListPage($dispfield))
				{
					$span.="<span ";
					$span.="id=\"edit".$recid."_".GoodFieldname($dispfield)."\" ";
					$span.="val=\"".htmlspecialchars($data[$dispfield])."\" ";
					$span.="></span>";
				}
				$record[GoodFieldname($lookupSelectField)."_value"].=$span;
			}
			if($col<$colsonpage)
				$record["endrecord_block"]=true;
			$record["grid_recordheader"]=true;
			$record["grid_vrecord"]=true;
			$row["grid_record"]["data"][]=$record;
			while($data=db_fetch_array($rs))
			{
				if(function_exists("BeforeProcessRowList"))
				{
					if(!BeforeProcessRowList($data))
						continue;
				}
				break;
			}
			$recno++;
			
		}
		while($col<=$colsonpage)
		{
			$record = array();
			if($col<$colsonpage)
				$record["endrecord_block"]=true;
			$row["grid_record"]["data"][]=$record;
			$col++;
		}
//	assign row spacings for vertical layout
		$row["grid_rowspace"]=true;
		$row["grid_recordspace"] = array("data"=>array());
		for($i=0;$i<$colsonpage*2-1;$i++)
			$row["grid_recordspace"]["data"][]=true;
		
		$rowinfo["data"][]=$row;
	}
	if(count($rowinfo["data"]))
		$rowinfo["data"][count($rowinfo["data"])-1]["grid_rowspace"]=false;
	$xt->assignbyref("grid_row",$rowinfo);


}


if($allow_search)
{

	$searchfor_attrs="autocomplete=off onkeydown=\"return listenEvent(event,this,'ordinary');\" onkeyup=\"searchSuggest(event,this,'ordinary');\"";
	if($mode==LIST_LOOKUP)
		$searchfor_attrs="onkeydown=\"e=event; if(!e) e = window.event; if (e.keyCode != 13) return true; e.cancel = true; RunSearch('".$id."'); return false;\"";
	if($_SESSION[$strTableName."_search"]==1)
	{
//	fill in search variables
	//	field selection
		if(@$_SESSION[$strTableName."_searchfield"])
			$xt->assign(GoodFieldName(@$_SESSION[$strTableName."_searchfield"])."_searchfieldoption","selected");
	// search type selection
		$xt->assign(GoodFieldName(@$_SESSION[$strTableName."_searchoption"])."_searchtypeoption","selected");
		$searchfor_attrs.=" value=\"".htmlspecialchars(@$_SESSION[$strTableName."_searchfor"])."\"";
	}
	$searchfor_attrs.=" name=\"ctlSearchFor".$id."\" id=\"ctlSearchFor".$id."\"";
	$xt->assign("searchfor_attrs",$searchfor_attrs);
	$xt->assign("searchbutton_attrs","onClick=\"javascript: RunSearch('".$id."');\"");
	$xt->assign("showallbutton_attrs","onClick=\"javascript: document.forms.frmSearch".$id.".a.value = 'showall'; document.forms.frmSearch".$id.".submit();\"");
}


if($mode==LIST_SIMPLE)
{

	

	$xt->assign("toplinks_block",true);

	$xt->assign("print_link",$allow_export);
	$xt->assign("printall_link",$allow_export);
	$xt->assign("printlink_attrs","href=\"lectures_print.php\" onclick=\"window.open('lectures_print.php','wPrint');return false;\"");
	$xt->assign("printalllink_attrs","href=\"lectures_print.php?all=1\" onclick=\"window.open('lectures_print.php?all=1','wPrint');return false;\"");
	$xt->assign("export_link",$allow_export);
	$xt->assign("exportlink_attrs","href=\"lectures_export.php\" onclick=\"window.open('lectures_export.php','wExport');return false;\"");
	
	$xt->assign("printselected_link",$allow_export);
	$xt->assign("printselectedlink_attrs","disptype=\"control1\" onclick=\"
	if(!\$('input[@type=checkbox][@checked][@name^=selection]').length)
		return true;
	document.forms.frmAdmin.action='lectures_print.php';
	document.forms.frmAdmin.target='_blank';
	document.forms.frmAdmin.submit(); 
	document.forms.frmAdmin.action='lectures_list.php'; 
	document.forms.frmAdmin.target='_self';return false\"
	href=\"lectures_print.php\"");
	$xt->assign("exportselected_link",$allow_export);
	$xt->assign("exportselectedlink_attrs","disptype=\"control1\" onclick=\"
	if(!\$('input[@type=checkbox][@checked][@name^=selection]').length)
		return true;
	document.forms.frmAdmin.action='lectures_export.php';
	document.forms.frmAdmin.target='_blank';
	document.forms.frmAdmin.submit(); 
	document.forms.frmAdmin.action='lectures_list.php'; 
	document.forms.frmAdmin.target='_self';return false;\"
	href=\"lectures_export.php\"");
	
	$xt->assign("add_link",$allow_add);
	$xt->assign("copy_column",$allow_add);
	$xt->assign("addlink_attrs","href=\"lectures_add.php\" onClick=\"window.location.href='lectures_add.php'\"");
	$xt->assign("inlineadd_link",$allow_add);
	$xt->assign("inlineaddlink_attrs","href=\"lectures_add.php\" onclick=\"return inlineEditing".$id.".inlineAdd(flyid++,null,'lectures_add.php');\"");

	$xt->assign("selectall_link",$allow_delete || $allow_export  || $allow_edit);
	$xt->assign("selectalllink_attrs","href=# onclick=\"var i; 
	bSelected=!bSelected;
if ((typeof frmAdmin.elements['selection[]'].length)=='undefined')
	frmAdmin.elements['selection[]'].checked=bSelected;
else
for (i=0;i<frmAdmin.elements['selection[]'].length;++i) 
	frmAdmin.elements['selection[]'][i].checked=bSelected\"");
	
	$xt->assign("checkbox_column",$allow_delete || $allow_export  || $allow_edit);
	$xt->assign("checkbox_header",true);
	$xt->assign("checkboxheader_attrs","onClick = \"var i; 
if ((typeof frmAdmin.elements['selection[]'].length)=='undefined')
	frmAdmin.elements['selection[]'].checked=this.checked;
else
for (i=0;i<frmAdmin.elements['selection[]'].length;++i) 
	frmAdmin.elements['selection[]'][i].checked=this.checked;\"");
	$xt->assign("editselected_link",$allow_edit);
	$xt->assign("editselectedlink_attrs","href=\"lectures_edit.php\" disptype=\"control1\" name=\"edit_selected".$id."\" onclick=\"\$('input[@type=checkbox][@checked][@id^=check]').each(function(i){
				if(!isNaN(parseInt(this.id.substr(5))))
					\$('a#ieditlink'+this.id.substr(5)).click();});\"");
	$xt->assign("saveall_link",$allow_edit||$allow_edit);
	$xt->assign("savealllink_attrs","disptype=\"control1\" name=\"saveall_edited".$id."\" style=\"display:none\" onclick=\"\$('a[@id^=save_]').click();\"");
	$xt->assign("cancelall_link",$allow_edit||$allow_edit);
	$xt->assign("cancelalllink_attrs","disptype=\"control1\" name=\"revertall_edited".$id."\" style=\"display:none\" onclick=\"\$('a[@id^=revert_]').click();\"");
	

	$xt->assign("edit_column",$allow_edit);
	$xt->assign("edit_headercolumn",$allow_edit);
	$xt->assign("edit_footercolumn",$allow_edit);
	$xt->assign("inlineedit_column",$allow_edit);
	$xt->assign("inlineedit_headercolumn",$allow_edit);
	$xt->assign("inlineedit_footercolumn",$allow_edit);
	
	$xt->assign("view_column",$allow_search);


	$xt->assign("delete_link",$allow_delete);
	$xt->assign("deletelink_attrs","onclick=\"
		if(\$('input[@type=checkbox][@checked][@name^=selection]').length && confirm('"."Do you really want to delete these records?"."'))
			frmAdmin.submit(); 
		return false;\"");

}
elseif ($mode==LIST_LOOKUP)
{
//	$xt->assign("checkbox_column",true);
	$xt->assign("inlineadd_link",$allow_add);
	$xt->assign("inlineaddlink_attrs","href=\"lectures_add.php\" onclick=\"return inlineEditing".$id.".inlineAdd(flyid++,".$id.",'lectures_add.php');\"");
//	$xt->assign("inlineedit_column",$allow_edit);
}

$xt->assign("CID_fieldheadercolumn",true);
$xt->assign("CID_fieldcolumn",true);
$xt->assign("CID_fieldfootercolumn",true);
$xt->assign("TID_fieldheadercolumn",true);
$xt->assign("TID_fieldcolumn",true);
$xt->assign("TID_fieldfootercolumn",true);
$xt->assign("Name_fieldheadercolumn",true);
$xt->assign("Name_fieldcolumn",true);
$xt->assign("Name_fieldfootercolumn",true);
$xt->assign("Continue_fieldheadercolumn",true);
$xt->assign("Continue_fieldcolumn",true);
$xt->assign("Continue_fieldfootercolumn",true);
$xt->assign("Type_fieldheadercolumn",true);
$xt->assign("Type_fieldcolumn",true);
$xt->assign("Type_fieldfootercolumn",true);
$xt->assign("Start_fieldheadercolumn",true);
$xt->assign("Start_fieldcolumn",true);
$xt->assign("Start_fieldfootercolumn",true);
$xt->assign("End_fieldheadercolumn",true);
$xt->assign("End_fieldcolumn",true);
$xt->assign("End_fieldfootercolumn",true);
$xt->assign("Time_fieldheadercolumn",true);
$xt->assign("Time_fieldcolumn",true);
$xt->assign("Time_fieldfootercolumn",true);
$xt->assign("Room_fieldheadercolumn",true);
$xt->assign("Room_fieldcolumn",true);
$xt->assign("Room_fieldfootercolumn",true);
$xt->assign("Duration_fieldheadercolumn",true);
$xt->assign("Duration_fieldcolumn",true);
$xt->assign("Duration_fieldfootercolumn",true);
$xt->assign("About_fieldheadercolumn",true);
$xt->assign("About_fieldcolumn",true);
$xt->assign("About_fieldfootercolumn",true);
$xt->assign("Announce_fieldheadercolumn",true);
$xt->assign("Announce_fieldcolumn",true);
$xt->assign("Announce_fieldfootercolumn",true);
	
$display_grid = $allow_add || $allow_search && $rowsfound;

$xt->assign("asearch_link",$allow_search);
$xt->assign("asearchlink_attrs","href=\"lectures_search.php\" onclick=\"window.location.href='lectures_search.php';return false;\"");
$xt->assign("import_link",$allow_import);
$xt->assign("importlink_attrs","href=\"lectures_import.php\" onclick=\"window.location.href='lectures_import.php';return false;\"");

$xt->assign("search_records_block",$allow_search);
$xt->assign("searchform",$allow_search);
$xt->assign("searchform_showall",$allow_search);
if($mode!=LIST_LOOKUP)
{
	$xt->assign("searchform_field",$allow_search);
	$xt->assign("searchform_option",$allow_search);
}
$xt->assign("searchform_text",$allow_search);
$xt->assign("searchform_search",$allow_search);

$xt->assign("usermessage",true);

if($display_grid)
{
	if($mode==LIST_SIMPLE)
		$xt->assign_section("grid_block",
		"<form method=\"POST\" action=\"lectures_list.php\" name=\"frmAdmin\" id=\"frmAdmin\">
		<input type=\"hidden\" id=\"a\" name=\"a\" value=\"delete\">",
		"</form>");
	elseif($mode==LIST_LOOKUP)
		$xt->assign_section("grid_block",
		"<form method=\"POST\" action=\"lectures_list.php\" name=\"frmAdmin".$id."\" id=\"frmAdmin".$id."\" target=\"flyframe".$id."\">
		<input type=\"hidden\" id=\"a".$id."\" name=\"a\" value=\"delete\">",
		"</form>");
	
	$record_header=array("data"=>array());
	$record_footer=array("data"=>array());
	for($i=0;$i<$colsonpage;$i++)
	{
		$rheader=array();
		$rfooter=array();
		if($i<$colsonpage-1)
		{
			$rheader["endrecordheader_block"]=true;
			$rfooter["endrecordfooter_block"]=true;
		}
		$record_header["data"][]=$rheader;
		$record_footer["data"][]=$rfooter;
	}
	$xt->assignbyref("record_header",$record_header);
	$xt->assignbyref("record_footer",$record_footer);
	$xt->assign("grid_header",true);
	$xt->assign("grid_footer",true);

	$xt->assign("record_controls",true);
}

$xt->assign("recordcontrols_block",$allow_add || $display_grid);

$xt->assign("newrecord_controls",$allow_add);

if($mode==LIST_SIMPLE)
{
	$xt->assign("details_block",$allow_search && $rowsfound);
	$xt->assign("recordspp_block",$allow_search && $rowsfound);
	$xt->assign("recordspp_attrs","onchange=\"javascript: document.location='lectures_list.php?pagesize='+this.options[this.selectedIndex].value;\"");
	$xt->assign("pages_block",$allow_search && $rowsfound);
}
else
	$xt->assign("recordspp_attrs","onchange=\"javascript: window.frames['flyframe".$id."'].location='lectures_list.php?".$lookupparams."&pagesize='+this.options[this.selectedIndex].value;\"");
$xt->assign("grid_controls",$display_grid);



//	display Back to Master link and master table info
$masterkeys=array();
if($mastertable=="attendance")
{
	$xt->assign("mastertable_block",true);
	$xt->assign("backtomasterlink_attrs","href=\"attendance_list.php?a=return\"");
}
if($mastertable=="assignments")
{
	$xt->assign("mastertable_block",true);
	$xt->assign("backtomasterlink_attrs","href=\"assignments_list.php?a=return\"");
}
if($mastertable=="marks")
{
	$xt->assign("mastertable_block",true);
	$xt->assign("backtomasterlink_attrs","href=\"marks_list.php?a=return\"");
}

	$code_end.="\$(\".addarea".$id."\").each(function(i) { \$(this).hide();});\r\n";
	$code_end.="if(flyid<".($recid+1).") flyid=".($recid+1).";\r\n";
	if(!$numrows)
	{
		$code_end .= "$('#record_controls".$id."').hide();";
		if($mode==LIST_SIMPLE)
			$code_end .= "$('[@name=maintable]').hide();";
		else
		{
			$code_end .= "$('[@name=maintable]',$('#fly".$id."')).hide();";
		}
	}

$html_end .= "<style>
#inline_error {
	font-family: Verdana, Arial, Helvetica, sans serif;
	font-size: 11px;
	position: absolute;
	background-color: white;
	border: 1px solid red;
	padding: 10px;
	background-repeat: no-repeat;
	display: none;
	}
</style>";
if($mode==LIST_SIMPLE)
	$code_end.="if(!$('[@disptype=control1]').length && $('[@disptype=controltable1]').length)
		$('[@disptype=controltable1]').hide();";
if($_SESSION[$strTableName."_search"]==1)
	$code_end.= "if(document.getElementById('ctlSearchFor".$id."')) document.getElementById('ctlSearchFor".$id."').focus();";

	
if($mode==LIST_SIMPLE)
{
	$body["end"]="<script language=\"javascript\">\$(document).ready(function () {".$code_end."});</script>";
	$body["end"].=$html_end;
}
elseif($mode==LIST_LOOKUP)
{
	$body["end"].=$html_end;
}
$xt->assignbyref("body",$body);
$xt->assign("style_block",true);
$xt->assign("iestyle_block",true);


$strSQL=$_SESSION[$strTableName."_sql"];
$xt->assign("changepwd_link",$_SESSION["AccessLevel"] != ACCESS_LEVEL_GUEST);
$xt->assign("changepwdlink_attrs","href=\"changepwd.php\" onclick=\"window.location.href='changepwd.php';return false;\"");



$xt->assign("quickjump_attrs","onchange=\"window.location.href=this.options[this.selectedIndex].value;\"");

$xt->assign("endrecordblock_attrs","colid=\"endrecord\"");
$templatefile = "lectures_list.htm";
if(function_exists("BeforeShowList"))
	BeforeShowList($xt,$templatefile);

if($mode==LIST_SIMPLE)
	$xt->display($templatefile);
elseif($mode==LIST_LOOKUP)
{
//	$code_end must run after all include files loaded
	$code_end = 'window.Init'.$id.' = function() {
		if('.$includes_vars.') 
		{
		'.$code_end.'
		}
		else setTimeout(Init'.$id.',200);
	};
	Init'.$id.'();';

	if($firsttime)
	{
		echo str_replace(array("\\","\r","\n"),array("\\\\","\\r","\\n"),$code_begin);
		echo str_replace(array("\\","\r","\n"),array("\\\\","\\r","\\n"),$code_end);
		echo "\n";
	}
	else
	{
		echo "<textarea id=data>decli";
		echo htmlspecialchars($code_begin);
		echo htmlspecialchars($code_end);
		echo "</textarea>";
	}
	$xt->load_template($templatefile);
	$xt->display_loaded("style_block");
	$xt->display_loaded("iestyle_block");
	$xt->display_loaded("body");
}


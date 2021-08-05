<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0); 

include("include/dbcommon.php");
include("include/classes_variables.php");



$filename="";
$status="";
$message="";
$usermessage="";
$error_happened=false;
$readavalues=false;


$showKeys = array();
$showValues = array();
$showRawValues = array();
$showFields = array();
$showDetailKeys = array();
$IsSaved = false;
$HaveData = true;

if(@$_REQUEST["editType"]=="inline")
	$inlineedit=ADD_INLINE;
elseif(@$_REQUEST["editType"]=="onthefly")
	$inlineedit=ADD_ONTHEFLY;
else
	$inlineedit=ADD_SIMPLE;
$keys=array();
if($inlineedit==ADD_INLINE)
	$templatefile = "classes_inline_add.htm";
else
	$templatefile = "classes_add.htm";

$id=postvalue("id");
	
//connect database
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessAdd"))
	BeforeProcessAdd($conn);

include('libs/xtempl.php');
$xt = new Xtempl();

// insert new record if we have to

if(@$_POST["a"]=="added")
{
	$afilename_values=array();
	$avalues=array();
	$files_move=array();
	$files_save=array();
//	processing CID - start
	$value = postvalue("value_CID");
	$type=postvalue("type_CID");
	if (in_assoc_array("type_CID",$_POST) || in_assoc_array("value_CID",$_POST) || in_assoc_array("value_CID",$_FILES))
	{
		$value=prepare_for_db("CID",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["CID"]=$value;
	}
//	processibng CID - end
//	processing Session - start
	$value = postvalue("value_Session");
	$type=postvalue("type_Session");
	if (in_assoc_array("type_Session",$_POST) || in_assoc_array("value_Session",$_POST) || in_assoc_array("value_Session",$_FILES))
	{
		$value=prepare_for_db("Session",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Session"]=$value;
	}
//	processibng Session - end
//	processing Semester - start
	$value = postvalue("value_Semester");
	$type=postvalue("type_Semester");
	if (in_assoc_array("type_Semester",$_POST) || in_assoc_array("value_Semester",$_POST) || in_assoc_array("value_Semester",$_FILES))
	{
		$value=prepare_for_db("Semester",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Semester"]=$value;
	}
//	processibng Semester - end
//	processing Subjects - start
	$value = postvalue("value_Subjects");
	$type=postvalue("type_Subjects");
	if (in_assoc_array("type_Subjects",$_POST) || in_assoc_array("value_Subjects",$_POST) || in_assoc_array("value_Subjects",$_FILES))
	{
		$value=prepare_for_db("Subjects",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Subjects"]=$value;
	}
//	processibng Subjects - end




//	insert masterkey value if exists and if not specified
	if(@$_SESSION[$strTableName."_mastertable"]=="students")
	{
		$avalues["CID"]=prepare_for_db("CID",$_SESSION[$strTableName."_masterkey1"]);
	}

if($inlineedit==ADD_ONTHEFLY || true)
{
}


//	add filenames to values
	foreach($afilename_values as $akey=>$value)
		$avalues[$akey]=$value;
//	make SQL string
	$strSQL = "insert into ".AddTableWrappers($strOriginalTableName)." ";
	$strFields="(";
	$strValues="(";
	
//	before Add event
	$retval = true;
	if(function_exists("BeforeAdd"))
		$retval=BeforeAdd($avalues,$usermessage,$inlineedit);
	if($retval)
	{
		foreach($avalues as $akey=>$value)
		{
			$strFields.=AddFieldWrappers($akey).", ";
			$strValues.=add_db_quotes($akey,$value).", ";
		}
		if(substr($strFields,-2)==", ")
			$strFields=substr($strFields,0,strlen($strFields)-2);
		if(substr($strValues,-2)==", ")
			$strValues=substr($strValues,0,strlen($strValues)-2);
		$strSQL.=$strFields.") values ".$strValues.")";
		LogInfo($strSQL);
		set_error_handler("add_error_handler");
		db_exec($strSQL,$conn);
		set_error_handler("error_handler");
//	move files
		if(!$error_happened)
		{
			foreach ($files_move as $file)
			{
				move_uploaded_file($file[0],$file[1]);
				if(strtoupper(substr(PHP_OS,0,3))!="WIN")
					@chmod($file[1],0777);
			}
			foreach($files_save as $file)
			{
				if(file_exists($file["filename"]))
						@unlink($file["filename"]);
				$th = fopen($file["filename"],"w");
				fwrite($th,$file["file"]);
				fclose($th);
			}
			if ( $inlineedit==ADD_INLINE ) 
			{
				$status="ADDED";
				$message=""."Record was added"."";
				$IsSaved = true;
			} 
			else
				$message="<div class=message><<< "."Record was added"." >>></div>";
			if($usermessage!="")
				$message = $usermessage;
if($inlineedit==ADD_INLINE || $inlineedit==ADD_ONTHEFLY || function_exists("AfterAdd"))
{

	$failed_inline_add = false;
		$keys["CID"]=$avalues["CID"];
}	

//	after edit event
			if(function_exists("AfterAdd"))
			{
				foreach($keys as $idx=>$val)
					$avalues[$idx]=$val;
				AfterAdd($avalues,$keys,$inlineedit);
			}
		}
	}
	else
	{
		$message = $usermessage;
		$status="DECLINED";
		$readavalues=true;
	}
}

$defvalues=array();


//	copy record
if(array_key_exists("copyid1",$_REQUEST) || array_key_exists("editid1",$_REQUEST))
{
	$copykeys=array();
	if(array_key_exists("copyid1",$_REQUEST))
	{
		$copykeys["CID"]=postvalue("copyid1");
	}
	else
	{
		$copykeys["CID"]=postvalue("editid1");
	}
	$strWhere=KeyWhere($copykeys);
	$strSQL = gSQLWhere($strWhere);

	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
	$defvalues=db_fetch_array($rs);
//	clear key fields
	$defvalues["CID"]="";
//call CopyOnLoad event
	if(function_exists("CopyOnLoad"))
		CopyOnLoad($defvalues,$strWhere);
}
else
{
}

//	set default values for the foreign keys
if(@$_SESSION[$strTableName."_mastertable"]=="students")
{
	$defvalues["CID"]=@$_SESSION[$strTableName."_masterkey1"];
}

if($inlineedit==ADD_ONTHEFLY || true)
{
}
if($readavalues)
{
	$defvalues["CID"]=@$avalues["CID"];
	$defvalues["Session"]=@$avalues["Session"];
	$defvalues["Semester"]=@$avalues["Semester"];
	$defvalues["Subjects"]=@$avalues["Subjects"];
}

/*
foreach($defvalues as $key=>$value)
	$smarty->assign("value_".GoodFieldName($key),$value);
*/

$linkdata="";
$includes="";
$arr_includes=array();
$bodyonload="";
	
if ( $inlineedit!=ADD_INLINE ) 
{
	//	include files

	//	validation stuff
	$onsubmit="";
	$needvalidate=false;

	if($needvalidate)
	{
		if($inlineedit==ADD_ONTHEFLY)
			$onsubmit="return validate_fly(this);";
		else
			$onsubmit="return validate();";
//		$bodyonload="onload=\"".$bodyonload."\"";
	}

	if($inlineedit!=ADD_ONTHEFLY)
	{
		$includes.="<script language=\"JavaScript\" src=\"include/jquery.js\"></script>\r\n";
		$includes.="<script language=\"JavaScript\" src=\"include/onthefly.js\"></script>\r\n";
		if ($useAJAX) 
			$includes.="<script language=\"JavaScript\" src=\"include/ajaxsuggest.js\"></script>\r\n";
		$includes.="<script language=\"JavaScript\" src=\"include/jsfunctions.js\"></script>\r\n";
	}
	if($inlineedit!=ADD_ONTHEFLY)
	{
		$includes.="<script language=\"JavaScript\">\r\n";
	}
	$includes.="var locale_dateformat = ".$locale_info["LOCALE_IDATE"].";\r\n".
	"var locale_datedelimiter = \"".$locale_info["LOCALE_SDATE"]."\";\r\n".
	"var bLoading=false;\r\n".
	"var TEXT_PLEASE_SELECT='".addslashes("Please select")."';\r\n";
	if ($useAJAX) {
	$includes.="var SUGGEST_TABLE='classes_searchsuggest.php';\r\n";
	}
	if($inlineedit!=ADD_ONTHEFLY)
	{
		$includes.="</script>\r\n";
		if ($useAJAX)
			$includes.="<div id=\"search_suggest\"></div>\r\n";
	}




	$xt->assign("CID_fieldblock",true);
	$xt->assign("Session_fieldblock",true);
	$xt->assign("Semester_fieldblock",true);
	$xt->assign("Subjects_fieldblock",true);
	
	$body=array();
	$formname="editform";
	if($inlineedit!=ADD_ONTHEFLY)
	{
		if($onsubmit)
			$onsubmit="onsubmit=\"".$onsubmit."\"";
		$body["begin"]=$includes.
		"<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"classes_add.php\" ".$onsubmit.">".
		"<input type=hidden name=\"a\" value=\"added\">";
		$xt->assign("backbutton_attrs","onclick=\"window.location.href='classes_list.php?a=return'\"");
		$xt->assign("back_button",true);
	}
	else
	{
		$formname="editform".$id;
		$body["begin"]="<form name=\"editform".$id."\" encType=\"multipart/form-data\" method=\"post\" action=\"classes_add.php\" ".$onsubmit." target=\"flyframe".$id."\">".
		"<input type=hidden name=\"a\" value=\"added\">".
		"<input type=hidden name=\"editType\" value=\"onthefly\">".
		"<input type=hidden name=\"table\" value=\"".postvalue("table")."\">".
		"<input type=hidden name=\"field\" value=\"".postvalue("field")."\">".
		"<input type=hidden name=\"category\" value=\"".postvalue("category")."\">".
		"<input type=hidden name=\"id\" value=\"".$id."\">";
		$xt->assign("cancelbutton_attrs","onclick=\"RemoveFlyDiv('".$id."');\"");
//		$xt->assign("cancelbutton_attrs","onclick=\"RemoveFlyDiv('".substr($id,3)."');\"");
		$xt->assign("cancel_button",true);
	}
	$xt->assign("save_button",true);
	$xt->assign("reset_button",true);
}

if($message)
{
	$xt->assign("message_block",true);
	$xt->assign("message",$message);
}
//$xt->assign("status",$status);

$readonlyfields=array();

//	show readonly fields


$record_id= postvalue("recordID");

if ($useAJAX) 
{
	if($inlineedit==ADD_ONTHEFLY)
		$record_id=$id;

	if ( $inlineedit==ADD_INLINE ) 
	{
		$linkdata=str_replace(array("&","<",">"),array("&amp;","&lt;","&gt;"),$linkdata);

	} 
	else 
	{
		$linkdata.="SetToFirstControl('".$formname."');";
		if($inlineedit==ADD_SIMPLE)
		{
			$linkdata = "<script type=\"text/javascript\">\r\n".
			"$(document).ready(function(){ \r\n".
			$linkdata.
			"});</script>";
		}
		else
		{
			$linkdata=$includes."\r\n".$linkdata;
			$includes="var s;";
			foreach($arr_includes as $file)
			{
				$includes.="s = document.createElement('script');s.src = '".$file."';\r\n".
				"document.getElementsByTagName('HEAD')[0].appendChild(s);\r\n";
			}			
			$linkdata=$includes."\r\n".$linkdata;

			if(!@$_POST["a"]=="added")
			{
				$linkdata = str_replace(array("\\","\r","\n"),array("\\\\","\\r","\\n"),$linkdata);
				echo $linkdata;
				echo "\n";
			}
			else if(@$_POST["a"]=="added" && ($error_happened || $status=="DECLINED"))
			{
				echo "<textarea id=\"data\">decli";
				echo htmlspecialchars($linkdata);
				echo "</textarea>";
			}

		}
	}
} 
else 
{
}

if($inlineedit!=ADD_ONTHEFLY)
{
	$body["end"]="</form>".$linkdata.
	"<script>".$bodyonload."</script>";
	
	$xt->assign("body",$body);
	$xt->assign("flybody",true);
}
else
{
	$xt->assign("flybody",$body);
	$xt->assign("body",true);
}




if(@$_POST["a"]=="added" && $inlineedit==ADD_ONTHEFLY && !$error_happened && $status!="DECLINED")
{
	$LookupSQL="";
	if($LookupSQL)
		$LookupSQL.=" from ".AddTableWrappers($strOriginalTableName);

	$data=0;
	if(count($keys) && $LookupSQL)
	{
		$where=KeyWhere($keys);
		$LookupSQL.=" where ".$where;
		$rs=db_query($LookupSQL,$conn);
		$data=db_fetch_numarray($rs);
	}
	if(!$data)
	{
		$data=array(@$avalues[$linkfield],@$avalues[$dispfield]);
	}
	echo "<textarea id=\"data\">";
	echo "added";
	print_inline_array($data);
	echo "</textarea>";
	exit();
}


if ( @$_POST["a"]=="added" && $inlineedit==ADD_INLINE ) 
{

	//Preparation   view values
	//	get current values and show edit controls

	$data=0;
	if(count($keys))
	{

		$where=KeyWhere($keys);
			$strSQL = gSQLWhere($where);

		LogInfo($strSQL);

		$rs=db_query($strSQL,$conn);
		$data=db_fetch_array($rs);
	}
	if(!$data)
	{
		$data=$avalues;
		$HaveData=false;
	}

	//check if correct values added

	
	
	$showKeys[] = htmlspecialchars($keys["CID"]);

	$keylink="";
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["CID"]));

//	foreach Fields as @f filter @f.bListPage order @f.nListPageOrder

	////////////////////////////////////////////
	//	CID - 
		$value="";
				$value = ProcessLargeText(GetData($data,"CID", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "CID";
				$showRawValues[] = substr($data["CID"],0,100);
	////////////////////////////////////////////
	//	Session - 
		$value="";
				$value = ProcessLargeText(GetData($data,"Session", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "Session";
				$showRawValues[] = substr($data["Session"],0,100);
	////////////////////////////////////////////
	//	Semester - 
		$value="";
				$value = ProcessLargeText(GetData($data,"Semester", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "Semester";
				$showRawValues[] = substr($data["Semester"],0,100);
	////////////////////////////////////////////
	//	Subjects - 
		$value="";
				$value = ProcessLargeText(GetData($data,"Subjects", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "Subjects";
				$showRawValues[] = substr($data["Subjects"],0,100);
}

if ( @$_POST["a"]=="added" && $inlineedit==ADD_INLINE ) 
{
	echo "<textarea id=\"data\">";
	if($IsSaved && count($showValues))
	{
		if($HaveData)
			echo "saved";
		else
			echo "savnd";
		print_inline_array($showKeys);
		echo "\n";
		print_inline_array($showValues);
		echo "\n";
		print_inline_array($showFields);
		echo "\n";
		print_inline_array($showRawValues);
		echo "\n";
		print_inline_array($showDetailKeys,true);
		echo "\n";
		print_inline_array($showDetailKeys);
		echo "\n";
		echo str_replace(array("&","<","\\","\r","\n"),array("&amp;","&lt;","\\\\","\\r","\\n"),$usermessage);
	}
	else
	{
		if($status=="DECLINED")
			echo "decli";
		else
			echo "error";
		echo str_replace(array("&","<","\\","\r","\n"),array("&amp;","&lt;","\\\\","\\r","\\n"),$message);
	}
	echo "</textarea>";
	exit();
} 

/////////////////////////////////////////////////////////////
//	prepare Edit Controls
/////////////////////////////////////////////////////////////
$control_CID=array();
$control_CID["func"]="xt_buildeditcontrol";
$control_CID["params"] = array();
$control_CID["params"]["field"]="CID";
$control_CID["params"]["value"]=@$defvalues["CID"];
$control_CID["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_CID["params"]["mode"]="inline_add";
else
	$control_CID["params"]["mode"]="add";
$xt->assignbyref("CID_editcontrol",$control_CID);
$control_Session=array();
$control_Session["func"]="xt_buildeditcontrol";
$control_Session["params"] = array();
$control_Session["params"]["field"]="Session";
$control_Session["params"]["value"]=@$defvalues["Session"];
$control_Session["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Session["params"]["mode"]="inline_add";
else
	$control_Session["params"]["mode"]="add";
$xt->assignbyref("Session_editcontrol",$control_Session);
$control_Semester=array();
$control_Semester["func"]="xt_buildeditcontrol";
$control_Semester["params"] = array();
$control_Semester["params"]["field"]="Semester";
$control_Semester["params"]["value"]=@$defvalues["Semester"];
$control_Semester["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Semester["params"]["mode"]="inline_add";
else
	$control_Semester["params"]["mode"]="add";
$xt->assignbyref("Semester_editcontrol",$control_Semester);
$control_Subjects=array();
$control_Subjects["func"]="xt_buildeditcontrol";
$control_Subjects["params"] = array();
$control_Subjects["params"]["field"]="Subjects";
$control_Subjects["params"]["value"]=@$defvalues["Subjects"];
$control_Subjects["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Subjects["params"]["mode"]="inline_add";
else
	$control_Subjects["params"]["mode"]="add";
$xt->assignbyref("Subjects_editcontrol",$control_Subjects);

$xt->assign("style_block",true);

if(function_exists("BeforeShowAdd"))
	BeforeShowAdd($xt,$templatefile);


if($inlineedit==ADD_ONTHEFLY)
{
	$xt->load_template($templatefile);
	$xt->display_loaded("style_block");
	$xt->display_loaded("flybody");
}
else
	$xt->display($templatefile);

function add_error_handler($errno, $errstr, $errfile, $errline)
{
	global $readavalues, $message, $status, $inlineedit, $error_happened;
	if ( $inlineedit!=ADD_SIMPLE ) 
		$message=""."Record was NOT added".". ".$errstr;
	else  
		$message="<div class=message><<< "."Record was NOT added"." >>><br><br>".$errstr."</div>";
	$readavalues=true;
	$error_happened=true;
}
?>

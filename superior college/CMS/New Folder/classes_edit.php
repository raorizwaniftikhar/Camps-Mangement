<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/classes_variables.php");



/////////////////////////////////////////////////////////////
//init variables
/////////////////////////////////////////////////////////////


$filename="";
$status="";
$message="";
$usermessage="";
$error_happened=false;
$readevalues=false;
$bodyonload="";


$body=array();
$showKeys = array();
$showValues = array();
$showRawValues = array();
$showFields = array();
$showDetailKeys = array();
$IsSaved = false;
$HaveData = true;
$inlineedit = (@$_REQUEST["editType"]=="inline") ? true : false;
$templatefile = ( $inlineedit ) ? "classes_inline_edit.htm" : "classes_edit.htm";

/////////////////////////////////////////////////////////////
//connect database
/////////////////////////////////////////////////////////////
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessEdit"))
	BeforeProcessEdit($conn);

$keys=array();
$keys["CID"]=postvalue("editid1");

/////////////////////////////////////////////////////////////
//	process entered data, read and save
/////////////////////////////////////////////////////////////

if(@$_POST["a"]=="edited")
{
	$strWhereClause=KeyWhere($keys);
	$strSQL = "update ".AddTableWrappers($strOriginalTableName)." set ";
	$evalues=array();
	$efilename_values=array();
	$files_delete=array();
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
	if($value!==false)
	{	



		$evalues["CID"]=$value;
	}

//	update key value
	if($value!==false)
		$keys["CID"]=$value;

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
	if($value!==false)
	{	



		$evalues["Session"]=$value;
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
	if($value!==false)
	{	



		$evalues["Semester"]=$value;
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
	if($value!==false)
	{	



		$evalues["Subjects"]=$value;
	}


//	processibng Subjects - end

	foreach($efilename_values as $ekey=>$value)
		$evalues[$ekey]=$value;
//	do event
	$retval=true;
	if(function_exists("BeforeEdit"))
		$retval=BeforeEdit($evalues,$strWhereClause,$dataold,$keys,$usermessage,$inlineedit);
	if($retval)
	{		
//	construct SQL string
		foreach($evalues as $ekey=>$value)
		{
			$strSQL.=AddFieldWrappers($ekey)."=".add_db_quotes($ekey,$value).", ";
		}
		if(substr($strSQL,-2)==", ")
			$strSQL=substr($strSQL,0,strlen($strSQL)-2);
		$strSQL.=" where ".$strWhereClause;
		set_error_handler("edit_error_handler");
		db_exec($strSQL,$conn);
		set_error_handler("error_handler");
		if(!$error_happened)
		{
//	delete & move files
			foreach ($files_delete as $file)
			{
				if(file_exists($file))
					@unlink($file);
			}
			foreach($files_move as $file)
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
			
			if ( $inlineedit ) 
			{
				$status="UPDATED";
				$message=""."Record updated"."";
				$IsSaved = true;
			} 
			else 
				$message="<div class=message><<< "."Record updated"." >>></div>";
			if($usermessage!="")
				$message = $usermessage;
//	after edit event
			if(function_exists("AfterEdit"))
			{
				foreach($dataold as $idx=>$val)
				{
					if(!array_key_exists($idx,$evalues))
						$evalues[$idx]=$val;
				}
				AfterEdit($evalues,KeyWhere($keys),$dataold,$keys,$inlineedit);
			}
		}
	}
	else
	{
		$readevalues=true;
		$message = $usermessage;
		$status="DECLINED";
	}
}

/////////////////////////////////////////////////////////////
//	read current values from the database
/////////////////////////////////////////////////////////////

$strWhereClause=KeyWhere($keys);

$strSQL=gSQLWhere($strWhereClause);

$strSQLbak = $strSQL;
//	Before Query event
if(function_exists("BeforeQueryEdit"))
	BeforeQueryEdit($strSQL,$strWhereClause);

if($strSQLbak == $strSQL)
	$strSQL=gSQLWhere($strWhereClause);
LogInfo($strSQL);
$rs=db_query($strSQL,$conn);
$data=db_fetch_array($rs);

if($readevalues)
{
	$data["CID"]=$evalues["CID"];
	$data["Session"]=$evalues["Session"];
	$data["Semester"]=$evalues["Semester"];
	$data["Subjects"]=$evalues["Subjects"];
}

/////////////////////////////////////////////////////////////
//	assign values to $xt class, prepare page for displaying
/////////////////////////////////////////////////////////////

include('libs/xtempl.php');
$xt = new Xtempl();

if ( !$inlineedit ) {
	//	include files
	$includes="";

	//	validation stuff
	$onsubmit="";

	if($bodyonload)
		$onsubmit="return validate();";

	$includes.="<script language=\"JavaScript\" src=\"include/jquery.js\"></script>\r\n";
	$includes.="<script language=\"JavaScript\" src=\"include/onthefly.js\"></script>\r\n";
	if ($useAJAX) 
	{
		$includes.="<script language=\"JavaScript\" src=\"include/ajaxsuggest.js\"></script>\r\n";
	}
	$includes.="<script language=\"JavaScript\" src=\"include/jsfunctions.js\"></script>\r\n";
	$includes.="<script language=\"JavaScript\">\r\n";
	$includes .= "var locale_dateformat = ".$locale_info["LOCALE_IDATE"].";\r\n".
	"var locale_datedelimiter = \"".$locale_info["LOCALE_SDATE"]."\";\r\n".
	"var bLoading=false;\r\n".
	"var TEXT_PLEASE_SELECT='".addslashes("Please select")."';\r\n";
	if ($useAJAX) {
	$includes.="var SUGGEST_TABLE='classes_searchsuggest.php';\r\n";
	}
	$includes.="</script>\r\n";

	if ($useAJAX)
		$includes.="<div id=\"search_suggest\"></div>\r\n";





	$xt->assign("CID_fieldblock",true);
	$xt->assign("Session_fieldblock",true);
	$xt->assign("Semester_fieldblock",true);
	$xt->assign("Subjects_fieldblock",true);

	if(strlen($onsubmit))
		$onsubmit="onSubmit=\"".$onsubmit."\"";
	$body["begin"]=$includes."
	<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"classes_edit.php\" ".$onsubmit.">".
	"<input type=hidden name=\"a\" value=\"edited\">";
	$body["begin"].="<input type=\"hidden\" name=\"editid1\" value=\"".htmlspecialchars($keys["CID"])."\">";
		$xt->assign("show_key1", htmlspecialchars(GetData($data,"CID", "")));

	$xt->assign("backbutton_attrs","onclick=\"window.location.href='classes_list.php?a=return'\"");
	$xt->assign("save_button",true);
	$xt->assign("reset_button",true);
	$xt->assign("back_button",true);
}

$showKeys[] = rawurlencode($keys["CID"]);

if($message)
{
	$xt->assign("message_block",true);
	$xt->assign("message",$message);
}

/////////////////////////////////////////////////////////////
//process readonly and auto-update fields
/////////////////////////////////////////////////////////////

$readonlyfields=array();



$linkdata="";

if ($useAJAX) 
{
	$record_id= postvalue("recordID");

	if ( $inlineedit ) 
	{

		$linkdata=str_replace(array("&","<",">"),array("&amp;","&lt;","&gt;"),$linkdata);

		$xt->assignbyref("linkdata",$linkdata);
	} 
	else
	{
		$linkdata = "<script type=\"text/javascript\">\r\n".
		"$(document).ready(function(){ \r\n".
		$linkdata.
		"});</script>";
	}
} 
else 
{
}

$body["end"]="</form>".$linkdata.
"<script>".$bodyonload."</script>".
"<script>SetToFirstControl('editform');</script>";
$xt->assignbyref("body",$body);

/////////////////////////////////////////////////////////////
//	return new data to the List page or report an error
/////////////////////////////////////////////////////////////

if ($_REQUEST["a"]=="edited" && $inlineedit ) 
{
	if(!$data)
	{
		$data=$evalues;
		$HaveData=false;
	}
	//Preparation   view values

//	detail tables

	$keylink="";
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["CID"]));


//	CID - 

		$value="";
				$value = ProcessLargeText(GetData($data,"CID", ""),"","",MODE_LIST);
//		$smarty->assign("show_CID",$value);
		$showValues[] = $value;
		$showFields[] = "CID";
				$showRawValues[] = substr($data["CID"],0,100);

//	Session - 

		$value="";
				$value = ProcessLargeText(GetData($data,"Session", ""),"","",MODE_LIST);
//		$smarty->assign("show_Session",$value);
		$showValues[] = $value;
		$showFields[] = "Session";
				$showRawValues[] = substr($data["Session"],0,100);

//	Semester - 

		$value="";
				$value = ProcessLargeText(GetData($data,"Semester", ""),"","",MODE_LIST);
//		$smarty->assign("show_Semester",$value);
		$showValues[] = $value;
		$showFields[] = "Semester";
				$showRawValues[] = substr($data["Semester"],0,100);

//	Subjects - 

		$value="";
				$value = ProcessLargeText(GetData($data,"Subjects", ""),"","",MODE_LIST);
//		$smarty->assign("show_Subjects",$value);
		$showValues[] = $value;
		$showFields[] = "Subjects";
				$showRawValues[] = substr($data["Subjects"],0,100);

/////////////////////////////////////////////////////////////
//	start inline output
/////////////////////////////////////////////////////////////

	echo "<textarea id=\"data\">";
	if($IsSaved)
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
$control_CID["params"]["value"]=@$data["CID"];
$control_CID["params"]["id"]=$record_id;
if($inlineedit)
	$control_CID["params"]["mode"]="inline_edit";
else
	$control_CID["params"]["mode"]="edit";
$xt->assignbyref("CID_editcontrol",$control_CID);
$control_Session=array();
$control_Session["func"]="xt_buildeditcontrol";
$control_Session["params"] = array();
$control_Session["params"]["field"]="Session";
$control_Session["params"]["value"]=@$data["Session"];
$control_Session["params"]["id"]=$record_id;
if($inlineedit)
	$control_Session["params"]["mode"]="inline_edit";
else
	$control_Session["params"]["mode"]="edit";
$xt->assignbyref("Session_editcontrol",$control_Session);
$control_Semester=array();
$control_Semester["func"]="xt_buildeditcontrol";
$control_Semester["params"] = array();
$control_Semester["params"]["field"]="Semester";
$control_Semester["params"]["value"]=@$data["Semester"];
$control_Semester["params"]["id"]=$record_id;
if($inlineedit)
	$control_Semester["params"]["mode"]="inline_edit";
else
	$control_Semester["params"]["mode"]="edit";
$xt->assignbyref("Semester_editcontrol",$control_Semester);
$control_Subjects=array();
$control_Subjects["func"]="xt_buildeditcontrol";
$control_Subjects["params"] = array();
$control_Subjects["params"]["field"]="Subjects";
$control_Subjects["params"]["value"]=@$data["Subjects"];
$control_Subjects["params"]["id"]=$record_id;
if($inlineedit)
	$control_Subjects["params"]["mode"]="inline_edit";
else
	$control_Subjects["params"]["mode"]="edit";
$xt->assignbyref("Subjects_editcontrol",$control_Subjects);

/////////////////////////////////////////////////////////////
//display the page
/////////////////////////////////////////////////////////////

if(function_exists("BeforeShowEdit"))
	BeforeShowEdit($xt,$templatefile);
$xt->display($templatefile);

function edit_error_handler($errno, $errstr, $errfile, $errline)
{
	global $readevalues, $message, $status, $inlineedit, $error_happened;
	if ( $inlineedit ) 
		$message=""."Record was NOT edited".". ".$errstr;
	else  
		$message="<div class=message><<< "."Record was NOT edited"." >>><br><br>".$errstr."</div>";
	$readevalues=true;
	$error_happened=true;
}

?>
<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/attendance_variables.php");



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
$templatefile = ( $inlineedit ) ? "attendance_inline_edit.htm" : "attendance_edit.htm";

/////////////////////////////////////////////////////////////
//connect database
/////////////////////////////////////////////////////////////
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessEdit"))
	BeforeProcessEdit($conn);

$keys=array();
$keys["ATID"]=postvalue("editid1");

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
//	processing SID - start
	$value = postvalue("value_SID");
	$type=postvalue("type_SID");
	if (in_assoc_array("type_SID",$_POST) || in_assoc_array("value_SID",$_POST) || in_assoc_array("value_SID",$_FILES))	
	{
		$value=prepare_for_db("SID",$value,$type);
	}
	else
		$value=false;
	if($value!==false)
	{	



		$evalues["SID"]=$value;
	}


//	processibng SID - end
//	processing LRID - start
	$value = postvalue("value_LRID");
	$type=postvalue("type_LRID");
	if (in_assoc_array("type_LRID",$_POST) || in_assoc_array("value_LRID",$_POST) || in_assoc_array("value_LRID",$_FILES))	
	{
		$value=prepare_for_db("LRID",$value,$type);
	}
	else
		$value=false;
	if($value!==false)
	{	



		$evalues["LRID"]=$value;
	}


//	processibng LRID - end
//	processing Daate - start
	$value = postvalue("value_Daate");
	$type=postvalue("type_Daate");
	if (in_assoc_array("type_Daate",$_POST) || in_assoc_array("value_Daate",$_POST) || in_assoc_array("value_Daate",$_FILES))	
	{
		$value=prepare_for_db("Daate",$value,$type);
	}
	else
		$value=false;
	if($value!==false)
	{	



		$evalues["Daate"]=$value;
	}


//	processibng Daate - end

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
	$data["SID"]=$evalues["SID"];
	$data["LRID"]=$evalues["LRID"];
	$data["Daate"]=$evalues["Daate"];
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
		$includes.="<script language=\"JavaScript\" src=\"include/validate.js\"></script>\r\n";
	$includes.="<script language=\"JavaScript\">\r\n";
	$includes.="var TEXT_FIELDS_REQUIRED='".addslashes("The Following fields are Required")."';\r\n";
	$includes.="var TEXT_FIELDS_ZIPCODES='".addslashes("The Following fields must be valid Zipcodes")."';\r\n";
	$includes.="var TEXT_FIELDS_EMAILS='".addslashes("The Following fields must be valid Emails")."';\r\n";
	$includes.="var TEXT_FIELDS_NUMBERS='".addslashes("The Following fields must be Numbers")."';\r\n";
	$includes.="var TEXT_FIELDS_CURRENCY='".addslashes("The Following fields must be currency")."';\r\n";
	$includes.="var TEXT_FIELDS_PHONE='".addslashes("The Following fields must be Phone Numbers")."';\r\n";
	$includes.="var TEXT_FIELDS_PASSWORD1='".addslashes("The Following fields must be valid Passwords")."';\r\n";
	$includes.="var TEXT_FIELDS_PASSWORD2='".addslashes("should be at least 4 characters long")."';\r\n";
	$includes.="var TEXT_FIELDS_PASSWORD3='".addslashes("Cannot be 'password'")."';\r\n";
	$includes.="var TEXT_FIELDS_STATE='".addslashes("The Following fields must be State Names")."';\r\n";
	$includes.="var TEXT_FIELDS_SSN='".addslashes("The Following fields must be Social Security Numbers")."';\r\n";
	$includes.="var TEXT_FIELDS_DATE='".addslashes("The Following fields must be valid dates")."';\r\n";
	$includes.="var TEXT_FIELDS_TIME='".addslashes("The Following fields must be valid time in 24-hours format")."';\r\n";
	$includes.="var TEXT_FIELDS_CC='".addslashes("The Following fields must be valid Credit Card Numbers")."';\r\n";
	$includes.="var TEXT_FIELDS_SSN='".addslashes("The Following fields must be Social Security Numbers")."';\r\n";
	$includes.="</script>\r\n";
			$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
//			$bodyonload.="define('value_SID','".$validatetype."','Student Name');";
			$bodyonload.="define('value_SID','".$validatetype."','".jsreplace("Student Name")."');";
				$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
//			$bodyonload.="define('value_LRID','".$validatetype."','Lecture Name');";
			$bodyonload.="define('value_LRID','".$validatetype."','".jsreplace("Lecture Name")."');";
				$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
//			$bodyonload.="define('value_Daate','".$validatetype."','On Date');";
			$bodyonload.="define('value_Daate','".$validatetype."','".jsreplace("On Date")."');";

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
	$includes.="var SUGGEST_TABLE='attendance_searchsuggest.php';\r\n";
	}
	$includes.="</script>\r\n";

	if ($useAJAX)
		$includes.="<div id=\"search_suggest\"></div>\r\n";

		//	include datepicker files
	$includes.="<script language=\"JavaScript\" src=\"include/calendar.js\"></script>\r\n";




	$xt->assign("SID_fieldblock",true);
	$xt->assign("LRID_fieldblock",true);
	$xt->assign("Daate_fieldblock",true);

	if(strlen($onsubmit))
		$onsubmit="onSubmit=\"".$onsubmit."\"";
	$body["begin"]=$includes."
	<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"attendance_edit.php\" ".$onsubmit.">".
	"<input type=hidden name=\"a\" value=\"edited\">";
	$body["begin"].="<input type=\"hidden\" name=\"editid1\" value=\"".htmlspecialchars($keys["ATID"])."\">";
		$xt->assign("show_key1", htmlspecialchars(GetData($data,"ATID", "")));

	$xt->assign("backbutton_attrs","onclick=\"window.location.href='attendance_list.php?a=return'\"");
	$xt->assign("save_button",true);
	$xt->assign("reset_button",true);
	$xt->assign("back_button",true);
}

$showKeys[] = rawurlencode($keys["ATID"]);

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
	$masterquery="mastertable=attendance";
	$masterquery.="&masterkey1=".rawurlencode($data["LRID"]);
	$showDetailKeys["lectures"]=$masterquery;
	$masterquery="mastertable=attendance";
	$masterquery.="&masterkey1=".rawurlencode($data["SID"]);
	$showDetailKeys["students"]=$masterquery;

	$keylink="";
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["ATID"]));


//	LRID - 

		$value="";
				$value=DisplayLookupWizard("LRID",$data["LRID"],$data,$keylink,MODE_LIST);
//		$smarty->assign("show_LRID",$value);
		$showValues[] = $value;
		$showFields[] = "LRID";
				$showRawValues[] = substr($data["LRID"],0,100);

//	SID - 

		$value="";
				$value=DisplayLookupWizard("SID",$data["SID"],$data,$keylink,MODE_LIST);
//		$smarty->assign("show_SID",$value);
		$showValues[] = $value;
		$showFields[] = "SID";
				$showRawValues[] = substr($data["SID"],0,100);

//	Daate - Short Date

		$value="";
				$value = ProcessLargeText(GetData($data,"Daate", "Short Date"),"","",MODE_LIST);
//		$smarty->assign("show_Daate",$value);
		$showValues[] = $value;
		$showFields[] = "Daate";
				$showRawValues[] = substr($data["Daate"],0,100);

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
$control_SID=array();
$control_SID["func"]="xt_buildeditcontrol";
$control_SID["params"] = array();
$control_SID["params"]["field"]="SID";
$control_SID["params"]["value"]=@$data["SID"];
$control_SID["params"]["id"]=$record_id;
if($inlineedit)
	$control_SID["params"]["mode"]="inline_edit";
else
	$control_SID["params"]["mode"]="edit";
$xt->assignbyref("SID_editcontrol",$control_SID);
$control_LRID=array();
$control_LRID["func"]="xt_buildeditcontrol";
$control_LRID["params"] = array();
$control_LRID["params"]["field"]="LRID";
$control_LRID["params"]["value"]=@$data["LRID"];
$control_LRID["params"]["id"]=$record_id;
if($inlineedit)
	$control_LRID["params"]["mode"]="inline_edit";
else
	$control_LRID["params"]["mode"]="edit";
$xt->assignbyref("LRID_editcontrol",$control_LRID);
$control_Daate=array();
$control_Daate["func"]="xt_buildeditcontrol";
$control_Daate["params"] = array();
$control_Daate["params"]["field"]="Daate";
$control_Daate["params"]["value"]=@$data["Daate"];
$control_Daate["params"]["id"]=$record_id;
if($inlineedit)
	$control_Daate["params"]["mode"]="inline_edit";
else
	$control_Daate["params"]["mode"]="edit";
$xt->assignbyref("Daate_editcontrol",$control_Daate);

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
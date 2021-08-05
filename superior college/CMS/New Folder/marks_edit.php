<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/marks_variables.php");



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
$templatefile = ( $inlineedit ) ? "marks_inline_edit.htm" : "marks_edit.htm";

/////////////////////////////////////////////////////////////
//connect database
/////////////////////////////////////////////////////////////
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessEdit"))
	BeforeProcessEdit($conn);

$keys=array();
$keys["MID"]=postvalue("editid1");

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
//	processing LID - start
	$value = postvalue("value_LID");
	$type=postvalue("type_LID");
	if (in_assoc_array("type_LID",$_POST) || in_assoc_array("value_LID",$_POST) || in_assoc_array("value_LID",$_FILES))	
	{
		$value=prepare_for_db("LID",$value,$type);
	}
	else
		$value=false;
	if($value!==false)
	{	



		$evalues["LID"]=$value;
	}


//	processibng LID - end
//	processing SESSINAL - start
	$value = postvalue("value_SESSINAL");
	$type=postvalue("type_SESSINAL");
	if (in_assoc_array("type_SESSINAL",$_POST) || in_assoc_array("value_SESSINAL",$_POST) || in_assoc_array("value_SESSINAL",$_FILES))	
	{
		$value=prepare_for_db("SESSINAL",$value,$type);
	}
	else
		$value=false;
	if($value!==false)
	{	



		$evalues["SESSINAL"]=$value;
	}


//	processibng SESSINAL - end
//	processing MIDTERM - start
	$value = postvalue("value_MIDTERM");
	$type=postvalue("type_MIDTERM");
	if (in_assoc_array("type_MIDTERM",$_POST) || in_assoc_array("value_MIDTERM",$_POST) || in_assoc_array("value_MIDTERM",$_FILES))	
	{
		$value=prepare_for_db("MIDTERM",$value,$type);
	}
	else
		$value=false;
	if($value!==false)
	{	



		$evalues["MIDTERM"]=$value;
	}


//	processibng MIDTERM - end
//	processing FINAL - start
	$value = postvalue("value_FINAL");
	$type=postvalue("type_FINAL");
	if (in_assoc_array("type_FINAL",$_POST) || in_assoc_array("value_FINAL",$_POST) || in_assoc_array("value_FINAL",$_FILES))	
	{
		$value=prepare_for_db("FINAL",$value,$type);
	}
	else
		$value=false;
	if($value!==false)
	{	



		$evalues["FINAL"]=$value;
	}


//	processibng FINAL - end

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
	$data["LID"]=$evalues["LID"];
	$data["SESSINAL"]=$evalues["SESSINAL"];
	$data["MIDTERM"]=$evalues["MIDTERM"];
	$data["FINAL"]=$evalues["FINAL"];
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
//			$bodyonload.="define('value_SID','".$validatetype."','Stuednt Name');";
			$bodyonload.="define('value_SID','".$validatetype."','".jsreplace("Stuednt Name")."');";
				$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
//			$bodyonload.="define('value_LID','".$validatetype."','Lecture Name');";
			$bodyonload.="define('value_LID','".$validatetype."','".jsreplace("Lecture Name")."');";
			  		$validatetype="IsNumeric";
			if($validatetype)
//			$bodyonload.="define('value_SESSINAL','".$validatetype."','Sessional');";
			$bodyonload.="define('value_SESSINAL','".$validatetype."','".jsreplace("Sessional")."');";
			  		$validatetype="IsNumeric";
			if($validatetype)
//			$bodyonload.="define('value_MIDTERM','".$validatetype."','Mid Term');";
			$bodyonload.="define('value_MIDTERM','".$validatetype."','".jsreplace("Mid Term")."');";
			  		$validatetype="IsNumeric";
			if($validatetype)
//			$bodyonload.="define('value_FINAL','".$validatetype."','Final Term');";
			$bodyonload.="define('value_FINAL','".$validatetype."','".jsreplace("Final Term")."');";

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
	$includes.="var SUGGEST_TABLE='marks_searchsuggest.php';\r\n";
	}
	$includes.="</script>\r\n";

	if ($useAJAX)
		$includes.="<div id=\"search_suggest\"></div>\r\n";





	$xt->assign("SID_fieldblock",true);
	$xt->assign("LID_fieldblock",true);
	$xt->assign("SESSINAL_fieldblock",true);
	$xt->assign("MIDTERM_fieldblock",true);
	$xt->assign("FINAL_fieldblock",true);

	if(strlen($onsubmit))
		$onsubmit="onSubmit=\"".$onsubmit."\"";
	$body["begin"]=$includes."
	<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"marks_edit.php\" ".$onsubmit.">".
	"<input type=hidden name=\"a\" value=\"edited\">";
	$body["begin"].="<input type=\"hidden\" name=\"editid1\" value=\"".htmlspecialchars($keys["MID"])."\">";
		$xt->assign("show_key1", htmlspecialchars(GetData($data,"MID", "")));

	$xt->assign("backbutton_attrs","onclick=\"window.location.href='marks_list.php?a=return'\"");
	$xt->assign("save_button",true);
	$xt->assign("reset_button",true);
	$xt->assign("back_button",true);
}

$showKeys[] = rawurlencode($keys["MID"]);

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
	$masterquery="mastertable=marks";
	$masterquery.="&masterkey1=".rawurlencode($data["LID"]);
	$showDetailKeys["lectures"]=$masterquery;
	$masterquery="mastertable=marks";
	$masterquery.="&masterkey1=".rawurlencode($data["SID"]);
	$showDetailKeys["students"]=$masterquery;

	$keylink="";
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["MID"]));


//	SID - 

		$value="";
				$value=DisplayLookupWizard("SID",$data["SID"],$data,$keylink,MODE_LIST);
//		$smarty->assign("show_SID",$value);
		$showValues[] = $value;
		$showFields[] = "SID";
				$showRawValues[] = substr($data["SID"],0,100);

//	LID - 

		$value="";
				$value=DisplayLookupWizard("LID",$data["LID"],$data,$keylink,MODE_LIST);
//		$smarty->assign("show_LID",$value);
		$showValues[] = $value;
		$showFields[] = "LID";
				$showRawValues[] = substr($data["LID"],0,100);

//	SESSINAL - 

		$value="";
				$value = ProcessLargeText(GetData($data,"SESSINAL", ""),"","",MODE_LIST);
//		$smarty->assign("show_SESSINAL",$value);
		$showValues[] = $value;
		$showFields[] = "SESSINAL";
				$showRawValues[] = substr($data["SESSINAL"],0,100);

//	MIDTERM - 

		$value="";
				$value = ProcessLargeText(GetData($data,"MIDTERM", ""),"","",MODE_LIST);
//		$smarty->assign("show_MIDTERM",$value);
		$showValues[] = $value;
		$showFields[] = "MIDTERM";
				$showRawValues[] = substr($data["MIDTERM"],0,100);

//	FINAL - 

		$value="";
				$value = ProcessLargeText(GetData($data,"FINAL", ""),"","",MODE_LIST);
//		$smarty->assign("show_FINAL",$value);
		$showValues[] = $value;
		$showFields[] = "FINAL";
				$showRawValues[] = substr($data["FINAL"],0,100);

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
$control_LID=array();
$control_LID["func"]="xt_buildeditcontrol";
$control_LID["params"] = array();
$control_LID["params"]["field"]="LID";
$control_LID["params"]["value"]=@$data["LID"];
$control_LID["params"]["id"]=$record_id;
if($inlineedit)
	$control_LID["params"]["mode"]="inline_edit";
else
	$control_LID["params"]["mode"]="edit";
$xt->assignbyref("LID_editcontrol",$control_LID);
$control_SESSINAL=array();
$control_SESSINAL["func"]="xt_buildeditcontrol";
$control_SESSINAL["params"] = array();
$control_SESSINAL["params"]["field"]="SESSINAL";
$control_SESSINAL["params"]["value"]=@$data["SESSINAL"];
$control_SESSINAL["params"]["id"]=$record_id;
if($inlineedit)
	$control_SESSINAL["params"]["mode"]="inline_edit";
else
	$control_SESSINAL["params"]["mode"]="edit";
$xt->assignbyref("SESSINAL_editcontrol",$control_SESSINAL);
$control_MIDTERM=array();
$control_MIDTERM["func"]="xt_buildeditcontrol";
$control_MIDTERM["params"] = array();
$control_MIDTERM["params"]["field"]="MIDTERM";
$control_MIDTERM["params"]["value"]=@$data["MIDTERM"];
$control_MIDTERM["params"]["id"]=$record_id;
if($inlineedit)
	$control_MIDTERM["params"]["mode"]="inline_edit";
else
	$control_MIDTERM["params"]["mode"]="edit";
$xt->assignbyref("MIDTERM_editcontrol",$control_MIDTERM);
$control_FINAL=array();
$control_FINAL["func"]="xt_buildeditcontrol";
$control_FINAL["params"] = array();
$control_FINAL["params"]["field"]="FINAL";
$control_FINAL["params"]["value"]=@$data["FINAL"];
$control_FINAL["params"]["id"]=$record_id;
if($inlineedit)
	$control_FINAL["params"]["mode"]="inline_edit";
else
	$control_FINAL["params"]["mode"]="edit";
$xt->assignbyref("FINAL_editcontrol",$control_FINAL);

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
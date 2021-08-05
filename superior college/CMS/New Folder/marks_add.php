<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0); 

include("include/dbcommon.php");
include("include/marks_variables.php");



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
	$templatefile = "marks_inline_add.htm";
else
	$templatefile = "marks_add.htm";

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
//	processing SID - start
	$value = postvalue("value_SID");
	$type=postvalue("type_SID");
	if (in_assoc_array("type_SID",$_POST) || in_assoc_array("value_SID",$_POST) || in_assoc_array("value_SID",$_FILES))
	{
		$value=prepare_for_db("SID",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["SID"]=$value;
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
	if(!($value===false))
	{


		$avalues["LID"]=$value;
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
	if(!($value===false))
	{


		$avalues["SESSINAL"]=$value;
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
	if(!($value===false))
	{


		$avalues["MIDTERM"]=$value;
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
	if(!($value===false))
	{


		$avalues["FINAL"]=$value;
	}
//	processibng FINAL - end





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
						$keys["MID"]=mysql_insert_id($conn);
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
		$copykeys["MID"]=postvalue("copyid1");
	}
	else
	{
		$copykeys["MID"]=postvalue("editid1");
	}
	$strWhere=KeyWhere($copykeys);
	$strSQL = gSQLWhere($strWhere);

	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
	$defvalues=db_fetch_array($rs);
//	clear key fields
	$defvalues["MID"]="";
//call CopyOnLoad event
	if(function_exists("CopyOnLoad"))
		CopyOnLoad($defvalues,$strWhere);
}
else
{
}


if($inlineedit==ADD_ONTHEFLY || true)
{
}
if($readavalues)
{
	$defvalues["SID"]=@$avalues["SID"];
	$defvalues["LID"]=@$avalues["LID"];
	$defvalues["SESSINAL"]=@$avalues["SESSINAL"];
	$defvalues["MIDTERM"]=@$avalues["MIDTERM"];
	$defvalues["FINAL"]=@$avalues["FINAL"];
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
	if($inlineedit!=ADD_ONTHEFLY)
		$includes.="<script language=\"JavaScript\" src=\"include/validate.js\"></script>\r\n";
	
	if($inlineedit!=ADD_ONTHEFLY)
	{
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
	}
	else
	{
		$includes.="var TEXT_INLINE_FIELD_REQUIRED='".jsreplace("Required field")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_ZIPCODE='".jsreplace("Field should be a valid zipcode")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_EMAIL='".jsreplace("Field should be a valid email address")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_NUMBER='".jsreplace("Field should be a valid number")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_CURRENCY='".jsreplace("Field should be a valid currency")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_PHONE='".jsreplace("Field should be a valid phone number")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_PASSWORD1='".jsreplace("Field can not be 'password'")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_PASSWORD2='".jsreplace("Field should be at least 4 characters long")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_STATE='".jsreplace("Field should be a valid US state name")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_SSN='".jsreplace("Field should be a valid Social Security Number")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_DATE='".jsreplace("Field should be a valid date")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_TIME='".jsreplace("Field should be a valid time in 24-hour format")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_CC='".jsreplace("Field should be a valid credit card number")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_SSN='".jsreplace("Field should be a valid Social Security Number")."';\r\n";
	}
			$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_SID_".$id."','".$validatetype."');";
			else
//				$bodyonload.="define('value_SID','".$validatetype."','Stuednt Name');";
				$bodyonload.="define('value_SID','".$validatetype."','".jsreplace("Stuednt Name")."');";
			
		}
			$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_LID_".$id."','".$validatetype."');";
			else
//				$bodyonload.="define('value_LID','".$validatetype."','Lecture Name');";
				$bodyonload.="define('value_LID','".$validatetype."','".jsreplace("Lecture Name")."');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_SESSINAL_".$id."','".$validatetype."');";
			else
//				$bodyonload.="define('value_SESSINAL','".$validatetype."','Sessional');";
				$bodyonload.="define('value_SESSINAL','".$validatetype."','".jsreplace("Sessional")."');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_MIDTERM_".$id."','".$validatetype."');";
			else
//				$bodyonload.="define('value_MIDTERM','".$validatetype."','Mid Term');";
				$bodyonload.="define('value_MIDTERM','".$validatetype."','".jsreplace("Mid Term")."');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_FINAL_".$id."','".$validatetype."');";
			else
//				$bodyonload.="define('value_FINAL','".$validatetype."','Final Term');";
				$bodyonload.="define('value_FINAL','".$validatetype."','".jsreplace("Final Term")."');";
			
		}

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
	$includes.="var SUGGEST_TABLE='marks_searchsuggest.php';\r\n";
	}
	if($inlineedit!=ADD_ONTHEFLY)
	{
		$includes.="</script>\r\n";
		if ($useAJAX)
			$includes.="<div id=\"search_suggest\"></div>\r\n";
	}




	$xt->assign("SID_fieldblock",true);
	$xt->assign("LID_fieldblock",true);
	$xt->assign("SESSINAL_fieldblock",true);
	$xt->assign("MIDTERM_fieldblock",true);
	$xt->assign("FINAL_fieldblock",true);
	
	$body=array();
	$formname="editform";
	if($inlineedit!=ADD_ONTHEFLY)
	{
		if($onsubmit)
			$onsubmit="onsubmit=\"".$onsubmit."\"";
		$body["begin"]=$includes.
		"<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"marks_add.php\" ".$onsubmit.">".
		"<input type=hidden name=\"a\" value=\"added\">";
		$xt->assign("backbutton_attrs","onclick=\"window.location.href='marks_list.php?a=return'\"");
		$xt->assign("back_button",true);
	}
	else
	{
		$formname="editform".$id;
		$body["begin"]="<form name=\"editform".$id."\" encType=\"multipart/form-data\" method=\"post\" action=\"marks_add.php\" ".$onsubmit." target=\"flyframe".$id."\">".
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

	$masterquery="mastertable=marks";
	$masterquery.="&masterkey1=".rawurlencode($data["LID"]);
	$showDetailKeys["lectures"]=$masterquery;
	$masterquery="mastertable=marks";
	$masterquery.="&masterkey1=".rawurlencode($data["SID"]);
	$showDetailKeys["students"]=$masterquery;
	
	
	$showKeys[] = htmlspecialchars($keys["MID"]);

	$keylink="";
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["MID"]));

//	foreach Fields as @f filter @f.bListPage order @f.nListPageOrder

	////////////////////////////////////////////
	//	SID - 
		$value="";
				$value=DisplayLookupWizard("SID",$data["SID"],$data,$keylink,MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "SID";
				$showRawValues[] = substr($data["SID"],0,100);
	////////////////////////////////////////////
	//	MID - 
		$value="";
				$value = ProcessLargeText(GetData($data,"MID", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "MID";
				$showRawValues[] = substr($data["MID"],0,100);
	////////////////////////////////////////////
	//	LID - 
		$value="";
				$value=DisplayLookupWizard("LID",$data["LID"],$data,$keylink,MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "LID";
				$showRawValues[] = substr($data["LID"],0,100);
	////////////////////////////////////////////
	//	SESSINAL - 
		$value="";
				$value = ProcessLargeText(GetData($data,"SESSINAL", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "SESSINAL";
				$showRawValues[] = substr($data["SESSINAL"],0,100);
	////////////////////////////////////////////
	//	MIDTERM - 
		$value="";
				$value = ProcessLargeText(GetData($data,"MIDTERM", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "MIDTERM";
				$showRawValues[] = substr($data["MIDTERM"],0,100);
	////////////////////////////////////////////
	//	FINAL - 
		$value="";
				$value = ProcessLargeText(GetData($data,"FINAL", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "FINAL";
				$showRawValues[] = substr($data["FINAL"],0,100);
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
$control_SID=array();
$control_SID["func"]="xt_buildeditcontrol";
$control_SID["params"] = array();
$control_SID["params"]["field"]="SID";
$control_SID["params"]["value"]=@$defvalues["SID"];
$control_SID["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_SID["params"]["mode"]="inline_add";
else
	$control_SID["params"]["mode"]="add";
$xt->assignbyref("SID_editcontrol",$control_SID);
$control_LID=array();
$control_LID["func"]="xt_buildeditcontrol";
$control_LID["params"] = array();
$control_LID["params"]["field"]="LID";
$control_LID["params"]["value"]=@$defvalues["LID"];
$control_LID["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_LID["params"]["mode"]="inline_add";
else
	$control_LID["params"]["mode"]="add";
$xt->assignbyref("LID_editcontrol",$control_LID);
$control_SESSINAL=array();
$control_SESSINAL["func"]="xt_buildeditcontrol";
$control_SESSINAL["params"] = array();
$control_SESSINAL["params"]["field"]="SESSINAL";
$control_SESSINAL["params"]["value"]=@$defvalues["SESSINAL"];
$control_SESSINAL["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_SESSINAL["params"]["mode"]="inline_add";
else
	$control_SESSINAL["params"]["mode"]="add";
$xt->assignbyref("SESSINAL_editcontrol",$control_SESSINAL);
$control_MIDTERM=array();
$control_MIDTERM["func"]="xt_buildeditcontrol";
$control_MIDTERM["params"] = array();
$control_MIDTERM["params"]["field"]="MIDTERM";
$control_MIDTERM["params"]["value"]=@$defvalues["MIDTERM"];
$control_MIDTERM["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_MIDTERM["params"]["mode"]="inline_add";
else
	$control_MIDTERM["params"]["mode"]="add";
$xt->assignbyref("MIDTERM_editcontrol",$control_MIDTERM);
$control_FINAL=array();
$control_FINAL["func"]="xt_buildeditcontrol";
$control_FINAL["params"] = array();
$control_FINAL["params"]["field"]="FINAL";
$control_FINAL["params"]["value"]=@$defvalues["FINAL"];
$control_FINAL["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_FINAL["params"]["mode"]="inline_add";
else
	$control_FINAL["params"]["mode"]="add";
$xt->assignbyref("FINAL_editcontrol",$control_FINAL);

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

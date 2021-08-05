<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0); 

include("include/dbcommon.php");
include("include/lectures_variables.php");



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
	$templatefile = "lectures_inline_add.htm";
else
	$templatefile = "lectures_add.htm";

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
//	processing TID - start
	$value = postvalue("value_TID");
	$type=postvalue("type_TID");
	if (in_assoc_array("type_TID",$_POST) || in_assoc_array("value_TID",$_POST) || in_assoc_array("value_TID",$_FILES))
	{
		$value=prepare_for_db("TID",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["TID"]=$value;
	}
//	processibng TID - end
//	processing Name - start
	$value = postvalue("value_Name");
	$type=postvalue("type_Name");
	if (in_assoc_array("type_Name",$_POST) || in_assoc_array("value_Name",$_POST) || in_assoc_array("value_Name",$_FILES))
	{
		$value=prepare_for_db("Name",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Name"]=$value;
	}
//	processibng Name - end
//	processing Continue - start
	$value = postvalue("value_Continue");
	$type=postvalue("type_Continue");
	if (in_assoc_array("type_Continue",$_POST) || in_assoc_array("value_Continue",$_POST) || in_assoc_array("value_Continue",$_FILES))
	{
		$value=prepare_for_db("Continue",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Continue"]=$value;
	}
//	processibng Continue - end
//	processing Type - start
	$value = postvalue("value_Type");
	$type=postvalue("type_Type");
	if (in_assoc_array("type_Type",$_POST) || in_assoc_array("value_Type",$_POST) || in_assoc_array("value_Type",$_FILES))
	{
		$value=prepare_for_db("Type",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Type"]=$value;
	}
//	processibng Type - end
//	processing Start - start
	$value = postvalue("value_Start");
	$type=postvalue("type_Start");
	if (in_assoc_array("type_Start",$_POST) || in_assoc_array("value_Start",$_POST) || in_assoc_array("value_Start",$_FILES))
	{
		$value=prepare_for_db("Start",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Start"]=$value;
	}
//	processibng Start - end
//	processing End - start
	$value = postvalue("value_End");
	$type=postvalue("type_End");
	if (in_assoc_array("type_End",$_POST) || in_assoc_array("value_End",$_POST) || in_assoc_array("value_End",$_FILES))
	{
		$value=prepare_for_db("End",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["End"]=$value;
	}
//	processibng End - end
//	processing Time - start
	$value = postvalue("value_Time");
	$type=postvalue("type_Time");
	if (in_assoc_array("type_Time",$_POST) || in_assoc_array("value_Time",$_POST) || in_assoc_array("value_Time",$_FILES))
	{
		$value=prepare_for_db("Time",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Time"]=$value;
	}
//	processibng Time - end
//	processing Room - start
	$value = postvalue("value_Room");
	$type=postvalue("type_Room");
	if (in_assoc_array("type_Room",$_POST) || in_assoc_array("value_Room",$_POST) || in_assoc_array("value_Room",$_FILES))
	{
		$value=prepare_for_db("Room",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Room"]=$value;
	}
//	processibng Room - end
//	processing Duration - start
	$value = postvalue("value_Duration");
	$type=postvalue("type_Duration");
	if (in_assoc_array("type_Duration",$_POST) || in_assoc_array("value_Duration",$_POST) || in_assoc_array("value_Duration",$_FILES))
	{
		$value=prepare_for_db("Duration",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Duration"]=$value;
	}
//	processibng Duration - end
//	processing About - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_About");
	$type=postvalue("type_About");
	if (in_assoc_array("type_About",$_POST) || in_assoc_array("value_About",$_POST) || in_assoc_array("value_About",$_FILES))
	{
		$value=prepare_for_db("About",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["About"]=$value;
	}
	}
//	processibng About - end
//	processing Announce - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_Announce");
	$type=postvalue("type_Announce");
	if (in_assoc_array("type_Announce",$_POST) || in_assoc_array("value_Announce",$_POST) || in_assoc_array("value_Announce",$_FILES))
	{
		$value=prepare_for_db("Announce",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Announce"]=$value;
	}
	}
//	processibng Announce - end




//	insert masterkey value if exists and if not specified
	if(@$_SESSION[$strTableName."_mastertable"]=="attendance")
	{
		$avalues["LID"]=prepare_for_db("LID",$_SESSION[$strTableName."_masterkey1"]);
	}
	if(@$_SESSION[$strTableName."_mastertable"]=="assignments")
	{
		$avalues["LID"]=prepare_for_db("LID",$_SESSION[$strTableName."_masterkey1"]);
	}
	if(@$_SESSION[$strTableName."_mastertable"]=="marks")
	{
		$avalues["LID"]=prepare_for_db("LID",$_SESSION[$strTableName."_masterkey1"]);
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
						$keys["LID"]=mysql_insert_id($conn);
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
		$copykeys["LID"]=postvalue("copyid1");
	}
	else
	{
		$copykeys["LID"]=postvalue("editid1");
	}
	$strWhere=KeyWhere($copykeys);
	$strSQL = gSQLWhere($strWhere);

	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
	$defvalues=db_fetch_array($rs);
//	clear key fields
	$defvalues["LID"]="";
//call CopyOnLoad event
	if(function_exists("CopyOnLoad"))
		CopyOnLoad($defvalues,$strWhere);
}
else
{
	$defvalues["Continue"]=1;
	$defvalues["Time"]="00:00:00 AM";
}

//	set default values for the foreign keys
if(@$_SESSION[$strTableName."_mastertable"]=="attendance")
{
	$defvalues["LID"]=@$_SESSION[$strTableName."_masterkey1"];
}
if(@$_SESSION[$strTableName."_mastertable"]=="assignments")
{
	$defvalues["LID"]=@$_SESSION[$strTableName."_masterkey1"];
}
if(@$_SESSION[$strTableName."_mastertable"]=="marks")
{
	$defvalues["LID"]=@$_SESSION[$strTableName."_masterkey1"];
}

if($inlineedit==ADD_ONTHEFLY || true)
{
}
if($readavalues)
{
	$defvalues["CID"]=@$avalues["CID"];
	$defvalues["TID"]=@$avalues["TID"];
	$defvalues["Name"]=@$avalues["Name"];
	$defvalues["Continue"]=@$avalues["Continue"];
	$defvalues["Type"]=@$avalues["Type"];
	$defvalues["Start"]=@$avalues["Start"];
	$defvalues["End"]=@$avalues["End"];
	$defvalues["Time"]=@$avalues["Time"];
	$defvalues["Room"]=@$avalues["Room"];
	$defvalues["Duration"]=@$avalues["Duration"];
	$defvalues["About"]=@$avalues["About"];
	$defvalues["Announce"]=@$avalues["Announce"];
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
				$linkdata.="define_fly('value_CID_".$id."','".$validatetype."');";
			else
//				$bodyonload.="define('value_CID','".$validatetype."','Class ID');";
				$bodyonload.="define('value_CID','".$validatetype."','".jsreplace("Class ID")."');";
			
		}
			$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_TID_".$id."','".$validatetype."');";
			else
//				$bodyonload.="define('value_TID','".$validatetype."','Teacher Name');";
				$bodyonload.="define('value_TID','".$validatetype."','".jsreplace("Teacher Name")."');";
			
		}
		  		$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_Name_".$id."','".$validatetype."');";
			else
//				$bodyonload.="define('value_Name','".$validatetype."','Lecure Name');";
				$bodyonload.="define('value_Name','".$validatetype."','".jsreplace("Lecure Name")."');";
			
		}
			$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_Type_".$id."','".$validatetype."');";
			else
//				$bodyonload.="define('value_Type','".$validatetype."','Type');";
				$bodyonload.="define('value_Type','".$validatetype."','".jsreplace("Type")."');";
			
		}
			$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_Start_".$id."','".$validatetype."');";
			else
//				$bodyonload.="define('value_Start','".$validatetype."','Start Date');";
				$bodyonload.="define('value_Start','".$validatetype."','".jsreplace("Start Date")."');";
			
		}
			$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_End_".$id."','".$validatetype."');";
			else
//				$bodyonload.="define('value_End','".$validatetype."','End Date');";
				$bodyonload.="define('value_End','".$validatetype."','".jsreplace("End Date")."');";
			
		}
		  		$validatetype="IsTime";
			$validatetype.="IsRequired";
		if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_Time_".$id."','".$validatetype."');";
			else
//				$bodyonload.="define('value_Time','".$validatetype."','Time');";
				$bodyonload.="define('value_Time','".$validatetype."','".jsreplace("Time")."');";
			
		}
		  		$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_Room_".$id."','".$validatetype."');";
			else
//				$bodyonload.="define('value_Room','".$validatetype."','Room');";
				$bodyonload.="define('value_Room','".$validatetype."','".jsreplace("Room")."');";
			
		}
		  		$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_Duration_".$id."','".$validatetype."');";
			else
//				$bodyonload.="define('value_Duration','".$validatetype."','Duration');";
				$bodyonload.="define('value_Duration','".$validatetype."','".jsreplace("Duration")."');";
			
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
	$includes.="var SUGGEST_TABLE='lectures_searchsuggest.php';\r\n";
	}
	if($inlineedit!=ADD_ONTHEFLY)
	{
		$includes.="</script>\r\n";
		if ($useAJAX)
			$includes.="<div id=\"search_suggest\"></div>\r\n";
	}
		//	include datepicker files
	if($inlineedit!=ADD_ONTHEFLY)
		$includes.="<script language=\"JavaScript\" src=\"include/calendar.js\"></script>\r\n";
	else
		$arr_includes[]="include/calendar.js";
	
		//	include Rich Text Editor files
	if($inlineedit!=ADD_ONTHEFLY)
		$includes.="<script language=\"JavaScript\" type=\"text/javascript\" src=\"include/richtext.js\"></script>\r\n";
	else
		$arr_includes[]="include/richtext.js";
	if($inlineedit!=ADD_ONTHEFLY)
	{
		$includes.="<script language=\"JavaScript\">\r\n";
		$includes.="var TEXT_VIEW_SOURCE='".addslashes("View Source")."';\r\n";
		$includes.="initRTE('include/images/', 'include/', '');\r\n";
		$includes.="</script>\r\n";
		$onsubmit="updateRTEs();".$onsubmit;
	}
	


	$xt->assign("CID_fieldblock",true);
	$xt->assign("TID_fieldblock",true);
	$xt->assign("Name_fieldblock",true);
	$xt->assign("Continue_fieldblock",true);
	$xt->assign("Type_fieldblock",true);
	$xt->assign("Start_fieldblock",true);
	$xt->assign("End_fieldblock",true);
	$xt->assign("Time_fieldblock",true);
	$xt->assign("Room_fieldblock",true);
	$xt->assign("Duration_fieldblock",true);
	$xt->assign("About_fieldblock",true);
	$xt->assign("Announce_fieldblock",true);
	
	$body=array();
	$formname="editform";
	if($inlineedit!=ADD_ONTHEFLY)
	{
		if($onsubmit)
			$onsubmit="onsubmit=\"".$onsubmit."\"";
		$body["begin"]=$includes.
		"<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"lectures_add.php\" ".$onsubmit.">".
		"<input type=hidden name=\"a\" value=\"added\">";
		$xt->assign("backbutton_attrs","onclick=\"window.location.href='lectures_list.php?a=return'\"");
		$xt->assign("back_button",true);
	}
	else
	{
		$formname="editform".$id;
		$body["begin"]="<form name=\"editform".$id."\" encType=\"multipart/form-data\" method=\"post\" action=\"lectures_add.php\" ".$onsubmit." target=\"flyframe".$id."\">".
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

	
	
	$showKeys[] = htmlspecialchars($keys["LID"]);

	$keylink="";
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["LID"]));

//	foreach Fields as @f filter @f.bListPage order @f.nListPageOrder

	////////////////////////////////////////////
	//	LID - 
		$value="";
				$value = ProcessLargeText(GetData($data,"LID", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "LID";
				$showRawValues[] = substr($data["LID"],0,100);
	////////////////////////////////////////////
	//	CID - 
		$value="";
				$value = ProcessLargeText(GetData($data,"CID", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "CID";
				$showRawValues[] = substr($data["CID"],0,100);
	////////////////////////////////////////////
	//	TID - 
		$value="";
				$value=DisplayLookupWizard("TID",$data["TID"],$data,$keylink,MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "TID";
				$showRawValues[] = substr($data["TID"],0,100);
	////////////////////////////////////////////
	//	Name - 
		$value="";
				$value = ProcessLargeText(GetData($data,"Name", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "Name";
				$showRawValues[] = substr($data["Name"],0,100);
	////////////////////////////////////////////
	//	Continue - Checkbox
		$value="";
				$value = GetData($data,"Continue", "Checkbox");
		$showValues[] = $value;
		$showFields[] = "Continue";
				$showRawValues[] = substr($data["Continue"],0,100);
	////////////////////////////////////////////
	//	Type - 
		$value="";
				$value = ProcessLargeText(GetData($data,"Type", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "Type";
				$showRawValues[] = substr($data["Type"],0,100);
	////////////////////////////////////////////
	//	Start - Short Date
		$value="";
				$value = ProcessLargeText(GetData($data,"Start", "Short Date"),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "Start";
				$showRawValues[] = substr($data["Start"],0,100);
	////////////////////////////////////////////
	//	End - Short Date
		$value="";
				$value = ProcessLargeText(GetData($data,"End", "Short Date"),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "End";
				$showRawValues[] = substr($data["End"],0,100);
	////////////////////////////////////////////
	//	Time - Time
		$value="";
				$value = ProcessLargeText(GetData($data,"Time", "Time"),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "Time";
				$showRawValues[] = substr($data["Time"],0,100);
	////////////////////////////////////////////
	//	Room - 
		$value="";
				$value = ProcessLargeText(GetData($data,"Room", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "Room";
				$showRawValues[] = substr($data["Room"],0,100);
	////////////////////////////////////////////
	//	Duration - 
		$value="";
				$value = ProcessLargeText(GetData($data,"Duration", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "Duration";
				$showRawValues[] = substr($data["Duration"],0,100);
	////////////////////////////////////////////
	//	About - HTML
		$value="";
				$value = GetData($data,"About", "HTML");
		$showValues[] = $value;
		$showFields[] = "About";
				$showRawValues[] = substr($data["About"],0,100);
	////////////////////////////////////////////
	//	Announce - HTML
		$value="";
				$value = GetData($data,"Announce", "HTML");
		$showValues[] = $value;
		$showFields[] = "Announce";
				$showRawValues[] = substr($data["Announce"],0,100);
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
$control_TID=array();
$control_TID["func"]="xt_buildeditcontrol";
$control_TID["params"] = array();
$control_TID["params"]["field"]="TID";
$control_TID["params"]["value"]=@$defvalues["TID"];
$control_TID["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_TID["params"]["mode"]="inline_add";
else
	$control_TID["params"]["mode"]="add";
$xt->assignbyref("TID_editcontrol",$control_TID);
$control_Name=array();
$control_Name["func"]="xt_buildeditcontrol";
$control_Name["params"] = array();
$control_Name["params"]["field"]="Name";
$control_Name["params"]["value"]=@$defvalues["Name"];
$control_Name["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Name["params"]["mode"]="inline_add";
else
	$control_Name["params"]["mode"]="add";
$xt->assignbyref("Name_editcontrol",$control_Name);
$control_Continue=array();
$control_Continue["func"]="xt_buildeditcontrol";
$control_Continue["params"] = array();
$control_Continue["params"]["field"]="Continue";
$control_Continue["params"]["value"]=@$defvalues["Continue"];
$control_Continue["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Continue["params"]["mode"]="inline_add";
else
	$control_Continue["params"]["mode"]="add";
$xt->assignbyref("Continue_editcontrol",$control_Continue);
$control_Type=array();
$control_Type["func"]="xt_buildeditcontrol";
$control_Type["params"] = array();
$control_Type["params"]["field"]="Type";
$control_Type["params"]["value"]=@$defvalues["Type"];
$control_Type["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Type["params"]["mode"]="inline_add";
else
	$control_Type["params"]["mode"]="add";
$xt->assignbyref("Type_editcontrol",$control_Type);
$control_Start=array();
$control_Start["func"]="xt_buildeditcontrol";
$control_Start["params"] = array();
$control_Start["params"]["field"]="Start";
$control_Start["params"]["value"]=@$defvalues["Start"];
$control_Start["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Start["params"]["mode"]="inline_add";
else
	$control_Start["params"]["mode"]="add";
$xt->assignbyref("Start_editcontrol",$control_Start);
$control_End=array();
$control_End["func"]="xt_buildeditcontrol";
$control_End["params"] = array();
$control_End["params"]["field"]="End";
$control_End["params"]["value"]=@$defvalues["End"];
$control_End["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_End["params"]["mode"]="inline_add";
else
	$control_End["params"]["mode"]="add";
$xt->assignbyref("End_editcontrol",$control_End);
$control_Time=array();
$control_Time["func"]="xt_buildeditcontrol";
$control_Time["params"] = array();
$control_Time["params"]["field"]="Time";
$control_Time["params"]["value"]=@$defvalues["Time"];
$control_Time["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Time["params"]["mode"]="inline_add";
else
	$control_Time["params"]["mode"]="add";
$xt->assignbyref("Time_editcontrol",$control_Time);
$control_Room=array();
$control_Room["func"]="xt_buildeditcontrol";
$control_Room["params"] = array();
$control_Room["params"]["field"]="Room";
$control_Room["params"]["value"]=@$defvalues["Room"];
$control_Room["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Room["params"]["mode"]="inline_add";
else
	$control_Room["params"]["mode"]="add";
$xt->assignbyref("Room_editcontrol",$control_Room);
$control_Duration=array();
$control_Duration["func"]="xt_buildeditcontrol";
$control_Duration["params"] = array();
$control_Duration["params"]["field"]="Duration";
$control_Duration["params"]["value"]=@$defvalues["Duration"];
$control_Duration["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Duration["params"]["mode"]="inline_add";
else
	$control_Duration["params"]["mode"]="add";
$xt->assignbyref("Duration_editcontrol",$control_Duration);
$control_About=array();
$control_About["func"]="xt_buildeditcontrol";
$control_About["params"] = array();
$control_About["params"]["field"]="About";
$control_About["params"]["value"]=@$defvalues["About"];
$control_About["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_About["params"]["mode"]="inline_add";
else
	$control_About["params"]["mode"]="add";
$xt->assignbyref("About_editcontrol",$control_About);
$control_Announce=array();
$control_Announce["func"]="xt_buildeditcontrol";
$control_Announce["params"] = array();
$control_Announce["params"]["field"]="Announce";
$control_Announce["params"]["value"]=@$defvalues["Announce"];
$control_Announce["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Announce["params"]["mode"]="inline_add";
else
	$control_Announce["params"]["mode"]="add";
$xt->assignbyref("Announce_editcontrol",$control_Announce);

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

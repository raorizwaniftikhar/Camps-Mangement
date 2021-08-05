<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/lectures_variables.php");



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
$templatefile = ( $inlineedit ) ? "lectures_inline_edit.htm" : "lectures_edit.htm";

/////////////////////////////////////////////////////////////
//connect database
/////////////////////////////////////////////////////////////
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessEdit"))
	BeforeProcessEdit($conn);

$keys=array();
$keys["LID"]=postvalue("editid1");

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
	if($value!==false)
	{	



		$evalues["TID"]=$value;
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
	if($value!==false)
	{	



		$evalues["Name"]=$value;
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
	if($value!==false)
	{	



		$evalues["Continue"]=$value;
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
	if($value!==false)
	{	



		$evalues["Type"]=$value;
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
	if($value!==false)
	{	



		$evalues["Start"]=$value;
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
	if($value!==false)
	{	



		$evalues["End"]=$value;
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
	if($value!==false)
	{	



		$evalues["Time"]=$value;
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
	if($value!==false)
	{	



		$evalues["Room"]=$value;
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
	if($value!==false)
	{	



		$evalues["Duration"]=$value;
	}


//	processibng Duration - end
//	processing About - start
	if(!$inlineedit)
	{
	$value = postvalue("value_About");
	$type=postvalue("type_About");
	if (in_assoc_array("type_About",$_POST) || in_assoc_array("value_About",$_POST) || in_assoc_array("value_About",$_FILES))	
	{
		$value=prepare_for_db("About",$value,$type);
	}
	else
		$value=false;
	if($value!==false)
	{	



		$evalues["About"]=$value;
	}


//	processibng About - end
	}
//	processing Announce - start
	if(!$inlineedit)
	{
	$value = postvalue("value_Announce");
	$type=postvalue("type_Announce");
	if (in_assoc_array("type_Announce",$_POST) || in_assoc_array("value_Announce",$_POST) || in_assoc_array("value_Announce",$_FILES))	
	{
		$value=prepare_for_db("Announce",$value,$type);
	}
	else
		$value=false;
	if($value!==false)
	{	



		$evalues["Announce"]=$value;
	}


//	processibng Announce - end
	}

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
	$data["TID"]=$evalues["TID"];
	$data["Name"]=$evalues["Name"];
	$data["Continue"]=$evalues["Continue"];
	$data["Type"]=$evalues["Type"];
	$data["Start"]=$evalues["Start"];
	$data["End"]=$evalues["End"];
	$data["Time"]=$evalues["Time"];
	$data["Room"]=$evalues["Room"];
	$data["Duration"]=$evalues["Duration"];
	$data["About"]=$evalues["About"];
	$data["Announce"]=$evalues["Announce"];
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
//			$bodyonload.="define('value_CID','".$validatetype."','Class ID');";
			$bodyonload.="define('value_CID','".$validatetype."','".jsreplace("Class ID")."');";
				$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
//			$bodyonload.="define('value_TID','".$validatetype."','Teacher Name');";
			$bodyonload.="define('value_TID','".$validatetype."','".jsreplace("Teacher Name")."');";
			  		$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
//			$bodyonload.="define('value_Name','".$validatetype."','Lecure Name');";
			$bodyonload.="define('value_Name','".$validatetype."','".jsreplace("Lecure Name")."');";
				$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
//			$bodyonload.="define('value_Type','".$validatetype."','Type');";
			$bodyonload.="define('value_Type','".$validatetype."','".jsreplace("Type")."');";
				$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
//			$bodyonload.="define('value_Start','".$validatetype."','Start Date');";
			$bodyonload.="define('value_Start','".$validatetype."','".jsreplace("Start Date")."');";
				$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
//			$bodyonload.="define('value_End','".$validatetype."','End Date');";
			$bodyonload.="define('value_End','".$validatetype."','".jsreplace("End Date")."');";
			  		$validatetype="IsTime";
			$validatetype.="IsRequired";
		if($validatetype)
//			$bodyonload.="define('value_Time','".$validatetype."','Time');";
			$bodyonload.="define('value_Time','".$validatetype."','".jsreplace("Time")."');";
			  		$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
//			$bodyonload.="define('value_Room','".$validatetype."','Room');";
			$bodyonload.="define('value_Room','".$validatetype."','".jsreplace("Room")."');";
			  		$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
//			$bodyonload.="define('value_Duration','".$validatetype."','Duration');";
			$bodyonload.="define('value_Duration','".$validatetype."','".jsreplace("Duration")."');";

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
	$includes.="var SUGGEST_TABLE='lectures_searchsuggest.php';\r\n";
	}
	$includes.="</script>\r\n";

	if ($useAJAX)
		$includes.="<div id=\"search_suggest\"></div>\r\n";

		//	include datepicker files
	$includes.="<script language=\"JavaScript\" src=\"include/calendar.js\"></script>\r\n";
	
		//	include Rich Text Editor files
	$includes.="<script language=\"JavaScript\" type=\"text/javascript\" src=\"include/richtext.js\"></script>\r\n";
	$includes.="<script language=\"JavaScript\">\r\n";
	$includes.="var TEXT_VIEW_SOURCE='".addslashes("View Source")."';\r\n";
	$includes.="initRTE('include/images/', 'include/', '');\r\n";
	$includes.="</script>\r\n";
	$onsubmit="updateRTEs();".$onsubmit;
	


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

	if(strlen($onsubmit))
		$onsubmit="onSubmit=\"".$onsubmit."\"";
	$body["begin"]=$includes."
	<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"lectures_edit.php\" ".$onsubmit.">".
	"<input type=hidden name=\"a\" value=\"edited\">";
	$body["begin"].="<input type=\"hidden\" name=\"editid1\" value=\"".htmlspecialchars($keys["LID"])."\">";
		$xt->assign("show_key1", htmlspecialchars(GetData($data,"LID", "")));

	$xt->assign("backbutton_attrs","onclick=\"window.location.href='lectures_list.php?a=return'\"");
	$xt->assign("save_button",true);
	$xt->assign("reset_button",true);
	$xt->assign("back_button",true);
}

$showKeys[] = rawurlencode($keys["LID"]);

if($message)
{
	$xt->assign("message_block",true);
	$xt->assign("message",$message);
}

/////////////////////////////////////////////////////////////
//process readonly and auto-update fields
/////////////////////////////////////////////////////////////

$readonlyfields=array();

$data["Continue"] = 1;


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
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["LID"]));


//	CID - 

		$value="";
				$value=DisplayLookupWizard("CID",$data["CID"],$data,$keylink,MODE_LIST);
//		$smarty->assign("show_CID",$value);
		$showValues[] = $value;
		$showFields[] = "CID";
				$showRawValues[] = substr($data["CID"],0,100);

//	TID - 

		$value="";
				$value=DisplayLookupWizard("TID",$data["TID"],$data,$keylink,MODE_LIST);
//		$smarty->assign("show_TID",$value);
		$showValues[] = $value;
		$showFields[] = "TID";
				$showRawValues[] = substr($data["TID"],0,100);

//	Name - 

		$value="";
				$value = ProcessLargeText(GetData($data,"Name", ""),"","",MODE_LIST);
//		$smarty->assign("show_Name",$value);
		$showValues[] = $value;
		$showFields[] = "Name";
				$showRawValues[] = substr($data["Name"],0,100);

//	Continue - Checkbox

		$value="";
				$value = GetData($data,"Continue", "Checkbox");
//		$smarty->assign("show_Continue",$value);
		$showValues[] = $value;
		$showFields[] = "Continue";
				$showRawValues[] = substr($data["Continue"],0,100);

//	Type - 

		$value="";
				$value = ProcessLargeText(GetData($data,"Type", ""),"","",MODE_LIST);
//		$smarty->assign("show_Type",$value);
		$showValues[] = $value;
		$showFields[] = "Type";
				$showRawValues[] = substr($data["Type"],0,100);

//	Start - Short Date

		$value="";
				$value = ProcessLargeText(GetData($data,"Start", "Short Date"),"","",MODE_LIST);
//		$smarty->assign("show_Start",$value);
		$showValues[] = $value;
		$showFields[] = "Start";
				$showRawValues[] = substr($data["Start"],0,100);

//	End - Short Date

		$value="";
				$value = ProcessLargeText(GetData($data,"End", "Short Date"),"","",MODE_LIST);
//		$smarty->assign("show_End",$value);
		$showValues[] = $value;
		$showFields[] = "End";
				$showRawValues[] = substr($data["End"],0,100);

//	Time - Time

		$value="";
				$value = ProcessLargeText(GetData($data,"Time", "Time"),"","",MODE_LIST);
//		$smarty->assign("show_Time",$value);
		$showValues[] = $value;
		$showFields[] = "Time";
				$showRawValues[] = substr($data["Time"],0,100);

//	Room - 

		$value="";
				$value = ProcessLargeText(GetData($data,"Room", ""),"","",MODE_LIST);
//		$smarty->assign("show_Room",$value);
		$showValues[] = $value;
		$showFields[] = "Room";
				$showRawValues[] = substr($data["Room"],0,100);

//	Duration - 

		$value="";
				$value = ProcessLargeText(GetData($data,"Duration", ""),"","",MODE_LIST);
//		$smarty->assign("show_Duration",$value);
		$showValues[] = $value;
		$showFields[] = "Duration";
				$showRawValues[] = substr($data["Duration"],0,100);

//	About - HTML

		$value="";
				$value = GetData($data,"About", "HTML");
//		$smarty->assign("show_About",$value);
		$showValues[] = $value;
		$showFields[] = "About";
				$showRawValues[] = substr($data["About"],0,100);

//	Announce - HTML

		$value="";
				$value = GetData($data,"Announce", "HTML");
//		$smarty->assign("show_Announce",$value);
		$showValues[] = $value;
		$showFields[] = "Announce";
				$showRawValues[] = substr($data["Announce"],0,100);

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
$control_TID=array();
$control_TID["func"]="xt_buildeditcontrol";
$control_TID["params"] = array();
$control_TID["params"]["field"]="TID";
$control_TID["params"]["value"]=@$data["TID"];
$control_TID["params"]["id"]=$record_id;
if($inlineedit)
	$control_TID["params"]["mode"]="inline_edit";
else
	$control_TID["params"]["mode"]="edit";
$xt->assignbyref("TID_editcontrol",$control_TID);
$control_Name=array();
$control_Name["func"]="xt_buildeditcontrol";
$control_Name["params"] = array();
$control_Name["params"]["field"]="Name";
$control_Name["params"]["value"]=@$data["Name"];
$control_Name["params"]["id"]=$record_id;
if($inlineedit)
	$control_Name["params"]["mode"]="inline_edit";
else
	$control_Name["params"]["mode"]="edit";
$xt->assignbyref("Name_editcontrol",$control_Name);
$control_Continue=array();
$control_Continue["func"]="xt_buildeditcontrol";
$control_Continue["params"] = array();
$control_Continue["params"]["field"]="Continue";
$control_Continue["params"]["value"]=@$data["Continue"];
$control_Continue["params"]["id"]=$record_id;
if($inlineedit)
	$control_Continue["params"]["mode"]="inline_edit";
else
	$control_Continue["params"]["mode"]="edit";
$xt->assignbyref("Continue_editcontrol",$control_Continue);
$control_Type=array();
$control_Type["func"]="xt_buildeditcontrol";
$control_Type["params"] = array();
$control_Type["params"]["field"]="Type";
$control_Type["params"]["value"]=@$data["Type"];
$control_Type["params"]["id"]=$record_id;
if($inlineedit)
	$control_Type["params"]["mode"]="inline_edit";
else
	$control_Type["params"]["mode"]="edit";
$xt->assignbyref("Type_editcontrol",$control_Type);
$control_Start=array();
$control_Start["func"]="xt_buildeditcontrol";
$control_Start["params"] = array();
$control_Start["params"]["field"]="Start";
$control_Start["params"]["value"]=@$data["Start"];
$control_Start["params"]["id"]=$record_id;
if($inlineedit)
	$control_Start["params"]["mode"]="inline_edit";
else
	$control_Start["params"]["mode"]="edit";
$xt->assignbyref("Start_editcontrol",$control_Start);
$control_End=array();
$control_End["func"]="xt_buildeditcontrol";
$control_End["params"] = array();
$control_End["params"]["field"]="End";
$control_End["params"]["value"]=@$data["End"];
$control_End["params"]["id"]=$record_id;
if($inlineedit)
	$control_End["params"]["mode"]="inline_edit";
else
	$control_End["params"]["mode"]="edit";
$xt->assignbyref("End_editcontrol",$control_End);
$control_Time=array();
$control_Time["func"]="xt_buildeditcontrol";
$control_Time["params"] = array();
$control_Time["params"]["field"]="Time";
$control_Time["params"]["value"]=@$data["Time"];
$control_Time["params"]["id"]=$record_id;
if($inlineedit)
	$control_Time["params"]["mode"]="inline_edit";
else
	$control_Time["params"]["mode"]="edit";
$xt->assignbyref("Time_editcontrol",$control_Time);
$control_Room=array();
$control_Room["func"]="xt_buildeditcontrol";
$control_Room["params"] = array();
$control_Room["params"]["field"]="Room";
$control_Room["params"]["value"]=@$data["Room"];
$control_Room["params"]["id"]=$record_id;
if($inlineedit)
	$control_Room["params"]["mode"]="inline_edit";
else
	$control_Room["params"]["mode"]="edit";
$xt->assignbyref("Room_editcontrol",$control_Room);
$control_Duration=array();
$control_Duration["func"]="xt_buildeditcontrol";
$control_Duration["params"] = array();
$control_Duration["params"]["field"]="Duration";
$control_Duration["params"]["value"]=@$data["Duration"];
$control_Duration["params"]["id"]=$record_id;
if($inlineedit)
	$control_Duration["params"]["mode"]="inline_edit";
else
	$control_Duration["params"]["mode"]="edit";
$xt->assignbyref("Duration_editcontrol",$control_Duration);
$control_About=array();
$control_About["func"]="xt_buildeditcontrol";
$control_About["params"] = array();
$control_About["params"]["field"]="About";
$control_About["params"]["value"]=@$data["About"];
$control_About["params"]["id"]=$record_id;
if($inlineedit)
	$control_About["params"]["mode"]="inline_edit";
else
	$control_About["params"]["mode"]="edit";
$xt->assignbyref("About_editcontrol",$control_About);
$control_Announce=array();
$control_Announce["func"]="xt_buildeditcontrol";
$control_Announce["params"] = array();
$control_Announce["params"]["field"]="Announce";
$control_Announce["params"]["value"]=@$data["Announce"];
$control_Announce["params"]["id"]=$record_id;
if($inlineedit)
	$control_Announce["params"]["mode"]="inline_edit";
else
	$control_Announce["params"]["mode"]="edit";
$xt->assignbyref("Announce_editcontrol",$control_Announce);

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
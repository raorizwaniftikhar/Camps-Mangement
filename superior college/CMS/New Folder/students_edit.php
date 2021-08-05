<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/students_variables.php");



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
$templatefile = ( $inlineedit ) ? "students_inline_edit.htm" : "students_edit.htm";

/////////////////////////////////////////////////////////////
//connect database
/////////////////////////////////////////////////////////////
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessEdit"))
	BeforeProcessEdit($conn);

$keys=array();
$keys["SID"]=postvalue("editid1");

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
//	processing RNUM - start
	$value = postvalue("value_RNUM");
	$type=postvalue("type_RNUM");
	if (in_assoc_array("type_RNUM",$_POST) || in_assoc_array("value_RNUM",$_POST) || in_assoc_array("value_RNUM",$_FILES))	
	{
		$value=prepare_for_db("RNUM",$value,$type);
	}
	else
		$value=false;
	if($value!==false)
	{	



		$evalues["RNUM"]=$value;
	}


//	processibng RNUM - end
//	processing Full_Name - start
	$value = postvalue("value_Full_Name");
	$type=postvalue("type_Full_Name");
	if (in_assoc_array("type_Full_Name",$_POST) || in_assoc_array("value_Full_Name",$_POST) || in_assoc_array("value_Full_Name",$_FILES))	
	{
		$value=prepare_for_db("Full_Name",$value,$type);
	}
	else
		$value=false;
	if($value!==false)
	{	



		$evalues["Full_Name"]=$value;
	}


//	processibng Full_Name - end
//	processing Status - start
	$value = postvalue("value_Status");
	$type=postvalue("type_Status");
	if (in_assoc_array("type_Status",$_POST) || in_assoc_array("value_Status",$_POST) || in_assoc_array("value_Status",$_FILES))	
	{
		$value=prepare_for_db("Status",$value,$type);
	}
	else
		$value=false;
	if($value!==false)
	{	



		$evalues["Status"]=$value;
	}


//	processibng Status - end
//	processing L1 - start
	$value = postvalue("value_L1");
	$type=postvalue("type_L1");
	if (in_assoc_array("type_L1",$_POST) || in_assoc_array("value_L1",$_POST) || in_assoc_array("value_L1",$_FILES))	
	{
		$value=prepare_for_db("L1",$value,$type);
	}
	else
		$value=false;
	if($value!==false)
	{	



		$evalues["L1"]=$value;
	}


//	processibng L1 - end
//	processing L2 - start
	$value = postvalue("value_L2");
	$type=postvalue("type_L2");
	if (in_assoc_array("type_L2",$_POST) || in_assoc_array("value_L2",$_POST) || in_assoc_array("value_L2",$_FILES))	
	{
		$value=prepare_for_db("L2",$value,$type);
	}
	else
		$value=false;
	if($value!==false)
	{	



		$evalues["L2"]=$value;
	}


//	processibng L2 - end
//	processing L3 - start
	$value = postvalue("value_L3");
	$type=postvalue("type_L3");
	if (in_assoc_array("type_L3",$_POST) || in_assoc_array("value_L3",$_POST) || in_assoc_array("value_L3",$_FILES))	
	{
		$value=prepare_for_db("L3",$value,$type);
	}
	else
		$value=false;
	if($value!==false)
	{	



		$evalues["L3"]=$value;
	}


//	processibng L3 - end
//	processing L4 - start
	$value = postvalue("value_L4");
	$type=postvalue("type_L4");
	if (in_assoc_array("type_L4",$_POST) || in_assoc_array("value_L4",$_POST) || in_assoc_array("value_L4",$_FILES))	
	{
		$value=prepare_for_db("L4",$value,$type);
	}
	else
		$value=false;
	if($value!==false)
	{	



		$evalues["L4"]=$value;
	}


//	processibng L4 - end
//	processing L5 - start
	$value = postvalue("value_L5");
	$type=postvalue("type_L5");
	if (in_assoc_array("type_L5",$_POST) || in_assoc_array("value_L5",$_POST) || in_assoc_array("value_L5",$_FILES))	
	{
		$value=prepare_for_db("L5",$value,$type);
	}
	else
		$value=false;
	if($value!==false)
	{	



		$evalues["L5"]=$value;
	}


//	processibng L5 - end
//	processing L6 - start
	$value = postvalue("value_L6");
	$type=postvalue("type_L6");
	if (in_assoc_array("type_L6",$_POST) || in_assoc_array("value_L6",$_POST) || in_assoc_array("value_L6",$_FILES))	
	{
		$value=prepare_for_db("L6",$value,$type);
	}
	else
		$value=false;
	if($value!==false)
	{	



		$evalues["L6"]=$value;
	}


//	processibng L6 - end
//	processing L7 - start
	$value = postvalue("value_L7");
	$type=postvalue("type_L7");
	if (in_assoc_array("type_L7",$_POST) || in_assoc_array("value_L7",$_POST) || in_assoc_array("value_L7",$_FILES))	
	{
		$value=prepare_for_db("L7",$value,$type);
	}
	else
		$value=false;
	if($value!==false)
	{	



		$evalues["L7"]=$value;
	}


//	processibng L7 - end

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
	$data["RNUM"]=$evalues["RNUM"];
	$data["Full_Name"]=$evalues["Full_Name"];
	$data["Status"]=$evalues["Status"];
	$data["L1"]=$evalues["L1"];
	$data["L2"]=$evalues["L2"];
	$data["L3"]=$evalues["L3"];
	$data["L4"]=$evalues["L4"];
	$data["L5"]=$evalues["L5"];
	$data["L6"]=$evalues["L6"];
	$data["L7"]=$evalues["L7"];
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
//			$bodyonload.="define('value_RNUM','".$validatetype."','Roll Number');";
			$bodyonload.="define('value_RNUM','".$validatetype."','".jsreplace("Roll Number")."');";
			  		$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
//			$bodyonload.="define('value_Full_Name','".$validatetype."','Full Name');";
			$bodyonload.="define('value_Full_Name','".$validatetype."','".jsreplace("Full Name")."');";
			  		$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
//			$bodyonload.="define('value_Status','".$validatetype."','Status');";
			$bodyonload.="define('value_Status','".$validatetype."','".jsreplace("Status")."');";
				$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
//			$bodyonload.="define('value_L1','".$validatetype."','Lecture 1');";
			$bodyonload.="define('value_L1','".$validatetype."','".jsreplace("Lecture 1")."');";

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
	$includes.="var SUGGEST_TABLE='students_searchsuggest.php';\r\n";
	}
	$includes.="</script>\r\n";

	if ($useAJAX)
		$includes.="<div id=\"search_suggest\"></div>\r\n";





	$xt->assign("CID_fieldblock",true);
	$xt->assign("RNUM_fieldblock",true);
	$xt->assign("Full_Name_fieldblock",true);
	$xt->assign("Status_fieldblock",true);
	$xt->assign("L1_fieldblock",true);
	$xt->assign("L2_fieldblock",true);
	$xt->assign("L3_fieldblock",true);
	$xt->assign("L4_fieldblock",true);
	$xt->assign("L5_fieldblock",true);
	$xt->assign("L6_fieldblock",true);
	$xt->assign("L7_fieldblock",true);

	if(strlen($onsubmit))
		$onsubmit="onSubmit=\"".$onsubmit."\"";
	$body["begin"]=$includes."
	<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"students_edit.php\" ".$onsubmit.">".
	"<input type=hidden name=\"a\" value=\"edited\">";
	$body["begin"].="<input type=\"hidden\" name=\"editid1\" value=\"".htmlspecialchars($keys["SID"])."\">";
		$xt->assign("show_key1", htmlspecialchars(GetData($data,"SID", "")));

	$xt->assign("backbutton_attrs","onclick=\"window.location.href='students_list.php?a=return'\"");
	$xt->assign("save_button",true);
	$xt->assign("reset_button",true);
	$xt->assign("back_button",true);
}

$showKeys[] = rawurlencode($keys["SID"]);

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
	$masterquery="mastertable=students";
	$masterquery.="&masterkey1=".rawurlencode($data["CID"]);
	$showDetailKeys["classes"]=$masterquery;

	$keylink="";
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["SID"]));


//	CID - 

		$value="";
				$value=DisplayLookupWizard("CID",$data["CID"],$data,$keylink,MODE_LIST);
//		$smarty->assign("show_CID",$value);
		$showValues[] = $value;
		$showFields[] = "CID";
				$showRawValues[] = substr($data["CID"],0,100);

//	RNUM - 

		$value="";
				$value = ProcessLargeText(GetData($data,"RNUM", ""),"","",MODE_LIST);
//		$smarty->assign("show_RNUM",$value);
		$showValues[] = $value;
		$showFields[] = "RNUM";
				$showRawValues[] = substr($data["RNUM"],0,100);

//	Full_Name - 

		$value="";
				$value = ProcessLargeText(GetData($data,"Full_Name", ""),"","",MODE_LIST);
//		$smarty->assign("show_Full_Name",$value);
		$showValues[] = $value;
		$showFields[] = "Full_Name";
				$showRawValues[] = substr($data["Full_Name"],0,100);

//	Status - 

		$value="";
				$value = ProcessLargeText(GetData($data,"Status", ""),"","",MODE_LIST);
//		$smarty->assign("show_Status",$value);
		$showValues[] = $value;
		$showFields[] = "Status";
				$showRawValues[] = substr($data["Status"],0,100);

//	L1 - 

		$value="";
				$value=DisplayLookupWizard("L1",$data["L1"],$data,$keylink,MODE_LIST);
//		$smarty->assign("show_L1",$value);
		$showValues[] = $value;
		$showFields[] = "L1";
				$showRawValues[] = substr($data["L1"],0,100);

//	L2 - 

		$value="";
				$value=DisplayLookupWizard("L2",$data["L2"],$data,$keylink,MODE_LIST);
//		$smarty->assign("show_L2",$value);
		$showValues[] = $value;
		$showFields[] = "L2";
				$showRawValues[] = substr($data["L2"],0,100);

//	L3 - 

		$value="";
				$value=DisplayLookupWizard("L3",$data["L3"],$data,$keylink,MODE_LIST);
//		$smarty->assign("show_L3",$value);
		$showValues[] = $value;
		$showFields[] = "L3";
				$showRawValues[] = substr($data["L3"],0,100);

//	L4 - 

		$value="";
				$value=DisplayLookupWizard("L4",$data["L4"],$data,$keylink,MODE_LIST);
//		$smarty->assign("show_L4",$value);
		$showValues[] = $value;
		$showFields[] = "L4";
				$showRawValues[] = substr($data["L4"],0,100);

//	L5 - 

		$value="";
				$value=DisplayLookupWizard("L5",$data["L5"],$data,$keylink,MODE_LIST);
//		$smarty->assign("show_L5",$value);
		$showValues[] = $value;
		$showFields[] = "L5";
				$showRawValues[] = substr($data["L5"],0,100);

//	L6 - 

		$value="";
				$value=DisplayLookupWizard("L6",$data["L6"],$data,$keylink,MODE_LIST);
//		$smarty->assign("show_L6",$value);
		$showValues[] = $value;
		$showFields[] = "L6";
				$showRawValues[] = substr($data["L6"],0,100);

//	L7 - 

		$value="";
				$value=DisplayLookupWizard("L7",$data["L7"],$data,$keylink,MODE_LIST);
//		$smarty->assign("show_L7",$value);
		$showValues[] = $value;
		$showFields[] = "L7";
				$showRawValues[] = substr($data["L7"],0,100);

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
$control_RNUM=array();
$control_RNUM["func"]="xt_buildeditcontrol";
$control_RNUM["params"] = array();
$control_RNUM["params"]["field"]="RNUM";
$control_RNUM["params"]["value"]=@$data["RNUM"];
$control_RNUM["params"]["id"]=$record_id;
if($inlineedit)
	$control_RNUM["params"]["mode"]="inline_edit";
else
	$control_RNUM["params"]["mode"]="edit";
$xt->assignbyref("RNUM_editcontrol",$control_RNUM);
$control_Full_Name=array();
$control_Full_Name["func"]="xt_buildeditcontrol";
$control_Full_Name["params"] = array();
$control_Full_Name["params"]["field"]="Full_Name";
$control_Full_Name["params"]["value"]=@$data["Full_Name"];
$control_Full_Name["params"]["id"]=$record_id;
if($inlineedit)
	$control_Full_Name["params"]["mode"]="inline_edit";
else
	$control_Full_Name["params"]["mode"]="edit";
$xt->assignbyref("Full_Name_editcontrol",$control_Full_Name);
$control_Status=array();
$control_Status["func"]="xt_buildeditcontrol";
$control_Status["params"] = array();
$control_Status["params"]["field"]="Status";
$control_Status["params"]["value"]=@$data["Status"];
$control_Status["params"]["id"]=$record_id;
if($inlineedit)
	$control_Status["params"]["mode"]="inline_edit";
else
	$control_Status["params"]["mode"]="edit";
$xt->assignbyref("Status_editcontrol",$control_Status);
$control_L1=array();
$control_L1["func"]="xt_buildeditcontrol";
$control_L1["params"] = array();
$control_L1["params"]["field"]="L1";
$control_L1["params"]["value"]=@$data["L1"];
$control_L1["params"]["id"]=$record_id;
if($inlineedit)
	$control_L1["params"]["mode"]="inline_edit";
else
	$control_L1["params"]["mode"]="edit";
$xt->assignbyref("L1_editcontrol",$control_L1);
$control_L2=array();
$control_L2["func"]="xt_buildeditcontrol";
$control_L2["params"] = array();
$control_L2["params"]["field"]="L2";
$control_L2["params"]["value"]=@$data["L2"];
$control_L2["params"]["id"]=$record_id;
if($inlineedit)
	$control_L2["params"]["mode"]="inline_edit";
else
	$control_L2["params"]["mode"]="edit";
$xt->assignbyref("L2_editcontrol",$control_L2);
$control_L3=array();
$control_L3["func"]="xt_buildeditcontrol";
$control_L3["params"] = array();
$control_L3["params"]["field"]="L3";
$control_L3["params"]["value"]=@$data["L3"];
$control_L3["params"]["id"]=$record_id;
if($inlineedit)
	$control_L3["params"]["mode"]="inline_edit";
else
	$control_L3["params"]["mode"]="edit";
$xt->assignbyref("L3_editcontrol",$control_L3);
$control_L4=array();
$control_L4["func"]="xt_buildeditcontrol";
$control_L4["params"] = array();
$control_L4["params"]["field"]="L4";
$control_L4["params"]["value"]=@$data["L4"];
$control_L4["params"]["id"]=$record_id;
if($inlineedit)
	$control_L4["params"]["mode"]="inline_edit";
else
	$control_L4["params"]["mode"]="edit";
$xt->assignbyref("L4_editcontrol",$control_L4);
$control_L5=array();
$control_L5["func"]="xt_buildeditcontrol";
$control_L5["params"] = array();
$control_L5["params"]["field"]="L5";
$control_L5["params"]["value"]=@$data["L5"];
$control_L5["params"]["id"]=$record_id;
if($inlineedit)
	$control_L5["params"]["mode"]="inline_edit";
else
	$control_L5["params"]["mode"]="edit";
$xt->assignbyref("L5_editcontrol",$control_L5);
$control_L6=array();
$control_L6["func"]="xt_buildeditcontrol";
$control_L6["params"] = array();
$control_L6["params"]["field"]="L6";
$control_L6["params"]["value"]=@$data["L6"];
$control_L6["params"]["id"]=$record_id;
if($inlineedit)
	$control_L6["params"]["mode"]="inline_edit";
else
	$control_L6["params"]["mode"]="edit";
$xt->assignbyref("L6_editcontrol",$control_L6);
$control_L7=array();
$control_L7["func"]="xt_buildeditcontrol";
$control_L7["params"] = array();
$control_L7["params"]["field"]="L7";
$control_L7["params"]["value"]=@$data["L7"];
$control_L7["params"]["id"]=$record_id;
if($inlineedit)
	$control_L7["params"]["mode"]="inline_edit";
else
	$control_L7["params"]["mode"]="edit";
$xt->assignbyref("L7_editcontrol",$control_L7);

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
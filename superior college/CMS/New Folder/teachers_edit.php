<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/teachers_variables.php");



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
$templatefile = ( $inlineedit ) ? "teachers_inline_edit.htm" : "teachers_edit.htm";

/////////////////////////////////////////////////////////////
//connect database
/////////////////////////////////////////////////////////////
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessEdit"))
	BeforeProcessEdit($conn);

$keys=array();
$keys["TID"]=postvalue("editid1");

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
//	processing Designation - start
	$value = postvalue("value_Designation");
	$type=postvalue("type_Designation");
	if (in_assoc_array("type_Designation",$_POST) || in_assoc_array("value_Designation",$_POST) || in_assoc_array("value_Designation",$_FILES))	
	{
		$value=prepare_for_db("Designation",$value,$type);
	}
	else
		$value=false;
	if($value!==false)
	{	



		$evalues["Designation"]=$value;
	}


//	processibng Designation - end
//	processing Phone - start
	$value = postvalue("value_Phone");
	$type=postvalue("type_Phone");
	if (in_assoc_array("type_Phone",$_POST) || in_assoc_array("value_Phone",$_POST) || in_assoc_array("value_Phone",$_FILES))	
	{
		$value=prepare_for_db("Phone",$value,$type);
	}
	else
		$value=false;
	if($value!==false)
	{	



		$evalues["Phone"]=$value;
	}


//	processibng Phone - end
//	processing Address - start
	$value = postvalue("value_Address");
	$type=postvalue("type_Address");
	if (in_assoc_array("type_Address",$_POST) || in_assoc_array("value_Address",$_POST) || in_assoc_array("value_Address",$_FILES))	
	{
		$value=prepare_for_db("Address",$value,$type);
	}
	else
		$value=false;
	if($value!==false)
	{	



		$evalues["Address"]=$value;
	}


//	processibng Address - end

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
	$data["Full_Name"]=$evalues["Full_Name"];
	$data["Designation"]=$evalues["Designation"];
	$data["Phone"]=$evalues["Phone"];
	$data["Address"]=$evalues["Address"];
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
	$includes.="var SUGGEST_TABLE='teachers_searchsuggest.php';\r\n";
	}
	$includes.="</script>\r\n";

	if ($useAJAX)
		$includes.="<div id=\"search_suggest\"></div>\r\n";





	$xt->assign("Full_Name_fieldblock",true);
	$xt->assign("Designation_fieldblock",true);
	$xt->assign("Phone_fieldblock",true);
	$xt->assign("Address_fieldblock",true);

	if(strlen($onsubmit))
		$onsubmit="onSubmit=\"".$onsubmit."\"";
	$body["begin"]=$includes."
	<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"teachers_edit.php\" ".$onsubmit.">".
	"<input type=hidden name=\"a\" value=\"edited\">";
	$body["begin"].="<input type=\"hidden\" name=\"editid1\" value=\"".htmlspecialchars($keys["TID"])."\">";
		$xt->assign("show_key1", htmlspecialchars(GetData($data,"TID", "")));

	$xt->assign("backbutton_attrs","onclick=\"window.location.href='teachers_list.php?a=return'\"");
	$xt->assign("save_button",true);
	$xt->assign("reset_button",true);
	$xt->assign("back_button",true);
}

$showKeys[] = rawurlencode($keys["TID"]);

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
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["TID"]));


//	Full_Name - 

		$value="";
				$value = ProcessLargeText(GetData($data,"Full_Name", ""),"","",MODE_LIST);
//		$smarty->assign("show_Full_Name",$value);
		$showValues[] = $value;
		$showFields[] = "Full_Name";
				$showRawValues[] = substr($data["Full_Name"],0,100);

//	Designation - 

		$value="";
				$value = ProcessLargeText(GetData($data,"Designation", ""),"","",MODE_LIST);
//		$smarty->assign("show_Designation",$value);
		$showValues[] = $value;
		$showFields[] = "Designation";
				$showRawValues[] = substr($data["Designation"],0,100);

//	Phone - 

		$value="";
				$value = ProcessLargeText(GetData($data,"Phone", ""),"","",MODE_LIST);
//		$smarty->assign("show_Phone",$value);
		$showValues[] = $value;
		$showFields[] = "Phone";
				$showRawValues[] = substr($data["Phone"],0,100);

//	Address - 

		$value="";
				$value = ProcessLargeText(GetData($data,"Address", ""),"","",MODE_LIST);
//		$smarty->assign("show_Address",$value);
		$showValues[] = $value;
		$showFields[] = "Address";
				$showRawValues[] = substr($data["Address"],0,100);

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
$control_Designation=array();
$control_Designation["func"]="xt_buildeditcontrol";
$control_Designation["params"] = array();
$control_Designation["params"]["field"]="Designation";
$control_Designation["params"]["value"]=@$data["Designation"];
$control_Designation["params"]["id"]=$record_id;
if($inlineedit)
	$control_Designation["params"]["mode"]="inline_edit";
else
	$control_Designation["params"]["mode"]="edit";
$xt->assignbyref("Designation_editcontrol",$control_Designation);
$control_Phone=array();
$control_Phone["func"]="xt_buildeditcontrol";
$control_Phone["params"] = array();
$control_Phone["params"]["field"]="Phone";
$control_Phone["params"]["value"]=@$data["Phone"];
$control_Phone["params"]["id"]=$record_id;
if($inlineedit)
	$control_Phone["params"]["mode"]="inline_edit";
else
	$control_Phone["params"]["mode"]="edit";
$xt->assignbyref("Phone_editcontrol",$control_Phone);
$control_Address=array();
$control_Address["func"]="xt_buildeditcontrol";
$control_Address["params"] = array();
$control_Address["params"]["field"]="Address";
$control_Address["params"]["value"]=@$data["Address"];
$control_Address["params"]["id"]=$record_id;
if($inlineedit)
	$control_Address["params"]["mode"]="inline_edit";
else
	$control_Address["params"]["mode"]="edit";
$xt->assignbyref("Address_editcontrol",$control_Address);

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
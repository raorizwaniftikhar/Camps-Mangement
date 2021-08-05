<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/attendance_variables.php");


//connect database
$conn=db_connect();


include('libs/xtempl.php');
$xt = new Xtempl();

//	Before Process event
if(function_exists("BeforeProcessSearch"))
	BeforeProcessSearch($conn);


$includes=
"<script language=\"JavaScript\" src=\"include/calendar.js\"></script>
<script language=\"JavaScript\" src=\"include/jsfunctions.js\"></script>\r\n";
$includes.="<script language=\"JavaScript\" src=\"include/jquery.js\"></script>";
if ($useAJAX) {
$includes.="<script language=\"JavaScript\" src=\"include/ajaxsuggest.js\"></script>\r\n";
}
$includes.="<script language=\"JavaScript\" type=\"text/javascript\">\r\n".
"var locale_dateformat = ".$locale_info["LOCALE_IDATE"].";\r\n".
"var locale_datedelimiter = \"".$locale_info["LOCALE_SDATE"]."\";\r\n".
"var bLoading=false;\r\n".
"var TEXT_PLEASE_SELECT='".addslashes("Please select")."';\r\n";
if ($useAJAX) {
$includes.="var SUGGEST_TABLE = \"attendance_searchsuggest.php\";\r\n";
}
$includes.="var detect = navigator.userAgent.toLowerCase();

function checkIt(string)
{
	place = detect.indexOf(string) + 1;
	thestring = string;
	return place;
}


function ShowHideControls()
{
	document.getElementById('second_SID').style.display =  
		document.forms.editform.elements['asearchopt_SID'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_LRID').style.display =  
		document.forms.editform.elements['asearchopt_LRID'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Daate').style.display =  
		document.forms.editform.elements['asearchopt_Daate'].value==\"Between\" ? '' : 'none'; 
	return false;
}
function ResetControls()
{
	var i;
	e = document.forms[0].elements; 
	for (i=0;i<e.length;i++) 
	{
		if (e[i].name!='type' && e[i].className!='button' && e[i].type!='hidden')
		{
			if(e[i].type=='select-one')
				e[i].selectedIndex=0;
			else if(e[i].type=='select-multiple')
			{
				var j;
				for(j=0;j<e[i].options.length;j++)
					e[i].options[j].selected=false;
			}
			else if(e[i].type=='checkbox' || e[i].type=='radio')
				e[i].checked=false;
			else 
				e[i].value = ''; 
		}
		else if(e[i].name.substr(0,6)=='value_' && e[i].type=='hidden')
			e[i].value = ''; 
	}
	ShowHideControls();	
	return false;
}";

$includes.="
$(document).ready(function() {
});
</script>
<div id=\"search_suggest\"></div>
";



$all_checkbox="value=\"and\"";
$any_checkbox="value=\"or\"";

if(@$_SESSION[$strTableName."_asearchtype"]=="or")
	$any_checkbox.=" checked";
else
	$all_checkbox.=" checked";
$xt->assign("any_checkbox",$any_checkbox);
$xt->assign("all_checkbox",$all_checkbox);

$editformats=array();

// SID 
$opt="";
$not=false;
$control_SID=array();
$control_SID["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["SID"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["SID"];
	$control_SID["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["SID"];
}
$control_SID["func"]="xt_buildeditcontrol";
$control_SID["params"]["field"]="SID";
$control_SID["params"]["mode"]="search";
$xt->assignbyref("SID_editcontrol",$control_SID);
$control1_SID=$control_SID;
$control1_SID["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_SID["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["SID"];
$xt->assignbyref("SID_editcontrol1",$control1_SID);
	
$xt->assign_section("SID_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"SID\">","");
$notbox_SID="name=\"not_SID\"";
if($not)
	$notbox_SID=" checked";
$xt->assign("SID_notbox",$notbox_SID);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_SID\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_SID",$searchtype);
//	edit format
$editformats["SID"]="Lookup wizard";
// LRID 
$opt="";
$not=false;
$control_LRID=array();
$control_LRID["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["LRID"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["LRID"];
	$control_LRID["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["LRID"];
}
$control_LRID["func"]="xt_buildeditcontrol";
$control_LRID["params"]["field"]="LRID";
$control_LRID["params"]["mode"]="search";
$xt->assignbyref("LRID_editcontrol",$control_LRID);
$control1_LRID=$control_LRID;
$control1_LRID["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_LRID["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["LRID"];
$xt->assignbyref("LRID_editcontrol1",$control1_LRID);
	
$xt->assign_section("LRID_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"LRID\">","");
$notbox_LRID="name=\"not_LRID\"";
if($not)
	$notbox_LRID=" checked";
$xt->assign("LRID_notbox",$notbox_LRID);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_LRID\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_LRID",$searchtype);
//	edit format
$editformats["LRID"]="Lookup wizard";
// Daate 
$opt="";
$not=false;
$control_Daate=array();
$control_Daate["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Daate"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Daate"];
	$control_Daate["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Daate"];
}
$control_Daate["func"]="xt_buildeditcontrol";
$control_Daate["params"]["field"]="Daate";
$control_Daate["params"]["mode"]="search";
$xt->assignbyref("Daate_editcontrol",$control_Daate);
$control1_Daate=$control_Daate;
$control1_Daate["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Daate["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Daate"];
$xt->assignbyref("Daate_editcontrol1",$control1_Daate);
	
$xt->assign_section("Daate_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Daate\">","");
$notbox_Daate="name=\"not_Daate\"";
if($not)
	$notbox_Daate=" checked";
$xt->assign("Daate_notbox",$notbox_Daate);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Daate\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Daate",$searchtype);
//	edit format
$editformats["Daate"]="Date";

$linkdata="";

$linkdata .= "<script type=\"text/javascript\">\r\n";

if ($useAJAX) {
}
else
{
}
$linkdata.="</script>\r\n";


$body=array();
$body["begin"]=$includes;
$body["end"]=$linkdata."<script>ShowHideControls()</script>";
$xt->assignbyref("body",$body);

$contents_block=array();
$contents_block["begin"]="<form method=\"POST\" ";
if(isset( $_GET["rname"]))
{
	$contents_block["begin"].="action=\"dreport.php?rname=".htmlspecialchars(rawurlencode(postvalue("rname")))."\" ";
}	
else if(isset( $_GET["cname"]))
{
	$contents_block["begin"].="action=\"dchart.php?cname=".htmlspecialchars(rawurlencode(postvalue("cname")))."\" ";
}	
else
{
$contents_block["begin"].="action=\"attendance_list.php\" ";
}
$contents_block["begin"].="name=\"editform\"><input type=\"hidden\" id=\"a\" name=\"a\" value=\"advsearch\">";
$contents_block["end"]="</form>";
$xt->assignbyref("contents_block",$contents_block);

$xt->assign("searchbutton_attrs","name=\"SearchButton\" onclick=\"javascript:document.forms.editform.submit();\"");
$xt->assign("resetbutton_attrs","onclick=\"return ResetControls();\"");

$xt->assign("backbutton_attrs","onclick=\"javascript: document.forms.editform.a.value='return'; document.forms.editform.submit();\"");

$xt->assign("conditions_block",true);
$xt->assign("search_button",true);
$xt->assign("reset_button",true);
$xt->assign("back_button",true);

	
$templatefile = "attendance_search.htm";
if(function_exists("BeforeShowSearch"))
	BeforeShowSearch($xt,$templatefile);

$xt->display($templatefile);

?>
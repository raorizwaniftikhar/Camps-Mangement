<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/users_variables.php");


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
$includes.="var SUGGEST_TABLE = \"users_searchsuggest.php\";\r\n";
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
	document.getElementById('second_ID').style.display =  
		document.forms.editform.elements['asearchopt_ID'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_UID').style.display =  
		document.forms.editform.elements['asearchopt_UID'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_PWD').style.display =  
		document.forms.editform.elements['asearchopt_PWD'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Type').style.display =  
		document.forms.editform.elements['asearchopt_Type'].value==\"Between\" ? '' : 'none'; 
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
	document.forms.editform.value_UID.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_UID,'advanced')};
	document.forms.editform.value1_UID.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_UID,'advanced1')};
	document.forms.editform.value_UID.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_UID,'advanced')};
	document.forms.editform.value1_UID.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_UID,'advanced1')};
	document.forms.editform.value_Type.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Type,'advanced')};
	document.forms.editform.value1_Type.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Type,'advanced1')};
	document.forms.editform.value_Type.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Type,'advanced')};
	document.forms.editform.value1_Type.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Type,'advanced1')};
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

// ID 
$opt="";
$not=false;
$control_ID=array();
$control_ID["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["ID"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["ID"];
	$control_ID["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["ID"];
}
$control_ID["func"]="xt_buildeditcontrol";
$control_ID["params"]["field"]="ID";
$control_ID["params"]["mode"]="search";
$xt->assignbyref("ID_editcontrol",$control_ID);
$control1_ID=$control_ID;
$control1_ID["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_ID["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["ID"];
$xt->assignbyref("ID_editcontrol1",$control1_ID);
	
$xt->assign_section("ID_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"ID\">","");
$notbox_ID="name=\"not_ID\"";
if($not)
	$notbox_ID=" checked";
$xt->assign("ID_notbox",$notbox_ID);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_ID\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_ID",$searchtype);
//	edit format
$editformats["ID"]=EDIT_FORMAT_TEXT_FIELD;
// UID 
$opt="";
$not=false;
$control_UID=array();
$control_UID["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["UID"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["UID"];
	$control_UID["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["UID"];
}
$control_UID["func"]="xt_buildeditcontrol";
$control_UID["params"]["field"]="UID";
$control_UID["params"]["mode"]="search";
$xt->assignbyref("UID_editcontrol",$control_UID);
$control1_UID=$control_UID;
$control1_UID["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_UID["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["UID"];
$xt->assignbyref("UID_editcontrol1",$control1_UID);
	
$xt->assign_section("UID_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"UID\">","");
$notbox_UID="name=\"not_UID\"";
if($not)
	$notbox_UID=" checked";
$xt->assign("UID_notbox",$notbox_UID);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_UID\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_UID",$searchtype);
//	edit format
$editformats["UID"]="Text field";
// PWD 
$opt="";
$not=false;
$control_PWD=array();
$control_PWD["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["PWD"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["PWD"];
	$control_PWD["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["PWD"];
}
$control_PWD["func"]="xt_buildeditcontrol";
$control_PWD["params"]["field"]="PWD";
$control_PWD["params"]["mode"]="search";
$xt->assignbyref("PWD_editcontrol",$control_PWD);
$control1_PWD=$control_PWD;
$control1_PWD["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_PWD["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["PWD"];
$xt->assignbyref("PWD_editcontrol1",$control1_PWD);
	
$xt->assign_section("PWD_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"PWD\">","");
$notbox_PWD="name=\"not_PWD\"";
if($not)
	$notbox_PWD=" checked";
$xt->assign("PWD_notbox",$notbox_PWD);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_PWD\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_PWD",$searchtype);
//	edit format
$editformats["PWD"]=EDIT_FORMAT_TEXT_FIELD;
// Type 
$opt="";
$not=false;
$control_Type=array();
$control_Type["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Type"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Type"];
	$control_Type["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Type"];
}
$control_Type["func"]="xt_buildeditcontrol";
$control_Type["params"]["field"]="Type";
$control_Type["params"]["mode"]="search";
$xt->assignbyref("Type_editcontrol",$control_Type);
$control1_Type=$control_Type;
$control1_Type["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Type["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Type"];
$xt->assignbyref("Type_editcontrol1",$control1_Type);
	
$xt->assign_section("Type_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Type\">","");
$notbox_Type="name=\"not_Type\"";
if($not)
	$notbox_Type=" checked";
$xt->assign("Type_notbox",$notbox_Type);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Type\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Type",$searchtype);
//	edit format
$editformats["Type"]="Text field";

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
$contents_block["begin"].="action=\"users_list.php\" ";
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

	
$templatefile = "users_search.htm";
if(function_exists("BeforeShowSearch"))
	BeforeShowSearch($xt,$templatefile);

$xt->display($templatefile);

?>
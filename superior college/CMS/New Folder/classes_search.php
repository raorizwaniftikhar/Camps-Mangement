<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/classes_variables.php");


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
$includes.="var SUGGEST_TABLE = \"classes_searchsuggest.php\";\r\n";
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
	document.getElementById('second_CID').style.display =  
		document.forms.editform.elements['asearchopt_CID'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Session').style.display =  
		document.forms.editform.elements['asearchopt_Session'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Semester').style.display =  
		document.forms.editform.elements['asearchopt_Semester'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Subjects').style.display =  
		document.forms.editform.elements['asearchopt_Subjects'].value==\"Between\" ? '' : 'none'; 
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
	document.forms.editform.value_CID.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_CID,'advanced')};
	document.forms.editform.value1_CID.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_CID,'advanced1')};
	document.forms.editform.value_CID.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_CID,'advanced')};
	document.forms.editform.value1_CID.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_CID,'advanced1')};
	document.forms.editform.value_Session.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Session,'advanced')};
	document.forms.editform.value1_Session.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Session,'advanced1')};
	document.forms.editform.value_Session.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Session,'advanced')};
	document.forms.editform.value1_Session.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Session,'advanced1')};
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

// CID 
$opt="";
$not=false;
$control_CID=array();
$control_CID["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["CID"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["CID"];
	$control_CID["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["CID"];
}
$control_CID["func"]="xt_buildeditcontrol";
$control_CID["params"]["field"]="CID";
$control_CID["params"]["mode"]="search";
$xt->assignbyref("CID_editcontrol",$control_CID);
$control1_CID=$control_CID;
$control1_CID["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_CID["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["CID"];
$xt->assignbyref("CID_editcontrol1",$control1_CID);
	
$xt->assign_section("CID_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"CID\">","");
$notbox_CID="name=\"not_CID\"";
if($not)
	$notbox_CID=" checked";
$xt->assign("CID_notbox",$notbox_CID);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_CID\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_CID",$searchtype);
//	edit format
$editformats["CID"]="Text field";
// Session 
$opt="";
$not=false;
$control_Session=array();
$control_Session["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Session"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Session"];
	$control_Session["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Session"];
}
$control_Session["func"]="xt_buildeditcontrol";
$control_Session["params"]["field"]="Session";
$control_Session["params"]["mode"]="search";
$xt->assignbyref("Session_editcontrol",$control_Session);
$control1_Session=$control_Session;
$control1_Session["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Session["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Session"];
$xt->assignbyref("Session_editcontrol1",$control1_Session);
	
$xt->assign_section("Session_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Session\">","");
$notbox_Session="name=\"not_Session\"";
if($not)
	$notbox_Session=" checked";
$xt->assign("Session_notbox",$notbox_Session);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Session\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Session",$searchtype);
//	edit format
$editformats["Session"]="Text field";
// Semester 
$opt="";
$not=false;
$control_Semester=array();
$control_Semester["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Semester"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Semester"];
	$control_Semester["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Semester"];
}
$control_Semester["func"]="xt_buildeditcontrol";
$control_Semester["params"]["field"]="Semester";
$control_Semester["params"]["mode"]="search";
$xt->assignbyref("Semester_editcontrol",$control_Semester);
$control1_Semester=$control_Semester;
$control1_Semester["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Semester["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Semester"];
$xt->assignbyref("Semester_editcontrol1",$control1_Semester);
	
$xt->assign_section("Semester_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Semester\">","");
$notbox_Semester="name=\"not_Semester\"";
if($not)
	$notbox_Semester=" checked";
$xt->assign("Semester_notbox",$notbox_Semester);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Semester\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Semester",$searchtype);
//	edit format
$editformats["Semester"]="Lookup wizard";
// Subjects 
$opt="";
$not=false;
$control_Subjects=array();
$control_Subjects["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Subjects"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Subjects"];
	$control_Subjects["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Subjects"];
}
$control_Subjects["func"]="xt_buildeditcontrol";
$control_Subjects["params"]["field"]="Subjects";
$control_Subjects["params"]["mode"]="search";
$xt->assignbyref("Subjects_editcontrol",$control_Subjects);
$control1_Subjects=$control_Subjects;
$control1_Subjects["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Subjects["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Subjects"];
$xt->assignbyref("Subjects_editcontrol1",$control1_Subjects);
	
$xt->assign_section("Subjects_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Subjects\">","");
$notbox_Subjects="name=\"not_Subjects\"";
if($not)
	$notbox_Subjects=" checked";
$xt->assign("Subjects_notbox",$notbox_Subjects);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Subjects\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Subjects",$searchtype);
//	edit format
$editformats["Subjects"]="Lookup wizard";

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
$contents_block["begin"].="action=\"classes_list.php\" ";
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

	
$templatefile = "classes_search.htm";
if(function_exists("BeforeShowSearch"))
	BeforeShowSearch($xt,$templatefile);

$xt->display($templatefile);

?>
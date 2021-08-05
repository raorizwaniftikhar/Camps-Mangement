<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/marks_variables.php");


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
$includes.="var SUGGEST_TABLE = \"marks_searchsuggest.php\";\r\n";
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
	document.getElementById('second_LID').style.display =  
		document.forms.editform.elements['asearchopt_LID'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_SESSINAL').style.display =  
		document.forms.editform.elements['asearchopt_SESSINAL'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_MIDTERM').style.display =  
		document.forms.editform.elements['asearchopt_MIDTERM'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_FINAL').style.display =  
		document.forms.editform.elements['asearchopt_FINAL'].value==\"Between\" ? '' : 'none'; 
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
	document.forms.editform.value_SESSINAL.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_SESSINAL,'advanced')};
	document.forms.editform.value1_SESSINAL.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_SESSINAL,'advanced1')};
	document.forms.editform.value_SESSINAL.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_SESSINAL,'advanced')};
	document.forms.editform.value1_SESSINAL.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_SESSINAL,'advanced1')};
	document.forms.editform.value_MIDTERM.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_MIDTERM,'advanced')};
	document.forms.editform.value1_MIDTERM.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_MIDTERM,'advanced1')};
	document.forms.editform.value_MIDTERM.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_MIDTERM,'advanced')};
	document.forms.editform.value1_MIDTERM.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_MIDTERM,'advanced1')};
	document.forms.editform.value_FINAL.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_FINAL,'advanced')};
	document.forms.editform.value1_FINAL.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_FINAL,'advanced1')};
	document.forms.editform.value_FINAL.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_FINAL,'advanced')};
	document.forms.editform.value1_FINAL.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_FINAL,'advanced1')};
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
// LID 
$opt="";
$not=false;
$control_LID=array();
$control_LID["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["LID"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["LID"];
	$control_LID["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["LID"];
}
$control_LID["func"]="xt_buildeditcontrol";
$control_LID["params"]["field"]="LID";
$control_LID["params"]["mode"]="search";
$xt->assignbyref("LID_editcontrol",$control_LID);
$control1_LID=$control_LID;
$control1_LID["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_LID["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["LID"];
$xt->assignbyref("LID_editcontrol1",$control1_LID);
	
$xt->assign_section("LID_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"LID\">","");
$notbox_LID="name=\"not_LID\"";
if($not)
	$notbox_LID=" checked";
$xt->assign("LID_notbox",$notbox_LID);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_LID\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_LID",$searchtype);
//	edit format
$editformats["LID"]="Lookup wizard";
// SESSINAL 
$opt="";
$not=false;
$control_SESSINAL=array();
$control_SESSINAL["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["SESSINAL"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["SESSINAL"];
	$control_SESSINAL["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["SESSINAL"];
}
$control_SESSINAL["func"]="xt_buildeditcontrol";
$control_SESSINAL["params"]["field"]="SESSINAL";
$control_SESSINAL["params"]["mode"]="search";
$xt->assignbyref("SESSINAL_editcontrol",$control_SESSINAL);
$control1_SESSINAL=$control_SESSINAL;
$control1_SESSINAL["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_SESSINAL["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["SESSINAL"];
$xt->assignbyref("SESSINAL_editcontrol1",$control1_SESSINAL);
	
$xt->assign_section("SESSINAL_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"SESSINAL\">","");
$notbox_SESSINAL="name=\"not_SESSINAL\"";
if($not)
	$notbox_SESSINAL=" checked";
$xt->assign("SESSINAL_notbox",$notbox_SESSINAL);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_SESSINAL\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_SESSINAL",$searchtype);
//	edit format
$editformats["SESSINAL"]="Text field";
// MIDTERM 
$opt="";
$not=false;
$control_MIDTERM=array();
$control_MIDTERM["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["MIDTERM"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["MIDTERM"];
	$control_MIDTERM["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["MIDTERM"];
}
$control_MIDTERM["func"]="xt_buildeditcontrol";
$control_MIDTERM["params"]["field"]="MIDTERM";
$control_MIDTERM["params"]["mode"]="search";
$xt->assignbyref("MIDTERM_editcontrol",$control_MIDTERM);
$control1_MIDTERM=$control_MIDTERM;
$control1_MIDTERM["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_MIDTERM["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["MIDTERM"];
$xt->assignbyref("MIDTERM_editcontrol1",$control1_MIDTERM);
	
$xt->assign_section("MIDTERM_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"MIDTERM\">","");
$notbox_MIDTERM="name=\"not_MIDTERM\"";
if($not)
	$notbox_MIDTERM=" checked";
$xt->assign("MIDTERM_notbox",$notbox_MIDTERM);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_MIDTERM\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_MIDTERM",$searchtype);
//	edit format
$editformats["MIDTERM"]="Text field";
// FINAL 
$opt="";
$not=false;
$control_FINAL=array();
$control_FINAL["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["FINAL"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["FINAL"];
	$control_FINAL["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["FINAL"];
}
$control_FINAL["func"]="xt_buildeditcontrol";
$control_FINAL["params"]["field"]="FINAL";
$control_FINAL["params"]["mode"]="search";
$xt->assignbyref("FINAL_editcontrol",$control_FINAL);
$control1_FINAL=$control_FINAL;
$control1_FINAL["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_FINAL["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["FINAL"];
$xt->assignbyref("FINAL_editcontrol1",$control1_FINAL);
	
$xt->assign_section("FINAL_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"FINAL\">","");
$notbox_FINAL="name=\"not_FINAL\"";
if($not)
	$notbox_FINAL=" checked";
$xt->assign("FINAL_notbox",$notbox_FINAL);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_FINAL\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_FINAL",$searchtype);
//	edit format
$editformats["FINAL"]="Text field";

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
$contents_block["begin"].="action=\"marks_list.php\" ";
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

	
$templatefile = "marks_search.htm";
if(function_exists("BeforeShowSearch"))
	BeforeShowSearch($xt,$templatefile);

$xt->display($templatefile);

?>
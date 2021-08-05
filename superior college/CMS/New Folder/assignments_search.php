<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/assignments_variables.php");


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
$includes.="var SUGGEST_TABLE = \"assignments_searchsuggest.php\";\r\n";
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
	document.getElementById('second_Title').style.display =  
		document.forms.editform.elements['asearchopt_Title'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Marks').style.display =  
		document.forms.editform.elements['asearchopt_Marks'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Description').style.display =  
		document.forms.editform.elements['asearchopt_Description'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_File').style.display =  
		document.forms.editform.elements['asearchopt_File'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_LID').style.display =  
		document.forms.editform.elements['asearchopt_LID'].value==\"Between\" ? '' : 'none'; 
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
	document.forms.editform.value_Title.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Title,'advanced')};
	document.forms.editform.value1_Title.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Title,'advanced1')};
	document.forms.editform.value_Title.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Title,'advanced')};
	document.forms.editform.value1_Title.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Title,'advanced1')};
	document.forms.editform.value_Marks.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Marks,'advanced')};
	document.forms.editform.value1_Marks.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Marks,'advanced1')};
	document.forms.editform.value_Marks.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Marks,'advanced')};
	document.forms.editform.value1_Marks.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Marks,'advanced1')};
	document.forms.editform.value_Description.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Description,'advanced')};
	document.forms.editform.value1_Description.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Description,'advanced1')};
	document.forms.editform.value_Description.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Description,'advanced')};
	document.forms.editform.value1_Description.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Description,'advanced1')};
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

// Title 
$opt="";
$not=false;
$control_Title=array();
$control_Title["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Title"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Title"];
	$control_Title["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Title"];
}
$control_Title["func"]="xt_buildeditcontrol";
$control_Title["params"]["field"]="Title";
$control_Title["params"]["mode"]="search";
$xt->assignbyref("Title_editcontrol",$control_Title);
$control1_Title=$control_Title;
$control1_Title["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Title["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Title"];
$xt->assignbyref("Title_editcontrol1",$control1_Title);
	
$xt->assign_section("Title_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Title\">","");
$notbox_Title="name=\"not_Title\"";
if($not)
	$notbox_Title=" checked";
$xt->assign("Title_notbox",$notbox_Title);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Title\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Title",$searchtype);
//	edit format
$editformats["Title"]="Text field";
// Marks 
$opt="";
$not=false;
$control_Marks=array();
$control_Marks["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Marks"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Marks"];
	$control_Marks["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Marks"];
}
$control_Marks["func"]="xt_buildeditcontrol";
$control_Marks["params"]["field"]="Marks";
$control_Marks["params"]["mode"]="search";
$xt->assignbyref("Marks_editcontrol",$control_Marks);
$control1_Marks=$control_Marks;
$control1_Marks["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Marks["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Marks"];
$xt->assignbyref("Marks_editcontrol1",$control1_Marks);
	
$xt->assign_section("Marks_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Marks\">","");
$notbox_Marks="name=\"not_Marks\"";
if($not)
	$notbox_Marks=" checked";
$xt->assign("Marks_notbox",$notbox_Marks);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Marks\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Marks",$searchtype);
//	edit format
$editformats["Marks"]="Text field";
// Description 
$opt="";
$not=false;
$control_Description=array();
$control_Description["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Description"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Description"];
	$control_Description["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Description"];
}
$control_Description["func"]="xt_buildeditcontrol";
$control_Description["params"]["field"]="Description";
$control_Description["params"]["mode"]="search";
$xt->assignbyref("Description_editcontrol",$control_Description);
$control1_Description=$control_Description;
$control1_Description["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Description["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Description"];
$xt->assignbyref("Description_editcontrol1",$control1_Description);
	
$xt->assign_section("Description_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Description\">","");
$notbox_Description="name=\"not_Description\"";
if($not)
	$notbox_Description=" checked";
$xt->assign("Description_notbox",$notbox_Description);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Description\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Description",$searchtype);
//	edit format
$editformats["Description"]="Text field";
// File 
$opt="";
$not=false;
$control_File=array();
$control_File["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["File"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["File"];
	$control_File["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["File"];
}
$control_File["func"]="xt_buildeditcontrol";
$control_File["params"]["field"]="File";
$control_File["params"]["mode"]="search";
$xt->assignbyref("File_editcontrol",$control_File);
$control1_File=$control_File;
$control1_File["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_File["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["File"];
$xt->assignbyref("File_editcontrol1",$control1_File);
	
$xt->assign_section("File_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"File\">","");
$notbox_File="name=\"not_File\"";
if($not)
	$notbox_File=" checked";
$xt->assign("File_notbox",$notbox_File);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_File\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_File",$searchtype);
//	edit format
$editformats["File"]=EDIT_FORMAT_TEXT_FIELD;
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
$contents_block["begin"].="action=\"assignments_list.php\" ";
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

	
$templatefile = "assignments_search.htm";
if(function_exists("BeforeShowSearch"))
	BeforeShowSearch($xt,$templatefile);

$xt->display($templatefile);

?>
<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/teachers_variables.php");


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
$includes.="var SUGGEST_TABLE = \"teachers_searchsuggest.php\";\r\n";
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
	document.getElementById('second_Full_Name').style.display =  
		document.forms.editform.elements['asearchopt_Full_Name'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_UID').style.display =  
		document.forms.editform.elements['asearchopt_UID'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Designation').style.display =  
		document.forms.editform.elements['asearchopt_Designation'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Phone').style.display =  
		document.forms.editform.elements['asearchopt_Phone'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Address').style.display =  
		document.forms.editform.elements['asearchopt_Address'].value==\"Between\" ? '' : 'none'; 
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
	document.forms.editform.value_Full_Name.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Full_Name,'advanced')};
	document.forms.editform.value1_Full_Name.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Full_Name,'advanced1')};
	document.forms.editform.value_Full_Name.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Full_Name,'advanced')};
	document.forms.editform.value1_Full_Name.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Full_Name,'advanced1')};
	document.forms.editform.value_UID.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_UID,'advanced')};
	document.forms.editform.value1_UID.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_UID,'advanced1')};
	document.forms.editform.value_UID.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_UID,'advanced')};
	document.forms.editform.value1_UID.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_UID,'advanced1')};
	document.forms.editform.value_Designation.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Designation,'advanced')};
	document.forms.editform.value1_Designation.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Designation,'advanced1')};
	document.forms.editform.value_Designation.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Designation,'advanced')};
	document.forms.editform.value1_Designation.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Designation,'advanced1')};
	document.forms.editform.value_Phone.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Phone,'advanced')};
	document.forms.editform.value1_Phone.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Phone,'advanced1')};
	document.forms.editform.value_Phone.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Phone,'advanced')};
	document.forms.editform.value1_Phone.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Phone,'advanced1')};
	document.forms.editform.value_Address.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Address,'advanced')};
	document.forms.editform.value1_Address.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Address,'advanced1')};
	document.forms.editform.value_Address.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Address,'advanced')};
	document.forms.editform.value1_Address.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Address,'advanced1')};
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

// Full_Name 
$opt="";
$not=false;
$control_Full_Name=array();
$control_Full_Name["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Full_Name"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Full_Name"];
	$control_Full_Name["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Full_Name"];
}
$control_Full_Name["func"]="xt_buildeditcontrol";
$control_Full_Name["params"]["field"]="Full_Name";
$control_Full_Name["params"]["mode"]="search";
$xt->assignbyref("Full_Name_editcontrol",$control_Full_Name);
$control1_Full_Name=$control_Full_Name;
$control1_Full_Name["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Full_Name["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Full_Name"];
$xt->assignbyref("Full_Name_editcontrol1",$control1_Full_Name);
	
$xt->assign_section("Full_Name_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Full_Name\">","");
$notbox_Full_Name="name=\"not_Full_Name\"";
if($not)
	$notbox_Full_Name=" checked";
$xt->assign("Full_Name_notbox",$notbox_Full_Name);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Full_Name\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Full_Name",$searchtype);
//	edit format
$editformats["Full_Name"]="Text field";
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
// Designation 
$opt="";
$not=false;
$control_Designation=array();
$control_Designation["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Designation"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Designation"];
	$control_Designation["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Designation"];
}
$control_Designation["func"]="xt_buildeditcontrol";
$control_Designation["params"]["field"]="Designation";
$control_Designation["params"]["mode"]="search";
$xt->assignbyref("Designation_editcontrol",$control_Designation);
$control1_Designation=$control_Designation;
$control1_Designation["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Designation["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Designation"];
$xt->assignbyref("Designation_editcontrol1",$control1_Designation);
	
$xt->assign_section("Designation_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Designation\">","");
$notbox_Designation="name=\"not_Designation\"";
if($not)
	$notbox_Designation=" checked";
$xt->assign("Designation_notbox",$notbox_Designation);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Designation\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Designation",$searchtype);
//	edit format
$editformats["Designation"]="Text field";
// Phone 
$opt="";
$not=false;
$control_Phone=array();
$control_Phone["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Phone"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Phone"];
	$control_Phone["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Phone"];
}
$control_Phone["func"]="xt_buildeditcontrol";
$control_Phone["params"]["field"]="Phone";
$control_Phone["params"]["mode"]="search";
$xt->assignbyref("Phone_editcontrol",$control_Phone);
$control1_Phone=$control_Phone;
$control1_Phone["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Phone["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Phone"];
$xt->assignbyref("Phone_editcontrol1",$control1_Phone);
	
$xt->assign_section("Phone_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Phone\">","");
$notbox_Phone="name=\"not_Phone\"";
if($not)
	$notbox_Phone=" checked";
$xt->assign("Phone_notbox",$notbox_Phone);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Phone\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Phone",$searchtype);
//	edit format
$editformats["Phone"]="Text field";
// Address 
$opt="";
$not=false;
$control_Address=array();
$control_Address["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Address"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Address"];
	$control_Address["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Address"];
}
$control_Address["func"]="xt_buildeditcontrol";
$control_Address["params"]["field"]="Address";
$control_Address["params"]["mode"]="search";
$xt->assignbyref("Address_editcontrol",$control_Address);
$control1_Address=$control_Address;
$control1_Address["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Address["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Address"];
$xt->assignbyref("Address_editcontrol1",$control1_Address);
	
$xt->assign_section("Address_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Address\">","");
$notbox_Address="name=\"not_Address\"";
if($not)
	$notbox_Address=" checked";
$xt->assign("Address_notbox",$notbox_Address);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Address\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Address",$searchtype);
//	edit format
$editformats["Address"]="Text field";

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
$contents_block["begin"].="action=\"teachers_list.php\" ";
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

	
$templatefile = "teachers_search.htm";
if(function_exists("BeforeShowSearch"))
	BeforeShowSearch($xt,$templatefile);

$xt->display($templatefile);

?>
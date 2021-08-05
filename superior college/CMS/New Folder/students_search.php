<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/students_variables.php");


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
$includes.="var SUGGEST_TABLE = \"students_searchsuggest.php\";\r\n";
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
	document.getElementById('second_RNUM').style.display =  
		document.forms.editform.elements['asearchopt_RNUM'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Full_Name').style.display =  
		document.forms.editform.elements['asearchopt_Full_Name'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_UID').style.display =  
		document.forms.editform.elements['asearchopt_UID'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Status').style.display =  
		document.forms.editform.elements['asearchopt_Status'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_L1').style.display =  
		document.forms.editform.elements['asearchopt_L1'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_L2').style.display =  
		document.forms.editform.elements['asearchopt_L2'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_L3').style.display =  
		document.forms.editform.elements['asearchopt_L3'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_L4').style.display =  
		document.forms.editform.elements['asearchopt_L4'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_L5').style.display =  
		document.forms.editform.elements['asearchopt_L5'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_L6').style.display =  
		document.forms.editform.elements['asearchopt_L6'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_L7').style.display =  
		document.forms.editform.elements['asearchopt_L7'].value==\"Between\" ? '' : 'none'; 
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
	document.forms.editform.value_RNUM.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_RNUM,'advanced')};
	document.forms.editform.value1_RNUM.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_RNUM,'advanced1')};
	document.forms.editform.value_RNUM.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_RNUM,'advanced')};
	document.forms.editform.value1_RNUM.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_RNUM,'advanced1')};
	document.forms.editform.value_Full_Name.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Full_Name,'advanced')};
	document.forms.editform.value1_Full_Name.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Full_Name,'advanced1')};
	document.forms.editform.value_Full_Name.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Full_Name,'advanced')};
	document.forms.editform.value1_Full_Name.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Full_Name,'advanced1')};
	document.forms.editform.value_UID.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_UID,'advanced')};
	document.forms.editform.value1_UID.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_UID,'advanced1')};
	document.forms.editform.value_UID.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_UID,'advanced')};
	document.forms.editform.value1_UID.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_UID,'advanced1')};
	document.forms.editform.value_Status.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Status,'advanced')};
	document.forms.editform.value1_Status.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Status,'advanced1')};
	document.forms.editform.value_Status.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Status,'advanced')};
	document.forms.editform.value1_Status.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Status,'advanced1')};
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
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_CID\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_CID",$searchtype);
//	edit format
$editformats["CID"]="Lookup wizard";
// RNUM 
$opt="";
$not=false;
$control_RNUM=array();
$control_RNUM["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["RNUM"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["RNUM"];
	$control_RNUM["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["RNUM"];
}
$control_RNUM["func"]="xt_buildeditcontrol";
$control_RNUM["params"]["field"]="RNUM";
$control_RNUM["params"]["mode"]="search";
$xt->assignbyref("RNUM_editcontrol",$control_RNUM);
$control1_RNUM=$control_RNUM;
$control1_RNUM["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_RNUM["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["RNUM"];
$xt->assignbyref("RNUM_editcontrol1",$control1_RNUM);
	
$xt->assign_section("RNUM_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"RNUM\">","");
$notbox_RNUM="name=\"not_RNUM\"";
if($not)
	$notbox_RNUM=" checked";
$xt->assign("RNUM_notbox",$notbox_RNUM);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_RNUM\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_RNUM",$searchtype);
//	edit format
$editformats["RNUM"]="Text field";
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
// Status 
$opt="";
$not=false;
$control_Status=array();
$control_Status["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Status"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Status"];
	$control_Status["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Status"];
}
$control_Status["func"]="xt_buildeditcontrol";
$control_Status["params"]["field"]="Status";
$control_Status["params"]["mode"]="search";
$xt->assignbyref("Status_editcontrol",$control_Status);
$control1_Status=$control_Status;
$control1_Status["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Status["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Status"];
$xt->assignbyref("Status_editcontrol1",$control1_Status);
	
$xt->assign_section("Status_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Status\">","");
$notbox_Status="name=\"not_Status\"";
if($not)
	$notbox_Status=" checked";
$xt->assign("Status_notbox",$notbox_Status);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Status\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Status",$searchtype);
//	edit format
$editformats["Status"]="Text field";
// L1 
$opt="";
$not=false;
$control_L1=array();
$control_L1["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["L1"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["L1"];
	$control_L1["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["L1"];
}
$control_L1["func"]="xt_buildeditcontrol";
$control_L1["params"]["field"]="L1";
$control_L1["params"]["mode"]="search";
$xt->assignbyref("L1_editcontrol",$control_L1);
$control1_L1=$control_L1;
$control1_L1["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_L1["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["L1"];
$xt->assignbyref("L1_editcontrol1",$control1_L1);
	
$xt->assign_section("L1_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"L1\">","");
$notbox_L1="name=\"not_L1\"";
if($not)
	$notbox_L1=" checked";
$xt->assign("L1_notbox",$notbox_L1);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_L1\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_L1",$searchtype);
//	edit format
$editformats["L1"]="Lookup wizard";
// L2 
$opt="";
$not=false;
$control_L2=array();
$control_L2["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["L2"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["L2"];
	$control_L2["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["L2"];
}
$control_L2["func"]="xt_buildeditcontrol";
$control_L2["params"]["field"]="L2";
$control_L2["params"]["mode"]="search";
$xt->assignbyref("L2_editcontrol",$control_L2);
$control1_L2=$control_L2;
$control1_L2["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_L2["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["L2"];
$xt->assignbyref("L2_editcontrol1",$control1_L2);
	
$xt->assign_section("L2_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"L2\">","");
$notbox_L2="name=\"not_L2\"";
if($not)
	$notbox_L2=" checked";
$xt->assign("L2_notbox",$notbox_L2);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_L2\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_L2",$searchtype);
//	edit format
$editformats["L2"]="Lookup wizard";
// L3 
$opt="";
$not=false;
$control_L3=array();
$control_L3["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["L3"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["L3"];
	$control_L3["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["L3"];
}
$control_L3["func"]="xt_buildeditcontrol";
$control_L3["params"]["field"]="L3";
$control_L3["params"]["mode"]="search";
$xt->assignbyref("L3_editcontrol",$control_L3);
$control1_L3=$control_L3;
$control1_L3["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_L3["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["L3"];
$xt->assignbyref("L3_editcontrol1",$control1_L3);
	
$xt->assign_section("L3_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"L3\">","");
$notbox_L3="name=\"not_L3\"";
if($not)
	$notbox_L3=" checked";
$xt->assign("L3_notbox",$notbox_L3);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_L3\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_L3",$searchtype);
//	edit format
$editformats["L3"]="Lookup wizard";
// L4 
$opt="";
$not=false;
$control_L4=array();
$control_L4["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["L4"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["L4"];
	$control_L4["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["L4"];
}
$control_L4["func"]="xt_buildeditcontrol";
$control_L4["params"]["field"]="L4";
$control_L4["params"]["mode"]="search";
$xt->assignbyref("L4_editcontrol",$control_L4);
$control1_L4=$control_L4;
$control1_L4["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_L4["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["L4"];
$xt->assignbyref("L4_editcontrol1",$control1_L4);
	
$xt->assign_section("L4_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"L4\">","");
$notbox_L4="name=\"not_L4\"";
if($not)
	$notbox_L4=" checked";
$xt->assign("L4_notbox",$notbox_L4);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_L4\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_L4",$searchtype);
//	edit format
$editformats["L4"]="Lookup wizard";
// L5 
$opt="";
$not=false;
$control_L5=array();
$control_L5["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["L5"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["L5"];
	$control_L5["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["L5"];
}
$control_L5["func"]="xt_buildeditcontrol";
$control_L5["params"]["field"]="L5";
$control_L5["params"]["mode"]="search";
$xt->assignbyref("L5_editcontrol",$control_L5);
$control1_L5=$control_L5;
$control1_L5["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_L5["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["L5"];
$xt->assignbyref("L5_editcontrol1",$control1_L5);
	
$xt->assign_section("L5_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"L5\">","");
$notbox_L5="name=\"not_L5\"";
if($not)
	$notbox_L5=" checked";
$xt->assign("L5_notbox",$notbox_L5);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_L5\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_L5",$searchtype);
//	edit format
$editformats["L5"]="Lookup wizard";
// L6 
$opt="";
$not=false;
$control_L6=array();
$control_L6["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["L6"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["L6"];
	$control_L6["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["L6"];
}
$control_L6["func"]="xt_buildeditcontrol";
$control_L6["params"]["field"]="L6";
$control_L6["params"]["mode"]="search";
$xt->assignbyref("L6_editcontrol",$control_L6);
$control1_L6=$control_L6;
$control1_L6["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_L6["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["L6"];
$xt->assignbyref("L6_editcontrol1",$control1_L6);
	
$xt->assign_section("L6_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"L6\">","");
$notbox_L6="name=\"not_L6\"";
if($not)
	$notbox_L6=" checked";
$xt->assign("L6_notbox",$notbox_L6);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_L6\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_L6",$searchtype);
//	edit format
$editformats["L6"]="Lookup wizard";
// L7 
$opt="";
$not=false;
$control_L7=array();
$control_L7["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["L7"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["L7"];
	$control_L7["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["L7"];
}
$control_L7["func"]="xt_buildeditcontrol";
$control_L7["params"]["field"]="L7";
$control_L7["params"]["mode"]="search";
$xt->assignbyref("L7_editcontrol",$control_L7);
$control1_L7=$control_L7;
$control1_L7["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_L7["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["L7"];
$xt->assignbyref("L7_editcontrol1",$control1_L7);
	
$xt->assign_section("L7_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"L7\">","");
$notbox_L7="name=\"not_L7\"";
if($not)
	$notbox_L7=" checked";
$xt->assign("L7_notbox",$notbox_L7);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_L7\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_L7",$searchtype);
//	edit format
$editformats["L7"]="Lookup wizard";

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
$contents_block["begin"].="action=\"students_list.php\" ";
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

	
$templatefile = "students_search.htm";
if(function_exists("BeforeShowSearch"))
	BeforeShowSearch($xt,$templatefile);

$xt->display($templatefile);

?>
<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/lectures_variables.php");


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
$includes.="var SUGGEST_TABLE = \"lectures_searchsuggest.php\";\r\n";
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
	document.getElementById('second_TID').style.display =  
		document.forms.editform.elements['asearchopt_TID'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Name').style.display =  
		document.forms.editform.elements['asearchopt_Name'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Continue').style.display =  
		document.forms.editform.elements['asearchopt_Continue'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Type').style.display =  
		document.forms.editform.elements['asearchopt_Type'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Start').style.display =  
		document.forms.editform.elements['asearchopt_Start'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_End').style.display =  
		document.forms.editform.elements['asearchopt_End'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Time').style.display =  
		document.forms.editform.elements['asearchopt_Time'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Room').style.display =  
		document.forms.editform.elements['asearchopt_Room'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Duration').style.display =  
		document.forms.editform.elements['asearchopt_Duration'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_About').style.display =  
		document.forms.editform.elements['asearchopt_About'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Announce').style.display =  
		document.forms.editform.elements['asearchopt_Announce'].value==\"Between\" ? '' : 'none'; 
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
	document.forms.editform.value_Name.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Name,'advanced')};
	document.forms.editform.value1_Name.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Name,'advanced1')};
	document.forms.editform.value_Name.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Name,'advanced')};
	document.forms.editform.value1_Name.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Name,'advanced1')};
	document.forms.editform.value_Room.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Room,'advanced')};
	document.forms.editform.value1_Room.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Room,'advanced1')};
	document.forms.editform.value_Room.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Room,'advanced')};
	document.forms.editform.value1_Room.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Room,'advanced1')};
	document.forms.editform.value_Duration.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Duration,'advanced')};
	document.forms.editform.value1_Duration.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Duration,'advanced1')};
	document.forms.editform.value_Duration.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Duration,'advanced')};
	document.forms.editform.value1_Duration.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Duration,'advanced1')};
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
// TID 
$opt="";
$not=false;
$control_TID=array();
$control_TID["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["TID"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["TID"];
	$control_TID["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["TID"];
}
$control_TID["func"]="xt_buildeditcontrol";
$control_TID["params"]["field"]="TID";
$control_TID["params"]["mode"]="search";
$xt->assignbyref("TID_editcontrol",$control_TID);
$control1_TID=$control_TID;
$control1_TID["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_TID["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["TID"];
$xt->assignbyref("TID_editcontrol1",$control1_TID);
	
$xt->assign_section("TID_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"TID\">","");
$notbox_TID="name=\"not_TID\"";
if($not)
	$notbox_TID=" checked";
$xt->assign("TID_notbox",$notbox_TID);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_TID\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_TID",$searchtype);
//	edit format
$editformats["TID"]="Lookup wizard";
// Name 
$opt="";
$not=false;
$control_Name=array();
$control_Name["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Name"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Name"];
	$control_Name["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Name"];
}
$control_Name["func"]="xt_buildeditcontrol";
$control_Name["params"]["field"]="Name";
$control_Name["params"]["mode"]="search";
$xt->assignbyref("Name_editcontrol",$control_Name);
$control1_Name=$control_Name;
$control1_Name["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Name["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Name"];
$xt->assignbyref("Name_editcontrol1",$control1_Name);
	
$xt->assign_section("Name_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Name\">","");
$notbox_Name="name=\"not_Name\"";
if($not)
	$notbox_Name=" checked";
$xt->assign("Name_notbox",$notbox_Name);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Name\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Name",$searchtype);
//	edit format
$editformats["Name"]="Text field";
// Continue 
$opt="";
$not=false;
$control_Continue=array();
$control_Continue["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Continue"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Continue"];
	$control_Continue["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Continue"];
}
$control_Continue["func"]="xt_buildeditcontrol";
$control_Continue["params"]["field"]="Continue";
$control_Continue["params"]["mode"]="search";
$xt->assignbyref("Continue_editcontrol",$control_Continue);
$control1_Continue=$control_Continue;
$control1_Continue["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Continue["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Continue"];
$xt->assignbyref("Continue_editcontrol1",$control1_Continue);
	
$xt->assign_section("Continue_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Continue\">","");
$notbox_Continue="name=\"not_Continue\"";
if($not)
	$notbox_Continue=" checked";
$xt->assign("Continue_notbox",$notbox_Continue);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Continue\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Continue",$searchtype);
//	edit format
$editformats["Continue"]="Checkbox";
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
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Type\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Type",$searchtype);
//	edit format
$editformats["Type"]="Lookup wizard";
// Start 
$opt="";
$not=false;
$control_Start=array();
$control_Start["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Start"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Start"];
	$control_Start["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Start"];
}
$control_Start["func"]="xt_buildeditcontrol";
$control_Start["params"]["field"]="Start";
$control_Start["params"]["mode"]="search";
$xt->assignbyref("Start_editcontrol",$control_Start);
$control1_Start=$control_Start;
$control1_Start["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Start["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Start"];
$xt->assignbyref("Start_editcontrol1",$control1_Start);
	
$xt->assign_section("Start_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Start\">","");
$notbox_Start="name=\"not_Start\"";
if($not)
	$notbox_Start=" checked";
$xt->assign("Start_notbox",$notbox_Start);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Start\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Start",$searchtype);
//	edit format
$editformats["Start"]="Date";
// End 
$opt="";
$not=false;
$control_End=array();
$control_End["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["End"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["End"];
	$control_End["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["End"];
}
$control_End["func"]="xt_buildeditcontrol";
$control_End["params"]["field"]="End";
$control_End["params"]["mode"]="search";
$xt->assignbyref("End_editcontrol",$control_End);
$control1_End=$control_End;
$control1_End["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_End["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["End"];
$xt->assignbyref("End_editcontrol1",$control1_End);
	
$xt->assign_section("End_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"End\">","");
$notbox_End="name=\"not_End\"";
if($not)
	$notbox_End=" checked";
$xt->assign("End_notbox",$notbox_End);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_End\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_End",$searchtype);
//	edit format
$editformats["End"]="Date";
// Time 
$opt="";
$not=false;
$control_Time=array();
$control_Time["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Time"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Time"];
	$control_Time["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Time"];
}
$control_Time["func"]="xt_buildeditcontrol";
$control_Time["params"]["field"]="Time";
$control_Time["params"]["mode"]="search";
$xt->assignbyref("Time_editcontrol",$control_Time);
$control1_Time=$control_Time;
$control1_Time["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Time["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Time"];
$xt->assignbyref("Time_editcontrol1",$control1_Time);
	
$xt->assign_section("Time_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Time\">","");
$notbox_Time="name=\"not_Time\"";
if($not)
	$notbox_Time=" checked";
$xt->assign("Time_notbox",$notbox_Time);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Time\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Time",$searchtype);
//	edit format
$editformats["Time"]="Time";
// Room 
$opt="";
$not=false;
$control_Room=array();
$control_Room["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Room"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Room"];
	$control_Room["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Room"];
}
$control_Room["func"]="xt_buildeditcontrol";
$control_Room["params"]["field"]="Room";
$control_Room["params"]["mode"]="search";
$xt->assignbyref("Room_editcontrol",$control_Room);
$control1_Room=$control_Room;
$control1_Room["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Room["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Room"];
$xt->assignbyref("Room_editcontrol1",$control1_Room);
	
$xt->assign_section("Room_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Room\">","");
$notbox_Room="name=\"not_Room\"";
if($not)
	$notbox_Room=" checked";
$xt->assign("Room_notbox",$notbox_Room);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Room\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Room",$searchtype);
//	edit format
$editformats["Room"]="Text field";
// Duration 
$opt="";
$not=false;
$control_Duration=array();
$control_Duration["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Duration"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Duration"];
	$control_Duration["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Duration"];
}
$control_Duration["func"]="xt_buildeditcontrol";
$control_Duration["params"]["field"]="Duration";
$control_Duration["params"]["mode"]="search";
$xt->assignbyref("Duration_editcontrol",$control_Duration);
$control1_Duration=$control_Duration;
$control1_Duration["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Duration["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Duration"];
$xt->assignbyref("Duration_editcontrol1",$control1_Duration);
	
$xt->assign_section("Duration_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Duration\">","");
$notbox_Duration="name=\"not_Duration\"";
if($not)
	$notbox_Duration=" checked";
$xt->assign("Duration_notbox",$notbox_Duration);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Duration\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Duration",$searchtype);
//	edit format
$editformats["Duration"]="Text field";
// About 
$opt="";
$not=false;
$control_About=array();
$control_About["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["About"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["About"];
	$control_About["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["About"];
}
$control_About["func"]="xt_buildeditcontrol";
$control_About["params"]["field"]="About";
$control_About["params"]["mode"]="search";
$xt->assignbyref("About_editcontrol",$control_About);
$control1_About=$control_About;
$control1_About["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_About["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["About"];
$xt->assignbyref("About_editcontrol1",$control1_About);
	
$xt->assign_section("About_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"About\">","");
$notbox_About="name=\"not_About\"";
if($not)
	$notbox_About=" checked";
$xt->assign("About_notbox",$notbox_About);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_About\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_About",$searchtype);
//	edit format
$editformats["About"]=EDIT_FORMAT_TEXT_FIELD;
// Announce 
$opt="";
$not=false;
$control_Announce=array();
$control_Announce["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Announce"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Announce"];
	$control_Announce["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Announce"];
}
$control_Announce["func"]="xt_buildeditcontrol";
$control_Announce["params"]["field"]="Announce";
$control_Announce["params"]["mode"]="search";
$xt->assignbyref("Announce_editcontrol",$control_Announce);
$control1_Announce=$control_Announce;
$control1_Announce["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Announce["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Announce"];
$xt->assignbyref("Announce_editcontrol1",$control1_Announce);
	
$xt->assign_section("Announce_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Announce\">","");
$notbox_Announce="name=\"not_Announce\"";
if($not)
	$notbox_Announce=" checked";
$xt->assign("Announce_notbox",$notbox_Announce);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Announce\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Announce",$searchtype);
//	edit format
$editformats["Announce"]=EDIT_FORMAT_TEXT_FIELD;

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
$contents_block["begin"].="action=\"lectures_list.php\" ";
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

	
$templatefile = "lectures_search.htm";
if(function_exists("BeforeShowSearch"))
	BeforeShowSearch($xt,$templatefile);

$xt->display($templatefile);

?>
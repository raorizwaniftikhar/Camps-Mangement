<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");



include('libs/xtempl.php');
$xt = new Xtempl();

$conn=db_connect();
//	Before Process event
if(function_exists("BeforeProcessMenu"))
	BeforeProcessMenu($conn);

$xt->assign("body",true);


$allow_users=true;
if($allow_users)
{
	$createmenu=true;
	$xt->assign("users_tablelink",true);
	$page="";
		$page="list";
		$strPerm = GetUserPermissions("users");
	if(strpos($strPerm, "A")!==false && strpos($strPerm, "S")===false)
		$page="add";
	$xt->assign("users_tablelink_attrs","href=\"users_".$page.".php\"");
}
$allow_classes=true;
if($allow_classes)
{
	$createmenu=true;
	$xt->assign("classes_tablelink",true);
	$page="";
		$page="list";
		$strPerm = GetUserPermissions("classes");
	if(strpos($strPerm, "A")!==false && strpos($strPerm, "S")===false)
		$page="add";
	$xt->assign("classes_tablelink_attrs","href=\"classes_".$page.".php\"");
}
$allow_lectures=true;
if($allow_lectures)
{
	$createmenu=true;
	$xt->assign("lectures_tablelink",true);
	$page="";
		$page="list";
		$strPerm = GetUserPermissions("lectures");
	if(strpos($strPerm, "A")!==false && strpos($strPerm, "S")===false)
		$page="add";
	$xt->assign("lectures_tablelink_attrs","href=\"lectures_".$page.".php\"");
}
$allow_students=true;
if($allow_students)
{
	$createmenu=true;
	$xt->assign("students_tablelink",true);
	$page="";
		$page="list";
		$xt->assign("students_tablelink_attrs","href=\"students_".$page.".php\"");
}
$allow_teachers=true;
if($allow_teachers)
{
	$createmenu=true;
	$xt->assign("teachers_tablelink",true);
	$page="";
		$page="list";
		$xt->assign("teachers_tablelink_attrs","href=\"teachers_".$page.".php\"");
}
$allow_attendance=true;
if($allow_attendance)
{
	$createmenu=true;
	$xt->assign("attendance_tablelink",true);
	$page="";
		$page="list";
		$xt->assign("attendance_tablelink_attrs","href=\"attendance_".$page.".php\"");
}
$allow_marks=true;
if($allow_marks)
{
	$createmenu=true;
	$xt->assign("marks_tablelink",true);
	$page="";
		$page="list";
		$strPerm = GetUserPermissions("marks");
	if(strpos($strPerm, "A")!==false && strpos($strPerm, "S")===false)
		$page="add";
	$xt->assign("marks_tablelink_attrs","href=\"marks_".$page.".php\"");
}
$allow_assignments=true;
if($allow_assignments)
{
	$createmenu=true;
	$xt->assign("assignments_tablelink",true);
	$page="";
		$page="list";
		$xt->assign("assignments_tablelink_attrs","href=\"assignments_".$page.".php\"");
}



$templatefile="menu.htm";
if(function_exists("BeforeShowMenu"))
	BeforeShowMenu($xt,$templatefile);

$xt->display($templatefile);
?>
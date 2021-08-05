<?php

$dal_info=array();
$daltable_assignments = array();
$daltable_assignments["AID"]=array();
$daltable_assignments["AID"]["nType"]=3;
	$daltable_assignments["AID"]["bKey"]=true;
$daltable_assignments["AID"]["varname"]="AID";
$daltable_assignments["Title"]=array();
$daltable_assignments["Title"]["nType"]=200;
	$daltable_assignments["Title"]["varname"]="Title";
$daltable_assignments["Type"]=array();
$daltable_assignments["Type"]["nType"]=200;
	$daltable_assignments["Type"]["varname"]="Type";
$daltable_assignments["Marks"]=array();
$daltable_assignments["Marks"]["nType"]=3;
	$daltable_assignments["Marks"]["varname"]="Marks";
$daltable_assignments["Description"]=array();
$daltable_assignments["Description"]["nType"]=200;
	$daltable_assignments["Description"]["varname"]="Description";
$daltable_assignments["File"]=array();
$daltable_assignments["File"]["nType"]=200;
	$daltable_assignments["File"]["varname"]="File";
$daltable_assignments["LID"]=array();
$daltable_assignments["LID"]["nType"]=3;
	$daltable_assignments["LID"]["varname"]="LID";
$daltable_assignments["Against"]=array();
$daltable_assignments["Against"]["nType"]=3;
	$daltable_assignments["Against"]["varname"]="Against";
$daltable_assignments["SID"]=array();
$daltable_assignments["SID"]["nType"]=3;
	$daltable_assignments["SID"]["varname"]="SID";
$dal_info["assignments"]=&$daltable_assignments;
$daltable_attendance = array();
$daltable_attendance["ATID"]=array();
$daltable_attendance["ATID"]["nType"]=3;
	$daltable_attendance["ATID"]["bKey"]=true;
$daltable_attendance["ATID"]["varname"]="ATID";
$daltable_attendance["SID"]=array();
$daltable_attendance["SID"]["nType"]=3;
	$daltable_attendance["SID"]["varname"]="SID";
$daltable_attendance["LRID"]=array();
$daltable_attendance["LRID"]["nType"]=3;
	$daltable_attendance["LRID"]["varname"]="LRID";
$daltable_attendance["Daate"]=array();
$daltable_attendance["Daate"]["nType"]=7;
	$daltable_attendance["Daate"]["varname"]="Daate";
$dal_info["attendance"]=&$daltable_attendance;
$daltable_classes = array();
$daltable_classes["CID"]=array();
$daltable_classes["CID"]["nType"]=200;
	$daltable_classes["CID"]["bKey"]=true;
$daltable_classes["CID"]["varname"]="CID";
$daltable_classes["Session"]=array();
$daltable_classes["Session"]["nType"]=200;
	$daltable_classes["Session"]["varname"]="Session";
$daltable_classes["Semester"]=array();
$daltable_classes["Semester"]["nType"]=200;
	$daltable_classes["Semester"]["varname"]="Semester";
$daltable_classes["Subjects"]=array();
$daltable_classes["Subjects"]["nType"]=200;
	$daltable_classes["Subjects"]["varname"]="Subjects";
$dal_info["classes"]=&$daltable_classes;
$daltable_lectures = array();
$daltable_lectures["LID"]=array();
$daltable_lectures["LID"]["nType"]=3;
	$daltable_lectures["LID"]["bKey"]=true;
$daltable_lectures["LID"]["varname"]="LID";
$daltable_lectures["CID"]=array();
$daltable_lectures["CID"]["nType"]=200;
	$daltable_lectures["CID"]["varname"]="CID";
$daltable_lectures["TID"]=array();
$daltable_lectures["TID"]["nType"]=3;
	$daltable_lectures["TID"]["varname"]="TID";
$daltable_lectures["Name"]=array();
$daltable_lectures["Name"]["nType"]=200;
	$daltable_lectures["Name"]["varname"]="Name";
$daltable_lectures["Continue"]=array();
$daltable_lectures["Continue"]["nType"]=200;
	$daltable_lectures["Continue"]["varname"]="fldContinue";
$daltable_lectures["Type"]=array();
$daltable_lectures["Type"]["nType"]=200;
	$daltable_lectures["Type"]["varname"]="Type";
$daltable_lectures["Start"]=array();
$daltable_lectures["Start"]["nType"]=7;
	$daltable_lectures["Start"]["varname"]="Start";
$daltable_lectures["End"]=array();
$daltable_lectures["End"]["nType"]=7;
	$daltable_lectures["End"]["varname"]="End";
$daltable_lectures["Time"]=array();
$daltable_lectures["Time"]["nType"]=200;
	$daltable_lectures["Time"]["varname"]="Time";
$daltable_lectures["Room"]=array();
$daltable_lectures["Room"]["nType"]=200;
	$daltable_lectures["Room"]["varname"]="Room";
$daltable_lectures["Duration"]=array();
$daltable_lectures["Duration"]["nType"]=200;
	$daltable_lectures["Duration"]["varname"]="Duration";
$daltable_lectures["About"]=array();
$daltable_lectures["About"]["nType"]=200;
	$daltable_lectures["About"]["varname"]="About";
$daltable_lectures["Announce"]=array();
$daltable_lectures["Announce"]["nType"]=200;
	$daltable_lectures["Announce"]["varname"]="Announce";
$dal_info["lectures"]=&$daltable_lectures;
$daltable_marks = array();
$daltable_marks["MID"]=array();
$daltable_marks["MID"]["nType"]=3;
	$daltable_marks["MID"]["bKey"]=true;
$daltable_marks["MID"]["varname"]="MID";
$daltable_marks["SID"]=array();
$daltable_marks["SID"]["nType"]=3;
	$daltable_marks["SID"]["varname"]="SID";
$daltable_marks["LID"]=array();
$daltable_marks["LID"]["nType"]=3;
	$daltable_marks["LID"]["varname"]="LID";
$daltable_marks["SESSINAL"]=array();
$daltable_marks["SESSINAL"]["nType"]=3;
	$daltable_marks["SESSINAL"]["varname"]="SESSINAL";
$daltable_marks["MIDTERM"]=array();
$daltable_marks["MIDTERM"]["nType"]=3;
	$daltable_marks["MIDTERM"]["varname"]="MIDTERM";
$daltable_marks["FINAL"]=array();
$daltable_marks["FINAL"]["nType"]=3;
	$daltable_marks["FINAL"]["varname"]="FINAL";
$dal_info["marks"]=&$daltable_marks;
$daltable_students = array();
$daltable_students["SID"]=array();
$daltable_students["SID"]["nType"]=3;
	$daltable_students["SID"]["bKey"]=true;
$daltable_students["SID"]["varname"]="SID";
$daltable_students["CID"]=array();
$daltable_students["CID"]["nType"]=200;
	$daltable_students["CID"]["varname"]="CID";
$daltable_students["RNUM"]=array();
$daltable_students["RNUM"]["nType"]=200;
	$daltable_students["RNUM"]["varname"]="RNUM";
$daltable_students["Full_Name"]=array();
$daltable_students["Full_Name"]["nType"]=200;
	$daltable_students["Full_Name"]["varname"]="Full_Name";
$daltable_students["UID"]=array();
$daltable_students["UID"]["nType"]=200;
	$daltable_students["UID"]["varname"]="UID";
$daltable_students["Status"]=array();
$daltable_students["Status"]["nType"]=200;
	$daltable_students["Status"]["varname"]="Status";
$daltable_students["L1"]=array();
$daltable_students["L1"]["nType"]=200;
	$daltable_students["L1"]["varname"]="L1";
$daltable_students["L2"]=array();
$daltable_students["L2"]["nType"]=200;
	$daltable_students["L2"]["varname"]="L2";
$daltable_students["L3"]=array();
$daltable_students["L3"]["nType"]=200;
	$daltable_students["L3"]["varname"]="L3";
$daltable_students["L4"]=array();
$daltable_students["L4"]["nType"]=200;
	$daltable_students["L4"]["varname"]="L4";
$daltable_students["L5"]=array();
$daltable_students["L5"]["nType"]=200;
	$daltable_students["L5"]["varname"]="L5";
$daltable_students["L6"]=array();
$daltable_students["L6"]["nType"]=200;
	$daltable_students["L6"]["varname"]="L6";
$daltable_students["L7"]=array();
$daltable_students["L7"]["nType"]=200;
	$daltable_students["L7"]["varname"]="L7";
$dal_info["students"]=&$daltable_students;
$daltable_teachers = array();
$daltable_teachers["TID"]=array();
$daltable_teachers["TID"]["nType"]=3;
	$daltable_teachers["TID"]["bKey"]=true;
$daltable_teachers["TID"]["varname"]="TID";
$daltable_teachers["Full_Name"]=array();
$daltable_teachers["Full_Name"]["nType"]=200;
	$daltable_teachers["Full_Name"]["varname"]="Full_Name";
$daltable_teachers["UID"]=array();
$daltable_teachers["UID"]["nType"]=200;
	$daltable_teachers["UID"]["varname"]="UID";
$daltable_teachers["Designation"]=array();
$daltable_teachers["Designation"]["nType"]=200;
	$daltable_teachers["Designation"]["varname"]="Designation";
$daltable_teachers["Phone"]=array();
$daltable_teachers["Phone"]["nType"]=200;
	$daltable_teachers["Phone"]["varname"]="Phone";
$daltable_teachers["Address"]=array();
$daltable_teachers["Address"]["nType"]=200;
	$daltable_teachers["Address"]["varname"]="Address";
$dal_info["teachers"]=&$daltable_teachers;
$daltable_users = array();
$daltable_users["ID"]=array();
$daltable_users["ID"]["nType"]=3;
	$daltable_users["ID"]["bKey"]=true;
$daltable_users["ID"]["varname"]="ID";
$daltable_users["UID"]=array();
$daltable_users["UID"]["nType"]=200;
	$daltable_users["UID"]["varname"]="UID";
$daltable_users["PWD"]=array();
$daltable_users["PWD"]["nType"]=200;
	$daltable_users["PWD"]["varname"]="PWD";
$daltable_users["Type"]=array();
$daltable_users["Type"]["nType"]=3;
	$daltable_users["Type"]["varname"]="Type";
$dal_info["users"]=&$daltable_users;



function CustomQuery($dalSQL)
{
	global $conn;
	$rs = db_query($dalSQL,$conn);
	  return $rs;
}

function UsersTableName()
{
	return "";
}


class tDAL
{
	var $assignments;
	var $attendance;
	var $classes;
	var $lectures;
	var $marks;
	var $students;
	var $teachers;
	var $users;
  function Table($strTable)
  {
          if(strtoupper($strTable)==strtoupper("assignments"))
              return $this->assignments;
          if(strtoupper($strTable)==strtoupper("attendance"))
              return $this->attendance;
          if(strtoupper($strTable)==strtoupper("classes"))
              return $this->classes;
          if(strtoupper($strTable)==strtoupper("lectures"))
              return $this->lectures;
          if(strtoupper($strTable)==strtoupper("marks"))
              return $this->marks;
          if(strtoupper($strTable)==strtoupper("students"))
              return $this->students;
          if(strtoupper($strTable)==strtoupper("teachers"))
              return $this->teachers;
          if(strtoupper($strTable)==strtoupper("users"))
              return $this->users;
//	check table names without dbo. and other prefixes
          if(strtoupper(cutprefix($strTable))==strtoupper(cutprefix("assignments")))
              return $this->assignments;
          if(strtoupper(cutprefix($strTable))==strtoupper(cutprefix("attendance")))
              return $this->attendance;
          if(strtoupper(cutprefix($strTable))==strtoupper(cutprefix("classes")))
              return $this->classes;
          if(strtoupper(cutprefix($strTable))==strtoupper(cutprefix("lectures")))
              return $this->lectures;
          if(strtoupper(cutprefix($strTable))==strtoupper(cutprefix("marks")))
              return $this->marks;
          if(strtoupper(cutprefix($strTable))==strtoupper(cutprefix("students")))
              return $this->students;
          if(strtoupper(cutprefix($strTable))==strtoupper(cutprefix("teachers")))
              return $this->teachers;
          if(strtoupper(cutprefix($strTable))==strtoupper(cutprefix("users")))
              return $this->users;
  }
}

$dal = new tDAL;

class tDALTable
{
	var $m_TableName;
	var $Param = array();
	var $Value = array();
	
	function TableName()
	{
		return AddTableWrappers($this->m_TableName);
	} 
	
	function Add() 
	{
		global $conn,$dal_info;
		$insertFields="";
		$insertValues="";
		$tableinfo = &$dal_info[$this->m_TableName];
//	prepare parameters		
		foreach($tableinfo as $fieldname=>$fld)
		{
			$command='if(isset($this->'.$fld['varname'].'))
			{
				$this->Value[\''.escapesq($fieldname).'\'] = $this->'.$fld['varname'].';
			}';
			eval($command);
			foreach($this->Value as $field=>$value)
			{
				if (strtoupper($field)!=strtoupper($fieldname))
					continue;
				$insertFields.= AddFieldWrappers($fieldname).",";
				if (NeedQuotes($fld["nType"]))
					$insertValues.= "'".db_addslashes($value) . "',";
				else
					$insertValues.= "".(0+$value) . ",";		
				break;
			}
		}
//	prepare and exec SQL
		if ($insertFields!="" && $insertValues!="")		
		{
			$insertFields = substr($insertFields,0,-1);
			$insertValues = substr($insertValues,0,-1);
			$dalSQL = "insert into ".AddTableWrappers($this->m_TableName)." (".$insertFields.") values (".$insertValues.")";
			db_exec($dalSQL,$conn);
		}
//	cleanup		
	    $this->Reset();
	}

	function QueryAll()
	{
		global $conn;
		$dalSQL = "select * from ".AddFieldWrappers($this->m_TableName);
		$rs = db_query($dalSQL,$conn);
		return $rs;
	}

	function Query($swhere="",$orderby="")
	{
		global $conn;
		if ($swhere)
			$swhere = " where ".$swhere;
		if ($orderby)
			$orderby = " order by ".$orderby;
		$dalSQL = "select * from ".AddTableWrappers($this->m_TableName).$swhere.$orderby;
		$rs = db_query($dalSQL,$conn);
		return $rs;
	}

	function Delete()
	{
		global $conn,$dal_info;
		$deleteFields="";
		$tableinfo = &$dal_info[$this->m_TableName];
//	prepare parameters		
		foreach($tableinfo as $fieldname=>$fld)
		{
			$command='if(isset($this->'.$fld['varname'].'))
			{
				$this->Value[\''.escapesq($fieldname).'\'] = $this->'.$fld['varname'].';
			}
			';
			eval($command);
			foreach($this->Value as $field=>$value)
			{
				if (strtoupper($field)!=strtoupper($fieldname))
					continue;
				if (NeedQuotes($fld["nType"]))
					$deleteFields.= AddFieldWrappers($fieldname)."='".db_addslashes($val) . "' and ";
				else
					$deleteFields.= AddFieldWrappers($fieldname)."=". (0+$val) . " and ";		
				break;
			}
		}
//	do delete
		if ($deleteFields)
		{
			$deleteFields = substr($deleteFields,0,-5);
			$dalSQL = "delete from ".AddFieldWrappers($this->m_TableName)." where ".$deleteFields;
			db_exec($dalSQL,$conn);
		}
	
//	cleanup
	    $this->Reset();
	}

	function Reset()
	{
		$this->Value=array();
		$this->Param=array();
		global $dal_info;
		$tableinfo = &$dal_info[$this->m_TableName];
//	prepare parameters		
		foreach($tableinfo as $fieldname=>$fld)
		{
			$command='unset($this->'.$fld["varname"].");";
			eval($command);
		}
	}	

	function Update()
	{
		global $conn,$dal_info;
		$tableinfo = &$dal_info[$this->m_TableName];
		$updateParam = "";
		$updateValue = "";

		foreach($tableinfo as $fieldname=>$fld)
		{
			$command='if(isset($this->'.$fld['varname'].')) { ';
			if($fld["bKey"])
				$command.='$this->Param[\''.escapesq($fieldname).'\'] = $this->'.$fld['varname'].';';
			else
				$command.='$this->Value[\''.escapesq($fieldname).'\'] = $this->'.$fld['varname'].';';
			$command.=' }';
			eval($command);
			if(!$fld["bKey"])
			{
				foreach($this->Value as $field=>$value)
				{
					if (strtoupper($field)!=strtoupper($fieldname))
						continue;
					if (NeedQuotes($fld["nType"]))
						$updateValue.= AddFieldWrappers($fieldname)."='".db_addslashes($value) . "', ";
					else
						$updateValue.= AddFieldWrappers($fieldname)."=".(0+$value) . ", ";
					break;
				}
			}
			else
			{
				foreach($this->Param as $field=>$value)
				{
					if (strtoupper($field)!=strtoupper($fieldname))
						continue;
					if (NeedQuotes($fld["nType"]))
						$updateParam.= AddFieldWrappers($fieldname)."='".db_addslashes($value) . "' and ";
					else
						$updateParam.= AddFieldWrappers($fieldname)."=".(0+$value) . " and ";
					break;
				}
			}
		}

//	construct SQL and do update	
		if ($updateParam)
			$updateParam = substr($updateParam,0,-5);
		if ($updateValue)
			$updateValue = substr($updateValue,0,-2);
		if ($updateValue && $updateParam)
		{
			$dalSQL = "update ".AddTableWrappers($this->m_TableName)." set ".$updateValue." where ".$updateParam;
			db_exec($dalSQL,$conn);
		}

//	cleanup
		$this->Reset();
	}

	function FetchByID()
	{
		global $conn,$dal_info;
		$tableinfo = &$dal_info[$this->m_TableName];

		$dal_where="";
		foreach($tableinfo as $fieldname=>$fld)
		{
			$command='if(isset($this->'.$fld['varname'].')) { ';
			$command.='$this->Value[\''.escapesq($fieldname).'\'] = $this->'.$fld['varname'].';';
			$command.=' }';
			eval($command);
			foreach($this->Value as $field=>$value)
			{
				if (strtoupper($field)!=strtoupper($fieldname))
					continue;
				if (NeedQuotes($fld["nType"]))
					$dal_where.= AddFieldWrappers($fieldname)."='".db_addslashes($value) . "' and ";
				else
					$dal_where.= AddFieldWrappers($fieldname)."=".(0+$value) . " and ";
				break;
			}
		}
//	cleanup
		$this->Reset();
//	construct and run SQL
		if ($dal_where)
			$dal_where = " where ".substr($dal_where,0,-5);
		$dalSQL = "select * from ".AddTableWrappers($this->m_TableName).$dal_where;
		$rs = db_query($dalSQL,$conn);
		return $rs;
	}
}

class class_assignments extends tDALTable
{
	var $AID;
	var $Title;
	var $Type;
	var $Marks;
	var $Description;
	var $File;
	var $LID;
	var $Against;
	var $SID;

	function class_assignments()
	{
		$this->m_TableName = "assignments";
	}
}
$dal->assignments = new class_assignments();
class class_attendance extends tDALTable
{
	var $ATID;
	var $SID;
	var $LRID;
	var $Daate;

	function class_attendance()
	{
		$this->m_TableName = "attendance";
	}
}
$dal->attendance = new class_attendance();
class class_classes extends tDALTable
{
	var $CID;
	var $Session;
	var $Semester;
	var $Subjects;

	function class_classes()
	{
		$this->m_TableName = "classes";
	}
}
$dal->classes = new class_classes();
class class_lectures extends tDALTable
{
	var $LID;
	var $CID;
	var $TID;
	var $Name;
	var $fldContinue;
	var $Type;
	var $Start;
	var $End;
	var $Time;
	var $Room;
	var $Duration;
	var $About;
	var $Announce;

	function class_lectures()
	{
		$this->m_TableName = "lectures";
	}
}
$dal->lectures = new class_lectures();
class class_marks extends tDALTable
{
	var $MID;
	var $SID;
	var $LID;
	var $SESSINAL;
	var $MIDTERM;
	var $FINAL;

	function class_marks()
	{
		$this->m_TableName = "marks";
	}
}
$dal->marks = new class_marks();
class class_students extends tDALTable
{
	var $SID;
	var $CID;
	var $RNUM;
	var $Full_Name;
	var $UID;
	var $Status;
	var $L1;
	var $L2;
	var $L3;
	var $L4;
	var $L5;
	var $L6;
	var $L7;

	function class_students()
	{
		$this->m_TableName = "students";
	}
}
$dal->students = new class_students();
class class_teachers extends tDALTable
{
	var $TID;
	var $Full_Name;
	var $UID;
	var $Designation;
	var $Phone;
	var $Address;

	function class_teachers()
	{
		$this->m_TableName = "teachers";
	}
}
$dal->teachers = new class_teachers();
class class_users extends tDALTable
{
	var $ID;
	var $UID;
	var $PWD;
	var $Type;

	function class_users()
	{
		$this->m_TableName = "users";
	}
}
$dal->users = new class_users();

class DalRecordset
{
	
	var $m_rs;
	var $m_fields;
	var $m_eof;
	
	function Fields($field="")
	{
		if(!$field)
			return $this->m_fields;
		return $this->Field($field);
	}
	
	function Field($field)
	{
		if($this->m_eof)
			return false;
		foreach($this->m_fields as $name=>$value)
		{
			if(!strcasecmp($name,$field))
				return $value;
		}
		return false;
	}
	function DalRecordset($rs)
	{
		$this->m_rs=$rs;
		$this->MoveNext();
	}
	function EOF()
	{
		return $this->m_eof;
	}
	
	function MoveNext()
	{
		if(!$this->m_eof)
			$this->m_fields=db_fetch_array($this->m_rs);
		$this->m_eof = !$this->m_fields;
		return !$this->m_eof;
	}
}

function cutprefix($table)
{
	$pos=strpos($table,".");
	if($pos===false)
		return $table;
	return substr($table,$pos+1);
}

function escapesq($str)
{
	return str_replace(array("\\","'"),array("\\\\","\\'"),$str);
}

?>
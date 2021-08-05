<?php

//	field labels
$fieldLabelsattendance = array();
$fieldLabelsattendance["English"]=array();
$fieldLabelsattendance["English"]["ATID"] = "ATID";
$fieldLabelsattendance["English"]["SID"] = "Student Name";
$fieldLabelsattendance["English"]["LRID"] = "Lecture Name";
$fieldLabelsattendance["English"]["Daate"] = "On Date";


$tdataattendance=array();
	 $tdataattendance[".NumberOfChars"]=80; 
	$tdataattendance[".ShortName"]="attendance";
	$tdataattendance[".OwnerID"]="";
	$tdataattendance[".OriginalTable"]="attendance";

	$keys=array();
	$keys[]="ATID";
	$tdataattendance[".Keys"]=$keys;

	
//	ATID
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "ATID";
		$fdata["FullName"]= "ATID";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
									$tdataattendance["ATID"]=$fdata;
	
//	SID
	$fdata = array();
	 $fdata["Label"]="Student Name"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
		
		$fdata["LookupType"]=1;
			$fdata["LinkField"]="`SID`";
	$fdata["LinkFieldType"]=3;
		$fdata["DisplayField"]="`Full_Name`";
	$fdata["LookupTable"]="students";
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "SID";
		$fdata["FullName"]= "SID";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 2;
	
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataattendance["SID"]=$fdata;
	
//	LRID
	$fdata = array();
	 $fdata["Label"]="Lecture Name"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
		
		$fdata["LookupType"]=1;
			$fdata["LinkField"]="`LID`";
	$fdata["LinkFieldType"]=3;
		$fdata["DisplayField"]="`Name`";
	$fdata["LookupTable"]="lectures";
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "LRID";
		$fdata["FullName"]= "LRID";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 3;
	
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataattendance["LRID"]=$fdata;
	
//	Daate
	$fdata = array();
	 $fdata["Label"]="On Date"; 
	
	
	$fdata["FieldType"]= 7;
	$fdata["EditFormat"]= "Date";
	$fdata["ViewFormat"]= "Short Date";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Daate";
		$fdata["FullName"]= "Daate";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 4;
	 $fdata["DateEditType"]=13; 
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataattendance["Daate"]=$fdata;
$tables_data["attendance"]=&$tdataattendance;
$field_labels["attendance"] = &$fieldLabelsattendance;


?>
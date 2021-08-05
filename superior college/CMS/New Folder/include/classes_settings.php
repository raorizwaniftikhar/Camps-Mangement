<?php

//	field labels
$fieldLabelsclasses = array();
$fieldLabelsclasses["English"]=array();
$fieldLabelsclasses["English"]["CID"] = "Class ID";
$fieldLabelsclasses["English"]["Session"] = "Session";
$fieldLabelsclasses["English"]["Semester"] = "Semester";
$fieldLabelsclasses["English"]["Subjects"] = "No. of Subjects";


$tdataclasses=array();
	 $tdataclasses[".NumberOfChars"]=80; 
	$tdataclasses[".ShortName"]="classes";
	$tdataclasses[".OwnerID"]="";
	$tdataclasses[".OriginalTable"]="classes";

	$keys=array();
	$keys[]="CID";
	$tdataclasses[".Keys"]=$keys;

	
//	CID
	$fdata = array();
	 $fdata["Label"]="Class ID"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "CID";
		$fdata["FullName"]= "CID";
	
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataclasses["CID"]=$fdata;
	
//	Session
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Session";
		$fdata["FullName"]= "`Session`";
	
	
	
	
	$fdata["Index"]= 2;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataclasses["Session"]=$fdata;
	
//	Semester
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
		
		$fdata["LookupType"]=0;
					$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Semester";
		$fdata["FullName"]= "Semester";
	
	
	
	
	$fdata["Index"]= 3;
	
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataclasses["Semester"]=$fdata;
	
//	Subjects
	$fdata = array();
	 $fdata["Label"]="No. of Subjects"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
		
		$fdata["LookupType"]=0;
					$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Subjects";
		$fdata["FullName"]= "Subjects";
	
	
	
	
	$fdata["Index"]= 4;
	
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataclasses["Subjects"]=$fdata;
$tables_data["classes"]=&$tdataclasses;
$field_labels["classes"] = &$fieldLabelsclasses;


?>
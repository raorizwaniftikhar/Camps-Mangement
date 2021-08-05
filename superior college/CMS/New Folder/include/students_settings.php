<?php

//	field labels
$fieldLabelsstudents = array();
$fieldLabelsstudents["English"]=array();
$fieldLabelsstudents["English"]["SID"] = "SID";
$fieldLabelsstudents["English"]["CID"] = "Class ID";
$fieldLabelsstudents["English"]["RNUM"] = "Roll Number";
$fieldLabelsstudents["English"]["Full_Name"] = "Full Name";
$fieldLabelsstudents["English"]["UID"] = "User ID";
$fieldLabelsstudents["English"]["Status"] = "Status";
$fieldLabelsstudents["English"]["L1"] = "Lecture 1";
$fieldLabelsstudents["English"]["L2"] = "Lecture 2";
$fieldLabelsstudents["English"]["L3"] = "Lecture 3";
$fieldLabelsstudents["English"]["L4"] = "Lecture 4";
$fieldLabelsstudents["English"]["L5"] = "Lecture 5";
$fieldLabelsstudents["English"]["L6"] = "Lecture 6";
$fieldLabelsstudents["English"]["L7"] = "Lecture 7";


$tdatastudents=array();
	 $tdatastudents[".NumberOfChars"]=80; 
	$tdatastudents[".ShortName"]="students";
	$tdatastudents[".OwnerID"]="";
	$tdatastudents[".OriginalTable"]="students";

	$keys=array();
	$keys[]="SID";
	$tdatastudents[".Keys"]=$keys;

	
//	SID
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "SID";
		$fdata["FullName"]= "SID";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
									$tdatastudents["SID"]=$fdata;
	
//	CID
	$fdata = array();
	 $fdata["Label"]="Class ID"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
		
		$fdata["LookupType"]=1;
			$fdata["LinkField"]="`CID`";
	$fdata["LinkFieldType"]=200;
		$fdata["DisplayField"]="`CID`";
	$fdata["LookupTable"]="classes";
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "CID";
		$fdata["FullName"]= "CID";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 2;
	
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatastudents["CID"]=$fdata;
	
//	RNUM
	$fdata = array();
	 $fdata["Label"]="Roll Number"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "RNUM";
		$fdata["FullName"]= "RNUM";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 3;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatastudents["RNUM"]=$fdata;
	
//	Full_Name
	$fdata = array();
	 $fdata["Label"]="Full Name"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Full_Name";
		$fdata["FullName"]= "Full_Name";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 4;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatastudents["Full_Name"]=$fdata;
	
//	UID
	$fdata = array();
	 $fdata["Label"]="User ID"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "UID";
		$fdata["FullName"]= "`UID`";
	
	
	
	
	$fdata["Index"]= 5;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
				$tdatastudents["UID"]=$fdata;
	
//	Status
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Status";
		$fdata["FullName"]= "Status";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 6;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatastudents["Status"]=$fdata;
	
//	L1
	$fdata = array();
	 $fdata["Label"]="Lecture 1"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
		
		$fdata["LookupType"]=1;
			$fdata["LinkField"]="`LID`";
	$fdata["LinkFieldType"]=3;
		$fdata["DisplayField"]="`Name`";
	$fdata["LookupTable"]="lectures";
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "L1";
		$fdata["FullName"]= "L1";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 7;
	
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatastudents["L1"]=$fdata;
	
//	L2
	$fdata = array();
	 $fdata["Label"]="Lecture 2"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
		
		$fdata["LookupType"]=1;
			$fdata["LinkField"]="`LID`";
	$fdata["LinkFieldType"]=3;
		$fdata["DisplayField"]="`Name`";
	$fdata["LookupTable"]="lectures";
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "L2";
		$fdata["FullName"]= "L2";
	
	
	
	
	$fdata["Index"]= 8;
	
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatastudents["L2"]=$fdata;
	
//	L3
	$fdata = array();
	 $fdata["Label"]="Lecture 3"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
		
		$fdata["LookupType"]=1;
			$fdata["LinkField"]="`LID`";
	$fdata["LinkFieldType"]=3;
		$fdata["DisplayField"]="`Name`";
	$fdata["LookupTable"]="lectures";
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "L3";
		$fdata["FullName"]= "L3";
	
	
	
	
	$fdata["Index"]= 9;
	
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatastudents["L3"]=$fdata;
	
//	L4
	$fdata = array();
	 $fdata["Label"]="Lecture 4"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
		
		$fdata["LookupType"]=1;
			$fdata["LinkField"]="`LID`";
	$fdata["LinkFieldType"]=3;
		$fdata["DisplayField"]="`Name`";
	$fdata["LookupTable"]="lectures";
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "L4";
		$fdata["FullName"]= "L4";
	
	
	
	
	$fdata["Index"]= 10;
	
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatastudents["L4"]=$fdata;
	
//	L5
	$fdata = array();
	 $fdata["Label"]="Lecture 5"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
		
		$fdata["LookupType"]=1;
			$fdata["LinkField"]="`LID`";
	$fdata["LinkFieldType"]=3;
		$fdata["DisplayField"]="`Name`";
	$fdata["LookupTable"]="lectures";
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "L5";
		$fdata["FullName"]= "L5";
	
	
	
	
	$fdata["Index"]= 11;
	
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatastudents["L5"]=$fdata;
	
//	L6
	$fdata = array();
	 $fdata["Label"]="Lecture 6"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
		
		$fdata["LookupType"]=1;
			$fdata["LinkField"]="`LID`";
	$fdata["LinkFieldType"]=3;
		$fdata["DisplayField"]="`Name`";
	$fdata["LookupTable"]="lectures";
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "L6";
		$fdata["FullName"]= "L6";
	
	
	
	
	$fdata["Index"]= 12;
	
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatastudents["L6"]=$fdata;
	
//	L7
	$fdata = array();
	 $fdata["Label"]="Lecture 7"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
		
		$fdata["LookupType"]=1;
			$fdata["LinkField"]="`LID`";
	$fdata["LinkFieldType"]=3;
		$fdata["DisplayField"]="`Name`";
	$fdata["LookupTable"]="lectures";
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "L7";
		$fdata["FullName"]= "L7";
	
	
	
	
	$fdata["Index"]= 13;
	
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatastudents["L7"]=$fdata;
$tables_data["students"]=&$tdatastudents;
$field_labels["students"] = &$fieldLabelsstudents;


?>
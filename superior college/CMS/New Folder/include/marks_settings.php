<?php

//	field labels
$fieldLabelsmarks = array();
$fieldLabelsmarks["English"]=array();
$fieldLabelsmarks["English"]["MID"] = "MID";
$fieldLabelsmarks["English"]["SID"] = "Stuednt Name";
$fieldLabelsmarks["English"]["LID"] = "Lecture Name";
$fieldLabelsmarks["English"]["SESSINAL"] = "Sessional";
$fieldLabelsmarks["English"]["MIDTERM"] = "Mid Term";
$fieldLabelsmarks["English"]["FINAL"] = "Final Term";


$tdatamarks=array();
	 $tdatamarks[".NumberOfChars"]=80; 
	$tdatamarks[".ShortName"]="marks";
	$tdatamarks[".OwnerID"]="";
	$tdatamarks[".OriginalTable"]="marks";

	$keys=array();
	$keys[]="MID";
	$tdatamarks[".Keys"]=$keys;

	
//	MID
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "MID";
		$fdata["FullName"]= "MID";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
									$tdatamarks["MID"]=$fdata;
	
//	SID
	$fdata = array();
	 $fdata["Label"]="Stuednt Name"; 
	
	
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
	$tdatamarks["SID"]=$fdata;
	
//	LID
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
	
	$fdata["GoodName"]= "LID";
		$fdata["FullName"]= "LID";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 3;
	
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatamarks["LID"]=$fdata;
	
//	SESSINAL
	$fdata = array();
	 $fdata["Label"]="Sessional"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "SESSINAL";
		$fdata["FullName"]= "SESSINAL";
	
	
	
	
	$fdata["Index"]= 4;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatamarks["SESSINAL"]=$fdata;
	
//	MIDTERM
	$fdata = array();
	 $fdata["Label"]="Mid Term"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "MIDTERM";
		$fdata["FullName"]= "MIDTERM";
	
	
	
	
	$fdata["Index"]= 5;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatamarks["MIDTERM"]=$fdata;
	
//	FINAL
	$fdata = array();
	 $fdata["Label"]="Final Term"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "FINAL";
		$fdata["FullName"]= "FINAL";
	
	
	
	
	$fdata["Index"]= 6;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatamarks["FINAL"]=$fdata;
$tables_data["marks"]=&$tdatamarks;
$field_labels["marks"] = &$fieldLabelsmarks;


?>
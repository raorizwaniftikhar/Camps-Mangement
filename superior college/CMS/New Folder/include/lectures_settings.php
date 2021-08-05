<?php

//	field labels
$fieldLabelslectures = array();
$fieldLabelslectures["English"]=array();
$fieldLabelslectures["English"]["LID"] = "LID";
$fieldLabelslectures["English"]["CID"] = "Class ID";
$fieldLabelslectures["English"]["TID"] = "Teacher Name";
$fieldLabelslectures["English"]["Name"] = "Lecure Name";
$fieldLabelslectures["English"]["Continue"] = "On Going";
$fieldLabelslectures["English"]["Type"] = "Type";
$fieldLabelslectures["English"]["Start"] = "Start Date";
$fieldLabelslectures["English"]["End"] = "End Date";
$fieldLabelslectures["English"]["Time"] = "Time";
$fieldLabelslectures["English"]["Room"] = "Room";
$fieldLabelslectures["English"]["Duration"] = "Duration";
$fieldLabelslectures["English"]["About"] = "About";
$fieldLabelslectures["English"]["Announce"] = "Announce";


$tdatalectures=array();
	 $tdatalectures[".NumberOfChars"]=80; 
	$tdatalectures[".ShortName"]="lectures";
	$tdatalectures[".OwnerID"]="";
	$tdatalectures[".OriginalTable"]="lectures";

	$keys=array();
	$keys[]="LID";
	$tdatalectures[".Keys"]=$keys;

	
//	LID
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "LID";
		$fdata["FullName"]= "LID";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
									$tdatalectures["LID"]=$fdata;
	
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
	$tdatalectures["CID"]=$fdata;
	
//	TID
	$fdata = array();
	 $fdata["Label"]="Teacher Name"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
		
		$fdata["LookupType"]=1;
			$fdata["LinkField"]="`TID`";
	$fdata["LinkFieldType"]=3;
		$fdata["DisplayField"]="`Full_Name`";
	$fdata["LookupTable"]="teachers";
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "TID";
		$fdata["FullName"]= "TID";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 3;
	
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatalectures["TID"]=$fdata;
	
//	Name
	$fdata = array();
	 $fdata["Label"]="Lecure Name"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Name";
		$fdata["FullName"]= "Name";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 4;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatalectures["Name"]=$fdata;
	
//	Continue
	$fdata = array();
	 $fdata["Label"]="On Going"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Checkbox";
	$fdata["ViewFormat"]= "Checkbox";
	
	
		
				
	$fdata["GoodName"]= "Continue";
		$fdata["FullName"]= "`Continue`";
	
	
	
	
	$fdata["Index"]= 5;
	
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatalectures["Continue"]=$fdata;
	
//	Type
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
		
		$fdata["LookupType"]=0;
					$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Type";
		$fdata["FullName"]= "`Type`";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 6;
	
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatalectures["Type"]=$fdata;
	
//	Start
	$fdata = array();
	 $fdata["Label"]="Start Date"; 
	
	
	$fdata["FieldType"]= 7;
	$fdata["EditFormat"]= "Date";
	$fdata["ViewFormat"]= "Short Date";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Start";
		$fdata["FullName"]= "`Start`";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 7;
	 $fdata["DateEditType"]=13; 
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatalectures["Start"]=$fdata;
	
//	End
	$fdata = array();
	 $fdata["Label"]="End Date"; 
	
	
	$fdata["FieldType"]= 7;
	$fdata["EditFormat"]= "Date";
	$fdata["ViewFormat"]= "Short Date";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "End";
		$fdata["FullName"]= "`End`";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 8;
	 $fdata["DateEditType"]=13; 
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatalectures["End"]=$fdata;
	
//	Time
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Time";
	$fdata["ViewFormat"]= "Time";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Time";
		$fdata["FullName"]= "`Time`";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 9;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=15";
					$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatalectures["Time"]=$fdata;
	
//	Room
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Room";
		$fdata["FullName"]= "Room";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 10;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=10";
					$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatalectures["Room"]=$fdata;
	
//	Duration
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Duration";
		$fdata["FullName"]= "Duration";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 11;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatalectures["Duration"]=$fdata;
	
//	About
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text area";
	$fdata["ViewFormat"]= "HTML";
	
	
		
				
	$fdata["GoodName"]= "About";
		$fdata["FullName"]= "About";
	
	 $fdata["UseRTE"]=true; 
	
	 $fdata["UploadFolder"]="files"; 
	$fdata["Index"]= 12;
	
		$fdata["EditParams"]="";
			$fdata["EditParams"].= " rows=250";
		$fdata["nRows"] = 250;
			$fdata["EditParams"].= " cols=500";
		$fdata["nCols"] = 500;
					$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatalectures["About"]=$fdata;
	
//	Announce
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text area";
	$fdata["ViewFormat"]= "HTML";
	
	
		
				
	$fdata["GoodName"]= "Announce";
		$fdata["FullName"]= "Announce";
	
	 $fdata["UseRTE"]=true; 
	
	
	$fdata["Index"]= 13;
	
		$fdata["EditParams"]="";
			$fdata["EditParams"].= " rows=250";
		$fdata["nRows"] = 250;
			$fdata["EditParams"].= " cols=500";
		$fdata["nCols"] = 500;
					$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatalectures["Announce"]=$fdata;
$tables_data["lectures"]=&$tdatalectures;
$field_labels["lectures"] = &$fieldLabelslectures;


?>
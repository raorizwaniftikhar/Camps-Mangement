<?php

//	field labels
$fieldLabelsassignments = array();
$fieldLabelsassignments["English"]=array();
$fieldLabelsassignments["English"]["AID"] = "AID";
$fieldLabelsassignments["English"]["Title"] = "Title";
$fieldLabelsassignments["English"]["Type"] = "Type";
$fieldLabelsassignments["English"]["Marks"] = "Marks";
$fieldLabelsassignments["English"]["Description"] = "Description";
$fieldLabelsassignments["English"]["File"] = "Associated File";
$fieldLabelsassignments["English"]["LID"] = "Lecture";
$fieldLabelsassignments["English"]["Against"] = "Against";
$fieldLabelsassignments["English"]["SID"] = "SID";


$tdataassignments=array();
	 $tdataassignments[".NumberOfChars"]=80; 
	$tdataassignments[".ShortName"]="assignments";
	$tdataassignments[".OwnerID"]="";
	$tdataassignments[".OriginalTable"]="assignments";

	$keys=array();
	$keys[]="AID";
	$tdataassignments[".Keys"]=$keys;

	
//	AID
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "AID";
		$fdata["FullName"]= "AID";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
									$tdataassignments["AID"]=$fdata;
	
//	Title
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Title";
		$fdata["FullName"]= "Title";
	
	
	
	
	$fdata["Index"]= 2;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=100";
					$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataassignments["Title"]=$fdata;
	
//	Type
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Type";
		$fdata["FullName"]= "`Type`";
	
	
	
	
	$fdata["Index"]= 3;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=2";
								$tdataassignments["Type"]=$fdata;
	
//	Marks
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Marks";
		$fdata["FullName"]= "Marks";
	
	
	
	
	$fdata["Index"]= 4;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataassignments["Marks"]=$fdata;
	
//	Description
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Description";
		$fdata["FullName"]= "Description";
	
	
	
	
	$fdata["Index"]= 5;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=500";
					$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataassignments["Description"]=$fdata;
	
//	File
	$fdata = array();
	 $fdata["Label"]="Associated File"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Document upload";
	$fdata["ViewFormat"]= "Document Download";
	
	
		
				
	$fdata["GoodName"]= "File";
		$fdata["FullName"]= "`File`";
	
	
	
	 $fdata["UploadFolder"]="files"; 
	$fdata["Index"]= 6;
	
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataassignments["File"]=$fdata;
	
//	LID
	$fdata = array();
	 $fdata["Label"]="Lecture"; 
	
	
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
	
	
	
	$fdata["Index"]= 7;
	
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdataassignments["LID"]=$fdata;
	
//	Against
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Against";
		$fdata["FullName"]= "Against";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 8;
	
			$fdata["EditParams"]="";
									$tdataassignments["Against"]=$fdata;
	
//	SID
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "SID";
		$fdata["FullName"]= "SID";
	
	
	
	
	$fdata["Index"]= 9;
	
			$fdata["EditParams"]="";
									$tdataassignments["SID"]=$fdata;
$tables_data["assignments"]=&$tdataassignments;
$field_labels["assignments"] = &$fieldLabelsassignments;


?>
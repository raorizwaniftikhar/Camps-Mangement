<?php

//	field labels
$fieldLabelsteachers = array();
$fieldLabelsteachers["English"]=array();
$fieldLabelsteachers["English"]["TID"] = "TID";
$fieldLabelsteachers["English"]["Full_Name"] = "Full Name";
$fieldLabelsteachers["English"]["UID"] = "User ID";
$fieldLabelsteachers["English"]["Designation"] = "Designation";
$fieldLabelsteachers["English"]["Phone"] = "Phone";
$fieldLabelsteachers["English"]["Address"] = "Address";


$tdatateachers=array();
	 $tdatateachers[".NumberOfChars"]=80; 
	$tdatateachers[".ShortName"]="teachers";
	$tdatateachers[".OwnerID"]="";
	$tdatateachers[".OriginalTable"]="teachers";

	$keys=array();
	$keys[]="TID";
	$tdatateachers[".Keys"]=$keys;

	
//	TID
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "TID";
		$fdata["FullName"]= "TID";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
									$tdatateachers["TID"]=$fdata;
	
//	Full_Name
	$fdata = array();
	 $fdata["Label"]="Full Name"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Full_Name";
		$fdata["FullName"]= "Full_Name";
	
	
	
	
	$fdata["Index"]= 2;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatateachers["Full_Name"]=$fdata;
	
//	UID
	$fdata = array();
	 $fdata["Label"]="User ID"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "UID";
		$fdata["FullName"]= "`UID`";
	
	
	
	
	$fdata["Index"]= 3;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
				$tdatateachers["UID"]=$fdata;
	
//	Designation
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Designation";
		$fdata["FullName"]= "Designation";
	
	
	
	
	$fdata["Index"]= 4;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
					$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatateachers["Designation"]=$fdata;
	
//	Phone
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Phone";
		$fdata["FullName"]= "Phone";
	
	
	
	
	$fdata["Index"]= 5;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=15";
					$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatateachers["Phone"]=$fdata;
	
//	Address
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Address";
		$fdata["FullName"]= "Address";
	
	
	
	
	$fdata["Index"]= 6;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatateachers["Address"]=$fdata;
$tables_data["teachers"]=&$tdatateachers;
$field_labels["teachers"] = &$fieldLabelsteachers;


?>
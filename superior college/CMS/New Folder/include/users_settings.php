<?php

//	field labels
$fieldLabelsusers = array();
$fieldLabelsusers["English"]=array();
$fieldLabelsusers["English"]["ID"] = "ID";
$fieldLabelsusers["English"]["UID"] = "User ID";
$fieldLabelsusers["English"]["PWD"] = "Password";
$fieldLabelsusers["English"]["Type"] = "Type";


$tdatausers=array();
	 $tdatausers[".NumberOfChars"]=80; 
	$tdatausers[".ShortName"]="users";
	$tdatausers[".OwnerID"]="";
	$tdatausers[".OriginalTable"]="users";

	$keys=array();
	$keys[]="ID";
	$tdatausers[".Keys"]=$keys;

	
//	ID
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Readonly";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "ID";
		$fdata["FullName"]= "ID";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatausers["ID"]=$fdata;
	
//	UID
	$fdata = array();
	 $fdata["Label"]="User ID"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "UID";
		$fdata["FullName"]= "`UID`";
	
	
	
	
	$fdata["Index"]= 2;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatausers["UID"]=$fdata;
	
//	PWD
	$fdata = array();
	 $fdata["Label"]="Password"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Password";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "PWD";
		$fdata["FullName"]= "PWD";
	
	
	
	
	$fdata["Index"]= 3;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatausers["PWD"]=$fdata;
	
//	Type
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "Type";
		$fdata["FullName"]= "`Type`";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 4;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
				$fdata["ListPage"]=true;
	$tdatausers["Type"]=$fdata;
$tables_data["users"]=&$tdatausers;
$field_labels["users"] = &$fieldLabelsusers;


?>
<style type="text/css">
<!--
.style1 {color: #669900}
h1 {
	color: #FFFFFF;
}
-->
</style>
<a href="reports.php" class="style1">&lt;&lt;back</a>
<?php

$ARG1 = $_GET['arg1'];

require 'cls_myquery_report.php';	//include the class module

$myreport = new MyQueryReport;		// Instantiate QueryReport Class.

// Connect to database parameters are - hostname,username,password,databaseName

$myreport->connect_db("localhost", "root", "", "bzu_el"); 

$query="select CID as \"Class ID\", RNUM as \"Roll Number\", Full_Name as \"Full Name\",".
		"   Status as \"Status\" from students WHERE CID = '$ARG1'";
	// Use 'as' option in SQL to set proper column heading names. Other wise it will
	// take the default field names as column headings.
	

	
$title= "Classes"; 	// Title to be displayed on the Report
$color = "green";		// Set colour flavour  of Report - Use VIBGYOR colors
				//violet, indigo, blue, green, yellow, orange, red 	
$myreport->display_report($query, $title,$color);// Display the Report 
						//with the query, title & color

?>

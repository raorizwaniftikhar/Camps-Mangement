<?php require_once('Connections/DataPool.php'); ?>
<?php
session_start();
$colname_Lecture_Info = "-1";
if (isset($_GET['LID'])) {
  $colname_Lecture_Info = (get_magic_quotes_gpc()) ? $_GET['LID'] : addslashes($_GET['LID']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Lecture_Info = sprintf("SELECT * FROM lectures WHERE LID = %s", $colname_Lecture_Info);
$Lecture_Info = mysql_query($query_Lecture_Info, $DataPool) or die(mysql_error());
$row_Lecture_Info = mysql_fetch_assoc($Lecture_Info);
$totalRows_Lecture_Info = mysql_num_rows($Lecture_Info);
$_SESSION['CID1'] = $row_Lecture_Info['CID'];
$colname_Students = "-1";
if (isset($_SESSION['CID1'])) {
  $colname_Students = (get_magic_quotes_gpc()) ? $_SESSION['CID1'] : addslashes($_SESSION['CID1']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Students = sprintf("SELECT * FROM students WHERE CID = '%s'", $colname_Students);
$Students = mysql_query($query_Students, $DataPool) or die(mysql_error());
$row_Students = mysql_fetch_assoc($Students);
$totalRows_Students = mysql_num_rows($Students);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<?php echo $row_Students['Full_Name']; ?>
<?php echo $row_Lecture_Info['CID']; ?>
</body>
</html>
<?php
mysql_free_result($Lecture_Info);

mysql_free_result($Students);
?>

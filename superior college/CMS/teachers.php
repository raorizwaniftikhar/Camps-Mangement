<?php require_once('Connections/DataPool.php'); ?>
<?php
session_start();
$colname_Teacher_Info = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Teacher_Info = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Teacher_Info = sprintf("SELECT * FROM teachers WHERE UID = '%s'", $colname_Teacher_Info);
$Teacher_Info = mysql_query($query_Teacher_Info, $DataPool) or die(mysql_error());
$row_Teacher_Info = mysql_fetch_assoc($Teacher_Info);
$totalRows_Teacher_Info = mysql_num_rows($Teacher_Info);

$_SESSION['TID']=$row_Teacher_Info['TID'];

$colname_Lectures = "-1";
if (isset($_SESSION['TID'])) {
  $colname_Lectures = (get_magic_quotes_gpc()) ? $_SESSION['TID'] : addslashes($_SESSION['TID']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Lectures = sprintf("SELECT * FROM lectures WHERE TID = %s", $colname_Lectures);
$Lectures = mysql_query($query_Lectures, $DataPool) or die(mysql_error());
$row_Lectures = mysql_fetch_assoc($Lectures);
$totalRows_Lectures = mysql_num_rows($Lectures);





?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<p><?php echo $row_Teacher_Info['Full_Name']; ?>
<?php echo $row_Teacher_Info['TID']; ?></p>
<?php do { ?>
  <table width="519" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><?php echo $row_Lectures['Name']; ?></td>
      <td><?php echo $row_Lectures['CID']; ?></td>
    </tr>
      </table>
  <?php } while ($row_Lectures = mysql_fetch_assoc($Lectures)); ?><p>&nbsp; </p>
</body>
</html>
<?php
mysql_free_result($Teacher_Info);

mysql_free_result($Lectures);
?>

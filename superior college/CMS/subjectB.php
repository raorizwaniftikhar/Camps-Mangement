<?php require_once('Connections/DataPool.php'); ?>
<?php
$colname_Subject_Info = "-1";
if (isset($_GET['L'])) {
  $colname_Subject_Info = (get_magic_quotes_gpc()) ? $_GET['L'] : addslashes($_GET['L']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Subject_Info = sprintf("SELECT * FROM lectures WHERE LID = %s", $colname_Subject_Info);
$Subject_Info = mysql_query($query_Subject_Info, $DataPool) or die(mysql_error());
$row_Subject_Info = mysql_fetch_assoc($Subject_Info);
$totalRows_Subject_Info = mysql_num_rows($Subject_Info);

$colname_Assignments = "-1";
if (isset($_GET['L'])) {
  $colname_Assignments = (get_magic_quotes_gpc()) ? $_GET['L'] : addslashes($_GET['L']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Assignments = sprintf("SELECT * FROM assignments WHERE Type='G' AND LID = %s", $colname_Assignments);
$Assignments = mysql_query($query_Assignments, $DataPool) or die(mysql_error());
$row_Assignments = mysql_fetch_assoc($Assignments);
$totalRows_Assignments = mysql_num_rows($Assignments);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $row_Subject_Info['Name']; ?></title>
</head>

<body>
<p><?php echo $_GET['L']; ?></p>
<p>&nbsp;</p>
<p>  <?php echo $row_Subject_Info['Name']; ?>  </p>
<p>Started : <?php echo $row_Subject_Info['Start']; ?> End : <?php echo $row_Subject_Info['End']; ?></p>
<p>Room : <?php echo $row_Subject_Info['Room']; ?></p>
<p>Time : <?php echo $row_Subject_Info['Time']; ?></p>
<p>Duration : <?php echo $row_Subject_Info['Duration']; ?></p>
<p>&nbsp;</p>
<?php do { ?>
  <table width="502" border="1">
    <tr>
      <td width="1">&nbsp;</td>
      <td width="120"><?php echo $row_Assignments['Title']; ?></td>
      <td width="134"><?php echo $row_Assignments['Marks']; ?></td>
      <td width="163"><?php echo $row_Assignments['Description']; ?></td>
      <td width="50"><?php echo $row_Assignments['File']; ?></td>
    </tr>
      </table>
  <?php } while ($row_Assignments = mysql_fetch_assoc($Assignments)); ?><p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Subject_Info);

mysql_free_result($Assignments);
?>

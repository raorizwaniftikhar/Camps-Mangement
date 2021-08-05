<?php require_once('Connections/DataPool.php'); ?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO attendance (SID, CID) VALUES (%s, %s)",
                       GetSQLValueString($_POST['SId'], "int"),
                       GetSQLValueString($_POST['CID'], "int"));

  mysql_select_db($database_DataPool, $DataPool);
  $Result1 = mysql_query($insertSQL, $DataPool) or die(mysql_error());
}

$colname_Students = "-1";
if (isset($_GET['CID'])) {
  $colname_Students = (get_magic_quotes_gpc()) ? $_GET['CID'] : addslashes($_GET['CID']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Students = sprintf("SELECT * FROM students WHERE CID = '%s' ORDER BY SID ASC", $colname_Students);
$Students = mysql_query($query_Students, $DataPool) or die(mysql_error());
$row_Students = mysql_fetch_assoc($Students);
$totalRows_Students = mysql_num_rows($Students);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <?php do { ?>
  <table width="482" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="167"><?php echo $row_Students['Full_Name']; ?>
        <input name="SId" type="hidden" id="SId" value="<?php echo $row_Students['SID']; ?>" /></td>
      <td width="315"><label>
        <input name="Present" type="checkbox" id="Present" value="checkbox" checked="checked" />
        <input name="CID" type="hidden" id="CID" value="<?php echo $_GET['CID']; ?>" />
      </label></td>
    </tr>
  </table>
  <?php } while ($row_Students = mysql_fetch_assoc($Students)); ?>
  <input type="hidden" name="MM_insert" value="form1">
  <label></label>
  <label>
  <input type="submit" name="Submit" value="Submit" />
  </label>
</form>
</body>
</html>
<?php
mysql_free_result($Students);
?>

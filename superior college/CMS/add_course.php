<?php require_once('Connections/DataPool.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO lectures (CID, TID, Name, `Continue`, Type, `Start`, `End`, `Time`, Room, Duration, About) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['CID'], "text"),
                       GetSQLValueString($_POST['TID'], "int"),
                       GetSQLValueString($_POST['Name'], "text"),
                       GetSQLValueString($_POST['Continue'], "text"),
                       GetSQLValueString($_POST['Type'], "text"),
                       GetSQLValueString($_POST['start'], "date"),
                       GetSQLValueString($_POST['end'], "date"),
                       GetSQLValueString($_POST['time'], "text"),
                       GetSQLValueString($_POST['room'], "text"),
                       GetSQLValueString($_POST['duration'], "text"),
                       GetSQLValueString($_POST['about'], "text"));

  mysql_select_db($database_DataPool, $DataPool);
  $Result1 = mysql_query($insertSQL, $DataPool) or die(mysql_error());
  $IID = mysql_insert_id();
  mkdir("files/courses/$IID", 0777);
  $insertGoTo = "coursedone.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_DataPool, $DataPool);
$query_Class = "SELECT * FROM classes";
$Class = mysql_query($query_Class, $DataPool) or die(mysql_error());
$row_Class = mysql_fetch_assoc($Class);
$totalRows_Class = mysql_num_rows($Class);

mysql_select_db($database_DataPool, $DataPool);
$query_Teachers = "SELECT * FROM teachers";
$Teachers = mysql_query($query_Teachers, $DataPool) or die(mysql_error());
$row_Teachers = mysql_fetch_assoc($Teachers);
$totalRows_Teachers = mysql_num_rows($Teachers);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Add Course</title>
<style type="text/css">
<!--
.style1 {color: #FF0000}
.style2 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style></head>

<body>
<table width="379" height="374" border="0">
  <tr>
    <td width="453" height="23" bgcolor="#33CC00"><span class="style2">&nbsp;&nbsp;Add Course </span></td>
  </tr>
  <tr>
    <td height="22" valign="middle"><span class="style1">
      <?php  if(@$_GET['ERR'] == '0') { echo "The Following User ID Already Exists : ".$_GET['requsername']; } ?>
    </span></td>
  </tr>
  <tr>
    <td height="304" valign="middle"><form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
      <label></label>
      <table width="373" border="0" cellspacing="10">
        <tr>
          <td width="53"><label>Class </label></td>
          <td width="331"><select name="CID" id="CID">
            <?php
do {  
?>
            <option value="<?php echo $row_Class['CID']?>"><?php echo $row_Class['CID']?></option>
            <?php
} while ($row_Class = mysql_fetch_assoc($Class));
  $rows = mysql_num_rows($Class);
  if($rows > 0) {
      mysql_data_seek($Class, 0);
	  $row_Class = mysql_fetch_assoc($Class);
  }
?>
          </select></td>
          </tr>
        <tr>
          <td><label>Teacher </label></td>
          <td><select name="TID" id="TID">
            <?php
do {  
?>
            <option value="<?php echo $row_Teachers['TID']?>"><?php echo $row_Teachers['Full_Name']?></option>
            <?php
} while ($row_Teachers = mysql_fetch_assoc($Teachers));
  $rows = mysql_num_rows($Teachers);
  if($rows > 0) {
      mysql_data_seek($Teachers, 0);
	  $row_Teachers = mysql_fetch_assoc($Teachers);
  }
?>
          </select></td>
          </tr>
        <tr>
          <td><label>Name </label></td>
          <td><span id="sprytextfield1">
            <input name="Name" type="text" id="Name" />
            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
          </tr>
        <tr>
          <td><label>Type </label></td>
          <td><select name="Type" id="Type">
            <option value="R" selected="selected">Regular</option>
            <option value="S">Supplimentry</option>
            <option value="A">Advance</option>
          </select></td>
          </tr>
        <tr>
          <td><input name="Continue" type="hidden" id="Continue" value="Y" /></td>
          <td><div align="center"><em>Details</em></div></td>
          </tr>
        <tr>
          <td><label>Start </label></td>
          <td><span id="sprytextfield2">
            <input name="start" type="text" id="start" />
            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
          </tr>
        <tr>
          <td><label>End </label></td>
          <td><span id="sprytextfield3">
            <input name="end" type="text" id="end" />
            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
          </tr>
        <tr>
          <td><label>Time </label></td>
          <td><span id="sprytextfield4">
            <input name="time" type="text" id="time" />
            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
          </tr>
        <tr>
          <td><label>Room </label></td>
          <td><span id="sprytextfield5">
            <input name="room" type="text" id="room" />
            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
          </tr>
        <tr>
          <td>Duration</td>
          <td><span id="sprytextfield6">
            <input name="duration" type="text" id="duration" />
            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
          </tr>
        <tr>
          <td valign="top">About</td>
          <td><textarea name="about" id="about" cols="45" rows="5"></textarea></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
            <div align="left">
              <input type="submit" name="Submit" value="Submit" />
              </div></td>
          </tr>
      </table>

      <input type="hidden" name="MM_insert" value="form1" />
    </form></td>
  </tr>
  <tr>
    <td height="23">&nbsp;</td>
  </tr>
</table>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5");
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6");
//-->
</script>
</body>
</html>
<?php
mysql_free_result($Class);

mysql_free_result($Teachers);
?>

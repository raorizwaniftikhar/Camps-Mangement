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
  $insertSQL = sprintf("INSERT INTO teachers (Full_Name, UID, Designation, Phone, Address) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Name'], "text"),
                       GetSQLValueString($_POST['UID'], "text"),
                       GetSQLValueString($_POST['Designation'], "text"),
                       GetSQLValueString($_POST['Phone'], "text"),
                       GetSQLValueString($_POST['Address'], "text"));

  mysql_select_db($database_DataPool, $DataPool);
  $Result1 = mysql_query($insertSQL, $DataPool) or die(mysql_error());

  $insertGoTo = "admin.php?MSG=T_D";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Add Teacher</title>
<style type="text/css">
<!--
.style1 {color: #FF0000}
-->
</style>
<link href="include/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="457" height="374" border="0" align="left" class="tableborder1">
  <tr>
    <td width="451" height="23" class="tableheader">Provide Teacher Details </td>
  </tr>
  <tr>
    <td height="22" valign="middle"><span class="style1">
      <?php  if(@$_GET['ERR'] == '0') { echo "The Following User ID Already Exists : ".$_GET['requsername']; } ?>
    </span></td>
  </tr>
  <tr>
    <td height="304" valign="middle"><form id="form1" name="form1" method="post" action="<?php echo $editFormAction; ?>">
      <p>
        <input name="UID" type="hidden" id="UID" value="<?php echo $_GET['UID']; ?>" />
      </p>
      <p>
        <label></label>
      </p>
      <table width="431" border="0" align="center" cellspacing="10">
        <tr>
          <td width="72">Name</td>
          <td width="325"><span id="sprytextfield1">
            <input name="Name" type="text" id="Name" />
            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
          </tr>
        <tr>
          <td>Designation</td>
          <td><span id="sprytextfield2">
            <input name="Designation" type="text" id="Designation" />
            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
          </tr>
        <tr>
          <td>Phone</td>
          <td><span id="sprytextfield3">
            <input name="Phone" type="text" id="Phone" />
            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
          </tr>
        <tr>
          <td>Address</td>
          <td><span id="sprytextfield4">
            <input name="Address" type="text" id="Address" />
            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><div align="right">
            <input name="Submit" type="submit" class="button" value="Submit" />
          </div></td>
          </tr>
      </table>
      <p>
        <label>                </label>
        </p>
      <p>
        <label></label>
      </p>
      <p>
        <label></label>
      </p>
      <input type="hidden" name="MM_insert" value="form1" />
    </form></td>
  </tr>
  <tr>
    <td height="23" class="footer">&nbsp;</td>
  </tr>
</table>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
//-->
</script>
</body>
</html>

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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "Addition_FORM")) {
  $insertSQL = sprintf("INSERT INTO classes (CID, `Session`, Semester, Subjects) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['CID'], "text"),
                       GetSQLValueString($_POST['Session'], "text"),
                       GetSQLValueString($_POST['Semester'], "text"),
                       GetSQLValueString($_POST['Subjects'], "text"));

  mysql_select_db($database_DataPool, $DataPool);
  $Result1 = mysql_query($insertSQL, $DataPool) or die(mysql_error());

  $insertGoTo = "classdone.php";
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
<title>Untitled Document</title>
<style type="text/css">
<!--
.style1 {color: #FF0000}
.style2 {	color: #FFFFFF;
	font-weight: bold;
}
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
<table width="447" height="347" border="0" align="left" class="tableborder1">
  <tr>
    <td width="451" height="23" bgcolor="#66CC00" class="tableheader"><span class="style2">&nbsp;Add New Class </span></td>
  </tr>
  <tr>
    <td height="22" valign="middle"><span class="style1">
      <?php  if(@$_GET['ERR'] == '0') { echo "The Following User ID Already Exists : ".$_GET['requsername']; } ?>
    </span></td>
  </tr>
  <tr>
    <td height="266" valign="middle"><form action="<?php echo $editFormAction; ?>" id="Addition_FORM" name="Addition_FORM" method="POST">
      <label></label>
      <table width="405" border="0" align="center" cellspacing="10">
        <tr>
          <td width="123">Class ID </td>
          <td width="241"><span id="sprytextfield1">
            <input name="CID" type="text" id="CID" />
            <span class="textfieldRequiredMsg">Required.</span></span></td>
          <td width="19">&nbsp;</td>
        </tr>
        <tr>
          <td>Session</td>
          <td><span id="sprytextfield2">
            <input name="Session" type="text" id="Session" />
            <span class="textfieldRequiredMsg">Required.</span></span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Semester</td>
          <td><span id="sprytextfield3">
            <input name="Semester" type="text" id="Semester" />
            <span class="textfieldRequiredMsg">Required.</span></span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>No. of Subjects</td>
          <td><span id="sprytextfield4">
          <input name="Subjects" type="text" id="Subjects" size="5" />
          <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
          <td>&nbsp;</td>
        </tr>
      </table>
      <div align="center"><br />  
          <input type="submit" name="Submit" value="Add Class" />
      </div>
      <input type="hidden" name="MM_insert" value="Addition_FORM">
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
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "integer");
//-->
</script>
</body>
</html>

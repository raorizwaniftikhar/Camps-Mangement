<?php require_once('Connections/DataPool.php'); ?>
<?php
// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="add.php?ERR=0";
  $loginUsername = $_POST['UID'];
  $LoginRS__query = "SELECT UID FROM users WHERE UID='" . $loginUsername . "'";
  mysql_select_db($database_DataPool, $DataPool);
  $LoginRS=mysql_query($LoginRS__query, $DataPool) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

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
  $insertSQL = sprintf("INSERT INTO users (UID, PWD, Type) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['UID'], "text"),
                       GetSQLValueString($_POST['PWD'], "text"),
                       GetSQLValueString($_POST['select'], "int"));

  mysql_select_db($database_DataPool, $DataPool);
  $Result1 = mysql_query($insertSQL, $DataPool) or die(mysql_error());

  $insertGoTo = "crossroad2.php?TYPE=".$_POST['select']."&UID=".$_POST['UID'];
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
<title>Add User</title>
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
.style2 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="411" height="347" border="0" align="left" class="tableborder1">
  <tr>
    <td width="451" height="23" bgcolor="#66CC00" class="tableheader"><span class="style2">&nbsp;Add New User </span></td>
  </tr>
  <tr>
    <td height="22" valign="middle"><span class="style1"><?php  if(@$_GET['ERR'] == '0') { echo "The Following User ID Already Exists : ".$_GET['requsername']; } ?>
    </span></td>
  </tr>
  <tr>
    <td height="266" valign="middle"><form action="<?php echo $editFormAction; ?>" id="Addition_FORM" name="Addition_FORM" method="post">
      <label></label>
      <table width="405" border="0" align="center" cellspacing="10">
        <tr>
          <td width="123">User ID </td>
          <td width="241"><span id="sprytextfield1">
            <input name="UID" type="text" id="UID" />
            <span class="textfieldRequiredMsg">Required.</span></span></td>
          <td width="19">&nbsp;</td>
        </tr>
        <tr>
          <td>Password</td>
          <td><span id="sprytextfield2">
            <input name="PWD" type="text" id="PWD" />
            <span class="textfieldRequiredMsg">Required.</span></span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Type</td>
          <td><select name="select">
              <option value="0">Admin</option>
              <option value="1">Student</option>
              <option value="2">Teacher</option>
          </select></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><div align="right">
              <input name="Submit" type="submit" class="button" value="Submit" />
          </div></td>
          <td>&nbsp;</td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form1" />
      <input type="hidden" name="MM_insert" value="Addition_FORM" />
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
//-->
</script>
</body>
</html>

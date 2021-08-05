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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "Assignment_FORM")) {


  $target = 'files/courses/'.$_POST['LID']."/".basename( $_FILES['file']['name']);
  echo $target;
  
  echo $_FILES['file']['name'];
  if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
  echo "File is valid, and was successfully uploaded.\n";
  } else {
    echo "Possible file upload attack!\n";
  }	
  $_POST['file'] = $target;
  $insertSQL = sprintf("INSERT INTO assignments (Title, Type, Marks, Description, File, LID) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['Type'], "text"),
                       GetSQLValueString($_POST['marks'], "int"),
                       GetSQLValueString($_POST['description'], "text"),
                       GetSQLValueString($_POST['file'], "text"),
                       GetSQLValueString($_POST['LID'], "int"));

  mysql_select_db($database_DataPool, $DataPool);
  $Result1 = mysql_query($insertSQL, $DataPool) or die(mysql_error());

  $insertGoTo = "a_added.php";
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
<title>Add Assignment</title>
<style type="text/css">
<!--
.style1 {color: #FF0000}
-->
</style>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="Assignment_FORM" id="Assignment_FORM">
  <label>  </label>
  <label>  </label>
  <table width="421" height="347" border="0" align="left" class="tableborder1">
    <tr>
      <td width="31" height="23" class="tableheader">&nbsp;</td>
      <td width="678" class="tableheader">New Assignment</td>
      <td width="12">&nbsp;</td>
    </tr>
    <tr>
      <td rowspan="2" valign="top">&nbsp;</td>
      <td height="22" valign="middle" bgcolor="#66FF33">&nbsp;</td>
      <td rowspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td height="266" valign="middle"><label></label>
          <table width="405" border="0" align="center" cellspacing="10">
            <tr>
              <td width="123">Title</td>
              <td width="241"><input name="title" type="text" id="title" /></td>
              <td width="19">&nbsp;</td>
            </tr>
            <tr>
              <td>Marks</td>
              <td><input name="marks" type="text" id="marks" /></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td valign="top">Description</td>
              <td><textarea name="description" cols="30" rows="6" id="description"></textarea></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Attachment</td>
              <td>
                  <div align="left">
                      <input type="file" name="file" />
                    </div></td><td>&nbsp;</td>
            </tr>
          </table>
        <label> </label>
          <p>
            <label></label>
          </p>
        <p align="center">
            <input name="Submit2" type="submit" class="button" value="Submit" />
          </p>
        <p>
            <label></label>
          </p>
        <p>
            <label></label>
        </p></td>
    </tr>
    <tr>
      <td height="23" class="footer">&nbsp;</td>
      <td bgcolor="#FFFF00" class="footer">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <label><br />
  <input name="Type" type="hidden" id="Type" value="G" />
  <input name="LID" type="hidden" id="LID" value="<?php echo $_GET['L']; ?>" />
  <br />
  </label>
  <label><br />
  </label>
  <p>
    <label></label>
  </p>
  <p>
    <input type="hidden" name="MM_insert" value="Assignment_FORM">
  </p>
  <p>
    <label></label>
</p>
</form>
<p>&nbsp;</p>
</body>
</html>

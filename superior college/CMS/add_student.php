<?php require_once('Connections/DataPool.php'); ?>
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
  $insertSQL = sprintf("INSERT INTO students (CID, RNUM, Full_Name, UID, Status, L1, L2, L3, L4, L5, L6, L7) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['CID'], "text"),
                       GetSQLValueString($_POST['RNUM'], "text"),
                       GetSQLValueString($_POST['NAME'], "text"),
                       GetSQLValueString($_POST['UID'], "text"),
                       GetSQLValueString($_POST['STATUS'], "text"),
                       GetSQLValueString($_POST['1'], "text"),
                       GetSQLValueString($_POST['2'], "text"),
                       GetSQLValueString($_POST['3'], "text"),
                       GetSQLValueString($_POST['4'], "text"),
                       GetSQLValueString($_POST['5'], "text"),
                       GetSQLValueString($_POST['6'], "text"),
                       GetSQLValueString($_POST['7'], "text"));

  mysql_select_db($database_DataPool, $DataPool);
  $Result1 = mysql_query($insertSQL, $DataPool) or die(mysql_error());

  $insertGoTo = "admin.php?MSG=S_D";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_DataPool, $DataPool);
$query_Classes = "SELECT CID FROM classes";
$Classes = mysql_query($query_Classes, $DataPool) or die(mysql_error());
$row_Classes = mysql_fetch_assoc($Classes);
$totalRows_Classes = mysql_num_rows($Classes);

mysql_select_db($database_DataPool, $DataPool);
$query_Courses = "SELECT * FROM lectures";
$Courses = mysql_query($query_Courses, $DataPool) or die(mysql_error());
$row_Courses = mysql_fetch_assoc($Courses);
$totalRows_Courses = mysql_num_rows($Courses);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Add Student</title>
<style type="text/css">
<!--
.style1 {color: #FF0000}
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>
<link href="include/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style2 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="397" height="374" border="0" align="left">
  <tr>
    <td width="435" height="23" bgcolor="#66CC00" class="tableheader style2">&nbsp;Give Student Details </td>
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
      <table width="391" border="0" align="center" cellspacing="10">
        <tr>
          <td width="82"><label>Class </label></td>
          <td width="257"><select name="CID" id="CID">
            <?php
do {  
?>
            <option value="<?php echo $row_Classes['CID']?>"<?php if (!(strcmp($row_Classes['CID'], $row_Classes['CID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Classes['CID']?></option>
            <?php
} while ($row_Classes = mysql_fetch_assoc($Classes));
  $rows = mysql_num_rows($Classes);
  if($rows > 0) {
      mysql_data_seek($Classes, 0);
	  $row_Classes = mysql_fetch_assoc($Classes);
  }
?>
          </select></td>
          <td width="18">&nbsp;</td>
        </tr>
        <tr>
          <td>Roll Number </td>
          <td><span id="sprytextfield1">
          <input name="RNUM" type="text" id="RNUM" size="5" />
          <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Full Name </td>
          <td><span id="sprytextfield2">
            <input name="NAME" type="text" id="NAME" />
            <span class="textfieldRequiredMsg">Required.</span></span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Status</td>
          <td><select name="STATUS" id="STATUS">
            <option value="OK" selected="selected">OK</option>
            <option value="DROPED">Dropped</option>
          </select></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><div align="center"><em>Subject Selection </em></div></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Course 1 </td>
          <td><span id="spryselect1">
            <select name="1" id="1">
              <option value="0">-- NONE --</option>
              <?php
do {  
?>
              <option value="<?php echo $row_Courses['LID']?>"><?php echo $row_Courses['Name']?></option>
              <?php
} while ($row_Courses = mysql_fetch_assoc($Courses));
  $rows = mysql_num_rows($Courses);
  if($rows > 0) {
      mysql_data_seek($Courses, 0);
	  $row_Courses = mysql_fetch_assoc($Courses);
  }
?>
            </select>
            <span class="selectInvalidMsg">Please select </span>            <span class="selectRequiredMsg">Please select an item.</span></span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Course 2 </td>
          <td><select name="2" id="2">
            <option value="0">-- NONE --</option>
            <?php
do {  
?>
            <option value="<?php echo $row_Courses['LID']?>"><?php echo $row_Courses['Name']?></option>
            <?php
} while ($row_Courses = mysql_fetch_assoc($Courses));
  $rows = mysql_num_rows($Courses);
  if($rows > 0) {
      mysql_data_seek($Courses, 0);
	  $row_Courses = mysql_fetch_assoc($Courses);
  }
?>
          </select></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Course 3 </td>
          <td><select name="3" id="3">
            <option value="0">-- NONE --</option>
            <?php
do {  
?>
            <option value="<?php echo $row_Courses['LID']?>"><?php echo $row_Courses['Name']?></option>
            <?php
} while ($row_Courses = mysql_fetch_assoc($Courses));
  $rows = mysql_num_rows($Courses);
  if($rows > 0) {
      mysql_data_seek($Courses, 0);
	  $row_Courses = mysql_fetch_assoc($Courses);
  }
?>
          </select></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Course 4 </td>
          <td><select name="4" id="4">
            <option value="0">-- NONE --</option>
            <?php
do {  
?>
            <option value="<?php echo $row_Courses['LID']?>"><?php echo $row_Courses['Name']?></option>
            <?php
} while ($row_Courses = mysql_fetch_assoc($Courses));
  $rows = mysql_num_rows($Courses);
  if($rows > 0) {
      mysql_data_seek($Courses, 0);
	  $row_Courses = mysql_fetch_assoc($Courses);
  }
?>
          </select></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Course 5 </td>
          <td><select name="5" id="5">
            <option value="0">-- NONE --</option>
            <?php
do {  
?>
            <option value="<?php echo $row_Courses['LID']?>"><?php echo $row_Courses['Name']?></option>
            <?php
} while ($row_Courses = mysql_fetch_assoc($Courses));
  $rows = mysql_num_rows($Courses);
  if($rows > 0) {
      mysql_data_seek($Courses, 0);
	  $row_Courses = mysql_fetch_assoc($Courses);
  }
?>
          </select></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Course 6 </td>
          <td><select name="6" id="6">
            <option value="0">-- NONE --</option>
            <?php
do {  
?>
            <option value="<?php echo $row_Courses['LID']?>"><?php echo $row_Courses['Name']?></option>
            <?php
} while ($row_Courses = mysql_fetch_assoc($Courses));
  $rows = mysql_num_rows($Courses);
  if($rows > 0) {
      mysql_data_seek($Courses, 0);
	  $row_Courses = mysql_fetch_assoc($Courses);
  }
?>
          </select></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Course 7 </td>
          <td><select name="7" id="7">
            <option value="0">-- NONE --</option>
            <?php
do {  
?>
            <option value="<?php echo $row_Courses['LID']?>"><?php echo $row_Courses['Name']?></option>
            <?php
} while ($row_Courses = mysql_fetch_assoc($Courses));
  $rows = mysql_num_rows($Courses);
  if($rows > 0) {
      mysql_data_seek($Courses, 0);
	  $row_Courses = mysql_fetch_assoc($Courses);
  }
?>
          </select></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
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
      <p>
        <label></label>
        <input type="hidden" name="MM_insert" value="form1" />
    </p>
      </form></td>
  </tr>
  <tr>
    <td height="23" class="footer">&nbsp;</td>
  </tr>
</table>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "integer");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1", {invalidValue:"0"});
//-->
</script>
</body>
</html>
<?php
mysql_free_result($Classes);

mysql_free_result($Courses);
?>

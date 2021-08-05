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

mysql_select_db($database_DataPool, $DataPool);
$query_Classes = "SELECT * FROM classes";
$Classes = mysql_query($query_Classes, $DataPool) or die(mysql_error());
$row_Classes = mysql_fetch_assoc($Classes);
$totalRows_Classes = mysql_num_rows($Classes);

mysql_select_db($database_DataPool, $DataPool);
$query_lects = "SELECT * FROM lectures";
$lects = mysql_query($query_lects, $DataPool) or die(mysql_error());
$row_lects = mysql_fetch_assoc($lects);
$totalRows_lects = mysql_num_rows($lects);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reports</title>
<style type="text/css">
<!--
.style1 {
	color: #FFFFFF;
	font-weight: bold;
	font-size: 36px;
}
.button1 {
	height: 50px;
	width: 200px;
}
-->
</style>
</head>

<body>
<table width="578" height="318" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="48" bgcolor="#66CC00"><span class="style1">&nbsp;Select Report</span></td>
  </tr>
  <tr>
    <td height="95"><form id="classes" name="classes" method="get" action="rep_class.php">
      <div align="center">View Student Report :
        <select name="arg1" id="arg1">
            <?php
do {  
?>
                <option value="<?php echo $row_Classes['CID']?>"><?php echo $row_Classes['CID']?></option>
            <?php
} while ($row_Classes = mysql_fetch_assoc($Classes));
  $rows = mysql_num_rows($Classes);
  if($rows > 0) {
      mysql_data_seek($Classes, 0);
	  $row_Classes = mysql_fetch_assoc($Classes);
  }
?>
        </select>
          <input type="submit" name="button" id="button" value="View Students Report" />
      </div>
    </form>
      <form id="classes2" name="classes" method="get" action="rep_att.php">
        <div align="center">View Attendance Report :
          <select name="arg1" id="arg1">
            <?php
do {  
?>
            <option value="<?php echo $row_lects['LID']?>"><?php echo $row_lects['Name']?> - <?php echo $row_lects['CID']; ?> </option>
            <?php
} while ($row_lects = mysql_fetch_assoc($lects));
  $rows = mysql_num_rows($lects);
  if($rows > 0) {
      mysql_data_seek($lects, 0);
	  $row_lects = mysql_fetch_assoc($lects);
  }
?>
                      </select>
            <input type="submit" name="button5" id="button5" value="ViewAttendance Report" />
        </div>
    </form></td>
  </tr>
  <tr>
    <td><form id="form1" name="form1" method="post" action="rep_teachers.php">
      <div align="center">
        <input name="button2" type="submit" class="button1" id="button2" value="View Teachers Reports" />
      </div>
    </form>
    </td>
  </tr>
  <tr>
    <td><form id="form2" name="form1" method="post" action="rep_students.php">
      <div align="center">
        <input name="button3" type="submit" class="button1" id="button3" value="View Classes Reports" />
      </div>
    </form></td>
  </tr>
  <tr>
    <td><form id="form3" name="form1" method="post" action="rep_asg.php">
      <div align="center">
        <input name="button4" type="submit" class="button1" id="button4" value="View Assignments Reports" />
      </div>
    </form>
      <form id="form5" name="form1" method="post" action="rep_classes.php">
        <div align="center">
          <input name="button6" type="submit" class="button1" id="button6" value="View Course Reports" />
        </div>
    </form></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Classes);

mysql_free_result($lects);
?>

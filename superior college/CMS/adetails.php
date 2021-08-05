<?php require_once('Connections/DataPool.php'); ?><?php session_start(); ?>
<?php
$colname_Lect_detail = "-1";
if (isset($_GET['id'])) {
  $colname_Lect_detail = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Lect_detail = sprintf("SELECT * FROM lectures WHERE LID = %s", $colname_Lect_detail);
$Lect_detail = mysql_query($query_Lect_detail, $DataPool) or die(mysql_error());
$row_Lect_detail = mysql_fetch_assoc($Lect_detail);
$totalRows_Lect_detail = mysql_num_rows($Lect_detail);

$_SESSION['TID'] = $row_Lect_detail['TID'];

$colname_Teacher_D = "-1";
if (isset($_SESSION['TID'])) {
  $colname_Teacher_D = (get_magic_quotes_gpc()) ? $_SESSION['TID'] : addslashes($_SESSION['TID']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Teacher_D = sprintf("SELECT * FROM teachers WHERE TID = %s", $colname_Teacher_D);
$Teacher_D = mysql_query($query_Teacher_D, $DataPool) or die(mysql_error());
$row_Teacher_D = mysql_fetch_assoc($Teacher_D);
$totalRows_Teacher_D = mysql_num_rows($Teacher_D);

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

$colname_Students = "-1";
if (isset($_POST['class'])) {
  $colname_Students = $_POST['class'];
}

 ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Admin Panel</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="styles.css" type="text/css" />
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
.style4 {color: #66CC00}
-->
</style>
</head>
<body>
<h1>&nbsp;</h1>
<table width="200" border="0">
  <tr>
    <td width="10"><h1>&nbsp;</h1>    </td>
    <td width="927"><h1>Superior Colledge CMS Admin Panel</h1>
    <hr /></td>
  </tr>
  <tr>
    <td colspan="2"><table width="941" height="325" border="0">
      <tr>
        <td width="204" valign="top"><div id="menu">
            <ul>
              <li><a href="adminT.php">Classes</a></li>
              <li><a href="ateach.php">Teachers</a></li>
              <li><a href="alect.php">Lectures</a></li>
              <li><a href="ausers.php">Users</a></li>
              <li><a href="#" onClick="window.open('add_class.php','mywindow','width=450,height=500')">Add Class</a></li>
              <li><a href="#" onClick="window.open('add_course.php','mywindow','width=450,height=500')">Add Course</a></li>
			  <li><a href="#" onClick="window.open('add.php','mywindow','width=420,height=500')">Add Users</a></li>
              <li><a target="_blank" href="reports.php">Reports</a></li>
			  			  <li><a href="logout.php">Logout</a></li>
            </ul>
        </div></td>
        <td width="757" valign="top"><br />          &nbsp;&nbsp;Welcome <br />
          <br />
          <table width="680" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td bgcolor="#A8CC45"> <span class="style1">&nbsp;&nbsp;Lecture Details </span></td>
            </tr>
            <tr>
              <td height="74" valign="top"><div align="center">
                <p align="left"> <a href="alect.php" class="style4">&lt;&lt; Back</a> <br />
                </p>
                </div>
                <br />
                <table width="564" border="0" align="center">
                  <tr>
                    <td width="91">Lecture ID : </td>
                    <td width="418"><?php echo $row_Lect_detail['LID']; ?></td>
                    <td width="33">&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>Class : </td>
                    <td><?php echo $row_Lect_detail['CID']; ?></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>Teacher : </td>
                    <td><?php echo $row_Teacher_D['Full_Name']; ?></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>Type : </td>
                    <td><?php echo $row_Lect_detail['Type']; ?></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>Started On : </td>
                    <td><?php echo $row_Lect_detail['Start']; ?></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>Will End : </td>
                    <td><?php echo $row_Lect_detail['End']; ?></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>At <?php echo $row_Lect_detail['Time']; ?> in <?php echo $row_Lect_detail['Room']; ?></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
          </table>
          <br /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Lect_detail);

mysql_free_result($Teacher_D);
?>
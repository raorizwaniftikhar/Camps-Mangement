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

$colname_Students = "-1";
if (isset($_POST['class'])) {
  $colname_Students = $_POST['class'];
}
mysql_select_db($database_DataPool, $DataPool);
$query_Students = "SELECT * FROM lectures";
$Students = mysql_query($query_Students, $DataPool) or die(mysql_error());
$row_Students = mysql_fetch_assoc($Students);
$totalRows_Students = mysql_num_rows($Students);
 session_start(); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Admin Panel</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="styles.css" type="text/css" />
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
.style2 {color: #339900}
.style3 {color: #33CC00}
-->
</style>
</head>
<body>
<h1>&nbsp;</h1>
<table width="200" border="0">
  <tr>
    <td width="10"><h1>&nbsp;</h1>    </td>
    <td width="927"><h1>Superior Group of Colleges OCMS Admin Panel</h1>
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
              <td bgcolor="#A8CC45"> <span class="style1">&nbsp;&nbsp;Lectures</span></td>
            </tr>
            <tr>
              <td height="74" valign="top"><div align="center"><br />
                Total Lectures : <?php echo $totalRows_Students ?> </div>
                <br />
                <?php do { ?>
                  <table width="636" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="123"><div align="center"><?php echo $row_Students['CID']; ?></div></td>
                        <td><a href="adetails.php?id=<?php echo $row_Students['LID']; ?>" class="style3"><?php echo $row_Students['Name']; ?></a></td>
                        <td width="58"><a href="del_lect.php?id=<?php echo $row_Students['LID']; ?>" class="style2">Delete</a></td>
                      </tr>
                      </table>
                  <?php } while ($row_Students = mysql_fetch_assoc($Students)); ?></td>
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
mysql_free_result($Students);
?>

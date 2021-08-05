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
$query_Classes = "SELECT * FROM classes ORDER BY CID ASC";
$Classes = mysql_query($query_Classes, $DataPool) or die(mysql_error());
$row_Classes = mysql_fetch_assoc($Classes);
$totalRows_Classes = mysql_num_rows($Classes);

mysql_select_db($database_DataPool, $DataPool);
$query_Class2 = "SELECT * FROM classes";
$Class2 = mysql_query($query_Class2, $DataPool) or die(mysql_error());
$row_Class2 = mysql_fetch_assoc($Class2);
$totalRows_Class2 = mysql_num_rows($Class2);
 session_start(); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Admin Panel</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="styles.css" type="text/css" />
<style type="text/css">
<!--
.style1 {color: #669900}
.style2 {color: #FFFFFF}
.style3 {color: #66CC00}
.style5 {font-size: 16px}
-->
</style>
</head>
<body>
<h1>&nbsp;</h1>
<table width="200" border="0">
  <tr>
    <td width="10"><h1>&nbsp;</h1>    </td>
    <td width="927"><h1 class="style5">Superior Group of Colleges OCMS Admin Panel </h1>
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
        <td width="757" valign="top">&nbsp;&nbsp;<br />
          Welcome <span class="style1"><?php echo $_SESSION['MM_Username']; ?></span><br />
            <br />
          <table width="611" height="144" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td height="41">Total Number of Classes : <?php echo $totalRows_Classes ?> </td>
            </tr>
            <tr>
              <td height="75"><form id="form1" name="form1" method="post" action="aclass.php">
                <div align="center">View Class : 
                  <select name="class" id="class">
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
                  &nbsp;&nbsp;
                  <input type="submit" name="button" id="button" value="Submit" />
                </div>
                            </form>              </td>
            </tr>
            <tr>
              <td height="14" bgcolor="#66CC00"> &nbsp;&nbsp;<span class="style2">Class List</span></td>
            </tr>
          </table>
          
          <br />
          <?php do { ?>
            <table width="614" height="27" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="144"><div align="center"><?php echo $row_Class2['CID']; ?></div></td>
                  <td width="364"><div align="center"><?php echo $row_Class2['Session']; ?></div></td>
                  <td width="106"><div align="center"><a href="del_class.php?id=<?php echo $row_Class2['CID']; ?>" class="style3">Delete</a></div></td>
                </tr>
                          </table>
            <?php } while ($row_Class2 = mysql_fetch_assoc($Class2)); ?></td>
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
mysql_free_result($Classes);

mysql_free_result($Class2);
?>

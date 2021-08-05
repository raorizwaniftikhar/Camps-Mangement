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

$j = 0 ;

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {

while ( $j <= $_POST['nbr'] ) {

$sid = "sid".$j;
$lid = "lid".$j;
$date = "date".$j;
$chk = "checkbox".$j;
 
  $insertSQL = sprintf("INSERT INTO attendance (SID, LRID, Daate, Present) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST[$sid], "int"),
                       GetSQLValueString($_POST[$lid], "int"),
                       GetSQLValueString($_POST[$date], "text"),
                       GetSQLValueString(isset($_POST[$chk]) ? "true" : "", "defined","'Y'","'N'")); $j++;


  mysql_select_db($database_DataPool, $DataPool);
  $Result1 = mysql_query($insertSQL, $DataPool) or die(mysql_error());
 							 }
}

$colname_Students = "-1";
if (isset($_GET['cid'])) {
  $colname_Students = (get_magic_quotes_gpc()) ? $_GET['cid'] : addslashes($_GET['cid']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Students = sprintf("SELECT * FROM students WHERE CID = '%s' ORDER BY RNUM ASC", $colname_Students);
$Students = mysql_query($query_Students, $DataPool) or die(mysql_error());
$row_Students = mysql_fetch_assoc($Students);
$totalRows_Students = mysql_num_rows($Students);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Attendance</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="layout.css" rel="stylesheet" type="text/css" />
</head>
<body id="page5">
<div class="site_center">
	<!-- header  -->
	<div class="bg_logo">
		<img src="images/00_home_06.jpg" style="width: 440px; height: 94px" /></div>
	<div class="menu">
	  <div class="bg_menu">
			<ul class="navigation">
				<li><a href="#"  class="bg_none"> </a></li>
				<li><a href="#"> </a></li>
			</ul>
		    <img src="images/1page_bg_menu.jpg" alt=" " width="469" height="106" usemap="#Menu_Map" />
		    <map name="Menu_Map" id="Menu_Map">
              <area shape="rect" coords="26,7,125,38" href="iteacher.php" />
              <area shape="rect" coords="142,8,223,38" href="aboutt.php" alt="" />
              <area shape="rect" coords="251,7,326,41" href="helpt.php" alt="" />
              <area shape="rect" coords="346,10,442,38" href="logout.php" alt="" />
            </map>
	  </div>
	</div>
	<!-- content -->
<div class="content">
		<div class="content_border_left">
			<div class="content_bot">
				<div class="content_corner_left">
					<div class="content_corner_right">
						<div class="bg_right_content">
							<div class="indent_content">
								<!--left_col-->
								<div class="col_1">
								  <div class="extra_left">
								    <p><br />
								      <br />
								    </p>
								    <form name="form2" id="form1" method="POST" action="<?php echo $editFormAction; ?>">
                                      <div align="right"><br />
                                        <br />
                                        <table width="397" border="0" align="center">
                                          <tr>
                                            <td width="157"><strong>Student Name </strong></td>
                                            <td width="230"><strong>Present ? </strong></td>
                                          </tr>
                                        </table>
                                        
                                        <?php 
									  $run = 0;
									  do { ?>
                                        <table width="397" border="0" align="center">
                                          <tr>
                                            <td width="159"><?php echo $row_Students['Full_Name']; ?></td>
                                            <td width="228"><input name="lid<?php echo $run; ?>" type="hidden" id="lid" value="<?php echo $_GET['lid']; ?>" />
                                              <input name="sid<?php echo $run; ?>" type="hidden" id="sid" value="<?php echo $row_Students['SID']; ?>" />
                                              <input name="date<?php echo $run; ?>" type="hidden" id="date" value="<?php echo date("F j , Y") ?>" />
                                              <input name="checkbox<?php echo $run; ?>" type="checkbox" value="checkbox" checked="checked" />
                                            <input name="nbr" type="hidden" id="nbr" value="<?php echo $run; ?>" /></td>
                                          </tr>
                                        </table>
                                          <?php 
										$run++;
										} while ($row_Students = mysql_fetch_assoc($Students)); ?>
                                        <br />
                                        <input type="hidden" name="MM_insert" value="form2">
                                        <input type="submit" name="Submit" value="Mark Attendance" />
                                      </div>
								    </form>
								    <p>&nbsp;</p>
								  </div>
								</div>
								<!--right_col-->
								<div class="col_2 ind_top_col">
                                	<div class="extra_left_1">
                               	  <img src="images/5page_title_2.gif" class="title_2" alt="" width="148" height="25" /></div>
                                  <div class="footer">OCMS&copy;2009  </div>
                              </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
  </div>
</div>
</body>
</html>
<?php
mysql_free_result($Students);
?>
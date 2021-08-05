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

session_start();

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form3")) {
  $updateSQL = sprintf("UPDATE lectures SET Announce=%s WHERE LID=%s",
                       GetSQLValueString($_POST['ann'], "text"),
                       GetSQLValueString($_POST['LID'], "int"));

  mysql_select_db($database_DataPool, $DataPool);
  $Result1 = mysql_query($updateSQL, $DataPool) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {


  $target = 'files/courses/'.$_POST['LID']."/".basename( $_FILES['file']['name']);
  if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
  $filex = "OK";
  } else {
  $filex = "BAD";
  }	
}

$colname_Subject_Info = "-1";
if (isset($_GET['L'])) {
  $colname_Subject_Info = (get_magic_quotes_gpc()) ? $_GET['L'] : addslashes($_GET['L']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Subject_Info = sprintf("SELECT * FROM lectures WHERE LID = %s", $colname_Subject_Info);
$Subject_Info = mysql_query($query_Subject_Info, $DataPool) or die(mysql_error());
$row_Subject_Info = mysql_fetch_assoc($Subject_Info);
$totalRows_Subject_Info = mysql_num_rows($Subject_Info);


$colname_Assignments = "-1";
if (isset($_GET['L'])) {
  $colname_Assignments = (get_magic_quotes_gpc()) ? $_GET['L'] : addslashes($_GET['L']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Assignments = sprintf("SELECT * FROM assignments WHERE Type='G' AND LID = %s", $colname_Assignments);
$Assignments = mysql_query($query_Assignments, $DataPool) or die(mysql_error());
$row_Assignments = mysql_fetch_assoc($Assignments);
$totalRows_Assignments = mysql_num_rows($Assignments);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo $row_Subject_Info['Name']; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="layout.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {
	color: #FFFFFF;
	font-weight: bold;
}
.style2 {
	color: #000000;
	font-weight: bold;
}
.style3 {color: #000000}
.style4 {color: #FFFFFF}
.style6 {font-size: 0.75em}
.style7 {font-size: 12px}
-->
</style>
</head>
<body id="page5">
<div class="site_center">
	<!-- header  -->
	<div class="bg_logo">
		<img src="images/00_home_06.jpg" style="width: 440px; height: 94px" /></div>
	<div class="menu">
		<div class="bg_menu"><img src="images/1page_bg_menu.jpg" alt=" " width="469" height="106" usemap="#Menu_Map" />
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
								    <p>Course Name : </p>
							        <table width="538" border="0">
                                      <tr>
                                        <td width="258" height="27"><span class="text"><?php echo $row_Subject_Info['Name']; ?></span></td>
                                        <td width="297"><div align="right" class="box_2"><a href="attend.php?cid=<?php echo $row_Subject_Info['CID']; ?>&lid=<?php echo $row_Subject_Info['LID']; ?>" class="link_1">Take Attendance </a></div></td>
                                      </tr>
                                    </table>
							        <table width="538" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td width="268"><table width="267" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td class="button_1 style1"> &nbsp;&nbsp;Started On <?php echo $row_Subject_Info['Start']; ?></td>
                                          </tr>
                                        </table></td>
                                        <td width="270"><table width="267" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td class="button_2 style2">&nbsp;&nbsp;Expected to End On <?php echo $row_Subject_Info['End']; ?></td>
                                          </tr>
                                        </table></td>
                                      </tr>
                                    </table>
								      <br />
								      <table width="539" height="70" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                          <td class="style2">Subject Description </td>
                                        </tr>
                                        <tr>
                                          <td><?php echo $row_Subject_Info['About']; ?></td>
                                        </tr>
                                      </table>
								      <p>&nbsp;</p>
								      <table width="538" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td width="268"><table width="267" border="0" cellspacing="0" cellpadding="0">
                                              <tr>
                                                <td class="button_4 style1">&nbsp;&nbsp;Is Given at <span class="style4"><?php echo $row_Subject_Info['Time']; ?></span></td>
                                              </tr>
                                          </table></td>
                                          <td width="270"><table width="267" border="0" cellspacing="0" cellpadding="0">
                                              <tr>
                                                <td class="button_3 style2">&nbsp; Is Delivered in room <span class="style3"><?php echo $row_Subject_Info['Room']; ?></span></td>
                                              </tr>
                                          </table></td>
                                        </tr>
                                      </table>
							          <br />
							          <br />
							          <table width="540" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><div align="center" class="style2">
                                              <div align="left" class="button">&nbsp;&nbsp;<span class="style4"> Subject Announcement</span></div>
                                          </div></td>
                                        </tr>
                                      </table>
							        
						              <table width="539" height="53" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                          <td height="18" class="style2">&nbsp;Current Subject Announcement</td>
                                        </tr>
                                        <tr>
                                          <td><?php echo $row_Subject_Info['Announce']; ?></td>
                                        </tr>
                                    </table>
								      <table width="539" height="53" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                          <td height="18" class="style2"><br />
                                          &nbsp;Change Subject Announcement</td>
                                        </tr>
                                        <tr>
                                          <td><form name="form3" id="form2" method="POST" action="<?php echo $editFormAction; ?>">
                                            <div align="center">
                                              <textarea name="ann" cols="60" id="ann"></textarea>
                                              <br />
                                              <input name="LID" type="hidden" id="LID" value="<?php echo $_GET['L']; ?>" />
                                              <input type="submit" name="Submit2" value="Update Announcement" />
</div>
                                            <input type="hidden" name="MM_update" value="form3">
                                          </form>
                                          </td>
                                        </tr>
                                      </table>
								      <br />
								      <br />
                                      <br />
<br />
                                      <table width="540" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><div align="center" class="style2">
                                              <div align="left" class="button">&nbsp;<span class="style4">&nbsp;Subject Matter </span></div>
                                          </div></td>
                                        </tr>
                                      </table>
                                      <table width="540" height="39" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                          <td width="287"><div align="center" class="style2">
                                            <div align="left"><strong>&nbsp;Access Subject Matter and Files </strong></div>
                                          </div></td>
                                          <td width="287"><a href="#" class="link_1" onClick="window.open('FileB.php?path=files/courses/<?php echo $row_Subject_Info['LID'];?>','mywindow','width=800,height=600')">Click Here</a></td>
                                        </tr>
                                    </table>
								      <br />
								      or<br />
								      <br />
								      <form name="form1" action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" id="Upload">
								        Upload File :
								          <input type="file" name="file" />
    <input type="submit" name="Submit" value="Upload" />
    <input type="hidden" name="MM_insert" value="form1" />
								      <input name="LID" type="hidden" id="LID" value="<?php echo $_GET['L']; ?>" />
							        </form>
								      <br />
								      <br />
								      <table width="540" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><div align="center" class="style2">
                                              <div align="left" class="button">&nbsp;<span class="style4">&nbsp;Assignments</span></div>
                                          </div></td>
                                        </tr>
                                      </table>
								      <table width="540" height="39" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                          <td width="287"><div align="center" class="style2">
                                              <div align="left"><strong>&nbsp;New Assignment </strong></div>
                                          </div></td>
                                          <td width="287"><a href="#" class="link_1" onclick="window.open('New_ASNMT.php?L=<?php echo $row_Subject_Info['LID'];?>','mywindow','width=400,height=350')">Click Here</a></td>
                                        </tr>
                                      </table>
								      <br />
								      <br />
								      <br />
								      <?php if ($totalRows_Assignments > 0) { // Show if recordset not empty ?>
								        <table width="541" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td class="style2">Assignments</td>
                                          </tr>
                                          <tr>
                                            <td><?php do { ?>
                                                      <table width="524" border="0" align="center" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                          <td>&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td>Assignment Name : <span class="text_1"><a href="<?php echo $row_Assignments['File']; ?>" class="link_1"><?php echo $row_Assignments['Title']; ?> </a></span></td>
                                                        </tr>
                                                        <tr>
                                                          <td>Marks :<span class="text_1"> <?php echo $row_Assignments['Marks']; ?></span></td>
                                                        </tr>
                                                        <tr>
                                                          <td>Description : </td>
                                                        </tr>
                                                        <tr>
                                                          <td class="text"><span class="text_1 style6"><?php echo $row_Assignments['Description']; ?></span></td>
                                                        </tr>
                                                        <tr>
                                                          <td class="text"><div align="right" class="link"><a href="asngmnt.php?id=<?php echo $row_Assignments['AID']; ?>" class="link_1 style7">See Submitions</a> </div></td>
                                                        </tr>
                                                        <tr>
                                                          <td class="text">&nbsp;</td>
                                                        </tr>
                                                      </table>
                                              <?php } while ($row_Assignments = mysql_fetch_assoc($Assignments)); ?></td>
                                          </tr>
                                      </table>
								        <br />
								        <?php } // Show if recordset not empty ?>
								      <br />
								  </div>
								</div>
								<!--right_col-->
                                <br />
                                <div class="col_2 ind_top_col">
                                	<div class="extra_left_1">
                                    	<img src="images/5page_title_2.gif" class="title_2" alt="" width="148" height="25" />
                                       On This Page you will find information on the subject related dates like start and end date and the subject description and the time and the room in at which and in which the subject lecture is delivered.
                                       <p>
                                   	   Subjects announcments can also be viewed here and they are given directly by the the course instructor. </p>
                                       <p>Any assignments if any are given by the course intructor will be availible to view and also to download if any link is provided with the assignment. </p>
                                       <p>Subject matter can also be accessed via a builtin web file browser which will enable you to view files associated with this course. </p>
                                    </div>
                                   <div class="footer">
                                     <div align="center"></div>
                                   </div>
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
mysql_free_result($Subject_Info);

mysql_free_result($Assignments);
?>

